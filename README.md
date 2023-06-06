## Flightradar24 API task

I made a few tweaks and adjustments to the base description of the assignment. Firstly I separated the logic between a flight and a ticket, so they are their own respective entities.
I also added rudimentary user and token management using Laravel Sanctum.

Finally I did a few tweaks to the seating, so that when you create a ticket you can either choose to pick a seat through the API, or just let the API assign you a random seat. You can also define how many seats are available on any given flight, when you create the flight object.

## Prerequisites

-   PHP >=8.1
-   Mysql Database

## Installation

-   Clone the repository into your desired folder
-   Run "composer install"
-   Copy ".env.example" to ".env" and update your database connection variables
-   Run "php artisan key:generate"
-   Run "php artisan migrate --seed"

`NOTE` After successfully running the migration, you will have 3 "dummy" client accounts to test with. The API-tokens for these will be visible in the terminal. Use these api-tokens in the Bearer tokens in the Authorization header to access the API.

Then simply run `php artisan serve` to start up the app.

## The API

App base uri: `/api/fr24/v1`

All payloads to the API should be posted as JSON-content in the Body

### Create a flight

`POST` /flights

| Params          | Description                                                                            |
| :-------------- | :------------------------------------------------------------------------------------- |
| origin          | The origin airport IATA code **Required**, **3 characters long**, **Uppercase**        |
| destination     | The destination airport IATA code - **Required**, **3 characters long**, **Uppercase** |
| departure_time  | Datetime of departure, UTC - **Required**, **Format: YYYY-MM-DD HH:ii:ss**             |
| departure_time  | Datetime of arrival, UTC - **Required**, **Format: YYYY-MM-DD HH:ii:ss**               |
| available_seats | Amount of seats available - **Required**, **Integer**, **Range: 1-32**                 |

### View flights

`GET` /flights

### View tickets for a flight

`GET` /flights/{flight_id}/tickets

### Create a ticket for a flight

`POST` /flights/{flight_id}/tickets

| Params          | Description                                                                                                                                                       |
| :-------------- | :---------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| passport_ref_no | Passport ID Code - **Required**, **Length: 8-60**, **Alphanumerical**                                                                                             |
| seat            | Desired seat on the flight - **Optional**, **Integer**, **Range: 1 through available_seats on the flight** <br />_If omitted, a seat will be assigned at random._ |

`Sidenote` _If I would have had a little bit more time, I would have liked to add a bit more details to a ticket, such as first/last name, date of birth etc. I can gladly explain how I would have approached this though. :)_

### View a single ticket

`GET` /tickets/{ticket_id}

### Update a ticket

`PUT` /tickets/{ticket_id}

| Params          | Description                                                                                                |
| :-------------- | :--------------------------------------------------------------------------------------------------------- |
| passport_ref_no | Passport ID Code - **Optional**, **Length: 8-60**, **Alphanumerical**                                      |
| seat            | Desired seat on the flight - **Optional**, **Integer**, **Range: 1 through available_seats on the flight** |

### Cancel a ticket

`DELETE` /tickets/{ticket_id}
