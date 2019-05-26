
Web application for students distribution among certification works' leaders
Author: Anton Poroshenko anton.poroshenko@nure.ua

<-----------------------------------------------Project------------------------------------------------------->
—остав проекта:
	app/Http/Controllers - различные контроллеры.
	app/Http/Middleware - посредники,- это фильтры обработки HTTP-запроса.
	app/Notifications - уведомление, переопредел€л только уведомление восстановлени€ парол€.
	database/migrations - миграции, дл€ создани€ таблиц.
	database/seeds - дл€ наполнени€ таблиц, по сути дл€ загрузки информации о пользовател€х достаточно создать этот класс.
	resources/views - представлени€.
	storage/app/public/ - хранилище файлов.
<------------------------------------------------------------------------------------------------------------->


<--------------------------------------------Installation----------------------------------------------------->
1. Ќеобходимо создать файл .env в корневом каталоге проекта и указать в нем базу данных, по примеру файла .env.example
2. ”становить laravel. composer, npm.
2. ¬ыполнить следующие команды в терминале PhpStorm:
	composer update --no-scripts
	npm install
	php artisan passport:install
	npm run dev
	php artisan config:cache
	php artisan migrate
	php artisan db:seed
3. ƒобавить директорию public в Open Server в качестве домена.
4. Ќаслаждатьс€. ¬ базе данных уже есть следующие пользователи:

	admin@nure.ua
	heorhii.ivashchenko@nure.ua
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
