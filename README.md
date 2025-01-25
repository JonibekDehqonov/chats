1. Скачать проект
Клонируйте проект на свой компьютер:
git clone https://github.com/JonibekDehqonov/proIO.git



После клонирования перейдите в папку проекта:
cd project-name

2. Установите необходимые пакеты
Пакеты устанавливаются в проекты Laravel через Composer. Затем выполните следующую команду:

composer install

3. Настройка файла конфигурации
Скопируйте файл .env.example из папки проекта с именем .env:

4. Подключение к базе данных: Измените следующие строки в файле .env в соответствии с вашей базой данных:
.env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

Подготовка базы данных
Создание базы данных:

Добавьте базу данных с именем your_database_name в MySQL или другую базу данных.
Управление миграциями в проекте:

php artisan migrate

php artisan migrate:fresh  --seed

Создание кэш-файлов и файлов питания
Чтобы очистить кэши и создать необходимые файлы:

php artisan config:cache
php artisan key:generate

Установка пакета Passport
composer require laravel/passport

Миграция таблиц паспортов
php artisan migrate

Добавить ресурсы паспорта
php artisan passport:install


Установка пакета для Swagger
composer require darkaonline/l5-swagger

php artisan l5-swagger:generate

php artisan serve

http://localhost:8000/api/documentation#/
