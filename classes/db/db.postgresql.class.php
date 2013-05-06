<?php
	/* расширенный класс соединения DBPOSTGRESQL для работы с базой данных POSTGRESQL посредством драйвера POSTGRESQL */


	class DBPOSTGRESQL extends DBConnection {


		// соединение с базой данных postgresql
		function connect() {
			try {
				if( !$this -> id = pg_connect( "host=".$this -> host." dbname=".$this -> name." user=".$this -> user." password=".$this -> password ) ) {
					throw new Exception( "Ошибка соединения с базой данных" );
				}
			} catch( Exception $error ) {
				$this -> id =0;
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBPOSTGRESQL: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
			}
			$this -> setDBCharset( $this -> charset );
		}


		// закрытие соединения с базой данных postgresql
		function close() {
			if( !empty( $this -> id ) ) {
				pg_close( $this -> id );
				$this -> id = 0;
			}
		}


		// выбор данных из базы данных postgresql
		function fetch( $query ) {
			$query = $this -> queryPrepare( $query );
			$data_array = array();
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "Попытка выполнения запроса ".$query." при отсутствии соединения" );
				}
				if( !$result = pg_query( $this -> id, $query ) ) {
					throw new Exception( "Ошибка выполнения запроса ".$query."<br />ERROR #".pg_last_error( $this -> id ) );
				}
				while( $data = pg_fetch_array( $result ) ) {
					array_push( $data_array, $data );
				}
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBPOSTGRESQL: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
			}
			return $data_array;
		}


		// выбор базы данных postgresql
		function setDBName( $name ) {
			$this -> name = $name;
			$this -> connect();
		}


		// установка кодировки в базе данных postgresql
		function setDBCharset( $charset ) {
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "Попытка установки кодировки при отсутствии соединения с базой данных" );
				}
				if( !pg_set_client_encoding( $this -> id, $charset ) ) {
					throw new Exception( "Кодировка ".$charset." не корректная для базы данных ".$this -> id."<br />ERROR #".pg_last_error( $this -> id ) );
				}
				$this -> charset = $charset;
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBPOSTGRESQL: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
			}
		}


		// выполнение запроса на добавление, удаление, обновдение в базе данных postgresql
		function queryPrepare( $query ) {
			$query = preg_replace("/(limit) (\d{1,}), (\d{1,})/", "limit $3 offset $2", $query);
			$this -> queryPrint( $query );
			return $query;
		}


		// подготовка запроса под базу данных postgresql
		function query( $query ) {
			$query = $this -> queryPrepare( $query );
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "Попытка выполнения запроса ".$query." при отсутствии соединения" );
				}
				if( !pg_query( $this -> id, $query ) ) {
					throw new Exception( "Ошибка выполнения запроса ".$query."<br />ERROR #".pg_last_error( $this -> id ) );
				}
				return array( "error" => 0, "count" => pg_affected_rows( $this -> id ) );
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBPOSTGRESQL: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
				return array( "error" => 1, "count" => 0 );
			}
		}


	}

?>