# midleo.CORE

Source code for Midleo Core application

## What is Midleo Core?
Midleo Core is software for document collaboration, knowledge base, PDF, Word import/export, middleware automation.

## Take a look

Some screenshots from the Midleo app:

### Knowledge base
![The Midleo web app](https://github.com/midleo/midleo-core/blob/master/github.assets/knowledge-base.png?raw=true)

### Application list
![The Midleo web app](https://github.com/midleo/midleo-core/blob/master/github.assets/applications.png?raw=true)

### Firewall import
![The Midleo web app](https://github.com/midleo/midleo-core/blob/master/github.assets/firewall_import.png?raw=true)

### Create package for IBM MQ deployment
![The Midleo web app](https://github.com/midleo/midleo-core/blob/master/github.assets/ibm-mq-package.png?raw=true)

### IBM MQ Preview changes befor deployment
![The Midleo web app](https://github.com/midleo/midleo-core/blob/master/github.assets/ibmmq-preview.png?raw=true)

### Server information page
![The Midleo web app](https://github.com/midleo/midleo-core/blob/master/github.assets/server-info.png?raw=true)


## How to install

```bash
cd /folder-you-want-to-install
git clone https://github.com/midleo/midleo-core.git
cd midleo.core/www/controller
composer install
```

Folder Docker contains all up to date configuration to start a container on your server or computer.

```bash
docker-compose up --build
//when everything is built -> cntrl+c
docker-compose up -d
```

- Once the containers are up and running, you need to create a user and provide permissions to the database.
- Database can be accessed via phpmyadmin on http://localhost:8082
- After that you need to start the application on address http://localhost , it will redirect to /install
- You need to select mysql, host: mariadb, database name and credentials: the one that you have created.
- Execute the script from www/data/db/mysql in the SQL window of the database.
- Go back to http://localhost/install and create your admin user account.
- You can now login with your credentials.


## Third party software

Midleo CORE is using:

- DRAW.io -> https://github.com/jgraph/drawio
- TinyMCE -> https://github.com/tinymce/tinymce
- FullCalendar -> https://github.com/fullcalendar/fullcalendar
- DropBox SDK -> https://github.com/dropbox/dropbox-sdk-js
- PhpSpreadsheet -> https://github.com/PHPOffice/PhpSpreadsheet
- PHPWord -> https://github.com/PHPOffice/PHPWord
- PHP AMQLib -> https://github.com/php-amqplib/php-amqplib
- OneDrive SDK -> https://docs.microsoft.com/en-us/onedrive/developer/rest-api/getting-started/msa-oauth?view=odsp-graph-online


## License

The Midleo CORE is licensed under the GPL v3 license.