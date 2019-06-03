
Web application for students distribution among certification works' leaders
Author: Anton Poroshenko anton.poroshenko@nure.ua

<-----------------------------------------------Project------------------------------------------------------->
—остав проекта:
	app/Http/Controllers - различные контроллеры.
	app/Http/Middleware - посредники,- это фильтры обработки HTTP-запроса.
	app/Notifications - уве
	домление, переопредел€л только уведомление восстановлени€ парол€.
	database/migrations - миграции, дл€ создани€ таблиц.
	database/seeds - дл€ наполнени€ таблиц, по сути дл€ загрузки информации о пользовател€х достаточно создать этот класс.
	resources/views - представлени€.
	storage/app/public/ - хранилище файлов.
<------------------------------------------------------------------------------------------------------------->


<--------------------------------------------Installation----------------------------------------------------->

“очно не знаю в чем проблема, у мен€ на полностью сыром ноуте все заработало, возможно дело в версии Open Server, оно не могло нормально подключитс€ к базе из-за ее версии.
я ставил Open Server 5.3.0 Premium (php 7.3 x64, MySQL 8.0 x64, PostgreSQL 11.2 x64). –аботает как на MySQL так и на PostgreSQL.


1. ”становить composer, npm, laravel.
2. Ќеобходимо создать файл .env в корневом каталоге проекта и указать в нем базу данных, по примеру файла .env.example
3. ¬ыполнить следующие команды в терминале PhpStorm:
	composer update
	npm install
	php artisan key:generate
	npm run dev
	php artisan config:cache
	php artisan migrate:fresh
	php artisan passport:install
	npm run dev
    php artisan config:cache
	php artisan migrate:fresh
	php artisan db:seed
4. ƒобавить директорию public в Open Server в качестве домена.
5. Ќаслаждатьс€. ¬ базе данных уже есть следующие пользователи:

	admin@nure.ua
	heorhii.ivashchenko@nu
	re.ua
	leader2@nure.ua
	leader3@nure.ua
	leader4@nure.ua
	examiner1@nure.ua
	stud1@nure.ua
	stud2@nure.ua
	stud3@nure.ua
	stud4@nure.ua

	ѕароль дл€ всех пользователей password. ѕри создании собственных пользователей необходимо учитывать, что у администратора id должен быть 1.
<------------------------------------------------------------------------------------------------------------->
