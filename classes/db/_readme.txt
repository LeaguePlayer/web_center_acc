<?php
	/* ����� ��� ������ � ���������� ������ ������ ( mysql, mysqli, sqlite, firebird, postgresql )*/


	// ������ ������������ ��
	// driver - ������� ���� ������, ����� ��������� �������� "mysql", "mysqli", "sqlite", "firebird", "postgresql"
	// host - ����� ���� ������
	// name - ��� ���� ������
	// user - ��� ������������ ��� ����������� � ���� ������
	// password - ������ ��� ����������� � ���� ������
	// charset - ��������� ���� ������
	$configMysql = array( "driver" => "mysql", "host" => "localhost", "name" => "wwwmysqliru", "user" => "root", "password" => "", "charset" => "cp1251" );


	// ����������� ������ DB
	require_once "classes/db/db.class.php";


	// ������������� ������ � �����������
	$db = DB::instance( $configMysql );


	// ��������� ���������� ���������� ������� � ��������� ������������� ������
	// ������ �� ��������� ������� � ����� �� �����
	$result = $db -> fetch( "select * from test" );
	foreach( $result as $data ) {
		echo $data["id"]." - ".$data["name"]."<br />"
	}


	// ���������� ������� �� ����������, ��������, ���������� ������ ���� ������
	$db -> query( "insert into test values( '', 'test' )" );


	// ����� ���� ������
	$db -> setDBName( "wwwtestru" );


	// ����� ��������� ���� ������
	$db -> setCharset( "cp1251" );


	// id ��������� ��������� ������ � ���� ������
	$db -> idLast();
?>