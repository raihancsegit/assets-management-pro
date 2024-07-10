# Personal Asset Management:
Manage you own assets easily

## ENV file (amp-app/.env):
Rename `.env.example` to `.env` from `amp-app/` and update following lines
```
DB_CONNECTION=mysql
DB_HOST=amp-database 
DB_PORT=3306
DB_DATABASE=amp
DB_USERNAME=amp
DB_PASSWORD=amp
```

## Setup:
```
docker-compose build --no-cache --force-rm
docker-compose up -d
docker exec amp-app bash -c "composer update"
docker exec amp-app bash -c "php artisan key:generate"
docker exec amp-app bash -c "php artisan migrate"
docker exec amp-app bash -c "php artisan seed"
```

## phpmyadmin:
http://localhost:8081

## app-server:
http://localhost:8080

## Test (Pint)
```
./vendor/bin/pint --test
./vendor/bin/pint
```
