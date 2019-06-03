
Web application for students distribution among certification works' leaders
Author: Anton Poroshenko anton.poroshenko@nure.ua

<-----------------------------------------------Project------------------------------------------------------->
������ �������:
	app/Http/Controllers - ��������� �����������.
	app/Http/Middleware - ����������,- ��� ������� ��������� HTTP-�������.
	app/Notifications - ���
	��������, ������������� ������ ����������� �������������� ������.
	database/migrations - ��������, ��� �������� ������.
	database/seeds - ��� ���������� ������, �� ���� ��� �������� ���������� � ������������� ���������� ������� ���� �����.
	resources/views - �������������.
	storage/app/public/ - ��������� ������.
<------------------------------------------------------------------------------------------------------------->


<--------------------------------------------Installation----------------------------------------------------->

����� �� ���� � ��� ��������, � ���� �� ��������� ����� ����� ��� ����������, �������� ���� � ������ Open Server, ��� �� ����� ��������� ����������� � ���� ��-�� �� ������.
� ������ Open Server 5.3.0 Premium (php 7.3 x64, MySQL 8.0 x64, PostgreSQL 11.2 x64). �������� ��� �� MySQL ��� � �� PostgreSQL.


1. ���������� composer, npm, laravel.
2. ���������� ������� ���� .env � �������� �������� ������� � ������� � ��� ���� ������, �� ������� ����� .env.example
3. ��������� ��������� ������� � ��������� PhpStorm:
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
4. �������� ���������� public � Open Server � �������� ������.
5. ������������. � ���� ������ ��� ���� ��������� ������������:

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

	������ ��� ���� ������������� password. ��� �������� ����������� ������������� ���������� ���������, ��� � �������������� id ������ ���� 1.
<------------------------------------------------------------------------------------------------------------->
