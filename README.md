## Installation

Rename configuration file

```
.env.example to .env
```

First compile container with command:

```
docker-compose build
```

Start container with command:

```
docker-compose up -d
```

Install composer

```
docker-compose run composer install
```

sometime w can get composer error to can't find gd module, in this case use command

```
docker-compose run composer install --ignore-platform-reqs
```

Generate artisan key

```
docker-compose run artisan key:generate
```

Install migrations

```
docker-compose run artisan migrate:fresh --seed
```

Stop Docker Container

```
docker-compose down
```

How view route list

```
docker-compose run artisan route:list
```

## Run front app
```
npm run dev
```
```
http://127.0.0.1:8000/
```

## API Documentation
```
docker-compose run artisan l5-swagger:generate
```
```
https://localhost/api/documentation
```




## URL
To open in browser use HTTPS **https://localhost**

To open in browser use HTTP **http://localhost:8000**


## PHPMyAdmin

open **http://localhost:8083** and use DB_USERNAME & DB_PASSWORD from **.env**
