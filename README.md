# Description
Laravel 9 boilerplate for a handy REST API backend development.<br />

_Project created mainly for myself - to learn and practice Laravel._



# Requirements
* PHP: >=8.0 
* MySQL: >=5.7
* Composer



# Packages used
* [php-open-source-saver/jwt-auth](https://github.com/PHP-Open-Source-Saver/jwt-auth "php-open-source-saver/jwt-auth") <br />
This repository is a fork from original [tymonsdesigns/jwt-auth](https://github.com/tymondesigns/jwt-auth/ "tymonsdesigns/jwt-auth"). Bunch of enthusiasts decided to work independent, because the original package was not being updated for a long time.



# Features
- Laravel 9
- REST API
- Authentication with JWT Tokens
- Refresh Tokens
- Login, register, email verification and password reset
- Role-based permissions
- Users management
- More to come...



# Installation
Clone repository:<br />
```
git clone https://github.com/zikju/laravel-rest-api-starter
```


Install composer dependencies:<br />
```
composer install
```

Rename file **_.env.example_**  to **_.env_**<br />
```
cp .env.example .env
```

Change MySQL logins variables to match your own database settings:

```dotenv
 DB_HOST=localhost
 DB_PORT=3306
 DB_DATABASE=database_name
 DB_USERNAME=root
 DB_PASSWORD=
```

Generate laravel app key:<br />
```
php artisan key:generate
```

Generate JWT secret key:<br />
```
php artisan jwt:secret
```

Migrate tables to database:<br />
```
php artisan migrate
```

Run server:<br />
````
php artisan serve
````




# POSTMAN
For easiest way to test endpoints - import file `POSTMAN_ENDPOINTS.json` into your [Postman](https://www.postman.com/ "Postman") workflow. <br />
  After file import - find Collection variables and change `API_URL` to your project url.



# IDE-Helper
For better development experience consider to use [barryvdh/laravel-ide-helper](https://github.com/barryvdh/laravel-ide-helper) (already pre-installed by default).
This package generates helper files that enable your IDE to provide accurate autocompletion. Generation is done based on the files in your project, so they are always up-to-date.

Run this commands:

```
php artisan clear-compiled
```

```
php artisan ide-helper:generate
```

```
php artisan ide-helper:meta
```



# JWT Access Tokens & Refresh Tokens

**Access Token** - is JWT token. Used to authorize requests and store in payload some additional information about the user (for example: _user_id_, _user_role_ and so on...).

**Refresh Token** - issued by the backend server upon successful authentication and is used to obtain a new pair of access/refresh tokens.

Each token has its own lifetime, for example access: 30 min, refresh: 2 hours.

You free to override tokens lifetime in `.env` file:

`JWT_TTL=60` - _Access Token_ lifetime in minutes

`JWT_REFRESH_TOKEN_TTL=120` - _Refresh Token_ lifetime in minutes

`JWT_REFRESH_TOKEN_HEADER_KEY="X-REFRESH-TOKEN-ID"` - HTTP Header name that will pass _Refresh Token_ from frontend



# Role permissions

By default, `users` database table contains these roles:
* `user`
* `manager`
* `admin`

User with role `admin` - can bypass any role-checker and access any route.



For example, you can protect certain routes with custom middleware:

```php
Route::post('users', [UserController::class, 'delete'])->middleware('role:manager');
```

You can allow multiple roles, just divide them with commas:

```php
Route::post('users', [UserController::class, 'delete'])->middleware('role:user,manager');
```
<br />

### To change `role` column values in database via migrations, you have to use `DB::statement`

Migration snippet:
```php 
public function up():
{
    \DB::statement("ALTER TABLE `users` CHANGE `role` `role` ENUM('user','manager','admin','super-admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user';");
}
```



# Endpoints

### Authentication:
| Method | Endpoint                         | Parameters                                                                                                           | Description         |
|--------|----------------------------------|----------------------------------------------------------------------------------------------------------------------|---------------------|
| `POST` | `/auth/login`                    | `email` *string* **required**<br/>`password` *string* **required**                                                   | login user          |
| `GET`  | `/auth/logout`                   |                                                                                                                      | logout user         |
| `GET`  | `/auth/refresh-tokens`           |                                                                                                                      | refresh tokens      |


### Registration:
| Method | Endpoint                         | Parameters                                                                                                           | Description         |
|--------|----------------------------------|----------------------------------------------------------------------------------------------------------------------|---------------------|
| `POST` | `/auth/register`                 | `email` *string* **required**<br/>`password` *string* **required**<br/>`password_confirmation` *string* **required** | registration        |
| `PUT`  | `/auth/register/confirm`         | `token` *string* **required**                                                                                        | confirm email       |


### Password recovery:
| Method | Endpoint                         | Parameters                                                                                                           | Description         |
|--------|----------------------------------|----------------------------------------------------------------------------------------------------------------------|---------------------|
| `PUT`  | `/auth/recovery/send-email`      | `email` *string* **required**                                                                                        | send recovery email |
| `PUT`  | `/auth/recovery/change-password` | `token` *string* **required**<br/>`password` *string* **required**<br/>`password_confirmation` *string* **required** | save new password   |

### Users create/delete:
| Method   | Endpoint | Parameters                                                                                                           | Description     |
|----------|----------|----------------------------------------------------------------------------------------------------------------------|-----------------|
| `POST`   | `/users` | `email` *string* **required**<br/>`password` *string* **required**<br/>`password_confirmation` *string* **required** | Create new User |
| `DELETE` | `/users` | `id` *string* **required**                                                                                           | Delete user     |
