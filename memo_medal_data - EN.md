# medAL-_data_ Cheat Sheet

NB: this documentation was initially written for Senegal and therefore contains specific examples that are relevant in the Senegal context.
You will have to replace the names of the application, the service or the database with the names applicable to your context (timci-medal-data / timci-medal-data2 / timci_medal_data2)

# Dokku Configuration and Management

## Fresh Installation on a New Server

[Comprehensive Documentation](https://bitbucket.org/wavemind_swiss/liwi-main-data/src/master/readme.md)

## medAL-_data_ container

### Clone the Bitbucket Repository Locally

Make sure you have a local version of the liwi-main-data repository from [bitbucket](https://bitbucket.org/wavemind_swiss/liwi-main-data/src/master/)

```bash
    git clone https://bitbucket.org/wavemind_swiss/liwi-main-data.git
```

### Remote Configuration

After the cloning proces, the git folder is automatically configured to update Bitbucket only.  
In order to update dokku on the server, you must declare the server as "remote".

```bash
    # To show the list of remote repositories currently configured
    git remote -v
    # To add the server to this list:
    # In the example below, replace the IP and the name of the container by your IP and name:
    git remote add dokku dokku@<server_IP>:timci-medal-data
```

```bash
    # The output of the remote repositories should now look like this:
    git remote -v
        dokku	dokku@<server_IP>:timci-medal-data (fetch)
        dokku	dokku@<server_IP>:timci-medal-data (push)
        origin	https://bitbucket.org/wavemind_swiss/liwi-main-data.git (fetch)
        origin	https://bitbucket.org/wavemind_swiss/liwi-main-data.git (push)
```

### SSH Keys

> If you are working with a new computer, the first thing you need to do is to remove your old SSH key:  
> `sudo dokku ssh-keys:remove dokku`

Create your public key first (if you haven't already)
[Creation of an SSH key linux/windows](https://confluence.atlassian.com/bitbucketserver/creating-ssh-keys-776639788.html)

And add your public key to the server:

```bash
    # If your key is somewhere else, please change the path:
    cat ~/.ssh/id_rsa.pub | ssh root@<ip_du_serveur> "sudo ssh-keys:add dokku"
    # If the root account is deactivated, you will need to do the following in 2 steps:
    # First copy the content of the public key
    cat ~/.ssh/id_rsa.pub
    # and once you're connected to the server
    ssh username@<Server_IP>
    # Add the SSH key as follows:
    echo "<paste your public key here>" | sudo ssh-keys:add dokku
```

### Dokku Update

```bash
    # We always deploy the master branch
    git checkout master
    # Make sure the local folder is up to date
    git pull
    # Your git status output looks like this
    git status
        On branch master
        Your branch is up to date with 'origin/master'.

    # Launch the update
    git push dokku master
```

## PostgreSQL Containter

To access the PostgreSQL container:

```bash
    sudo dokku postgres:enter timci-medal-data2
```

> [All availabe postgres commands](https://github.com/dokku/dokku-postgres)

Once you're inside the container, you can access the psql commands.
Example: display the number of medical cases existing in the database

```bash
    psql -U postgres timci_medal_data2 -c "select count(*) from public.medical_cases;"

    # NB: you can combine these 2 commands as one:
    sudo dokku postgres:enter timci-medal-data2 psql -U postgres timci_medal_data2 -c "select count(*) from public.medical_cases;"

    # Display number of medical cases not yet exported to REDCap:
    dokku postgres:enter dynamic-medal-data-db psql -U postgres dynamic_medal_data_db -c "SELECT count(*) FROM public.medical_cases where redcap=false"
    # Retrieve the config and version
    dokku postgres:enter dynamic-medal-data-db psql -U postgres dynamic_medal_data_db -c "SELECT config,version_id FROM public.patient_configs"
```

For more info on available psql commands:

- [psql](https://www.postgresql.org/docs/13/app-psql.html) All commands
- [pg_dump](https://www.postgresql.org/docs/9.3/app-pgdump.html) Export the database (data only)
- [pg_dumpall](https://www.postgresql.org/docs/9.2/app-pg-dumpall.html) Export the whole database (data+schema)
- [pg_restore](https://www.postgresql.org/docs/9.2/app-pgrestore.html) Restore db from a previous export

## Environment Variables

All the environment variables are configured in the Dokku container
Here are the commands to check they've been set properly:

```bash
    sudo dokku timci-medal-data config:show
    # To see the value of a specific variable (CREATOR_ALGORITHM_URL)
    sudo dokku timci-medal-data config:get CREATOR_ALGORITHM_URL
```

To change the value of an environment variable, you must first find its name and then set its value. Example:

```bash
    sudo dokku timci-medal-data config:set MAIL_USERNAME="exemple@mail.com"
    # You can add the command --no-restart to avoid having to restart
    sudo dokku timci-medal-data config:set STUDY_ID="Dynamic Tanzania" --no-restart
```

# Identifying Issues and Debugging

## Missing Medical Case on medAL-_data_

### Primary Suspect: the Tablet

### Configuring the Tablet

- Is the tablet connected to the Internet (via WiFi or 4G)?

- Is the MAC address set properly?
  > New in Android: you now have to manually configure the settings for each WiFi network to "Device MAC address"
- Is the tablet assigned to the proper algorithm and health facility in medAL-*creato*r?

### Sync Error in medAL-_reader_

- Error message whilst attempting to sync?
  > If possible, make a screenshot of the error and share it over Slack
- Are there any medical cases left to sync?

- Has the POST request containing the file been sent to medAL-_data_?
  > `sudo dokku logs timci-medal-data -n 1000 | grep "sync_medical_cases"`

### Next Suspect: medAL-_data_

- Are there any JSON files in the json_failure folder?

  > `ls /var/lib/dokku/data/storage/medal-data/app/cases_zip/json_failure | wc -l`  
  > If the output is not 0, relaunch the medical cases import manually:
  > `sudo dokku run timci-medal-data bash` and  
  > `php artisan cases:reload`

- Keep an eye on the logs to check for an error. Ideally, run this command immediately after your sync attempt:

  > `sudo dokku logs timci-medal-data -p worker`  
  > https://dokku.com/docs/deployment/logs/  
  > Share the content of the error over Slack

## Other Useful Commands (WiP)

Copy a file locally from the server

```bash
scp zip1.zip username@<ip_du_serveur>:/var/lib/dokku/data/storage/timci-medal-data/app/cases_zip/2XlcgspHJlCuqDPQFJhzLZaR5s0SGPDAOdwxlMWw.zip
```
