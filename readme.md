<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

# SOP: Setup of medAL-*data* system

## Purpose and Scope

This document lists instructions to setup a medAL-*data* server instance and is intended to be read carefully by the entities involved in setting up the various components of the medAL suite. 

## Responsibilities and Procedures

### Initial Requirements

- Linux Server. Minimal requirements:
  - 8GB RAM
  - 300GB Disk capacity
  - Operating System: Ubuntu >= 18.04
- Controlling workstation (Linux / MacOS / Windows) with the following installed:
  - **git** version control software (see https://git-scm.com/)
  - bash terminal (for windows see : https://gitforwindows.org/)
- Domain Name (In the installation we will use the name **example.com**) pointing to the server's IP address


### Installation (Manual)

#### Environment setup

1. Open up two terminals **[t_local]** and **[t_remote]** on the controlling workstation, on **[t_remote]** connect to the root account of the remote server using ssh.

#### Dokku installation on remote server

1. **[t_remote]** : run the following commands to install docker:
   ```bash
   sudo apt update
   sudo apt install apt-transport-https ca-certificates curl software-properties-common
   curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add
   sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu bionic stable"
   sudo apt update
   apt-cache policy docker-ce
   sudo apt install docker-ce
   ```
   To check if the installation was successfull run `sudo systemctl status docker`
2. **[t_remote]**: run the following commands to install nginx and set the firewall rules:
   ```bash
   sudo apt install nginx
   sudo ufw allow 'Nginx Full'
   ```
3. **[t_remote]**: Install dokku with the following commands:
   ```bash
   wget https://raw.githubusercontent.com/dokku/dokku/v0.21.4/bootstrap.sh
   sudo DOKKU_TAG=v0.21.4 bash bootstrap.sh
   ```
4. **[t_local]**: On the local workstation open a browser and head to your servers domain **example.com** where you will be able to setup the public SSH key used when deploying the source code to the server and optionnaly enable virtual hosting on the server:
   -  If you already have a ssh key configured for **git** on your local workstation, then copy the value of the public key and paste in the dialog on dokku's web interface. Otherwise, follow this guide to generate new keys: https://betterprogramming.pub/how-to-set-up-multiple-ssh-keys-ae6688f76570
   -  If you plan to host other web services on the remote server, then enable virtual hosting.
   -  Enter your domain name **example.com** in the corresponding dialog box 

#### Application Deployment

1. **[t_remote]**: Run the following commands to create and configure the dokku app for the medal-*data* server:
   ```bash
   # Create the App
   dokku apps:create medal-data
   # Install the postgres DB plugin
   sudo dokku plugin:install https://github.com/dokku/dokku-postgres.git postgres
   # Create Database
   dokku postgres:create medal-data-db
   # Link the App to the database
   dokku postgres:link medal-data-db medal-data
   # Set Config variables for Laravel
   dokku config:set medal-data DB_CONNECTION=postgres
   # Add the PHP buildpack to the apps config
   dokku config:set medal-data BUILDPACK_URL=https://github.com/heroku/heroku-buildpack-php
   ```
2. **[t_local]**: On the local workstation, clone the source code of the medal-data server from the bitbucket repository by running the command `git clone https://informatique_unisante@bitbucket.org/wavemind_swiss/liwi-main-data.git` and navigate to the project folder `cd liwi-main-data`. 
3. **[t_local]**: Link and deploy the server with the following **git** commands (replace **example.com** with your own domain name):
   ```bash
   git remote add dokku dokku@example.com:medal-data
   git push dokku master
   ```
   If the push did not work, then make sure you have correctly set up the SSH key on the dokku server using the web interface. (more information on https://dokku.com/docs/deployment/user-management/)
4. **[t_remote]**: Back on the remote server, run the following command to set the APP_KEY environment variable:
   ```bash
   dokku config:set medal-data APP_KEY=$(dokku run medal-data php artisan key:generate --show)
   ```
5. **[t_remote]**: Finally, migrate the database using:
   ```bash
   dokku run medal-data php artisan migrate
   ```


## Passport Integration

In this section, we summarize the changes made to the server when integrating the Laravel Passport functionality. The plan is to have a new type of stakeholders which are in charge of registering devices such as medal-*reader* and medal-*hub* to the system, which will then allow these devices to fetch an access token from this server which it can then use to access API routes which we will protect with laravel's passport `auth:api` middleware, verifying the validity of tokens. Below, we summarize the functionality and provide details on the steps taken to integrate this into the server. 

### OAuth Functionality

The server already uses Spaties permission package and therefore we will leverage this existing package to create a new permission `Manage_Devices` with an associated role `device-manager`, which, when held, gives access to a web page allowing to register new devices, such as *hubs* and *readers* into the system. Once registered, a device manager will then have to login to this server from the device, which under the hood uses the OAuth's PKCE authorization flow. (for more details about the protocol see: https://auth0.com/docs/flows/authorization-code-flow-with-proof-key-for-code-exchange-pkce). If the login is successful, the device will obtain a token which is tied to the users permission. This token will then be used automatically by the devices when hitting the `sync_medical_cases` API endpoint which will be guarded by both the `auth:api` and `permission:Manage_Devices` middlewares. 

#### Testing the Functionality (Postman)

Before following the instructions, make sure that your server has migrated all the tables and optionally has been seeded with users. Moreover, you should ensure that the OAuth keys are present in the `storage` folder, otherwise run `php artisan passport:install` to generate the keys. 

1. Login to the server using an account with the `Device Manager` role (if the DB is seeded with `php artisan db:seed`, then you can use u:`devicemanager@dynamic.com` , pwd:`DeviceManager`)

2. Head to the *Manage Devices* tab and click on *Create a New Client* 

   1. Provide any name and for the callback add `http://localhost:5555` (it can be any valid URL)
   2. uncheck the *confidential* check-box

3. Once the client is created you should see it appear in the list below, along with an ID to remember

4. Head to Postman and create a new request without entering any URL 

   1. Go to the authorization tab and choose OAuth 2.0 as the Type in the drop-down list

   2. In the form enter the following information

      | Header Prefix         | Bearer                                |
      | --------------------- | ------------------------------------- |
      | Grant Type            | Authorization code with PKCE          |
      | Auth URL              | <your server address>/oauth/authorize |
      | Callback URL          | http://localhost:5555                 |
      | Access Token URL      | <your server address>/oauth/token     |
      | Client ID             | <the ID of the newly created client>  |
      | Code Challenge Method | SHA-256                               |
      | State                 | Any Value (ex 1234)                   |
      | Client Authentication | Send as Basic Auth header             |

   3. Press on the `Get New Access Token` button, this will prompt you to login on the server and then ask you to authorize the application which will in turn yield an access token: View the access token and copy it to your clipboard.

5. Now lets test if the token can be used to access the `api/protected-api` endpoint.

6. In postman create a new Get request to `<your server address>/api/protected-api` in the authorization tab, choose the `Bearer Token` type and copy the value of the token in the `Token` dialog on the right. 

7. Now head to the the Headers tab and add the following key value pair `Accept -> application/json` 

8. Send the request and if everything is setup correctly you should obtain information about the user in the response.

### Back-end Changes

the back-end changes are mainly the newly added passport routes (we should remove the ones that are unused), a new API route, as well as a Device Controller. 

#### Passport Installation

In order to install passport we had to make slight changes of the laravel version currently used by this server. Originally, the `composer.json` file had the following version:

```json
 "require": {
...
        "laravel/framework": "6.0.*",
...
    },
```

Which we changed to: 

```json
"require": {
    ....
        "laravel/framework": "^6.20.26",
    ...
    },
```

for the passport installation to work properly. After changing the `composer.json` we run `composer update` followed by `composer require laravel/passport` which successfully installed passport's back-end routes.

#### Passport Routes Registration

The `boot` method from the `app/Providers/AuthServiceProvider.php` has been modified to include all of passport routes, which are all guarded by default with the middleware `web,auth` , with the only exception of routes that are used to access passport's `ClientController` (used to manage device clients) which are protected using additionally the `permission:Manage_Devices` middleware. 

```php
public function boot()
    {
        $this->registerPolicies();
        Passport::routes(function ($router) {
                    $router->forAuthorization();
                    $router->forAccessTokens();
                    $router->forTransientTokens();
                    $router->forPersonalAccessTokens();
                });
            // Here the routes to manage clients are guarded with additionnal middleware
        Route::group(['middleware'=>['web','auth','permission:Manage_Devices']], function(){ 
            Passport::routes(function ($router) {
                $router->forClients();
            });
        });
    }
```

#### Device Controller

The controller responsible for managing OAuth Clients (which are equivalent to devices such as *hubs* and *readers* in our case) is the `ClientController` from Laravel's passport package for which the front-end consist of Vue.js components generated automatically (see next section for more details). The components are included in a blade template which is served through the route `/devices` which is handled through the `app/Http/Controllers/DevicesController@index` method which simply returns the rendered template containing the vue components.  

#### Protected API route for testing

A new test route has been added to test the functionality of tokens: `api/protected-api` and simply returns the user information upon success. It is guarded by both the `auth:api` middleware which validates the token and resolves the user, followed by the `permission:Manage_Devices` middleware. 

### Front-end Changes

The front-end changes mainly consist of a newly added page containing vue components used to access the Clients resource from the passport package. 

#### Upgrade Laravel-Mix

The version of Laravel mix previously declared in `package.json` was making the node compilation failed (on heroku) so it has been changed to : `"laravel-mix": "^5.0.1",` 

after which the command `npm install` is run to update the dependencies. 

#### Generate Passport Components

Vue.js Passport components to manage devices were added using `php artisan vendor:publish --tag=passport-components` and then registered in `ressources/js/app.js` 

#### Devices Management Page

A new template has been created to manage devices using for now the Vue.js Passport components in `ressources/views/devices/index.blade.php`. This template extends from the `adminlte::page` template used for other pages and additionally includes the scripts and divs needed to use the Vue components. 

The Device management page has also been registered to the Navigation Menu:

```php
[
    'text' => 'Manage Devices',
    'url'  => '/devices',
    'icon' => 'fas fa-tablet-alt',
    'can' =>  'Manage_Devices'
],
```

from `config/adminlte.php` 

## Procedure for deploying the server on Heroku





## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1100 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost you and your team's skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[British Software Development](https://www.britishsoftware.co)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- [UserInsights](https://userinsights.com)
- [Fragrantica](https://www.fragrantica.com)
- [SOFTonSOFA](https://softonsofa.com/)
- [User10](https://user10.com)
- [Soumettre.fr](https://soumettre.fr/)
- [CodeBrisk](https://codebrisk.com)
- [1Forge](https://1forge.com)
- [TECPRESSO](https://tecpresso.co.jp/)
- [Runtime Converter](http://runtimeconverter.com/)
- [WebL'Agence](https://weblagence.com/)
- [Invoice Ninja](https://www.invoiceninja.com)
- [iMi digital](https://www.imi-digital.de/)
- [Earthlink](https://www.earthlink.ro/)
- [Steadfast Collective](https://steadfastcollective.com/)
- [We Are The Robots Inc.](https://watr.mx/)
- [Understand.io](https://www.understand.io/)
- [Abdel Elrafa](https://abdelelrafa.com)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
