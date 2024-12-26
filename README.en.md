<p align="center">
<a href="#">
        <img width="300" src="public/images/logo.png" alt="TRT Conseil">
</a>
<br><br>
</p>

# TRT Conseil web app

<a href="https://github.com/danian3wa/trtconseil/blob/main/LICENSE">
<img src ="https://img.shields.io/github/license/danian3wa/trtconseil" />
</a>
<a href="https://github.com/danian3wa/trtconseil/issues">
<img src ="https://img.shields.io/github/issues/danian3wa/trtconseil" />
</a><br><br>

Link to the online version of the project: [Click here](https://trtconseil.technidan.com)


## Project context

TRT Conseil is a recruitment agency specializing in the hotel and catering industry. Founded in 2014, the company has grown over the years and now has more than 12 centers scattered across France.

The coronavirus crisis having hit this sector hard, the company wants to gradually set up a tool allowing a greater number of recruiters and candidates to find what they are looking for.

TRT Conseil wants to have a minimum viable product in order to test whether the demand is really there. The agency wants to offer for the moment a simple interface with authentication.

4 types of users will need to be able to connect:

Recruiters: A company looking for an employee. 
Candidates: A server, catering manager, chef, etc. 
Consultants: Commissioned by TRT Conseil to manage the links on the back office between recruiters and candidates. 
Administrator: The person in charge of maintaining the application.

## Features

### US1. Create an account

Available for recruiters and candidates. Enter a valid email and a secure password. 
Approval of the request by a consultant before activating the account.

### US2. Log in

Accessible to recruiters, candidates, consultants and administrator. Authentication by email and password.

### US3. Create a consultant

Functionality reserved for the administrator. Allows you to add new consultants to the platform.

### US4. Complete your profile

Accessible to candidates and recruiters. Candidates can enter their first and last name and upload their CV (mandatory PDF format). Recruiters can indicate the name of the company and an address.

### US5. Publish an ad

Functionality for recruiters. Form for the job title, the place of work and a detailed description (hours, salary, etc.). Validation of the ad by a consultant before publication. List of candidates validated by TRT Conseil and having applied for the advert accessible to the recruiter.

### US6. Apply for an advert

Accessible to candidates, button to apply for an offer from the list of available ads. Approval of the application by a consultant. Sending an email to the recruiter with the name, first name and CV of the candidate if the application is approved.

## Working environment configuration

- IDE: [Visual Studio Code 1.87.2](https://code.visualstudio.com/)

- IDE: [PhpStorm 2023.3.6](https://www.jetbrains.com/phpstorm/download)

- version control system: [Git version 2.44.0](https://git-scm.com/)

- local web server: [XAMPP 8.2.4-0](https://www.apachefriends.org/download.html)

- general scripting language: [PHP 8.3.4](https://www.php.net/downloads)

- PHP dependency management: [Composer version 2.7.2](https://getcomposer.org/download/)

- development tool to create, run and manage your Symfony applications: [Symfony CLI version 5.8.14](https://symfony.com/download)

- JavaScript runtime: [Node.js 20.12.0](https://nodejs.org/en/download)

- Node.js JavaScript "npm" package manager: [npm 10.5.0](https://docs.npmjs.com/try-the-latest-stable-version-of-npm)

- npx package execution: [npx 10.5.0](https://www.npmjs.com/package/npx)

- package manager: [yarn 1.22.19](https://classic.yarnpkg.com/lang/en/docs/install/)

- Web browser: [Google Chrome 123.0.6312.87](https://www.google.com/intl/fr/chrome/)

## Installation

You can clone this repository to create a local copy on your computer:

```bash
git clone git@github.com:danian3wa/trtconseil.git
```
To use a MySQL database, you need to enable the driver in php.ini on your device if it is not already enabled. Uncomment "extension=php_pdo_mysql.dll" in your php.ini file.

After setting up the working environment you can proceed to installing the necessary components. You need to open the cloned project in your IDE. In the terminal of your IDE you must go to the folder of the newly created project after cloning if it is not already the case:

```bash
cd trtconseil
```

With this command, in the terminal you install the dependencies of the project present in: [composer.json](composer.json):

```bash
composer install
```

If composer is not installed on your working environment you will find at this address information allowing you to install:

- [https://getcomposer.org/download/](https://getcomposer.org/download/)

With this command, in the terminal you install the dependencies of the project present in [yarn.lock](yarn.lock):

```bash
yarn
```

If yarn is not installed on your working environment you will find at this address information allowing you to install:

- [https://classic.yarnpkg.com/lang/en/docs/install/](https://classic.yarnpkg.com/lang/en/docs/install/)

If node.js is not installed on your working environment you will find at this address information allowing you to install:

- [https://nodejs.org/en/download](https://nodejs.org/en/download)

In the file .env we must define the information concerning the access to the database.
DBHOST="127.0.0.1" -> for the local IP address, DBPORT="3306" -> for the port used, DBNAME="TRTConseil" -> the name of the database, DATABASE_PASSWORD="" -> without password locally, MYSQL_DB_USER="root" -> for the database username.

```bash
DBHOST="127.0.0.1"
DBPORT="3306"
DBNAME="TRTConseil"
#DATABASE_PASSWORD=""
MYSQL_DB_USER="root"
```

You need to start the Apache Web Server and MySQL Database servers in the XAMPP application in the Manage Servers section

With this command, in the terminal of your IDE, you create the database TRTConseil

```bash
symfony console doctrine:database:create
```

With this command, in the terminal you create the migration of the entities:

```bash
symfony console make:migration
```

With this command, in the terminal, you perform the migration to the database:

```bash
symfony console doctrine:migration:migrate
```

You need to start the Apache Web Server and MySQL Database servers in the XAMPP application in the Manage Servers section

With this command, in the terminal of your IDE, you create the database TRTConseil

```bash
symfony console doctrine:database:create
```

With this command, in the terminal you create the migration of the entities:

```bash
symfony console make:migration
```

With this command, in the terminal, you perform the migration to the database:

```bash
symfony console doctrine:migration:migrate
```

With this command, in the terminal of your IDE, you install certificates to be able to browse in https:

```bash
symfony server:ca:install
```

You can open phpMyAdmin in your browser to view the new database. [http://127.0.0.1/phpmyadmin/index.php?route=/](http://127.0.0.1/phpmyadmin/index.php?route=/)

## Fixtures

Fixtures in this Symfony project are predefined test data that are used to populate the database with dummy information, simulating how the application will work in a development environment. They are particularly useful for evaluating the correct operation of the application, testing different features, and ensuring data consistency. The fixture files are located in the [/src/DataFixtures/](/src/DataFixtures/) folder.

The [PosteFixtures.php](/src/DataFixtures/PosteFixtures.php) file creates instances of the Poste class with predefined post labels, registering each instance in the database. It also stores a reference for each position, which allows them to be retrieved in other fixtures.

The file [UserFixtures.php](/src/DataFixtures/UserFixtures.php) is responsible for creating 4 user accounts with different roles (ROLE_ADMIN, ROLE_CONSULTANT, ROLE_RECRUITEUR_TOVALID, ROLE_CANDIDAT_TOVALID). Creates 28 recruitment ad entries for each referenced position with random data such as title, contract type, city, salary, using the Faker library. The created ads are associated with recruiters and consultants.

These fixtures are used to pre-populate the database with test data to facilitate development. They also ensure data consistency when initializing the database with new installations or when resetting the test data.

Load the fixtures into the database with the command:

```bash
php bin/console doctrine:fixtures:load
```

You can launch the application.

With this command, in the terminal of your IDE, you start the development server:

```bash
npx encore dev-server --hot
```

With this command, in a new terminal of your IDE, you start the internal Symfony server:

```bash
symfony serve
```

The Symfony server informs you that it is listening at the address https://127.0.0.1:8000
You can open this link in your web browser.

When logging in, depending on the role, the user is redirected to the administration area concerning him.

When loading fixtures among other data, users were inserted into the database:

1. TRT Conseil, ROLE_ADMIN, email: admin@mail.com, password: 12345678
2. JONES Adele, ROLE_CONSULTANT, email: consultant@mail.com, password: 12345678
3. MICHELLE Eda, ROLE_RECRUTEUR_TOVALID, email: recrutement@mail.com, password: 12345678
4. MARY Lea, ROLE_CANDIDAT_TOVALID, email: candidat@mail.com, password: 12345678

The email addresses are fictitious addresses.

You now have the possibility to log in to the user area with an email address and password.

You can use the web application as described in the features with different types of users.

For example, to activate the account of a registered recruiter, you connect with the account of a consultant using the email address consultant@mail.com and the password: 12345678 and in the "Recruiters" tab you validate the account of the registered recruiter. You can do the same thing for the candidate account in the "Candidates" tab.

To be able to test the email sending functionality when validating an application you must enter a valid DSN in the [.env](.env) file at the level of MAILER_DSN=smtp://EMAIL:PASSWORD@SMTP_SERVER:PORT

In the file [CandidatureController.php](/src/Controller/CandidatureController.php) line number 103 you must replace test@mail.com with your email address relating to the DSN.

You also need to change the recruiter's email address in the database (using phpMyAdmin) with a valid email address to be able to receive the email sent. You will obviously use this new address to connect to the recruiter account.

With this command, in your IDE terminal, you stop the Symfony intern server:

```bash
symfony server:stop
```

To stop the development server, use the command Control+C for MacOS or CTRL+C for Windows.

**Note:**

> This is a web application in development mode and not a web application in production mode.

## SQL Insertion

### Example:

The file [schema.sql](/resources/schema.sql) was written by hand and allows to create the TRTConseil_SQL database and to insert data into this database via PhpMyAdmin.

To do this, you need to start the Apache Web Server and MySQL Database servers in the XAMPP application in the Manage Servers section if it is not already done and then in a web browser open [http://127.0.0.1/phpmyadmin/index.php](http://127.0.0.1/phpmyadmin/index.php) and select the Import tab, click on the Choose File button and select the schema.sql file from your project directory /TRTConseil/resources/ and then click on the Import button.

You need to change the database name in the [.env](/.env) file on line 33, DBNAME="TRTConseil_SQL".

Once the import is complete, you can launch the application. Among other data, 4 users have been inserted into the database:
1. TRT Conseil, ROLE_ADMIN, email: admin@mail.com, password: 12345678
2. JONES Adele, ROLE_CONSULTANT, email: consultant@mail.com, password: 12345678
3. MICHELLE Eda, ROLE_RECRUTEUR_TOVALID, email: recrutement@mail.com, password: 12345678
4. MARY Lea, ROLE_CANDIDAT_TOVALID, email: candidat@mail.com, password: 12345678

You can test the application.

## API

### GET /api/annonces - List of all ads:

This route returns the complete list of all available ads.
- **Method** : GET
- **Parameters** : None
- **Response** : A JSON array containing the listing information.
- **Example usage** : GET /api/ads

### GET /api/adsby - Ads pagination :

This route allows you to paginate the available listings.
- **Method** : GET
- **Parameters** : page (optional) : Number of the page to retrieve (default: 1)
- **Response** : A JSON array containing the details of the paginated listings, as well as information about the pagination such as the current page number, the number of listings per page, and the total number of listings.
- **Example usage**: GET /api/annoncesby?page=1

### GET /api/annonce/{id} - Details of a specific ad:
This route returns the details of a specific ad identified by its ID.

- **Method**: GET
- **Parameters**: id: Identifier of the ad to retrieve
- **Response**: The details of the specified ad in JSON form.
- **Example usage**: GET /api/annonce/

### GET /api/annoncesby/{poste} - Ads by post:

This route is used to retrieve ads filtered by post.
- **Method**: GET
- **Parameters**: post: Name of the post for which ads are searched
- **Response**: A JSON array containing the details of the ads associated with the specified post, paginated if necessary, with information on pagination.
- **Example usage** : GET /api/annoncesby/cuisinier

### GET /api/annoncesbyposte/{id} - Advertisements by job ID :
This route allows you to get the advertisements associated with a specific job identified by its ID.

- **Method** : GET
- **Parameters** : id : Identifier of the job for which the advertisements are sought
- **Response** : A JSON array containing the details of the advertisements associated with the specified job.
- **Example of use** : GET /api/annoncesbyposte/456

## Diagrams

[Class diagram](/resources/Class_diagram.png)

[Use case diagram](/resources/Diagramme%20de%20cas%20d'utilisation.png)

[Sequence diagram 1](/resources/Diagramme_de_sequence.jpg)

[Sequence diagram 2](/resources/Diagramme%20de%20sequence_2.jpg)

## Project management

[Trello link](https://trello.com/invite/b/94RP6TYI/ATTI6d07260e89068ca9fe89f0ef6f89de9eC65DF6D1/trt-conseil)
