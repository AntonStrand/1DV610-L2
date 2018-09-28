# 1DV610 L2

My solution for L2. It fulfills 100% of the automatic tests.

## How to get started

This guide assumes that you know how to set up a php project and will only get into specifics to this project.

### 1. Create database

First, create a database and add a table for `users` and another for `cookies`.

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
