# COVID Vaccine Registration System

### Overview
This project is a COVID vaccine registration system built with Laravel and Tailwind CSS. The system allows users to register for vaccinations, schedule their appointments based on vaccine center availability, and check their registration status by NID. It also sends automated email notifications to users the night before their scheduled vaccination date.

### Requirements
Before setting up the project locally, ensure that you have the following installed:
````
PHP >= 8.3
Composer 2.x
Laravel 11.x
Node.js >= 16.x (for npm and Vite)
SQLite Database
Redis (for caching and queue management)
Mail Server (or use services like Mailtrap for local development)
````

### Installation
Follow these steps to set up the project locally:

#### Clone the Repository:

````
git clone https://github.com/mhsun/covac-center.git
cd covac-center
````

#### Install Dependencies:
````
composer install
````
#### Environment Configuration:

Copy the .env.example file to .env:

````
cp .env.example .env
php artisan key:generate
````

Update the .env file with your local database, Redis, and mail server configurations.
Example:

````
DB_CONNECTION=sqlite # or use your preferred database
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_user
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=null

REDIS_CLIENT=predis # or configure to use phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

CACHE_STORE=redis
CACHE_PREFIX=covax # or any prefix you prefer
````

#### Database Setup:

Create the database under database directory if you're using SQLite (if not there already).
For this simple task I didn't opt to use mysql or any other database. 
SQLite serves the purpose needed for this project unless we opt to choose a 
different scaling plan.

Run migrations with seeders:

````
touch database.sqlite
php artisan migrate --seed
````
#### Compile Front-End Assets:

Nothing needed here for now as we use tailwindcss CDN and didn't focus a lot
in the UI/UX part of the project.

#### Running the Application
To run the application locally:

Start the Laravel Development Server:

````
php artisan serve
````
Run the Background Job Worker and Queue:
````
php artisan schedule:work
php artisan queue:work
````

To run the test cases:
````
php artisan test
````

#### Optimizing the Application

To optimize the application, you should run the following commands:

````
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
````

Apart from that, we should also consider the followings:

_Queue Failed Job Cleanup:_
````
php artisan queue:flush
````

_Disable Query Logs and Debug Bar in production._

### Usage and Assumptions

- A simple _sqlite_ database has been picked for simplicity
- The NID column in the ``users`` table has been indexed (unique-index) for faster search
- Validation rules are kept simple against NID. But in real-world scenarios, we can have more complex validation rules to ensure a valid NID number.
- The database seeder creates 10 vaccine centers with random availability. This random availability is used to check the vaccine center's availability for a specific date when user picks a center in the registration form.
- A renowned package `spatie/laravel-honeypot` has been used to prevent spam in the registration form so that the form is not submitted by bots. Feel free to customise its value in the `config/honeypot.php` file.
- Caching has been used to store the vaccine centers data for an hour to display in the registration form. This is to reduce the load on the database and to improve the performance of the application. So that in the form, the vaccine centers data is fetched from the cache instead of the database. The caching time can be adjusted in the depending on the future needs.
- A caching is also used to store user's search results for a specific NID. This is to reduce the load on the database and to improve the performance of the application. So that the search results are fetched from the cache instead of the database. The caching time can be adjusted in the depending on the future needs. Currently it is set to 10 mins.
- The idea behind allocating dates via an event-listener is keep the flexibility of the system. If more actions need to be performed in the future when a user registered for a vaccine, we can easily add more listeners to the event. There could be also other possible solution like creating a route to allocate dates to users or may be a CLI command to allocate dates to users. But I chose the event-listener approach to keep the system flexible. As there was not enough story on the future cases regarding this, I opted to keep it simple.
- Storing and flushing caching is kept simple. But it can be extracted to a separate layer to make it more maintainable and scalable. (Ex: using a repository pattern)
- An atomic lock is used to avoid race conditions when allocating dates to users. This is to ensure that only one user is allocated a date at a time so that we do not exceed the vaccine center's capacity.
- Laravel's Notification is used to send mail to notify users about their scheduled vaccination date. This is chosen to keep the system flexible. **If in the future, we need to send SMS or any other notification, we can easily add more channels to the notification.**
- Please use **mailtrap** or directly view the email body in the browser to check the content of the mail. The mailtrap credentials can be updated in the `.env` file.
- The queue can be managed using the Laravel Horizon package (for multiple process and auto distribution). But for simplicity, I have used the default queue worker.
- Test cases are there in place to test the core functionalities of the application. We avoided testing the UI part as it is not the main focus of the project. The test cases can be extended to cover more scenarios.
