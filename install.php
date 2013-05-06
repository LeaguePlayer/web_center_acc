<?php

	require_once "cfg/config.php";
	require_once "classes/db/db.class.php";

	$install = DB::instance( $config );
/*

	$install -> query( "CREATE TABLE IF NOT EXISTS pages ( id INT AUTO_INCREMENT, name CHAR( 255 ), content TEXT, title CHAR( 255 ), keywords CHAR( 255 ), description CHAR( 255 ), razdel INT, alias CHAR( 100 ), position INT, visible BOOLEAN, view CHAR( 100 ), PRIMARY KEY( id ) )" );

	// configEmail
	$install -> query( "CREATE TABLE IF NOT EXISTS configEmail ( id INT AUTO_INCREMENT, email CHAR( 100 ), view CHAR( 100 ), PRIMARY KEY( id ) )" );

	// pages
	$install -> query( "INSERT INTO pages VALUES( '1', '��������� ��������', '<p>������ ��������� � ����������...</p>', '��������� ��������', '��������� ��������', '��������� ��������', '0', '', '1', true, 'pages' )" );

	// photogallerySimple
	$install -> query( "INSERT INTO pages VALUES( '2', '����������� �������', '<p>������ ��������� � ����������...</p>', '����������� �������', '����������� �������', '����������� �������', '0', 'photogallerySimple', '2', true, 'photogallerySimple' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS photogallerySimple ( id INT AUTO_INCREMENT, pages INT, picture CHAR( 30 ), name CHAR( 255 ), PRIMARY KEY( id ) )" );

	// news
	$install -> query( "INSERT INTO pages VALUES( '3', '�������', '<p>������ ��������� � ����������...</p>', '�������', '�������', '�������', '0', 'news', '3', true, 'news' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS news ( id INT AUTO_INCREMENT, name CHAR( 255 ), date DATE, content TEXT, picture CHAR( 30 ), pages INT, PRIMARY KEY( id ) )" );

	// feedback
	$install -> query( "INSERT INTO pages VALUES( '4', '�������� �����', '<p>������ ��������� � ����������...</p>', '�������� �����', '�������� �����', '�������� �����', '0', 'feedback', '4', true, 'feedback' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS feedback ( id INT AUTO_INCREMENT, email CHAR( 100 ), PRIMARY KEY( id ) )" );
	$install -> query( "INSERT INTO configEmail VALUES( NULL, 'fuuuuueeeee@ya.ru', 'feedback' )" );

	// guestbook
	$install -> query( "INSERT INTO pages VALUES( '5', '�������� �����', '<p>������ ��������� � ����������...</p>', '�������� �����', '�������� �����', '�������� �����', '0', 'guestbook', '5', true, 'guestbook' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS guestbook ( id INT AUTO_INCREMENT, name CHAR( 255 ), date DATETIME, message TEXT, email CHAR( 100 ), checked BOOLEAN, PRIMARY KEY( id ) )" );
	$install -> query( "INSERT INTO configEmail VALUES( NULL, 'fuuuuueeeee@ya.ru', 'guestbook' )" );

	// files
	$install -> query( "INSERT INTO pages VALUES( '6', '�����', '<p>������ ��������� � ����������...</p>', '�����', '�����', '�����', '0', 'files', '6', true, 'files' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS files ( id INT AUTO_INCREMENT, name CHAR( 255 ), date DATETIME, path CHAR( 30 ), description TEXT, pages INT, downloads INT, PRIMARY KEY( id ) )" );

	// find
	$install -> query( "INSERT INTO pages VALUES( '7', '�����', '<p>������ ��������� � ����������...</p>', '�����', '�����', '�����', '0', 'find', '7', true, 'find' )" );

	// photogallery
	$install -> query( "INSERT INTO pages VALUES( '8', '�����������', '<p>������ ��������� � ����������...</p>', '�����������', '�����������', '�����������', '0', 'photogallery', '8', true, 'photogallery' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS photogallery ( id INT AUTO_INCREMENT, pages INT, picture CHAR( 30 ), name CHAR( 255 ), date DATE, description TEXT, PRIMARY KEY( id ) )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS photogalleryPicture ( id INT AUTO_INCREMENT, photogallery INT, picture CHAR( 30 ), name CHAR( 255 ), PRIMARY KEY( id ) )" );

	// users
	$install -> query( "INSERT INTO pages VALUES( '9', '������������', '<p>������ ��������� � ����������...</p>', '������������', '������������', '������������', '0', 'users', '9', true, 'users' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS users ( id INT AUTO_INCREMENT, login CHAR( 100 ), password CHAR( 100 ), name CHAR( 255 ), date DATE, activate BOOLEAN, access INT, mail CHAR( 100 ), recovery CHAR( 100 ), PRIMARY KEY( id ) )" );
	$install -> query( "INSERT INTO users VALUES( NULL, 'fuuuuueeeee', '".md5( "Ser3EJA89" )."', '������� ������ �������������', '2012-06-21', '1', '3', 'fuuuuueeeee@ya.ru', '' )" );
	$install -> query( "INSERT INTO configEmail VALUES( NULL, 'users@ya.ru', 'users' )" );

*/

/*
	$install -> query( "CREATE TABLE IF NOT EXISTS pages ( id INT AUTO_INCREMENT, name CHAR( 255 ), content TEXT, title CHAR( 255 ), keywords CHAR( 255 ), description CHAR( 255 ), razdel INT, alias CHAR( 100 ), position INT, visible BOOLEAN, view CHAR( 100 ), PRIMARY KEY( id ) )" );

	// pages
	$install -> query( "INSERT INTO pages VALUES( '1', '� �����������', '<p>������ ��������� � ����������...</p>', '� �����������', '� �����������', '� �����������', '0', '', '1', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '2', '�������� � �����������', '<p>������ ��������� � ����������...</p>', '�������� � �����������', '�������� � �����������', '�������� � �����������', '0', 'membership', '2', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '3', '���������� �������� ���������� ������ ��', '<p>������ ��������� � ����������...</p>', '���������� �������� ���������� ������ ��', '���������� �������� ���������� ������ ��', '���������� �������� ���������� ������ ��', '0', 'reviews', '3', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '4', '������� ��', '<p>������ ��������� � ����������...</p>', '������� ��', '������� ��', '������� ��', '0', 'news', '4', true, 'news' )" );
	$install -> query( "INSERT INTO pages VALUES( '5', '�������� ����������', '<p>������ ��������� � ����������...</p>', '�������� ����������', '�������� ����������', '�������� ����������', '0', 'information', '5', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '6', '���� ��� ������ ��', '<p>������ ��������� � ����������...</p>', '���� ��� ������ ��', '���� ��� ������ ��', '���� ��� ������ ��', '0', 'enter', '6', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '7', '��������', '<p>������ ��������� � ����������...</p>', '��������', '��������', '��������', '0', 'contacts', '7', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '8', '��������������', '<p>������ ��������� � ����������...</p>', '��������������', '��������������', '��������������', '0', 'cooperation', '8', true, 'pages' )" );

	$install -> query( "INSERT INTO pages VALUES( '9', '������������ ����������', '<p>������ ��������� � ����������...</p>', '������������ ����������', '������������ ����������', '������������ ����������', '1', 'advantages', '9', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '10', '��������������� ���������', '<p>������ ��������� � ����������...</p>', '��������������� ���������', '��������������� ���������', '��������������� ���������', '1', 'organizational', '10', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '11', '��������� � ��������', '<p>������ ��������� � ����������...</p>', '��������� � ��������', '��������� � ��������', '��������� � ��������', '1', 'provision', '11', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '12', '���������', '<p>������ ��������� � ����������...</p>', '���������', '���������', '���������', '1', 'structure', '12', true, 'pages' )" );

	$install -> query( "INSERT INTO pages VALUES( '13', '���������� � ��', '<p>������ ��������� � ����������...</p>', '���������� � ��', '���������� � ��', '���������� � ��', '2', 'introduction', '13', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '14', '������� ����������', '<p>������ ��������� � ����������...</p>', '������� ����������', '������� ����������', '������� ����������', '2', 'condition', '14', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '15', '������ ������ �������', '<p>������ ��������� � ����������...</p>', '������ ������ �������', '������ ������ �������', '������ ������ �������', '2', 'physical', '15', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '16', '������ ������ ������', '<p>������ ��������� � ����������...</p>', '������ ������ ������', '������ ������ ������', '������ ������ ������', '2', 'legal', '16', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '17', '�������������� ������� �� �������', '<p>������ ��������� � ����������...</p>', '�������������� ������� �� �������', '�������������� ������� �� �������', '�������������� ������� �� �������', '2', 'extracts', '17', true, 'pages' )" );

	$install -> query( "INSERT INTO pages VALUES( '18', '�������� �� ���������� ����������', '<p>������ ��������� � ����������...</p>', '�������� �� ���������� ����������', '�������� �� ���������� ����������', '�������� �� ���������� ����������', '3', 'expert', '18', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '19', '�������� �� ������ �� ������', '<p>������ ��������� � ����������...</p>', '�������� �� ������ �� ������', '�������� �� ������ �� ������', '�������� �� ������ �� ������', '3', 'reports', '19', true, 'pages' )" );

	$install -> query( "INSERT INTO pages VALUES( '20', '��� ���������', '<p>������ ��������� � ����������...</p>', '��� ���������', '��� ���������', '��� ���������', '5', 'forexperts', '20', true, 'pages' )" );
	$install -> query( "INSERT INTO pages VALUES( '21', '��� ���������', '<p>������ ��������� � ����������...</p>', '��� ���������', '��� ���������', '��� ���������', '5', 'forappraisers', '21', true, 'pages' )" );


	// news
	$install -> query( "CREATE TABLE IF NOT EXISTS news ( id INT AUTO_INCREMENT, name CHAR( 255 ), date DATE, content TEXT, picture CHAR( 30 ), pages INT, PRIMARY KEY( id ) )" );

	// configEmail
	$install -> query( "CREATE TABLE IF NOT EXISTS configEmail ( id INT AUTO_INCREMENT, email CHAR( 100 ), view CHAR( 100 ), PRIMARY KEY( id ) )" );

	// users
	$install -> query( "INSERT INTO pages VALUES( '22', '������������', '<p>������ ��������� � ����������...</p>', '������������', '������������', '������������', '0', 'users', '9', false, 'users' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS users ( id INT AUTO_INCREMENT, login CHAR( 100 ), password CHAR( 100 ), name CHAR( 255 ), date DATE, activate BOOLEAN, access INT, mail CHAR( 100 ), recovery CHAR( 100 ), PRIMARY KEY( id ) )" );
	$install -> query( "INSERT INTO users VALUES( NULL, 'fuuuuueeeee', '".md5( "Ser3EJA89" )."', '������� ������ �������������', '2012-06-21', '1', '3', 'fuuuuueeeee@ya.ru', '' )" );
	$install -> query( "INSERT INTO configEmail VALUES( NULL, 'users@ya.ru', 'users' )" );

	// files
	$install -> query( "INSERT INTO pages VALUES( '23', '���������', '<p>������ ��������� � ����������...</p>', '���������', '���������', '���������', '0', 'documents', '23', false, 'files' )" );
	$install -> query( "CREATE TABLE IF NOT EXISTS files ( id INT AUTO_INCREMENT, name CHAR( 255 ), date DATETIME, path CHAR( 30 ), description TEXT, pages INT, downloads INT, users INT, PRIMARY KEY( id ) )" );

*/

?>