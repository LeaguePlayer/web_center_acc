<?php
	/* расширенный класс соединения DBMYSQL для работы с базой данных MYSQL посредством драйвера MYSQL */


	class DBMYSQL extends DBConnection {


		// соединение с базой данных mysql
		function connect() {
			try{
				if( !$this -> id = mysql_connect( $this -> host, $this -> user, $this -> password ) ) {
					throw new Exception( "Ошибка соединения с базой данных" );
					$this -> id =0;
				}
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBMYSQL: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
			}
			$this -> setDBCharset( $this -> charset );
			$this -> setDBName( $this -> name );
		}


		// закрытие соединения с базой данных mysql
		function close() {
			if( $this -> id ) {
				mysql_close( $this -> id );
				$this -> id = 0;
			}
		}


		// выбор данных из базы данных mysql
		function fetch( $query ) {
			$this -> queryPrepare( $query );
			$data_array = array();
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "Попытка выполнения запроса ".$query." при отсутсвии соединения" );
				}
				if( !$result = mysql_query( $query, $this -> id ) ) {
					throw new Exception( "Ошибка выполнения запроса ".$query."<br />ERROR #".mysql_errno( $this -> id ).": ".mysql_error( $this -> id ) );
				}
				while( $data = mysql_fetch_array( $result ) ) {
					array_push( $data_array, $data );
				}
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBMYSQL: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
			}
			return $data_array;
		}


		// выбор базы данных mysql
		function setDBName( $name ) {
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "Попытка выбрать базу данных $name при отсутствии соединения" );		
				}
				if( !mysql_select_db( $name, $this -> id ) ) {
					throw new Exception( "База данных ".$name." не существует<br />ERROR #".mysql_errno( $this -> id ).": ".mysql_error( $this -> id ) );
				}
				$this -> name = $name;
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBMYSQL: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
			}
		}


		// установка кодировки в базе данных mysql
		function setDBCharset( $charset ) {
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "Попытка выбора кодировки ".$charset." при отсутствии соединения" );
				}
				if( !mysql_set_charset( $charset, $this -> id ) ) {
					throw new Exception( "Кодировка ".$charset." не корректная для базы данных<br />ERROR #".mysql_errno( $this -> id ).": ".mysql_error( $this -> id ) );
				}
				$this -> charset = $charset;
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBMYSQL: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
			}
		}


		// выполнение запроса на добавление, удаление, обновдение в базе данных mysql
		function query( $query ) {
			$this -> queryPrepare( $query );
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "Попытка выполнить запрос при отсутствии соединения" );
				}
				if( !mysql_query( $query, $this -> id ) ) {
					throw new Exception( "Ошибка выполнения запроса ".$query."<br />ERROR #".mysql_errno( $this -> id ).": ".mysql_error( $this -> id ) );
				}
				return array( "error" => 0, "count" => mysql_affected_rows( $this -> id ) );
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBMYSQL: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
				return array( "error" => 1, "count" => 0 );
			}
		}


		// подготовка запроса под базу данных mysql
		function queryPrepare( $query ) {
			$this -> queryPrint( $query );
			return $query;
		}


		// последний внесенный id
		function idLast() {
			return mysql_insert_id( $this -> id );
		}


	}
?>