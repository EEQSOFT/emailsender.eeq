# EmailSender with Newsletter

## Setup/Installation

### 1. Set the root directory of the php files to "public"

Set the path for your hosting:

```
<path to project>/public
```

### 2. Create a database with full user permissions

Use the database name as in your config:

```
for example: "emailsender"
```

### 3. Change the settings in the "database.php" file

Set as in the "database.php.dist" file (check "config"):

```
'db_host' => 'localhost'
'db_port' => 3306
'db_user' => '<user>'
'db_password' => '<password>'
'db_database' => 'emailsender'
```

### 4. If necessary, set these permissions

Change the permissions of the "options.php" file to 666:

```
<path to project>/config/options.php
```

Change the permissions of the directories to 777:

```
<path to project>/cron
<path to project>/data/Export
<path to project>/data/Import
```

### 5. Install the application by running the "install.php" file

Open the url below in your browser:

```
https://emailsender.<domain.com>/install.php
```

### 6. Open this web application in your browser

Open the url below in your browser:

```
https://emailsender.<domain.com>
```

### 7. Check if your hosting allows you to use exec() for Cron

The Cron class uses the exec() function to send emails:

```
exec('crontab ' . $cronFile);
```

If your email sending starts without errors, everything is fine.

## Docker Desktop

### 1. Go to the application directory

```
cd <path to project>
```

### 2. Start the application stack defined in "docker-compose.yml"

```
docker-compose up -d
```

### 3. Set the permissions and access for the "php-apache" container

```
docker exec -it php-apache bash
cd /var/www
ls -l
chmod 777 /var/www/html
chown -R www-data:www-data /var/www/html
ls -l
exit
```

### 4. Open this web application and/or phpMyAdmin in your browser

```
http://localhost:8000
http://localhost:8001
```

### 5. Stop the application stack

```
docker-compose down
```

## Information

Copyright (c) 2024 EEQSOFT

https://emailsender.eeqsoft.com
