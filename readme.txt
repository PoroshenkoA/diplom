
Web application for students distribution among certification works' leaders
Author: Anton Poroshenko anton.poroshenko@nure.ua


<--------------------------------------------Installation----------------------------------------------------->
1. Необходимо создать файл .env в корневом каталоге проекта и указать в нем базу данных, по примеру файла .env.example
2. Установить laravel. composer, npm.
2. Выполнить следующие команды в терминале PhpStorm:
	composer update --no-scripts
	npm install
	php artisan passport:install
	npm run dev
	php artisan config:cache
	php artisan migrate
	php artisan db:seed
3. Добавить директорию public в Open Server в качестве домена.
4. Наслаждаться. В базе данных уже есть следующие пользователи:

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

	Пароль для всех пользователей password. При создании собственных пользователей необходимо учитывать, что у администратора id должен быть 1.
<------------------------------------------------------------------------------------------------------------->
