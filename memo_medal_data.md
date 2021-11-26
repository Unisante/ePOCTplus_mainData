# Aide mémoire MedAL-Data

Cette documentation a été écrite pour les besoins du Sénégal  
Il vous faudra remplacer le nom de l'application, du service ou de la database (timci-medal-data / timci-medal-data2 / timci_medal_data2) par les vôtres

# Configuration/Gestion de Dokku

## Installation sur un nouveau serveur

[Documentation complète](https://bitbucket.org/wavemind_swiss/liwi-main-data/src/master/readme.md)

## Gestion du conteneur MedAL-Data

### Cloner le dépôt du Bitbucket en local

Tout d'abord assurez-vous d'avoir en local le dépôt de liwi-main-data du [bitbucket](https://bitbucket.org/wavemind_swiss/liwi-main-data/src/master/)

```bash
    git clone https://bitbucket.org/wavemind_swiss/liwi-main-data.git
```

### Configuration des remotes

Après le clonage, le dossier git est configuré par défaut pour mettre à jour uniquement le Bitbucket.  
Afin de mettre à jour dokku sur le serveur il faut ajouter comme "remote" le serveur.

```bash
    # Pour afficher la liste des remotes actuellement configuré
    git remote -v
    # Pour ajouter le serveur dans cette liste :
    # Remplacer l'ip et le nom du conteneur par les vôtres
    git remote add dokku dokku@<ip_du_serveur>:timci-medal-data
```

```bash
    # Désormais l'output des remotes doit être comme ceci
    git remote -v
        dokku	dokku@<ip_du_serveur>:timci-medal-data (fetch)
        dokku	dokku@<ip_du_serveur>:timci-medal-data (push)
        origin	https://bitbucket.org/wavemind_swiss/liwi-main-data.git (fetch)
        origin	https://bitbucket.org/wavemind_swiss/liwi-main-data.git (push)
```

### Gestion des clés SSH

> Si vous avez changé de machine, il faut dans un premier temps retirer votre ancienne clé SSH  
> `sudo dokku ssh-keys:remove dokku`

Créer dans un premier temps votre clé publique si nous n'en n'avez pas encore  
[Création d'une clé SSH linux/windows](https://www.hostinger.fr/tutoriels/generer-cle-ssh)

Puis ajouter votre clé publique sur le serveur :

```bash
    # Si votre clé se trouve ailleurs, remplacer le chemin
    cat ~/.ssh/id_rsa.pub | ssh root@<ip_du_serveur> "sudo ssh-keys:add dokku"
    # Si le compte root est désactivé il vous faut faire ces étapes en deux temps
    # Tout d'abord copier le contenu de la clé publique
    cat ~/.ssh/id_rsa.pub
    # Puis après s'être connecté au serveur
    ssh username@<ip_du_serveur>
    # Ajouter la clé SSH comme ceci
    echo "<coller ici votre clé publique>" | sudo ssh-keys:add dokku
```

### Mise à jour de Dokku

```bash
    # On va toujours déployer master
    git checkout master
    # S'assurer que le dossier local est à jour
    git pull
    # S'assurer que l'output de git status est comme ceci
    git status
        On branch master
        Your branch is up to date with 'origin/master'.

    # Lancer la mise à jour
    git push dokku master
```

## Gestion du conteneur PostgreSQL

Pour accéder au conteneur PostgreSQL :

```bash
    sudo dokku postgres:enter timci-medal-data2
```

> [Toutes les commandes postgres disponible](https://github.com/dokku/dokku-postgres)

Une fois entré dans le conteneur, nous avons accès aux commandes psql  
Exemple pour afficher le nombre de medical case présent dans la database :

```bash
    psql -U postgres timci_medal_data2 -c "select count(*) from public.medical_cases;"
    # Il est parfaitement possible de taper ces deux commandes en une :
    sudo dokku postgres:enter timci-medal-data2 psql -U postgres timci_medal_data2 -c "select count(*) from public.medical_cases;"
    # Afficher le nombre de medical cases pas encore exporté sur REDCap
    dokku postgres:enter dynamic-medal-data-db psql -U postgres dynamic_medal_data_db -c "SELECT count(*) FROM public.medical_cases where redcap=false"
    # Afficher la config et la version
    dokku postgres:enter dynamic-medal-data-db psql -U postgres dynamic_medal_data_db -c "SELECT config,version_id FROM public.patient_configs"
```

Pour plus d'informations sur les commandes disponibles psql :

- [psql](https://www.postgresql.org/docs/13/app-psql.html) Toute les commandes
- [pg_dump](https://www.postgresql.org/docs/9.3/app-pgdump.html) Sauvegarder la database
- [pg_dumpall](https://www.postgresql.org/docs/9.2/app-pg-dumpall.html) Sauvegarder l'entièreté de la database
- [pg_restore](https://www.postgresql.org/docs/9.2/app-pgrestore.html) Restaurer un fichier sauvegardé préalablement

## Gestion des variables d'environnement

Toutes les variables d'environnement sont configurées directement dans le conteneur Dokku  
Afin de vérifier qu'elles soient correctes, voici la commande

```bash
    sudo dokku timici-medal-data config:show
    # Si on veut voir la valeur d'une seule variable dont on connait le nom
    sudo dokku timici-medal-data config:get CREATOR_ALGORITHM_URL
```

Pour modifier l'une de ces variables il faut récupérer le nom de la variable puis par exemple :

```bash
    sudo dokku timici-medal-data config:set MAIL_USERNAME="exemple@mail.com"
    # Il est possible d'ajouter la commande --no-restart afin de ne pas rédemarrer
    sudo dokku timici-medal-data config:set STUDY_ID="Dynamic Tanzania" --no-restart
```

# Identifier et déboguer un problème

## Medical case manquant sur MedAL-Data

### Premier suspect la tablette

### Configuration de la tablette

- Est-ce qu'elle est bien connectée au wifi ou à la 4G ?

- Est-ce que la MAC adresse est bien configurée ?
  > Nouveauté avec Android. Désormais il faut configurer la MAC adresse du device pour chaque réseau wifi
- Est-ce que la tablette est bien assignée sur medal-creator ?

### Erreur d'envoi de la tablette

- Message d'erreur lors de l'envoi ?
  > Si possible prendre un screenshot de l'erreur et l'envoyer sur Slack
- Est-ce qu'il reste des medical case à synchroniser ?

- Est-ce que la requête POST avec le fichier a été faite à MedAL-Data ?
  > `sudo dokku logs timci-medal-data -n 1000 | grep "sync_medical_cases"`

### Deuxième suspect MedAL-Data

- Est-ce qu'il y a des fichiers JSON dans le dossier d'échec d'import ?

  > `ls /var/lib/dokku/data/storage/medal-data/app/cases_zip/json_failure | wc -l`  
  > Si l'output n'est pas 0, relancer l'import des medical case manuellement  
  > `sudo dokku run timci-medal-data bash` puis  
  > `php artisan cases:reload`

- Vérifier dans les logs si une erreur apparait. Idéalement entrer cette commande juste après une tentative de synchronisation

  > `sudo dokku logs timci-medal-data -p worker`  
  > https://dokku.com/docs/deployment/logs/  
  > Envoyer sur Slack le contenu de l'erreur

## Autre commandes utiles (wip)

Récupérer en local un fichier du serveur

```bash
scp zip1.zip username@<ip_du_serveur>:/var/lib/dokku/data/storage/timci-medal-data/app/cases_zip/2XlcgspHJlCuqDPQFJhzLZaR5s0SGPDAOdwxlMWw.zip
```
