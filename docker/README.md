## Midleo

Midleo Docker images

## MidlEO setup

    create volume -

    db-data

    . This is where all db data will be stored, even if container is restarted, data will be there.
    environment variable

    MYSQL_ROOT_PASSWORD: password

    - sets root password for mariadb container.
      environment variable

    PMA_ARBITRARY=1

    - adds "server" input field to phpmyadmin login page (this way you can use this phpmyadmin with an external MySQL DB, and not just this local setup)
    environment variable

    PMA_HOST=mariadb

    - told phpmyadmin how to connect to mariadb map ports for

    phpmyadmin - 8082:80  - this maps inner port 80 from inside the container, to port 8000 on my host machine

    depends_on - prevents container to start before other container, on which it depends

## MidlEO Docker run

docker-compose up --build   # cntrl+c
docker-compose up -d        # start in background mode
docker-compose down         # stop resources