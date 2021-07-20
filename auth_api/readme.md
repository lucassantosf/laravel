## Requisitos
- PHP 7.2 ou +
- MSYQL/PHPMyADMIN (Windows: Recomendado usar WAMP - http://www.wampserver.com/en/)
- Composer (https://getcomposer.org/)

## Como utilizar
- 1) Renomeie o arquivo ".env.example" para ".env" e configure os campos APP_URL, DB_DATABASE, DB_USERNAME e DB_PASSWORD.
- 2) Execute no terminal os seguintes comandos no diretório do projeto: 
- 3) "composer install" 
- 4) "php artisan key:generate"
- 5) "npm install"
- 6) "php artisan migrate --seed"
- 7) "php artisan passport:install"  