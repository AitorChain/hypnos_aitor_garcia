
# Groupe Hôtelier Hypnos

Projet realisé par Aitor García Bellver comme ECF pour Studi.

## Documents annexes

Sur le fichier Annexes vous trouverez:

- Manual d'utilisation
- Charte graphique
- Documentation technique


## Requirements

* Symfony 6
* PHP 8.0.2 ou superieur.
* Extensions de PHP: intl, pgsql(si vous souhaitez utiliser une base de données POSTGRES)
* Composer
* NPM - Node.js
* Docker


## Deploiement en local


Cloner le projet

```bash
  git clone git@github.com:AdonaiA/hypnos_aitor_garcia.git
```

Se placer dans le projet

```bash
  cd hypnos_aitor_garcia
```

Instalation des dependences

```bash
  npm install
  composer install
```

Mise en fonctionnement de POSTGRES (On utilisera l\'image POSTGRESQL de Docker, vous êtes libre d'utiliser celle de votre choix)
```bash
  sudo docker-compose up -d
```

Recuperation du port de la base de données

```bash
  sudo symfony var:export --multiline
```

Maintenant, on prend le port de la cle DATABASE_PORT et on modifié la clé DATABASE_URL dans le fichier .env en ajoutant notre port et le nom qu'on souhaite donner a la database:
```bash
  DATABASE_URL="postgres://symfony:ChangeMe@127.0.0.1:VOTRE_PORT/NOM_BASE_DONNEES?sslmode=disable&charset=utf8"
```

Création et mise a jour de la base de données

```bash
  symfony console doctrine:database:create
  symfony console doctrine:migrations:migrate
```

On compile le fichier sass et js du webpack encore

```bash
  npm run build
```

On finalise en mettant en marche le serveur symfony

```bash
  symfony server:start -d
```

L'application est deployé.

### Creation d'un compte d'administrateur

D'abord, on hash notre mot de passe avec la commande:

```bash
  symfony console security:hash-password
```

Puis on se connecte a POSTGRES:

```bash
  sudo symfony run psql
```

On se connect a notre database:

```bash
  \c <nom choisi pour la Database>
```


Et on ajoute notre compte en faisant attention a ajouter le mot de passe hashé qu'on vient de génerer:

```bash
INSERT INTO utilisateur (id, email, roles, password, nom, prenom)
VALUES (nextval('utilisateur_id_seq'), 'admin@admin.com', '["ROLE_ADMIN"]',
'PASSWORD HASHED', 'admin', 'admin');
```

Et voila, votre compte d'utilisateur a été créee.



## Deploiement en ligne

On va utiliser Heroku, si vous ne l'avez pas installé vous pouvez suivre les indications dans cet lien https://devcenter.heroku.com/articles/heroku-cli

Creation du projet Heroku
```bash
heroku create <nom de projet>
```

On configure l'environment en production
```bash
heroku config:set APP_ENV=prod
```

On va sur la page de notre projet Heroku et on click sur l'onglet "Resources". Dans ajouter une nouvelle add-on on cherche HEROKU POSTGRES et on selectionne le plan FREE.

Puis, on va sur l'onglet "Settings" et en "buildpack" on ajoute NODEJS



On fait le push a Heroku

```bash
git push heroku main
```

L' application est deployé.

### Creation d'un compte d'administrateur

D'abord, on hash notre mot de passe avec la commande:

```bash
  symfony console security:hash-password
```

Puis on se connecte au POSTGRES de heroku:

```bash
  heroku pg:psql
```

Et on ajoute notre compte en faisant attention a ajouter le mot de passe hashé qu'on vient de génerer:

```bash
INSERT INTO utilisateur (id, email, roles, password, nom, prenom)
VALUES (nextval('utilisateur_id_seq'), 'admin@admin.com', '["ROLE_ADMIN"]',
'PASSWORD HASHED', 'admin', 'admin');
```

Et voila, votre compte d'utilisateur a été créee.


