# employees system by lumen
Create employment system with lumen microservices

# This Project using lumen as API framework and Swagger as live API Documentation tools :

# How this library works:

## Clone the repository

```sh
    git clone https://github.com/mustafadahab/employees-system-by-lumen.git
```


## Install all the dependencies using composer
```sh
    composer install
```

## make the required configuration changes (edit database name and connection) in the .env file

## Run php artisan passport:install to get (CLIENT_ID,CLIENT_SECRET) the add then to .env file
```sh
    CLIENT_ID= 1
    CLIENT_SECRET= abcdefg....
```

## you need to generate APP_KEY and place it on .env file 
go to ``` yourproject-name```/public/key_gen
copy the key and and past it on 
```sh
    APP_KEY=kjhkjhkkjh
```



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

## Integrate Swagger 
go to user controle change the following
```sh
     *      @OA\Server(
     *         description="Production",
     *         url="yourproject-name/public",
     *     )
```

## Access documentation
```sh
yourproject-name/public/api/documentation
```

# Front-end part
```sh
front-end/html/index.html
```



