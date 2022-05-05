# Description
Laravel 9 boilerplate for a handy REST API backend development.<br />

_Project created mainly for myself - to learn and practice Laravel._

# Requirements
* PHP: >=8.0 
* MySQL: >=5.7
* Composer

# Packages used
[php-open-source-saver/jwt-auth](https://github.com/PHP-Open-Source-Saver/jwt-auth "php-open-source-saver/jwt-auth") <br />
_This repository it a fork from original [tymonsdesigns/jwt-auth](https://github.com/tymondesigns/jwt-auth/ "tymonsdesigns/jwt-auth"). Open-source enthusiasts decided to work independent, because the original one was not being updated for long time and keep doing support for the application._

# Features
- JWT Token Authentication
- Refresh Tokens
- Registration
- Password recovery
- Users management
- More to come...

# Installation
_TBA_

# JWT Access Tokens & Refresh Tokens

**Access Token** - is JWT token. Used to authorize requests and store in payload some additional information about the user (for example: _user_id_, _user_role_ and so on...).

**Refresh Token** - issued by the backend server upon successful authentication and is used to obtain a new pair of access/refresh tokens.

Each token has its own lifetime, for example access: 30 min, refresh: 30 days

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
