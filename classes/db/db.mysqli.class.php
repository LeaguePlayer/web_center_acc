<?php
	/* расширенный класс соединения DBMYSQLI для работы с базой данных MYSQL посредством драйвера MYSQLI */


	class DBMYSQLI extends DBConnection {


		// соединение с базой данных mysql
		function connect() {
			try {
				if( !$this -> id = mysqli_connect( $this -> host, $this -> user, $this -> password ) ) {
					throw new Exception( "Ошибка соединения с базой данных" );
				}
			} catch( Exception $error ) {
				$this -> id =0;
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBMYSQLI: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
			}
			$this -> setDBName( $this -> name );
			$this -> setDBCharset( $this -> charset );
		}


		// закрытие соединения с базой данных mysql
		function close() {
			if( $this -> id ) {
				mysqli_close( $this -> id );
				$this -> id = 0;
			}
		}


		// выбор данных из базы данных mysql
		function fetch( $query ) {
			$this -> queryPrepare( $query );
			$data_array = array();
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "Попытка выполнения запроса ".$query." при отсутствии соединения" );
				}
				if( !$result = mysqli_query( $this -> id, $query ) ) {
					throw new Exception( "Ошибка выполнения запроса ".$query."<br />ERROR #".mysqli_errno( $this -> id ).": ".mysqli_error( $this -> id ) );
				}
				while( $data = mysqli_fetch_array( $result ) ) {
					array_push( $data_array, $data );
				}
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBMYSQLI: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
			}
			return $data_array;
		}


		// выбор базы данных mysql
		function setDBName( $name ) {
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "Попытка выбора базы данных ".$name." при отсутствии соединения" );
				}
				if( !mysqli_select_db( $this -> id, $name ) ) {
					throw new Exception( "База данных ".$name." не существует<br />ERROR #".mysqli_errno( $this -> id ).": ".mysqli_error( $this -> id ) );
				}
				$this -> name = $name;
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBMYSQLI: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
			}
		}


		// установка кодировки в базе данных mysql
		function setDBCharset( $charset ) {
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "Попытка установить кодировку при отсутствии соединения с базой данных" );
				}
				if( !mysqli_set_charset( $this -> id, $charset ) ) {
					throw new Exception( "Кодировка ".$charset." не корректная для базы данных ".$this -> id."<br />ERROR #".mysqli_errno( $this -> id ).": ".mysqli_error( $this -> id ) );
				}
				$this -> charset = $charset;
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBMYSQLI: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
			}
		}


		// выполнение запроса на добавление, удаление, обновдение в базе данных mysql
		function query( $query ) {
			$this -> queryPrepare( $query );
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "Попытка выполнить запрос ".$query." при отсутствии соединения c базой данных" );
				}
				if( !mysqli_query( $this -> id, $query ) ) {
					throw new Exception( "Ошибка выполнения запроса ".$query."<br />ERROR #".mysqli_errno( $this -> id ).": ".mysqli_error( $this -> id ) );
				}
				return array( "error" => 0, "count" => mysqli_affected_rows( $this -> id ) );
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBMYSQLI: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
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
			return mysqli_insert_id( $this -> id );
		}


	}
?>