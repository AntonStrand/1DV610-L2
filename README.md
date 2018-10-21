# 1DV610

My solution for 1DV610. It fulfills 100% of the automatic tests.

It is a todo app and you can find it here: [1dv610.antonstrand.se](http://1dv610.antonstrand.se/)

### Use cases

All use cases are implemented.

1. [UC1 Add a todo item](https://github.com/AntonStrand/1DV610-L2/wiki/UC1-Add-a-todo-item)
2. [UC2 List todos](https://github.com/AntonStrand/1DV610-L2/wiki/UC2-Lista-todos)
3. [UC3 Complete a todo](https://github.com/AntonStrand/1DV610-L2/wiki/UC3-Complete-a-todo)

### Test cases

All tests are successful.

1. [Test for Use case 1](https://github.com/AntonStrand/1DV610-L2/wiki/Test-case---UC1)
2. [Test for Use case 2](https://github.com/AntonStrand/1DV610-L2/wiki/Test-case---UC2)
3. [Test for Use case 2](https://github.com/AntonStrand/1DV610-L2/wiki/Test-case---UC3)

## How to get started

This guide assumes that you know how to set up a php project and will only get into specifics to this project.

### 1. Create database

First, create a database and add a table for `users`, `cookies` and `todos`. You can use the SQL snippets below to speed up this process.

#### SQL for creating `users` table

```SQL
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

#### SQL for creating `cookies` table

```SQL
CREATE TABLE `cookies` (
  `id` int(11) NOT NULL,
  `username` varchar(128) NOT NULL UNIQUE,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

#### SQL for creating `todos` table

```SQL
CREATE TABLE `todos` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `task` text NOT NULL,
  `isComplete` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

### 2. Create a Settings.php

Create a `Settings.php` in the root folder. Use the template below and change the information to fit your setup.

```PHP
<?php
class Settings
{
    public static $HOST = 'localhost';
    public static $USER = 'user';
    public static $PASSWORD = 'password';
    public static $DB = 'dbName';
}
```
