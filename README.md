# Сервис чата

#### dev - запуск
```sh
 ./vendor/bin/sail up -d
docker exec -it chat-back-laravel.test-1 sh -c "php artisan migrate"
docker exec -it chat-back-laravel.test-1 sh -c "php artisan scribe:generate"
```


#### Исправить стиль кода
```sh
docker exec -it chat-back-laravel.test-1 sh -c "php ./vendor/bin/php-cs-fixer fix"
```
