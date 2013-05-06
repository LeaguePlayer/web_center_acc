<?php
	/* ����������� ����� ���������� � ����� ������ */


	abstract class DBConnection {


		// ������������ ���������
		protected $host;
		protected $name;
		protected $user;
		protected $password;
		protected $charset;
		public $dbid = 0;
		public $debug;


		// �����������
		public function __construct( $config ) {
			$this -> setParams( $config );
			$this -> connect();
		}


		// ����������
		public function __destruct() {
			$this -> close();
		}


		// ������������� ����������
		public function setParams( $config ) {
			$this -> host = isset( $config["host"] ) ? $config["host"] : "localhost";
			$this -> name = isset( $config["name"] ) ? $config["name"] : "";
			$this -> user = isset( $config["user"] ) ? $config["user"] : "root";
			$this -> password = isset( $config["password"] ) ? $config["password"] : "";
			$this -> charset = isset( $config["charset"] ) ? $config["charset"] : "cp1251";
			$this -> debug = isset( $config["debug"] ) ? $config["debug"] : false;
		}


		// ����� ���� ������
		public function setDBName( $name ) {}


		// ��������� ��������� � ���� ������
		public function setDBCharset( $charset ) {}


		// ���������� � ����� ������
		public function connect() {}


		// �������� ���������� � ����� ������
		public function close() {}


		// ���������� ������� �� ����������, ��������, ���������� � ���� ������
		public function query( $query ) {}


		// ����� ������ �� ���� ������
		public function fetch( $query ) {
			return array();
		}


		// ���������� ������� ��� ������������ ���� ������
		public function queryPrepare( $query ) {}


		// ����� ������� � ������ �������
		public function queryPrint( $query ) {
			if( $this -> debug ) {
				echo "<div class='debug'>".$query."</div>";
			}
		}


		// ��������� ��������� id
		public function idLast() {}


	}


	/* ������� ���������� */
	class DB {

		public static function instance( $config ) {

			try {
				$class = "DB".strtoupper( $config[ "driver" ] );
				$class_path = "db.".$config[ "driver" ];

				// �������� ������������� ����� �������� ������
				if( !file_exists( dirname(__FILE__)."/".$class_path.".class.php" ) ) {
					throw new Exception("�� ���������� ����� �������� ������ <b>classes/db/".$class_path.".class.php</b>");
				}
				require_once dirname(__FILE__)."/".$class_path.".class.php";

				// �������� ������������� ������
				if( !class_exists( $class ) ) {
					throw new Exception("�� ���������� ����� <b>$class</b>");
				}
				$DBDriver = new $class ( $config );
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>������ ������ DB: ������ � ����� <b>".$error -> getFile()."</b> � ������ ".$error -> getLine()."<br />".$error -> getMessage()."</div>";

				// ����������� ������ ������
				require_once "/classes/db/db.error.class.php";
				$DBDriver = new DBERROR ( $config );
			}
			return $DBDriver;
		}


	}
?>