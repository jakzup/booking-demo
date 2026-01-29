## First-time project setup

```
cp .env.example .env

docker compose up --build -d

docker exec -it workspace bash

composer install

php artisan key:generate
php artisan config:clear
php artisan migrate

# optional - insert some dummy data
php artisan db:seed RoomSeeder


App will be run on localhost:8080
```


