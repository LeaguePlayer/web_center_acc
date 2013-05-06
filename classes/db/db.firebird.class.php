<?php
	/* расширенный класс соединения DBFIREBIRD для работы с базой данных FIREBIRD посредством драйвера FIREBIRD */


	class DBFIREBIRD extends DBConnection {


		// соединение с базой данных firebird
		function connect() {
			try {
				if( !$this -> id = ibase_connect( $this -> host."".$this -> name, $this -> user, $this -> password, $this -> charset ) ) {
					throw new Exception( "Ошибка соединения с базой данных" );
				}
			} catch( Exception $error ) {
				$this -> id =0;
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBFIREBIRD: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
			}
		}


		// закрытие соединения с базой данных firebird
		function close() {
			if( $this -> dbid ) {
				ibase_close( $this -> id );
				$this -> id = 0;
			}
		}


		// выбор данных из базы данных firebird
		function fetch( $query ) {
			$query = $this -> queryPrepare( $query );
			$data_array = array();
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "Попытка выполнения запроса ".$query." при отсутствии соединения" );
				}
				if( !$result = ibase_query( $this -> id, $query ) ) {
					throw new Exception( "Ошибка выполнения запроса ".$query."<br />ERROR #".ibase_errcode().": ".ibase_errmsg() );
				}
				while( $data = ibase_fetch_assoc( $result ) ) {
					$data = array_change_key_case( $data, CASE_LOWER );
					array_push( $data_array, $data );
				}
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBFIREBIRD: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
			}
			return $data_array;
		}


		// выбор базы данных firebird
		function setDBName( $name ) {
			$this -> name = $name;
			$this -> connect();
		}


		// выполнение запроса на добавление, удаление, обновдение в базе данных firebird
		function query( $query ) {
			$query = $this -> queryPrepare( $query );
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "Попытка выполнения запроса ".$query." при отсутствии соединения" );
				}
				if( !ibase_query( $query, $this -> id ) ) {
					throw new Exception( "Ошибка выполнения запроса ".$query."<br />ERROR #".ibase_errcode().": ".ibase_errmsg() );
				}
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBFIREBIRD: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
			}
		}


		// подготовка запроса под базу данных firebird
		function queryPrepare( $query ) {
			$query = preg_replace("/(limit) (\d{1,}), (\d{1,})/", "rows $2+1 to $3", $query);
			$this -> queryPrint( $query );
			return $query;
		}


	}
?>