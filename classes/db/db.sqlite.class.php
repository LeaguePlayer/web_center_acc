<?php
	/* расширенный класс соединения DBSQLITE для работы с базой данных SQLITE посредством драйвера SQLITE */


	class DBSQLITE extends DBConnection {


		// соединение с базой данных sqlite
		function connect() {
			try {
				if( !file_exists( $this -> name ) ) {
					throw new Exception( "Ошибка соединения с базой данных. База данных ".$this -> name." не существует" );
				}
				if( !$this -> id = sqlite_open( $this -> name, 0777 ) ) {
					throw new Exception( "Ошибка соединения с базой данных" );
				}
			} catch( Exception $error ) {
				$this -> id =0;
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBSQLITE: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
			}
		}


		// закрытие соединения с базой данных sqlite
		function close() {
			if( $this -> id ) {
				sqlite_close( $this -> id );
				$this -> id = 0;
			}
		}


		// выбор данных из базы данных sqlite
		function fetch( $query ) {
			$query = $this -> queryPrepare( $query );
			$data_array = array();
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "Попытка выполнения запроса ".$query." при отсутствии соединения" );
				}
				if( !$result = sqlite_query( $this -> id, $query ) ) {
					throw new Exception( "Ошибка выполнения запроса ".$query."<br />ERROR #".sqlite_last_error( $this -> id ).": ".sqlite_error_string( sqlite_last_error( $this -> id ) ) );
				}
				while( $data = sqlite_fetch_array( $result ) ) {
					array_push( $data_array, $data );
				}
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBSQLITE: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
			}
			return $data_array;
		}


		// выбор базы данных sqlite
		function setDBName( $name ) {
			$this -> name = $name;
			$this -> connect();
		}


		// выполнение запроса на добавление, удаление, обновдение в базе данных sqlite
		function query( $query ) {
			$query = $this -> queryPrepare( $query );
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "Попытка выполнения запроса ".$query." при отсутствии соединения с базой данных" );
				}
				if( !$result = sqlite_query( $this -> id, $query ) ) {
					throw new Exception( "Ошибка выполнения запроса ".$query."<br />ERROR #".sqlite_last_error( $this -> id ).": ".sqlite_error_string( sqlite_last_error( $this -> id ) ) );
				}
				return array( "error" => 0, "count" => sqlite_changes( $this -> id ) );
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DBSQLITE: Ошибка в файле <b>".$error -> getFile()."</b> в ".$error -> getLine()." строке<br />".$error -> getMessage()."</div>";
				return array( "error" => 1, "count" => 0 );
			}
		}


		// подготовка запроса под базу данных sqlite
		function queryPrepare( $query ) {
			$this -> queryPrint( $query );
			return $query;
		}


		// последний внесенный id
		function idLast() {
			return sqlite_last_insert_rowid( $this -> id );
		}


	}
?>