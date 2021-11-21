## Kumu Exam (Backend Developer Assessment)

This app serves as an assessement exam for the position Backend Developer. This service allows registered users to get github users' information using their username.

## Dependencies
- Laravel passport
- Predis
- GuzzleHttp

## Installation / How to use
After successfully pulling the code base from the repository, the following steps should be executed to run the API endpoints.

### Step 1 - Create a database
Create a database named `kumu_db`
### Step 2 - Update the .env file
Fill up your localhost and database credentials and
update the following lines
```
CACHE_DRIVER=redis
REDIS_CLIENT=predis
```
### Step 3 - Install the dependencies
```
composer install
```
### Step 4 - Setup the database tables and fields and generate key
In the project dir run
```
php artisan migrate
php artisan passport:install
```
### Step 5 - Start the app
```
php artisan serve
```

### Step 6 - Access the endpoints
#### Register
```
curl --location --request POST 'http://localhost:8000/api/user/register' \
--form 'name="John Doe"' \
--form 'email="jdoe@test.com"' \
--form 'password="mypassword"'
```
#### Login / Getting the access token
```
curl --location --request POST 'http://localhost:8000/api/user/login' \
--form 'email="jdoe@test.com"' \
--form 'password="mypassword"'
```
It should return a json that has this structure
```
{
	"access_token": {access_token}
}
```
#### Fetching the github usernames information
This endpoint fetches the github users' information from it's API.
Each username is separated by comma i.e. john12,peter23,mark45
```
curl --location --request GET 'http://localhost:8000/api/githubusers/{usernames,}' \
--header 'Authorization: {access_token}'
```

## BONUS Challenge
Bonus challenge is in the _BONUS_CHALLENGE folder
