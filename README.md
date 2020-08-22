# krass - BE: Work Sample
A sample RESTful API that allows Paystack users carry out money transfers as well as list and search through their transfer history.

## Project Requirements
* MySQL database
* Redis server
* Apache web server
* PHP 7.2.5 or higher

## Project Setup
#### Database Setup
* Create a new MySQL database called "krass"
* Create a new user MySQL user with username = "krass" and password = "krass"
* Grant the new MySQL user ("krass") full privileges to the "krass" database

#### Project Initialization &amp; Configuration
* Open a new terminal window in a folder of your choice
* From the terminal, run the command `git clone https://github.com/kheme/krass.git`
* Enter the the "krass" folder and make a copy of the `.env.example` with the command `cp .env.example .env`
* Open the `.env` file in an editor of your choice, and set your `REDIS_PASSWORD` and `PAYSTACK_SECRET_KEY`
* From the terminal window, run the following commands:
  * `composer update` to install dependencies
  * `php artisan migrate` to migrate database tables
  * `php artisan passport:install` to install Lumen Passport
  * `php artisan passport:keys` to generate Lumen Passport keys
  * Finally `php -S localhost:8000 -t ./public` to start the built-in development server

## Krass API Documentation
Documentation for this API is available on Postman via [https://documenter.getpostman.com/view/8339368/T1LLFoLr](https://documenter.getpostman.com/view/8339368/T1LLFoLr)

### Paystack API Documentation
Paystack's API documentation is publicly available via [https://paystack.com/docs/api/](https://paystack.com/docs/api/)