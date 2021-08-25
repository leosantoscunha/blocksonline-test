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
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=


composer install

php artisan key:generate

php artisan migrate

php artisan serve
```

If you use docker feel free to run these commands
```
docker-compose up -d --build  
```

SQL Scripts
```
create table migrations
(
    id        int unsigned auto_increment
        primary key,
    migration varchar(255) not null,
    batch     int          not null
)
    collate = utf8mb4_unicode_ci;

create table personal_access_tokens
(
    id             bigint unsigned auto_increment
        primary key,
    tokenable_type varchar(255)    not null,
    tokenable_id   bigint unsigned not null,
    name           varchar(255)    not null,
    token          varchar(64)     not null,
    abilities      text            null,
    last_used_at   timestamp       null,
    created_at     timestamp       null,
    updated_at     timestamp       null,
    constraint personal_access_tokens_token_unique
        unique (token)
)
    collate = utf8mb4_unicode_ci;

create index personal_access_tokens_tokenable_type_tokenable_id_index
    on personal_access_tokens (tokenable_type, tokenable_id);

create table users
(
    id                bigint unsigned auto_increment
        primary key,
    user_name         varchar(255) not null,
    user_email        varchar(255) not null,
    user_password     varchar(255) not null,
    age               date         not null,
    registration_date datetime     not null,
    constraint users_user_email_unique
        unique (user_email)
)
    collate = utf8mb4_unicode_ci;

create table user_addresses
(
    id        bigint unsigned auto_increment
        primary key,
    address   varchar(255)    not null,
    city      varchar(255)    not null,
    post_code varchar(5)      not null,
    user_id   bigint unsigned not null,
    constraint user_addresses_user_id_foreign
        foreign key (user_id) references users (id)
)
    collate = utf8mb4_unicode_ci;


```

