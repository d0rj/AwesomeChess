# Awesome chess

## Description

Simple chess backend REST/RESTful API written in pure PHP. 

## Features

1. Account features:
    * Users creation;
    * Signin and sessions;
    * Users searching by name, id, rating;
    * User information editing;
2. Chess functions:
    * Game creation;
    * Search list of available games for logged user;
    * Search game by id;


## API

All `POST` requests arguments must be in form data.

All `PUT` requests arguments must be in raw JSON format.

### Account 

`GET /api/users` - list of all registered users with rating;

`GET /api/users/[some_id:integer]` - get user with ID;

`GET /api/users/[some_name:string]` - get user with given name;

`GET /api/users/rating/[some_rating:integer]` - get users with given rating;

`POST /api/users` - registers user with `name`, `email` and `password`;

`PUT /api/users` - update some user information. Arguments: `newName`, `newEmail`, `newPassword`. You need to be signed in;

### Login

`GET /api/login` - get your login status;

`POST /api/login` - try to sign in. Arguments: `email` and `password`;

### Game

`GET /api/game` - get all available games for signed in user;

`GET /api/game/[some_id:integer]` - search game by given id;

`POST /api/game` - create new game with second person as black player. Arguments: `secondEmail`;


## Roadmap

* Rework chess engine. Allow users to 'castling', to change the pawn that has reached the end;
* Create some bots for single play;
* 