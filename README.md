## About this app

With this app users can register their own custom workouts or be challenged by any of the predefined ones. Admin users can manage predefined workouts and exercises for users to choose.

### Frameworks used

- [Laravel 9](https://laravel.com/)
- [Inertia.js](https://inertiajs.com/)
- [Vue.js 3](https://vuejs.org/)
- [Tailwind CSS 3](https://tailwindcss.com/)

## Instructions to install

- Clone the repository
- Composer install
- Create the .env file by using the console command "cp .env.example .env"
- Create a database
- Fillup the .env file database requirements with your local settings
- Generate key using "php artisan key:generate"
- Migrate the database with seed "php artisan migrate:fresh --seed"
- Serve the project with "php artisan serve"
