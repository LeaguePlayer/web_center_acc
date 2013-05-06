<?php
	require_once "cfg/config.php";
	require_once "classes/db/db.class.php";
	require_once "components/functions.php";

	$table = isset( $_GET['table'] ) ? stringSimple( $_GET['table'] ) : NULL;
	$name = isset( $_GET['name'] ) ? stringSimple( $_GET['name'] ) : NULL;
	$path = isset( $_GET['path'] ) ? stringSimple( $_GET['path'] ) : NULL;
	$downloads = isset( $_GET['downloads'] ) ? stringSimple( $_GET['downloads'] ) : NULL;
	$id = isset( $_GET['id'] ) ? stringSimple( $_GET['id'] ) : NULL;
	$dir = isset( $_GET['dir'] ) ? stringSimple( $_GET['dir'] ) : NULL;

	$filesName = '';
	$filesPath = '';

	$url = urlGet();
	$db = DB::instance( $config );

	$flag = true;
	$message = "";

	if( empty( $table ) || empty( $downloads ) || empty( $id ) || empty( $name ) || empty( $path ) || empty( $dir ) ) {
		$flag = false;
		$message .= "<div class='statusCancel'>Переданы не все обязательные параметры для скачивания файла</div>";
	}

	$result = $db -> fetch( "SELECT $name, $path FROM $table WHERE id = '$id' " );
	foreach( $result as $data ) {
		$filesName = $data[ $name ];
		$filesPath = $data[ $path ];

		if( empty( $filesName ) ) {
			$filesName = $filesPath;
		} else {
			$filesInfo = pathinfo( $dir.$filesPath );
			$filesExtension = @$filesInfo['extension'];
			$filesName .= ".".$filesExtension;
		}
	}
	if( count( $result ) == 0 ) {
		$flag = false;
		$message .= "<div class='statusCancel'>Отсутствуют данные о скачиваемом файле</div>";
	}

	if( $flag ) {
		$result = $db -> query( "UPDATE $table SET $downloads = $downloads + 1 WHERE id = '$id' " );
		if( !empty( $result['error'] ) ) {
			$flag = false;
			$message .= "<div class='statusCancel'>Ошибка обновления информации скачиваемого файла</div>";
		}
	}

	if( $flag ) {
		header( "Content-type: text/html; charset=windows-1251" ); 
		header( "Content-Type: application/force-download" );
		header( "Content-Transfer-Encoding: binary" );
		header( "Content-Disposition: attachment; filename=\"$filesName\"" );
		header( "Content-Length: ".filesize( $dir.$filesPath ) );
		readfile( $dir.$filesPath );
	} else {
		echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
		<html xmlns='http://www.w3.org/1999/xhtml'>
			<head>
				<meta http-equiv='content-type' content='text/html; charset=windows-1251' />
				<title>Ошибка скачивания файла</title>
				<base href='$url' />

				<link rel='stylesheet' type='text/css' href='css/style.css' />

				<meta name='title' content='Ошибка скачивания файла' />
				<meta name='keywords' content='Ошибка скачивания файла' />
				<meta name='description' content='Ошибка скачивания файла' />
			</head>
			<body>
				$message
			</body>
		</html>";
	}
?>