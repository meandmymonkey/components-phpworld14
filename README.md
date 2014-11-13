# About

Code examples for my Symfony2 Components talk at php[world] 2014.

## Install

Clone this repository and make sure you have a recent version of composer installed. Install dependencies with

    $ ./composer.phar install
    
Create a database and table

    CREATE database components_phpworld14;

    CREATE TABLE `todo` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(255) NOT NULL,
        `is_done` tinyint(1) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
Run the project using

    $ php -S 0.0.0.0:8080 -t web
    
Navigate to http://localhost:8080/app.php
  
