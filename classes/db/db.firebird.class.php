<?php
	/* ����������� ����� ���������� DBFIREBIRD ��� ������ � ����� ������ FIREBIRD ����������� �������� FIREBIRD */


	class DBFIREBIRD extends DBConnection {


		// ���������� � ����� ������ firebird
		function connect() {
			try {
				if( !$this -> id = ibase_connect( $this -> host."".$this -> name, $this -> user, $this -> password, $this -> charset ) ) {
					throw new Exception( "������ ���������� � ����� ������" );
				}
			} catch( Exception $error ) {
				$this -> id =0;
				echo "<div class='statusCancel'>������ ������ DBFIREBIRD: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
		}


		// �������� ���������� � ����� ������ firebird
		function close() {
			if( $this -> dbid ) {
				ibase_close( $this -> id );
				$this -> id = 0;
			}
		}


		// ����� ������ �� ���� ������ firebird
		function fetch( $query ) {
			$query = $this -> queryPrepare( $query );
			$data_array = array();
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "������� ���������� ������� ".$query." ��� ���������� ����������" );
				}
				if( !$result = ibase_query( $this -> id, $query ) ) {
					throw new Exception( "������ ���������� ������� ".$query."<br />ERROR #".ibase_errcode().": ".ibase_errmsg() );
				}
				while( $data = ibase_fetch_assoc( $result ) ) {
					$data = array_change_key_case( $data, CASE_LOWER );
					array_push( $data_array, $data );
				}
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>������ ������ DBFIREBIRD: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
			return $data_array;
		}


		// ����� ���� ������ firebird
		function setDBName( $name ) {
			$this -> name = $name;
			$this -> connect();
		}


		// ���������� ������� �� ����������, ��������, ���������� � ���� ������ firebird
		function query( $query ) {
			$query = $this -> queryPrepare( $query );
			try {
				if( empty( $this -> id ) ) {
					throw new Exception( "������� ���������� ������� ".$query." ��� ���������� ����������" );
				}
				if( !ibase_query( $query, $this -> id ) ) {
					throw new Exception( "������ ���������� ������� ".$query."<br />ERROR #".ibase_errcode().": ".ibase_errmsg() );
				}
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>������ ������ DBFIREBIRD: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
		}


		// ���������� ������� ��� ���� ������ firebird
		function queryPrepare( $query ) {
			$query = preg_replace("/(limit) (\d{1,}), (\d{1,})/", "rows $2+1 to $3", $query);
			$this -> queryPrint( $query );
			return $query;
		}


	}
?>