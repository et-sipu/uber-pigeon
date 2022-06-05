## System Requirement

- Laravel 9
- PHP 8.1.5 (PHP > 8)
- Composer version 2.0.14

## Installation

Clone the project in your workspace

    $ git clone https://github.com/et-sipu/uber-pigeon.git
    $ cd uber-pigeon

Copy .env file

    $ copy .env.localhost .env

Install composer

    $ composer install

Run php artisan command

    $ php artisan key:generate
    $ php artisan migrate
    $ php artisan serve

## API details

This API uses GET request. The URL request are as shown below:

	{APP_URL}/api/checkOrder/distance/{distance}/deadline/{deadline}/pigeon/{pigeon_name}

Example:

	localhost:8000/api/checkOrder/distance/600/deadline/2022-06-07 01:00:00/pigeon/Antonio

<b>IMPORTANT: </b>This project uses a simple token authentication. Token key are required in the header during API request. The token key/value are specifically as shown below:

	token: da96b638852b05fe49a2368fd140538a

Request header sample for this API:

	GET /api/checkOrder/distance/600/deadline/2022-06-07 01:00:00/pigeon/Antonio HTTP/1.1
	Host: localhost:8000
	Accept: application/json
	token: da96b638852b05fe49a2368fd140538a
	Cache-Control: no-cache
	Postman-Token: c8b788df-119d-1356-5ecd-eb176d938e8e
