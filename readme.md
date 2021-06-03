<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>
## Passport Integration

In this section, we summarize the changes made to the server when integrating the Laravel Passport functionality. The plan is to have a new type of stakeholders which are in charge of registering devices such as medal-*reader* and medal-*hub* to the system, which will then allow these devices to fetch an access token from this server which it can then use to access API routes which we will protect with laravel's passport `auth:api` middleware, verifying the validity of tokens. Below, we summarize the functionality and provide details on the steps taken to integrate this into the server. 

### OAuth Functionality Summary

The server already uses Spaties permission package and therefore we will leverage this existing package to create a new permission `Manage_Devices` with an associated role `device-manager`, which, when held, gives access to a web page allowing to register new devices, such as *hubs* and *readers* into the system. Once registered, a device manager will then have to login to this server from the device, which under the hood uses the OAuth's PKCE authorization flow. (for more details about the protocol see: https://auth0.com/docs/flows/authorization-code-flow-with-proof-key-for-code-exchange-pkce). If the login is successful, the device will obtain a token which is tied to the users permission. This token will then be used automatically by the devices when hitting the `sync_medical_cases` API endpoint which will be guarded by both the `auth:api` and `permission:Manage_Devices` middlewares. 

### Back-end Changes

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







### Front-end Changes

#### Upgrade Laravel-Mix

The version of Laravel mix in `package.json` was making the node compilation failed (on heroku) so it has been changed to : `"laravel-mix": "^5.0.1",` 

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
