<?php

	require_once "cfg/config.php";
	require_once "classes/db/db.class.php";

	$install = DB::instance( $config );
/*

	$install -> query( "CREATE TABLE IF NOT EXISTS pages ( id INT AUTO_INCREMENT, name CHAR( 255 ), content TEXT, title CHAR( 255 ), keywords CHAR( 255 ), description CHAR( 255 ), razdel INT, alias CHAR( 100 ), position INT, visible BOOLEAN, view CHAR( 100 ), PRIMARY KEY( id ) )" );

	// configEmail
	$install -> query( "CREATE TABLE IF NOT EXISTS configEmail ( id INT AUTO_INCREMENT, email CHAR( 100 ), view CHAR( 100 ), PRIMARY KEY( id ) )" );

	// pages
	$install -> query( "INSERT INTO pages VALUES( '1', 'Текстовая страница', '<p>Раздел находится в разработке...</p>', 'Текстовая страница', 'Текстовая страница', 'Текстовая страница', '0', '', '1', true, 'pages' )" );

	// photogallerySimple
	$install -> query( "INSERT INTO pages VALUES( '2', 'Фотогалерея простая', '<p>Раздел находится в разработке...</p>', 'Фотогалерея простая', 'Фотогалерея простая', 'Фотогалерея простая', '0', 'photogallerySimple', '2', true, 'photogallerySimple' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS photogallerySimple ( id INT AUTO_INCREMENT, pages INT, picture CHAR( 30 ), name CHAR( 255 ), PRIMARY KEY( id ) )" );

	// news
	$install -> query( "INSERT INTO pages VALUES( '3', 'Новости', '<p>Раздел находится в разработке...</p>', 'Новости', 'Новости', 'Новости', '0', 'news', '3', true, 'news' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS news ( id INT AUTO_INCREMENT, name CHAR( 255 ), date DATE, content TEXT, picture CHAR( 30 ), pages INT, PRIMARY KEY( id ) )" );

	// feedback
	$install -> query( "INSERT INTO pages VALUES( '4', 'Обратная связь', '<p>Раздел находится в разработке...</p>', 'Обратная связь', 'Обратная связь', 'Обратная связь', '0', 'feedback', '4', true, 'feedback' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS feedback ( id INT AUTO_INCREMENT, email CHAR( 100 ), PRIMARY KEY( id ) )" );
	$install -> query( "INSERT INTO configEmail VALUES( NULL, 'fuuuuueeeee@ya.ru', 'feedback' )" );

	// guestbook
	$install -> query( "INSERT INTO pages VALUES( '5', 'Гостевая книга', '<p>Раздел находится в разработке...</p>', 'Гостевая книга', 'Гостевая книга', 'Гостевая книга', '0', 'guestbook', '5', true, 'guestbook' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS guestbook ( id INT AUTO_INCREMENT, name CHAR( 255 ), date DATETIME, message TEXT, email CHAR( 100 ), checked BOOLEAN, PRIMARY KEY( id ) )" );
	$install -> query( "INSERT INTO configEmail VALUES( NULL, 'fuuuuueeeee@ya.ru', 'guestbook' )" );

	// files
	$install -> query( "INSERT INTO pages VALUES( '6', 'Файлы', '<p>Раздел находится в разработке...</p>', 'Файлы', 'Файлы', 'Файлы', '0', 'files', '6', true, 'files' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS files ( id INT AUTO_INCREMENT, name CHAR( 255 ), date DATETIME, path CHAR( 30 ), description TEXT, pages INT, downloads INT, PRIMARY KEY( id ) )" );

	// find
	$install -> query( "INSERT INTO pages VALUES( '7', 'Поиск', '<p>Раздел находится в разработке...</p>', 'Поиск', 'Поиск', 'Поиск', '0', 'find', '7', true, 'find' )" );

	// photogallery
	$install -> query( "INSERT INTO pages VALUES( '8', 'Фотогалерея', '<p>Раздел находится в разработке...</p>', 'Фотогалерея', 'Фотогалерея', 'Фотогалерея', '0', 'photogallery', '8', true, 'photogallery' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS photogallery ( id INT AUTO_INCREMENT, pages INT, picture CHAR( 30 ), name CHAR( 255 ), date DATE, description TEXT, PRIMARY KEY( id ) )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS photogalleryPicture ( id INT AUTO_INCREMENT, photogallery INT, picture CHAR( 30 ), name CHAR( 255 ), PRIMARY KEY( id ) )" );

	// users
	$install -> query( "INSERT INTO pages VALUES( '9', 'Пользователи', '<p>Раздел находится в разработке...</p>', 'Пользователи', 'Пользователи', 'Пользователи', '0', 'users', '9', true, 'users' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS users ( id INT AUTO_INCREMENT, login CHAR( 100 ), password CHAR( 100 ), name CHAR( 255 ), date DATE, activate BOOLEAN, access INT, mail CHAR( 100 ), recovery CHAR( 100 ), PRIMARY KEY( id ) )" );
	$install -> query( "INSERT INTO users VALUES( NULL, 'fuuuuueeeee', '".md5( "Ser3EJA89" )."', 'Сахаров Сергей Александрович', '2012-06-21', '1', '3', 'fuuuuueeeee@ya.ru', '' )" );
	$install -> query( "INSERT INTO configEmail VALUES( NULL, 'users@ya.ru', 'users' )" );

*/

/*
	$install -> query( "CREATE TABLE IF NOT EXISTS pages ( id INT AUTO_INCREMENT, name CHAR( 255 ), content TEXT, title CHAR( 255 ), keywords CHAR( 255 ), description CHAR( 255 ), razdel INT, alias CHAR( 100 ), position INT, visible BOOLEAN, view CHAR( 100 ), PRIMARY KEY( id ) )" );

	// pages
	$install -> query( "INSERT INTO pages VALUES( '1', 'О партнерстве', '<p>Раздел находится в разработке...</p>', 'О партнерстве', 'О партнерстве', 'О партнерстве', '0', '', '1', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '2', 'Членство в партнерстве', '<p>Раздел находится в разработке...</p>', 'Членство в партнерстве', 'Членство в партнерстве', 'Членство в партнерстве', '0', 'membership', '2', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '3', 'Проведение рецензий материалов членов НП', '<p>Раздел находится в разработке...</p>', 'Проведение рецензий материалов членов НП', 'Проведение рецензий материалов членов НП', 'Проведение рецензий материалов членов НП', '0', 'reviews', '3', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '4', 'Новости НП', '<p>Раздел находится в разработке...</p>', 'Новости НП', 'Новости НП', 'Новости НП', '0', 'news', '4', true, 'news' )" );
	$install -> query( "INSERT INTO pages VALUES( '5', 'Полезная информация', '<p>Раздел находится в разработке...</p>', 'Полезная информация', 'Полезная информация', 'Полезная информация', '0', 'information', '5', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '6', 'Вход для членов НП', '<p>Раздел находится в разработке...</p>', 'Вход для членов НП', 'Вход для членов НП', 'Вход для членов НП', '0', 'enter', '6', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '7', 'Контакты', '<p>Раздел находится в разработке...</p>', 'Контакты', 'Контакты', 'Контакты', '0', 'contacts', '7', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '8', 'Сотрудничество', '<p>Раздел находится в разработке...</p>', 'Сотрудничество', 'Сотрудничество', 'Сотрудничество', '0', 'cooperation', '8', true, 'pages' )" );

	$install -> query( "INSERT INTO pages VALUES( '9', 'Преимущества вступления', '<p>Раздел находится в разработке...</p>', 'Преимущества вступления', 'Преимущества вступления', 'Преимущества вступления', '1', 'advantages', '9', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '10', 'Организационная структура', '<p>Раздел находится в разработке...</p>', 'Организационная структура', 'Организационная структура', 'Организационная структура', '1', 'organizational', '10', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '11', 'Положение о членстве', '<p>Раздел находится в разработке...</p>', 'Положение о членстве', 'Положение о членстве', 'Положение о членстве', '1', 'provision', '11', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '12', 'Структура', '<p>Раздел находится в разработке...</p>', 'Структура', 'Структура', 'Структура', '1', 'structure', '12', true, 'pages' )" );

	$install -> query( "INSERT INTO pages VALUES( '13', 'Вступление в НП', '<p>Раздел находится в разработке...</p>', 'Вступление в НП', 'Вступление в НП', 'Вступление в НП', '2', 'introduction', '13', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '14', 'Условия вступления', '<p>Раздел находится в разработке...</p>', 'Условия вступления', 'Условия вступления', 'Условия вступления', '2', 'condition', '14', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '15', 'Реестр членов физлица', '<p>Раздел находится в разработке...</p>', 'Реестр членов физлица', 'Реестр членов физлица', 'Реестр членов физлица', '2', 'physical', '15', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '16', 'Реестр членов юрлица', '<p>Раздел находится в разработке...</p>', 'Реестр членов юрлица', 'Реестр членов юрлица', 'Реестр членов юрлица', '2', 'legal', '16', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '17', 'Предоставление выписок из реестра', '<p>Раздел находится в разработке...</p>', 'Предоставление выписок из реестра', 'Предоставление выписок из реестра', 'Предоставление выписок из реестра', '2', 'extracts', '17', true, 'pages' )" );

	$install -> query( "INSERT INTO pages VALUES( '18', 'Рецензии на экспертные заключения', '<p>Раздел находится в разработке...</p>', 'Рецензии на экспертные заключения', 'Рецензии на экспертные заключения', 'Рецензии на экспертные заключения', '3', 'expert', '18', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '19', 'Рецензии на отчеты об оценке', '<p>Раздел находится в разработке...</p>', 'Рецензии на отчеты об оценке', 'Рецензии на отчеты об оценке', 'Рецензии на отчеты об оценке', '3', 'reports', '19', true, 'pages' )" );

	$install -> query( "INSERT INTO pages VALUES( '20', 'Для экспертов', '<p>Раздел находится в разработке...</p>', 'Для экспертов', 'Для экспертов', 'Для экспертов', '5', 'forexperts', '20', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '21', 'Для оценщиков', '<p>Раздел находится в разработке...</p>', 'Для оценщиков', 'Для оценщиков', 'Для оценщиков', '5', 'forappraisers', '21', true, 'pages' )" );


	// news
	$install -> query( "CREATE TABLE IF NOT EXISTS news ( id INT AUTO_INCREMENT, name CHAR( 255 ), date DATE, content TEXT, picture CHAR( 30 ), pages INT, PRIMARY KEY( id ) )" );

	// configEmail
	$install -> query( "CREATE TABLE IF NOT EXISTS configEmail ( id INT AUTO_INCREMENT, email CHAR( 100 ), view CHAR( 100 ), PRIMARY KEY( id ) )" );

	// users
	$install -> query( "INSERT INTO pages VALUES( '22', 'Пользователи', '<p>Раздел находится в разработке...</p>', 'Пользователи', 'Пользователи', 'Пользователи', '0', 'users', '9', false, 'users' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS users ( id INT AUTO_INCREMENT, login CHAR( 100 ), password CHAR( 100 ), name CHAR( 255 ), date DATE, activate BOOLEAN, access INT, mail CHAR( 100 ), recovery CHAR( 100 ), PRIMARY KEY( id ) )" );
	$install -> query( "INSERT INTO users VALUES( NULL, 'fuuuuueeeee', '".md5( "Ser3EJA89" )."', 'Сахаров Сергей Александрович', '2012-06-21', '1', '3', 'fuuuuueeeee@ya.ru', '' )" );
	$install -> query( "INSERT INTO configEmail VALUES( NULL, 'users@ya.ru', 'users' )" );

	// files
	$install -> query( "INSERT INTO pages VALUES( '23', 'Документы', '<p>Раздел находится в разработке...</p>', 'Документы', 'Документы', 'Документы', '0', 'documents', '23', false, 'files' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS files ( id INT AUTO_INCREMENT, name CHAR( 255 ), date DATETIME, path CHAR( 30 ), description TEXT, pages INT, downloads INT, users INT, PRIMARY KEY( id ) )" );

*/

?>