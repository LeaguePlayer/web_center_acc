<?php
	/* ����������� ����� ���������� DBMYSQL ��� ������ � ����� ������ MYSQL ����������� �������� MYSQL */


	class DBMYSQL extends DBConnection {


		// ���������� � ����� ������ mysql
		function connect() {
			try{
				if( !$this -> id = mysql_connect( $this -> host, $this -> user, $this -> password ) ) {
					throw new Exception( "������ ���������� � ����� ������" );
					$this -> id =0;
				}
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>������ ������ DBMYSQL: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
			$this -> setDBCharset( $this -> charset );
			$this -> setDBName( $this -> name );
		}


		// �������� ���������� � ����� ������ mysql
		function close() {
			if( $this -> id ) {
				mysql_close( $this -> id );
				$this -> id = 0;
			}
		}


		// ����� ������ �� ���� ������ mysql
		function fetch( $query ) {
			$this -> queryPrepare( $query );
			$data_array = array();
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "������� ���������� ������� ".$query." ��� ��������� ����������" );
				}
				if( !$result = mysql_query( $query, $this -> id ) ) {
					throw new Exception( "������ ���������� ������� ".$query."<br />ERROR #".mysql_errno( $this -> id ).": ".mysql_error( $this -> id ) );
				}
				while( $data = mysql_fetch_array( $result ) ) {
					array_push( $data_array, $data );
				}
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>������ ������ DBMYSQL: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
			return $data_array;
		}


		// ����� ���� ������ mysql
		function setDBName( $name ) {
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "������� ������� ���� ������ $name ��� ���������� ����������" );		
				}
				if( !mysql_select_db( $name, $this -> id ) ) {
					throw new Exception( "���� ������ ".$name." �� ����������<br />ERROR #".mysql_errno( $this -> id ).": ".mysql_error( $this -> id ) );
				}
				$this -> name = $name;
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>������ ������ DBMYSQL: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
		}


		// ��������� ��������� � ���� ������ mysql
		function setDBCharset( $charset ) {
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "������� ������ ��������� ".$charset." ��� ���������� ����������" );
				}
				if( !mysql_set_charset( $charset, $this -> id ) ) {
					throw new Exception( "��������� ".$charset." �� ���������� ��� ���� ������<br />ERROR #".mysql_errno( $this -> id ).": ".mysql_error( $this -> id ) );
				}
				$this -> charset = $charset;
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>������ ������ DBMYSQL: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
		}


		// ���������� ������� �� ����������, ��������, ���������� � ���� ������ mysql
		function query( $query ) {
			$this -> queryPrepare( $query );
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "������� ��������� ������ ��� ���������� ����������" );
				}
				if( !mysql_query( $query, $this -> id ) ) {
					throw new Exception( "������ ���������� ������� ".$query."<br />ERROR #".mysql_errno( $this -> id ).": ".mysql_error( $this -> id ) );
				}
				return array( "error" => 0, "count" => mysql_affected_rows( $this -> id ) );
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>������ ������ DBMYSQL: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
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
			return mysql_insert_id( $this -> id );
		}


	}
?>