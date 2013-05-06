<?php
	/* ����������� ����� ���������� DBMYSQLI ��� ������ � ����� ������ MYSQL ����������� �������� MYSQLI */


	class DBMYSQLI extends DBConnection {


		// ���������� � ����� ������ mysql
		function connect() {
			try {
				if( !$this -> id = mysqli_connect( $this -> host, $this -> user, $this -> password ) ) {
					throw new Exception( "������ ���������� � ����� ������" );
				}
			} catch( Exception $error ) {
				$this -> id =0;
				echo "<div class='statusCancel'>������ ������ DBMYSQLI: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
			$this -> setDBName( $this -> name );
			$this -> setDBCharset( $this -> charset );
		}


		// �������� ���������� � ����� ������ mysql
		function close() {
			if( $this -> id ) {
				mysqli_close( $this -> id );
				$this -> id = 0;
			}
		}


		// ����� ������ �� ���� ������ mysql
		function fetch( $query ) {
			$this -> queryPrepare( $query );
			$data_array = array();
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "������� ���������� ������� ".$query." ��� ���������� ����������" );
				}
				if( !$result = mysqli_query( $this -> id, $query ) ) {
					throw new Exception( "������ ���������� ������� ".$query."<br />ERROR #".mysqli_errno( $this -> id ).": ".mysqli_error( $this -> id ) );
				}
				while( $data = mysqli_fetch_array( $result ) ) {
					array_push( $data_array, $data );
				}
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>������ ������ DBMYSQLI: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
			return $data_array;
		}


		// ����� ���� ������ mysql
		function setDBName( $name ) {
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "������� ������ ���� ������ ".$name." ��� ���������� ����������" );
				}
				if( !mysqli_select_db( $this -> id, $name ) ) {
					throw new Exception( "���� ������ ".$name." �� ����������<br />ERROR #".mysqli_errno( $this -> id ).": ".mysqli_error( $this -> id ) );
				}
				$this -> name = $name;
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>������ ������ DBMYSQLI: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
		}


		// ��������� ��������� � ���� ������ mysql
		function setDBCharset( $charset ) {
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "������� ���������� ��������� ��� ���������� ���������� � ����� ������" );
				}
				if( !mysqli_set_charset( $this -> id, $charset ) ) {
					throw new Exception( "��������� ".$charset." �� ���������� ��� ���� ������ ".$this -> id."<br />ERROR #".mysqli_errno( $this -> id ).": ".mysqli_error( $this -> id ) );
				}
				$this -> charset = $charset;
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>������ ������ DBMYSQLI: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
		}


		// ���������� ������� �� ����������, ��������, ���������� � ���� ������ mysql
		function query( $query ) {
			$this -> queryPrepare( $query );
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "������� ��������� ������ ".$query." ��� ���������� ���������� c ����� ������" );
				}
				if( !mysqli_query( $this -> id, $query ) ) {
					throw new Exception( "������ ���������� ������� ".$query."<br />ERROR #".mysqli_errno( $this -> id ).": ".mysqli_error( $this -> id ) );
				}
				return array( "error" => 0, "count" => mysqli_affected_rows( $this -> id ) );
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>������ ������ DBMYSQLI: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
				return array( "error" => 1, "count" => 0 );
			}
		}


		// ���������� ������� ��� ���� ������ mysql
		function queryPrepare( $query ) {
			$this -> queryPrint( $query );
			return $query;
		}


		// ��������� ��������� id
		function idLast() {
			return mysqli_insert_id( $this -> id );
		}


	}
?>