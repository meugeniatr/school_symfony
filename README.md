# my_movies_list

## Pre-requisite

### Install composer

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer
```

### Install symfony

Install:
```bash
wget https://get.symfony.com/cli/installer -O - | bash
```

Upgrade:
`symfony`

It will ask you to upgrade say `Y`, be sure it works, you might need to use `sudo`

### Mysql

You need to have a MYSQL Database runnning

### Install node

Go to [NodeJS](https://nodejs.org/) to follow step to install.

### Install dependencies

To install local dependencies for the project. They **ARE NOT COMMITED** and **SHOULD NOT**.
`composer install`

`npm install`

### Build encore assets

In development:
`npm run watch`

In Production
`npm run build`

### Environment

Copy .env.example to .env and modify it to have the correct DATABASE_URL

### Create Database

Create db: `php bin/console doctrine:database:create`

Run migration to initiate the Database: `php bin/console doctrine:migrations:migrate`

## Run the server

In the commande line:
`symfony server:start`

Then in the browser:
`http://localhost:8000`

