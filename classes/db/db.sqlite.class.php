<?php
	/* ����������� ����� ���������� DBSQLITE ��� ������ � ����� ������ SQLITE ����������� �������� SQLITE */


	class DBSQLITE extends DBConnection {


		// ���������� � ����� ������ sqlite
		function connect() {
			try {
				if( !file_exists( $this -> name ) ) {
					throw new Exception( "������ ���������� � ����� ������. ���� ������ ".$this -> name." �� ����������" );
				}
				if( !$this -> id = sqlite_open( $this -> name, 0777 ) ) {
					throw new Exception( "������ ���������� � ����� ������" );
				}
			} catch( Exception $error ) {
				$this -> id =0;
				echo "<div class='statusCancel'>������ ������ DBSQLITE: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
		}


		// �������� ���������� � ����� ������ sqlite
		function close() {
			if( $this -> id ) {
				sqlite_close( $this -> id );
				$this -> id = 0;
			}
		}


		// ����� ������ �� ���� ������ sqlite
		function fetch( $query ) {
			$query = $this -> queryPrepare( $query );
			$data_array = array();
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "������� ���������� ������� ".$query." ��� ���������� ����������" );
				}
				if( !$result = sqlite_query( $this -> id, $query ) ) {
					throw new Exception( "������ ���������� ������� ".$query."<br />ERROR #".sqlite_last_error( $this -> id ).": ".sqlite_error_string( sqlite_last_error( $this -> id ) ) );
				}
				while( $data = sqlite_fetch_array( $result ) ) {
					array_push( $data_array, $data );
				}
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>������ ������ DBSQLITE: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
			return $data_array;
		}


		// ����� ���� ������ sqlite
		function setDBName( $name ) {
			$this -> name = $name;
			$this -> connect();
		}


		// ���������� ������� �� ����������, ��������, ���������� � ���� ������ sqlite
		function query( $query ) {
			$query = $this -> queryPrepare( $query );
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "������� ���������� ������� ".$query." ��� ���������� ���������� � ����� ������" );
				}
				if( !$result = sqlite_query( $this -> id, $query ) ) {
					throw new Exception( "������ ���������� ������� ".$query."<br />ERROR #".sqlite_last_error( $this -> id ).": ".sqlite_error_string( sqlite_last_error( $this -> id ) ) );
				}
				return array( "error" => 0, "count" => sqlite_changes( $this -> id ) );
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>������ ������ DBSQLITE: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
				return array( "error" => 1, "count" => 0 );
			}
		}


		// ���������� ������� ��� ���� ������ sqlite
		function queryPrepare( $query ) {
			$this -> queryPrint( $query );
			return $query;
		}


		// ��������� ��������� id
		function idLast() {
			return sqlite_last_insert_rowid( $this -> id );
		}


	}
?>