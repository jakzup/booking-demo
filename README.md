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

# restart the containers:
docker compose restart
```


# Access the application
**Frontend:** `http://localhost:8080/`

**Admin panel:** `http://localhost:8080/admin`

**CMS panel:** `http://localhost:8080/cms`
