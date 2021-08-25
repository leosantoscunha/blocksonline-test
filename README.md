# Blocks Online test create by Leonardo Cunha

### Setup

```
cp .env.example .env
```


Change your db credetials
```

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blocks_db
DB_USERNAME=root
DB_PASSWORD=secret


composer install

php artisan key:generate

php artisan migrate

php artisan serve
```
