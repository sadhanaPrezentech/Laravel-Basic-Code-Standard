## Credentials

### Admin

```
Email: sadhana@prezentechnolabs.com
Password: admin123

```

### User

```
Email: sadhana@gmail.com
Password: user123
```

---
## Installation steps
 

 "php": "^7.3|^8.0",.
 "laravel/framework": "^8.75",

 
-   clone from laravel_CRUD repository
-   copy appropriate .env as per server environtment from root of project folder
-   For local server, run below command:

```
sudo cp .env.local .env
```

-   For staging server, run below command:

```
sudo cp .env.staging .env
```

-   For staging server, run below command:

```
sudo cp .env.staging .env
```

-   For production server, run below command:

```
sudo cp .env.production .env
```

-   after copying run below command

```
composer install
```

-   run migration and seed using below command

```
php artisan migrate --seed
```

-   To install passport

```
php artisan passport:install
```

-   To install telescope

```
php artisan telescope:install
```

-   Keep Queue running on your local system using below command
```
php artisan queue:work
```

-   When any changes has been added into the Queueble files means in the file where "implements ShouldQueue" there, you should run below command to make affect in on going queue
```
php artisan queue:restart
```

---
## Use below seeder to store all the permissions of all the modules

```
php artisan db:seed --class=PermissionSeeder
```
