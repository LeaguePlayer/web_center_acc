<?php
	/* абстрактный класс соединения с базой данных */


	abstract class DBConnection {


		// используемые параметры
		protected $host;
		protected $name;
		protected $user;
		protected $password;
		protected $charset;
		public $dbid = 0;
		public $debug;


		// конструктор
		public function __construct( $config ) {
			$this -> setParams( $config );
			$this -> connect();
		}


		// деструктор
		public function __destruct() {
			$this -> close();
		}


		// инициализация параметров
		public function setParams( $config ) {
			$this -> host = isset( $config["host"] ) ? $config["host"] : "localhost";
			$this -> name = isset( $config["name"] ) ? $config["name"] : "";
			$this -> user = isset( $config["user"] ) ? $config["user"] : "root";
			$this -> password = isset( $config["password"] ) ? $config["password"] : "";
			$this -> charset = isset( $config["charset"] ) ? $config["charset"] : "cp1251";
			$this -> debug = isset( $config["debug"] ) ? $config["debug"] : false;
		}


		// выбор базы данных
		public function setDBName( $name ) {}


		// установка кодировки в базе данных
		public function setDBCharset( $charset ) {}


		// соединение с базой данных
		public function connect() {}


		// закрытие соединения с базой данных
		public function close() {}


		// выполнение запроса на добавление, удаление, обновдение в базе данных
		public function query( $query ) {}


		// выбор данных из базы данных
		public function fetch( $query ) {
			return array();
		}


		// подготовка запроса под определенную базу данных
		public function queryPrepare( $query ) {}


		// вывод запроса в режиме отладки
		public function queryPrint( $query ) {
			if( $this -> debug ) {
				echo "<div class='debug'>".$query."</div>";
			}
		}


		// последний внесенный id
		public function idLast() {}


	}


	/* фабрика соединений */
	class DB {

		public static function instance( $config ) {

			try {
				$class = "DB".strtoupper( $config[ "driver" ] );
				$class_path = "db.".$config[ "driver" ];

				// проверка существования файла описания класса
				if( !file_exists( dirname(__FILE__)."/".$class_path.".class.php" ) ) {
					throw new Exception("Не существует файла описания класса <b>classes/db/".$class_path.".class.php</b>");
				}
				require_once dirname(__FILE__)."/".$class_path.".class.php";

				// проверка существования класса
				if( !class_exists( $class ) ) {
					throw new Exception("Не существует класс <b>$class</b>");
				}
				$DBDriver = new $class ( $config );
			} catch( Exception $error ) {
				echo "<div class='statusCancel'>ОШИБКА КЛАССА DB: Ошибка в файле <b>".$error -> getFile()."</b> в строке ".$error -> getLine()."<br />".$error -> getMessage()."</div>";

				// подключение класса ошибок
				require_once "/classes/db/db.error.class.php";
				$DBDriver = new DBERROR ( $config );
			}
			return $DBDriver;
		}


	}
?>