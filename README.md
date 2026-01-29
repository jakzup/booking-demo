## First-time project setup

```
cp .env.example .env

docker compose -f compose.dev.yaml up --build -d

docker exec -it workspace bash

composer install

php artisan key:generate
php artisan config:clear
php artisan migrate

# optional - insert some dummy data
php artisan db:seed RoomSeeder
```


