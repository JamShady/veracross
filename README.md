Requirements
- Php 8.2
- Laravel 11+
- MySql 8+
- Node 18+
- NPM 6.14.6 or Yarn 1.13.0

Installation instructions

1. Run "composer install".
2. Create a new MySql databse with utf8mb4 encoding
3. Copy the .env.example and rename as .env.
4. Update .env with your MySQL database connection details.
5. Run "php artisan key:generate".
6. Run "php artisan migrate:fresh --seed".
7. Run "php artisan db:seed" command
8. Run "npm install" command or "yarn install" command
9. Run "npm run dev" command or "yarn run dev" command
10. Run "php artisan serve".

Using php artisan serve
- Browse to the url the "php artisan serve" command output to view the application.

Using Valet
- Run "valet link"
- Browse to the url the "valet links" command output to access the application
