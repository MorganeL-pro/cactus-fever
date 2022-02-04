# Cactus Fever*

## Presentation

It's symfony website-skeleton project with some additional library (webpack, fixtures) and tools to validate code standards.

* GrumPHP, as pre-commit hook, will run 2 tools when `git commit` is run :
  
    * PHP_CodeSniffer to check PSR12 
    
  If tests fail, the commit is canceled and a warning message is displayed to developper.

### Prerequisites

1. Check composer is installed
2. Check yarn & node are installed

### Install

1. Clone this project
2. Run `composer install`
3. Run `yarn install`
4. Run `yarn encore dev` to build assets
5. Create a file .env.local in root (copy of .env file)
6. Configure your database in .env.local

### Working

1. Run migrations files with `bin/console d:m:m`
2. Run `symfony server:start` to launch your local php web server
3. Run `yarn run dev --watch` to launch your local server for assets

==> A special user with admin role is created. Ask me for password to test admin panel

### Testing

Run `php ./vendor/bin/phpcs` to launch PHP code sniffer

### Windows Users

If you develop on Windows, you should edit you git configuration to change your end of line rules with this command :

`git config --global core.autocrlf true`

## Deployment

Some files are used to manage automatic deployments (using tools as Caprover, Docker and Github Action). Please do not modify them.

* [captain-definition](https://github.com/WildCodeSchool/sf4-pjt3-starter-kit/blob/master/captain-definition) Caprover entry point
* [Dockerfile](https://github.com/WildCodeSchool/sf4-pjt3-starter-kit/blob/master/Dockerfile) Web app configuration for Docker container
* [docker-compose.yml](https://github.com/WildCodeSchool/sf4-pjt3-starter-kit/blob/master/docker-compose.yml) ...not use it's used 😅
* [docker-entry.sh](https://github.com/WildCodeSchool/sf4-pjt3-starter-kit/blob/master/docker-entry.sh) shell instruction to execute when docker image is built
* [nginx.conf](https://github.com/WildCodeSchool/sf4-pjt3-starter-kit/blob/master/nginx.conf) Nginx server configuration
* [php.ini](https://github.com/WildCodeSchool/sf4-pjt3-starter-kit/blob/master/php.ini) Php configuration


## Built With

* [Symfony](https://github.com/symfony/symfony)
* [GrumPHP](https://github.com/phpro/grumphp)
* [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)

## Authors

Morgane Lailler (and some support form Maxime, Bruno and Elodie)

## Ressources

* [Google Doc](https://docs.google.com/document/d/1PZM_1EBGxjuZ-Cxmx0NVS8WtejDLlMU2SBTo5tPsB_M/edit?usp=sharing)
