# Midleo Core

Source code for Midleo Core application

## What is Midleo Core?
Midleo Core is software made by IT for IT. It can help people working in administration and development section to better organize their resources - servers, firewal rules, application servers, documents, visio diagrams. It can help for better organize your daily tasks and collaborate more with the colleagues.

## Take a look

Some screenshots from the Midleo app:

### Project management
![The Midleo web app](https://gitlab.com/midleo/midleo-core/-/raw/master/gitlab.assets/service-management.png)

### Application list
![The Midleo web app](https://gitlab.com/midleo/midleo-core/-/raw/master/gitlab.assets/applications.png)

### Create package for IBM MQ deployment
![The Midleo web app](https://gitlab.com/midleo/midleo-core/-/raw/master/gitlab.assets/ibm-mq-package.png)

### Server information page
![The Midleo web app](https://gitlab.com/midleo/midleo-core/-/raw/master/gitlab.assets/server-info.png)

## How to install

There is a folder Docker. It contains all up to date configuration to start a container on your server or computer.

- docker-compose up --build
- when everything is built -> cntrl+c
- docker-compose up -d

Once the containers are up and running, you need to create a user and provide permissions to the database.
Database can be accessed via phpadmin on http://localhost:8082

After that you need to start the application on address http://localhost , it will redirect to /install
You need to select mysql, host: mariadb, database name and credentials: the one that you have created.
In www/data/db/mysql, you can find the script that can be pasted in the SQL window of the database.

## License

The Midleo CORE is licensed under the GPL v3 license. See the [license file](https://gitlab.com/midleo/midleo-core/-/blob/master/LICENSE) for more information.