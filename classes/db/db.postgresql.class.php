<?php
	/* ����������� ����� ���������� DBPOSTGRESQL ��� ������ � ����� ������ POSTGRESQL ����������� �������� POSTGRESQL */


	class DBPOSTGRESQL extends DBConnection {


		// ���������� � ����� ������ postgresql
		function connect() {
			try {
				if( !$this -> id = pg_connect( "host=".$this -> host." dbname=".$this -> name." user=".$this -> user." password=".$this -> password ) ) {
					throw new Exception( "������ ���������� � ����� ������" );
				}
			} catch( Exception $error ) {
				$this -> id =0;
				echo "<div class='statusCancel'>������ ������ DBPOSTGRESQL: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
			$this -> setDBCharset( $this -> charset );
		}


		// �������� ���������� � ����� ������ postgresql
		function close() {
			if( !empty( $this -> id ) ) {
				pg_close( $this -> id );
				$this -> id = 0;
			}
		}


		// ����� ������ �� ���� ������ postgresql
		function fetch( $query ) {
			$query = $this -> queryPrepare( $query );
			$data_array = array();
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "������� ���������� ������� ".$query." ��� ���������� ����������" );
				}
				if( !$result = pg_query( $this -> id, $query ) ) {
					throw new Exception( "������ ���������� ������� ".$query."<br />ERROR #".pg_last_error( $this -> id ) );
				}
				while( $data = pg_fetch_array( $result ) ) {
					array_push( $data_array, $data );
				}
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>������ ������ DBPOSTGRESQL: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
			return $data_array;
		}


		// ����� ���� ������ postgresql
		function setDBName( $name ) {
			$this -> name = $name;
			$this -> connect();
		}


		// ��������� ��������� � ���� ������ postgresql
		function setDBCharset( $charset ) {
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "������� ��������� ��������� ��� ���������� ���������� � ����� ������" );
				}
				if( !pg_set_client_encoding( $this -> id, $charset ) ) {
					throw new Exception( "��������� ".$charset." �� ���������� ��� ���� ������ ".$this -> id."<br />ERROR #".pg_last_error( $this -> id ) );
				}
				$this -> charset = $charset;
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>������ ������ DBPOSTGRESQL: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
		}


		// ���������� ������� �� ����������, ��������, ���������� � ���� ������ postgresql
		function queryPrepare( $query ) {
			$query = preg_replace("/(limit) (\d{1,}), (\d{1,})/", "limit $3 offset $2", $query);
			$this -> queryPrint( $query );
			return $query;
		}


		// ���������� ������� ��� ���� ������ postgresql
		function query( $query ) {
			$query = $this -> queryPrepare( $query );
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "������� ���������� ������� ".$query." ��� ���������� ����������" );
				}
				if( !pg_query( $this -> id, $query ) ) {
					throw new Exception( "������ ���������� ������� ".$query."<br />ERROR #".pg_last_error( $this -> id ) );
				}
				return array( "error" => 0, "count" => pg_affected_rows( $this -> id ) );
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>������ ������ DBPOSTGRESQL: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
				return array( "error" => 1, "count" => 0 );
			}
		}


	}

?>