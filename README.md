# this app run use CI 4 and PHP 8.1.10

please do composer install after clone project
please create .env file before start. Use cp env .env

do run : php spark migrate
do run : php spark db:seed UserSeeder  

seeder user : 
email : admin@mail.com
password: admin123 

see all routes using : php spark routes

this code use JWT Auth

run project by : php spark serve