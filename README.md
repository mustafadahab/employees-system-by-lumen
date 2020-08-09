# employees system by lumen
Create employment system with lumen microservices

# This Project using lumen as API framework and Swagger as live API Documentation tools :

# How this library works:

## Clone the repository

```sh
    https://github.com/mustafadahab/employees-system-by-lumen.git
```


## Install all the dependencies using composer
```sh
    composer install
```

## make the required configuration changes (edit database name and connection) in the .env file

## Run the database migrations
```sh
    php artisan migrate
```

## Start the local development server

```sh
php -S localhost:8000 -t public
```

You can now access the server at http://localhost:8000

## OR

## create virtual domain pointing to the public directory

# Swagger Part

generate documentation using swagger on each update
```sh
php artisan swagger-lume:generate 
```

## Access documentation
```sh
<yourproject-name>/public/api/documentation
```

# Front-end part
```sh
front-end/html/index.html
```



