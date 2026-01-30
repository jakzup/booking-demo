## Quickstart guide

```bash
# 1. Environment setup
cp .env.example .env

# 2. Start Docker containers (storage symlink created automatically)
docker compose up --build -d

# 3. Access workspace container
docker exec -it workspace bash

# 4. Install dependencies
composer install

# 5. Generate app key and setup database
php artisan key:generate
php artisan config:clear
php artisan migrate

# 6. Optional - insert dummy data
php artisan db:seed RoomSeeder
```

**Application will be available at** `http://localhost:8080`

**Admin panel:** `http://localhost:8080/admin`
**CMS panel:** `http://localhost:8080/cms`


