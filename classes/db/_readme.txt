<?php
	/* класс для работы с различными базами данных ( mysql, mysqli, sqlite, firebird, postgresql )*/


	// конфиг подклбючения бд
	// driver - драйвер базы данных, может принимать значения "mysql", "mysqli", "sqlite", "firebird", "postgresql"
	// host - адрес базы данных
	// name - имя базы данных
	// user - имя пользователя для подключения к базе данных
	// password - пароль для подключения к базе данных
	// charset - кодировка базы данных
	$configMysql = array( "driver" => "mysql", "host" => "localhost", "name" => "wwwmysqliru", "user" => "root", "password" => "", "charset" => "cp1251" );


	// подключение класса DB
	require_once "classes/db/db.class.php";


	// инициализация класса с настройками
	$db = DB::instance( $configMysql );


	// получение результата выполнения запроса в двумерный ассоциативный массив
	// проход по элементам массива и вывод на экран
	$result = $db -> fetch( "select * from test" );
	foreach( $result as $data ) {
		echo $data["id"]." - ".$data["name"]."<br />"
	}


	// выполнение запроса на добавление, удаление, обновление данных базы данных
	$db -> query( "insert into test values( '', 'test' )" );


	// смена базы данных
	$db -> setDBName( "wwwtestru" );


	// смена кодировки базы данных
	$db -> setCharset( "cp1251" );


	// id последней внесенной записи в базу данных
	$db -> idLast();
?>