<p align="center">
<a href="#">
		<img width="300" src="public/images/logo.png" alt="TRT Conseil">
</a>
<br><br>
</p>

[![fr](https://img.shields.io/badge/lang-fr-blue.svg)](https://github.com/danian3wa/trtconseil/blob/main/README.md)


# TRT Conseil web app

<a href="https://github.com/danian3wa/trtconseil/blob/main/LICENSE">
<img src ="https://img.shields.io/github/license/danian3wa/trtconseil" />
</a>
<a href="https://github.com/danian3wa/trtconseil/issues">
<img src ="https://img.shields.io/github/issues/danian3wa/trtconseil" />
</a><br><br>

Lien vers la version en ligne du projet: [Cliquez ici](https://trtconseil.technidan.com)

Le projet TRT Conseil c'est une application web réalisé pour une évaluation.

## Contexte du projet

TRT Conseil est une agence de recrutement spécialisée dans l’hôtellerie et la restauration. Fondée en 2014, la société s’est agrandie au fil des ans et possède dorénavant plus de 12 centres dispersés aux quatre coins de la France.

La crise du coronavirus ayant frappée de plein fouet ce secteur, la société souhaite progressivement mettre en place un outil permettant à un plus grand nombre de recruteurs et de candidats de trouver leur bonheur.

TRT Conseil désire avoir un produit minimum viable afin de tester si la demande est réellement présente. L’agence souhaite proposer pour l’instant une simple interface avec une authentification.

4 types d’utilisateur devront pouvoir se connecter :

 Les recruteurs : Une entreprise qui recherche un employé.
 Les candidats : Un serveur, responsable de la restauration, chef cuisinier etc.
 Les consultants : Missionnés par TRT Conseil pour gérer les liaisons sur le back-office entre recruteurs et candidats.
 L’administrateur : La personne en charge de la maintenance de l’application.

## Fonctionnalités

### US1. Créer un compte

Disponible pour les recruteurs et les candidats
Renseignement d'un email valide et d'un mot de passe sécurisé
Approbation de la demande par un consultant avant activation du compte

### US2. Se connecter

Accessible aux recruteurs, candidats, consultants et administrateur
Authentification par email et mot de passe

### US3. Créer un consultant

Fonctionnalité réservée à l'administrateur
Permet d'ajouter de nouveaux consultants à la plateforme

### US4. Compléter son profil

Accessible aux candidats et aux recruteurs
Les candidats peuvent renseigner leur nom, prénom et télécharger leur CV (format PDF obligatoire)
Les recruteurs peuvent indiquer le nom de l'entreprise et une adresse

### US5. Publier une annonce

Fonctionnalité pour les recruteurs
Formulaire pour l'intitulé du poste, le lieu de travail et une description détaillée (horaires, salaire, etc.)
Validation de l'annonce par un consultant avant publication
Liste des candidats validés par TRT Conseil et ayant postulé à l'annonce accessible au recruteur

### US6. Postuler à une annonce

Accessible aux candidats, bouton pour postuler à une offre depuis la liste des annonces disponibles
Approbation de la candidature par un consultant
Envoi d'un email au recruteur avec le nom, prénom et CV du candidat si la candidature est approuvée

## Configuration de l'environnement de travail

- ordinateur: [Apple Mac Mini - Apple M2 Pro](https://www.apple.com/newsroom/2023/01/apple-introduces-new-mac-mini-with-m2-and-m2-pro-more-powerful-capable-and-versatile-than-ever/)

- système d'exploitation: [macOS Sonoma 14.4.1](https://developer.apple.com/documentation/macos-release-notes/macos-14_4-release-notes)

- IDE: [Visual Studio Code 1.87.2](https://code.visualstudio.com/)

- IDE: [PhpStorm 2023.3.6](https://www.jetbrains.com/phpstorm/download)

- système de contrôle de version: [Git version 2.44.0](https://git-scm.com/)

- serveur Web local: [XAMPP 8.2.4-0](https://www.apachefriends.org/download.html)

- langage de scripts généraliste: [PHP 8.3.4](https://www.php.net/downloads)

- gestion des dépendances en PHP: [Composer version 2.7.2](https://getcomposer.org/download/)

- outil de développement pour créer, exécuter et gérer vos applications Symfony: [Symfony CLI version 5.8.14](https://symfony.com/download)

- moteur d'exécution JavaScript: [Node.js 20.12.0](https://nodejs.org/en/download)

- gestionnaire de paquets "npm" JavaScript Node.js: [npm 10.5.0](https://docs.npmjs.com/try-the-latest-stable-version-of-npm)

- npx exécution des paquets: [npx 10.5.0](https://www.npmjs.com/package/npx)

- gestionnaire de packages: [yarn 1.22.19](https://classic.yarnpkg.com/lang/en/docs/install/)

- navigateur Web: [Google Chrome 123.0.6312.87](https://www.google.com/intl/fr/chrome/)

## Installation

Vous pouvez cloner ce dépôt pour créer une copie locale sur votre ordinateur:

```bash
git clone git@github.com:danian3wa/trtconseil.git
```
Pour utiliser une base de données MySQL, vous devez activer le pilote dans php.ini sur votre appareil s'il n'est pas déjà activé.
Décommentez "extension=php_pdo_mysql.dll" dans votre fichier php.ini.

Après la configuration de l'environnement du travail vous pouvez passer à l'installation des composants nécessaire. Vous devez ouvrir le projet cloné dans votre IDE. Dans le terminal de votre IDE vous devez vous rendre dans le dossier du projet nouvellement crée après le clonage si ce n'est pas deja le cas:

```bash
cd trtconseil
```

Avec cette commande, dans le terminal vous installez les dépendances du projet présentes dans [composer.json](composer.json):

```bash
composer install
```

Si composer n'est pas installé sur votre environnement de travail vous trouvere a cette adresse des information vous permetant l'instalation:

- [https://getcomposer.org/download/](https://getcomposer.org/download/)

Avec cette commande, dans le terminal vous installez les dépendances du projet présentes dans [yarn.lock](yarn.lock):

```bash
yarn
```

Si yarn n'est pas installé sur votre environnement de travail vous trouvere a cette adresse des information vous permetant l'instalation:

- [https://classic.yarnpkg.com/lang/en/docs/install/](https://classic.yarnpkg.com/lang/en/docs/install/)

Si node.js n'est pas installé sur votre environnement de travail vous trouvere a cette adresse des information vous permetant l'instalation:

- [https://nodejs.org/en/download](https://nodejs.org/en/download)

Dans le fichie [.env](.env) on doit définir les informations concernant l'access a la base des données.
DBHOST="127.0.0.1" -> pour l'addresse IP locale, DBPORT="3306" -> pour le port utilisé, DBNAME="TRTConseil" -> le nom de la base de données, DATABASE_PASSWORD="" -> sans mot de passe en locale, MYSQL_DB_USER="root" -> pour le nom d'utilisateur.

```bash
DBHOST="127.0.0.1"
DBPORT="3306"
DBNAME="TRTConseil"
#DATABASE_PASSWORD=""
MYSQL_DB_USER="root"
```

Il faut démarrer les serveurs Apache Web Server et MySQL Database dans l'application XAMPP dans la section Manage Servers

Avec cette commande, dans le terminal de votre IDE, vous créez la base de données TRTConseil

```bash
symfony console doctrine:database:create
```

Avec cette commande, dans le terminal vous créez la migration des entites :

```bash
symfony console make:migration
```

Avec cette commande, dans le terminal, vous effectuez la migration vers la base de données :

```bash
symfony console doctrine:migration:migrate
```

Avec cette commande, dans le terminal de votre IDE, vous installez des certificats pour pouvoir naviguer en https :

```bash
symfony server:ca:install
```

Vous pouvez ouvrir phpMyAdmin dans votre navigateur pour visualiser la nouvelle base de données.
[http://127.0.0.1/phpmyadmin/index.php?route=/](http://127.0.0.1/phpmyadmin/index.php?route=/)

## Fixtures

Les fixtures dans le cadre de ce projet Symfony sont des données de test prédéfinies qui sont utilisées pour remplir la base de données avec des informations fictives, simulant ainsi le fonctionnement de l'application dans un environnement de développement. Elles sont particulièrement utiles pour évaluer le bon fonctionnement de l'application, tester différentes fonctionnalités, et assurer la cohérence des données. Les fichiers des fixtures se trouvent dans le dossier [/src/DataFixtures/](/src/DataFixtures/).

Le fichier [PosteFixtures.php](/src/DataFixtures/PosteFixtures.php) crée des instances de la classe Poste avec des libelles de postes prédéfinis, enregistrant chaque instance dans la base de données. Il enregistre également une référence pour chaque poste, ce qui permet de les récupérer dans d'autres fixtures.

Le fichier [UserFixtures.php](/src/DataFixtures/UserFixtures.php) est responsable de la création de 4 comptes utilisateurs avec différents rôles (ROLE_ADMIN, ROLE_CONSULTANT, ROLE_RECRUTEUR_TOVALID, ROLE_CANDIDAT_TOVALID). Crée 28 entrées des annonces de recrutement pour chaque poste référencé avec des données aléatoires telles que le titre, le type de contrat, la ville, le salaire, à l'aide de la bibliothèque Faker. Les annonces créées sont associées à des recruteurs et des consultants.

Ces fixtures sont utilisées pour pré-remplir la base de données avec des données de test pour faciliter le développement. Elles assurent également la cohérence des données lors de l'initialisation de la base de données avec de nouvelles installations ou lors de la réinitialisation des données de test.

Chargez les fixtures dans la base de données avec la commande:

```bash
php bin/console doctrine:fixtures:load
```

Vous pouvez lancer l'application.

Avec cette commande, dans le terminal de votre IDE, vous démarrez le serveur de développement :

```bash
npx encore dev-server --hot
```

Avec cette commande, dans un nouveau terminal de votre IDE, vous lancez le serveur interne de Symfony:

```bash
symfony serve
```

Le serveur Symfony vous informe qu'il est en écoute à l'adresse https://127.0.0.1:8000
Vous pouvez ouvrir ce lien dans votre navigateur.

Au moment de la connexion selon le rôle, l'utilisateur est redirigé vers l'espace d'administration le concernant.

Lors du chargement des fixtures parmi d'autres données, des utilisateurs ont été insérés dans la base de données:

1. TRT Conseil, ROLE_ADMIN, email: admin@mail.com, mot de passe: 12345678
2. JONES Adele, ROLE_CONSULTANT, email: consultant@mail.com, mot de passe: 12345678
3. MICHELLE Eda, ROLE_RECRUTEUR_TOVALID, email: recruteur@mail.com, mot de passe: 12345678
4. MARY Lea, ROLE_CANDIDAT_TOVALID, email: candidat@mail.com, mot de passe: 12345678

Les addresses email sont des adresse fictives.

Vous avez désormais la possibilité de vous connecter à l'espace utilisateur avec une adresse email et le mot de passe.

Vous pouvez utiliser l'application Web comme décrit dans les fonctionnalités avec différents types d'utilisateurs.

Par exemple, pour activer le compte d'un recruteur inscrit, vous vous connectez avec le compte d'un consultant en utilisant l'adresse email consultant@mail.com et le mot de passe: 12345678 et dans l'onglet "Recruteurs" vous validez le compte du recruteur inscrit. Vous pouvez faire la même chose pour le compte candidat dans l'onglet "Candidats".  

Pour pouvoir tester la fonctionnalité d'envoi d'email lors de la validation d'une candidature vous devez saisir un DSN valide dans le fichie [.env](.env) ligne 48 au niveau du MAILER_DSN=smtp://EMAIL:PASSWORD@SMTP_SERVER:PORT et décommenter la ligne.

Dans le fichier [CandidatureController.php](/src/Controller/CandidatureController.php) ligne numéro 103 vous devez remplacer test@mail.com par votre adresse e-mail relative à la DSN.

Vous devez également modifier l'adresse email du recruteur dans la base de données (à l'aide de phpMyAdmin) avec une adresse email valide pour pouvoir recevoir l'email envoyé. Vous utiliserez bien évidemment cette nouvelle adresse pour vous connecter au compte recruteur.

Avec cette commande, dans le terminal de votre IDE, vous arrêtez le serveur intern de Symfony :

```bash
symfony server:stop
```

Pour arrêter le serveur de développement, utilisez la commande Control+C pour MacOS ou CTRL+C pour Windows.

**Note:**

> Il s'agit d'une application web en mode développement et non pas d'une application web en mode production.

## API

### GET /api/annonces - Liste de tous les annonces :

Cette route renvoie la liste complète de tous les annonces disponibles.
- **Méthode** : GET
- **Paramètres** : Aucun
- **Réponse** : Un tableau JSON contenant les informations des annonces.
- **Exemple d'utilisation** : GET /api/annonces

### GET /api/annoncesby - Pagination des annonces :

Cette route permet de paginer les annonces disponibles.
- **Méthode** : GET
- **Paramètres** : page (optionnel) : Numéro de la page à récupérer (par défaut : 1)
- **Réponse** : Un tableau JSON contenant les détails des annonces paginées, ainsi que des informations sur la pagination comme le numéro de page actuelle, le nombre d'annonces par page et le nombre total d'annonces.
- **Exemple d'utilisation** : GET /api/annoncesby?page=1
  
### GET /api/annonce/{id} - Détails d'une annonce spécifique :
Cette route renvoie les détails d'une annonce spécifique identifiée par son ID.

- **Méthode** : GET
- **Paramètres** : id : Identifiant de l'annonce à récupérer
- **Réponse** : Les détails de l'annonce spécifiée sous forme JSON.
- **Exemple d'utilisation** : GET /api/annonce/
  
### GET /api/annoncesby/{poste} - Annonces par poste :

Cette route permet de récupérer les annonces filtrées par poste.
- **Méthode** : GET
- **Paramètres** : poste : Nom du poste pour lequel les annonces sont recherchées
- **Réponse** : Un tableau JSON contenant les détails des annonces associées au poste spécifié, paginées si nécessaire, avec des informations sur la pagination.
- **Exemple d'utilisation** : GET /api/annoncesby/cuisinier

### GET /api/annoncesbyposte/{id} - Annonces par ID de poste :
Cette route permet d'obtenir les annonces associées à un poste spécifique identifié par son ID.

- **Méthode** : GET
- **Paramètres** : id : Identifiant du poste pour lequel les annonces sont recherchées
- **Réponse** : Un tableau JSON contenant les détails des annonces associées au poste spécifié.
- **Exemple d'utilisation** : GET /api/annoncesbyposte/456

## Diagrammes

[Diagramme de classes](/resources/Class_diagram.png)

[Diagramme de cas d'utilisations](/resources/Diagramme%20de%20cas%20d'utilisation.jpg)

[Diagramme de séqence 1](/resources/Diagramme_de_sequence.jpg)

[Diagramme de séqence 2](/resources/Diagramme%20de%20sequence_2.jpg)

## Gestion du projet

[Lien](https://trtconseil.technidan.com)