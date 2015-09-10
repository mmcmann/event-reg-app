# Event Registration App

## Download

* https://github.com/mmcmann/event-reg-app

## Requirements

* Apache 2
* MySQL 5.5
* PHP 5.5

## Installation

* Unzip on the server or use Git
* Import `/events.sql` into your MySQL server
* Change values in `/api/config.php`
* Open `http://server.com/path/to/event-reg-app/`

## Demo

* Live demo: https://php-events-mmcmann.c9.io/event-reg-app/
* Sign up, or use username `mmcmann2@gmail` and password `1234` to see a user with past events

## Features

* Built with AngularJS, PHP, and MySQL
* Uses RESTful API architecture
* Built entirely in the cloud on [Cloud9](http://c9.io)
* Uses Slim PHP framework
* Form validation (client-side and server-side)
    * Password matching
    * Angular directive forcing numbers only in the phone field
* Data validation and sanitizing
    * Password hashing
    * PHP filters
    * PDO used to avoid SQL injections
* Normalized relational database architecture with LEFT JOINS and all that good stuff
* PDO extension used for extensibility and added security

## Improvements

This app is not production ready. It is intended as a working
prototype.

Some future development:

* Refactor, abstract, decompose, etc.
* Comments and TODOs
* Implement a more OO PHP approach
* Implement BDD testing
* Manage libraries with composer and bower/npm/etc
* Implement build process with Gulp/Grunt
* Obviously much more to do with the business logic in terms of
the registration system

