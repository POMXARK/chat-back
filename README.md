# Сервис чата

### фронт: https://github.com/POMXARK/chat-front

![me](https://github.com/POMXARK/chat-back/blob/develop/docs/2024-07-21_21-42.gif)


#### dev - запуск
```sh
 ./vendor/bin/sail up -d
docker exec -it chat-back-laravel.test-1 sh -c "php artisan migrate"
docker exec -it chat-back-laravel.test-1 sh -c "php artisan scribe:generate"
docker exec -it chat-back-laravel.test-1 sh -c "php artisan queue:listen"
docker exec -it chat-back-laravel.test-1 sh -c "php artisan reverb:start --port=6001"
```


#### Исправить стиль кода
```sh
docker exec -it chat-back-laravel.test-1 sh -c "php ./vendor/bin/php-cs-fixer fix"
```

#### Тестирование
```sh
docker exec -it chat-back-laravel.test-1 sh -c "./vendor/bin/sail test"
```

![me](https://github.com/POMXARK/chat-back/blob/develop/docs/2024-07-21_21-16.gif)
