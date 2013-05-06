<?php

	// поиск пользователя
	function usersGet( $db = 0, $usersEmail = '', $usersPassword = '' ) {
		$result = $db -> fetch( "SELECT id, login, password, name, date, activate, access, mail FROM users WHERE mail LIKE '".stringSimple( $usersEmail )."' AND password LIKE '".stringSimple( $usersPassword )."' AND activate AND access > '1' " );
		foreach( $result as $data ) {
			return array( 
				'flag' => true, 
				'id' => $data['id'], 
				'login' => $data['login'], 
				'password' => $data['password'], 
				'name' => $data['name'], 
				'date' => $data['date'], 
				'activate' => $data['activate'], 
				'access' => $data['access'], 
				'mail' => $data['mail'] 
			);
		}
		if( count( $result ) == 0 ) {
			return array( 
				'flag' => false, 
				'id' => 0, 
				'login' => '', 
				'password' => '', 
				'name' => '', 
				'date' => '', 
				'activate' => '0', 
				'access' => '0', 
				'mail' => '' 
			);
		}
	}

	// поиск подстроки в строке с обрезкой по кол-ву символов
	function stringSearch( $findText, $content ) {
		$content = stringSimple( $content );
		$pos = strpos( $content, $findText );
		$posStart = $pos - 100;
		if( $posStart < 0 ){ $posStart = 0; }
		$content = trim( substr( $content, $posStart, 400 ) )."...";
		if( $posStart != 0 ){ $content = "...".$content; }
		return $content;
	}

	// заказчка файлов
	function filesUpload( $files, $filesDirectory = "../uploadfiles/files/", $filesExtensionAllowed = "doc docx" ) {

		$filesName = $_FILES[ $files ][ 'name' ];
		$filesInfo = pathinfo( $_FILES[ $files ][ 'name' ] );
		$filesExtension = @$filesInfo['extension'];
		$filesPath = $_FILES[ $files ][ 'tmp_name' ];
		$filesType = $_FILES[ $files ][ 'type' ];
		$filesNameNew = strtotime("now")."-".rand( 1000000000, 9999999999 ).".".$filesExtension;

		$filesFlag = 1;

		if( empty( $filesName ) || !file_exists( $filesPath ) ) {
			$filesFlag = 0;
			echo "<div class='statusCancel'>Выберите корректный файл для закачки</div>";
		}

		if( file_exists( "../includes/mime/mime.php" ) ) { require_once "../includes/mime/mime.php"; }
		if( file_exists( "includes/mime/mime.php" ) ) { require_once "includes/mime/mime.php"; }
		$mimeTable = mimeGet( $filesExtension );
		if( $mimeTable != $filesType ) {
			$filesFlag = 0;
			echo "<div class='statusCancel'>Расширение и тип файла не соответсвуют друг другу либо в таблице MIME-типов нет данных о текущем расширении файла</div>";
		}

		// создание директории при ее отсутствии
		if( !is_dir( $filesDirectory ) ) {
			if(  !mkdir( $filesDirectory, "0777", true ) ) {
				$filesFlag = 0;
				echo "<div class='statusCancel'>Ошибка создания директории '$filesDirectory'</div>";
			} else {
				echo "<div class='statusOk'>Директории '$filesDirectory' успешно создана</div>";
			}
		}

		if( strpos( $filesExtensionAllowed, $filesExtension ) === FALSE ) {
			if( $filesFlag ) {
				echo "<div class='statusCancel'>Данное расширение фала не разрешено для закачки</div>";
			}
			$filesFlag = 0;
		}

		// копирование файла
		if( !$filesFlag || !copy( $filesPath, $filesDirectory.$filesNameNew ) ) {
			if( $filesFlag ) {
				echo "<div class='statusCancel'>Ошибка копирования файла</div>";
			}
			$filesFlag = 0;
		}


		if( $filesFlag ) {
			return $filesNameNew;
		} else {
			return $filesFlag;
		}

	}

	// вывод меню
	function menuUser( $db, $razdel = 0, $active = 0, $root = true, $prefix = "", $submenu = true ) {

		$razdelActive = 0;

		$result = $db -> fetch( "SELECT razdel FROM pages WHERE id = '$active' ORDER BY id LIMIT 0, 1" );
		foreach( $result as $data ) {
			$razdelActive = $data['razdel'];
		}

		$result = $db -> fetch ( "SELECT id, name, alias, razdel FROM pages WHERE visible AND razdel = '$razdel' ORDER BY position, id" );

		if( $prefix == 'admin/' && $razdel == '6'  ) {
			$result = array( 
				array( 
					'id' => '23',
					'name' => 'Документы',
					'alias' => 'documents',
					'razdel' => '6'
				) 
			);
		}

		if( count( $result ) > 0 ) {
			echo "<ul "; if( $root ){ echo "id='menu'"; } echo ">";
		}
	
			foreach( $result as $data ) {
				echo "<li class='submenu".$data['alias']." "; if( $data['id'] == $active || $data['id'] == $razdelActive ){ echo "item"; } echo"'>
					<div>
						<a href='$prefix"; if( !empty( $data['alias'] ) ) { echo "page/".$data['alias']."/"; } echo "' title='".$data['name']."' class='submenu".$data['alias']." "; if( $data['id'] == $active || $data['id'] == $razdelActive ){ echo " item"; } echo"'>";
							echo $data['name'];
						echo"</a>
					</div>";

					if( $submenu ) { 
						menuUser( $db, $data['id'], $active, false, $prefix );
					}
			
				echo "</li>";
			}
	
		if( count( $result ) > 0 ) {
			echo "</ul>";
			if( $root ) {
				echo "<script type='text/javascript' src='includes/menuhorizontal/menuhorizontal.js'></script>";
			}
		}

	}


	// перевод в верхний / нижний регистр
	function loToUp( $source, $toSmall = false ) {
		$arrayLo = array( 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я' );
		$arrayUp = array( 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я' );
		if( $toSmall ) {
			return strtolower( str_replace( $arrayUp, $arrayLo, $source ) );
		} else {
			return strtoupper( str_replace( $arrayLo, $arrayUp, $source ) );
		}
	}


	// перевод текста в транслит
	function toTrunslit( $source ) {
		$sourse= loToUp( $source, $toSmall = true );
		$arraySource=array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я','~','`','!','@','&quot;','#','№',';','$','%','^',':','&amp;','?','*','(',')','_','+','=','/','\\','|','}',']','[','{','&#039;','&quot;',':',';','?','/','.',',','.','&gt;',',','&lt;','"','&','\'','<','>',' ' );
		$arrayReturn=array('a','b','v','g','d','e','yo','j','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','tc','ch','sh','sh','','i','','e','yu','ya','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','-' );
		return str_replace( $arraySource, $arrayReturn, $source );
	}


	// убрать все спец символы из текста
	function stringSimple( $source )
	{
		$search = array (
			"'<script[^>]*?>.*?</script>'si",
			"'<[\/\!]*?[^<>]*?>'si",
			"'([\r\n])[\s]+'",
			"'&(quot|#34);'i",
			"'&(amp|#38);'i",
			"'&(lt|#60);'i",
			"'&(gt|#62);'i",
			"'&(nbsp|#160);'i",
			"'&(iexcl|#161);'i",
			"'&(cent|#162);'i",
			"'&(pound|#163);'i",
			"'&(copy|#169);'i",
			"'&#(\d+);'e"
		);

		$replace = array (
			"",
			"",
			"\\1",
			"\"",
			"&",
			"<",
			">",
			" ",
			chr(161),
			chr(162),
			chr(163),
			chr(169),
			"chr(\\1)"
		);
		$source = preg_replace( $search, $replace, $source );
		$source = htmlspecialchars( trim( strip_tags( $source ) ), ENT_QUOTES );
		return $source;
	}



	// преобразование даты
	function dateFormat( $date, $time="0:0:0" ) {
		$date=explode( ".", $date );
		$time=explode( ":", $time );
		return mktime( $time[0], $time[1], $time[2], $date[1], $date[0], $date[2] );
	}


	// обрезать строку
	function stringTruncate( $source, $stringLength = 300, $stringPostfix = "..." ) {
		$source = stringSimple( $source );
		$stringPost = "";
		if( strlen( $source ) > $stringLength ) {
			$stringPost = $stringPostfix;
		}
		$source = trim( substr( $source, 0, $stringLength ) ).$stringPost;
		return $source;
	}



	// вывод страниц
	function navigate( $db = 0, $query = "", $navigate = 0, $limit = 6, $page = "", $extra = "" ) {

		$url = urlGet(  );
		if( !empty( $page ) ) {
			$url .= "page/$page/";
		}

		$query=str_replace( array( "limit $navigate, $limit", "LIMIT $navigate, $limit" ), "", $query );
		$result = $db -> fetch( $query );
		$number = ceil( count( $result ) / $limit );

		if( $number > 1 ) {

			echo "<div class='navigate'>";
				echo "Страницы: ";
				for( $i = 0; $i < $number; $i++ ) {

					if( $i == 0 ) {

						//echo "<a href='"; if( !empty( $page ) ){ echo "page/$page/"; } echo $extra."' title='Первая'>«</a>";

						if( $navigate > ( $i + 3 ) * $limit ) {
							echo "<a href='"; if( !empty( $page ) ){ echo "page/$page/"; } echo $extra."' title='1'>1</a> ";
						}

						if( $navigate > ( $i + 4 ) * $limit ) {
							echo "... ";
						}

					}

					$pageVisible = array( $navigate / $limit - 3, $navigate / $limit - 2, $navigate / $limit - 1, $navigate / $limit, $navigate / $limit + 1, $navigate / $limit + 2, $navigate / $limit + 3 );
					if( in_array( $i, $pageVisible ) ) {
						if( $i * $limit == $navigate ) {
							echo "<span title='".( $i + 1 )."'>".( $i + 1 )."</span> ";
						} else {
							echo "<a href='"; if( !empty( $page ) ){ echo "page/$page/"; } echo $extra; if( !empty( $i ) ) { echo "navigate/".( $i * $limit )."/"; } echo "' title='".( $i + 1 )."'>".( $i + 1 )."</a> ";
						}

					}

					if( $i == $number - 1 ) {

						if( $navigate < ( $i - 4 ) * $limit ) {
							echo "... ";
						}

						if( $navigate < ( $i - 3 ) * $limit ) {
							echo "<a href='"; if( !empty( $page ) ){ echo "page/$page/"; } echo $extra."navigate/".( $i * $limit )."/' title='".( ceil( count( $result ) / $limit ) )."'>".( ceil( count( $result ) / $limit ) )."</a> ";
						}

						//echo "<a href='"; if( !empty( $page ) ){ echo "page/$page/"; } echo $extra."navigate/".( $i * $limit )."/"' title='Последняя'>»</a>";

					}
				}
			echo "</div>
			<div class='line'></div>";

			// переключение страниц по 'ctrl + стрелка влево', 'ctrl + стрелка вправо'
			echo "<script type='text/javascript' src='includes/navigate/navigate.js'></script>
			<script type='text/javascript'>navigate( '$url', '$navigate', '$limit', '$extra', '$number' );</script>";
		}
	}



	// перекодирование заголовков в письме
	function mailHeaderEncode( $str, $dataCharset, $sendCharset )
	{
		if( $dataCharset != $sendCharset ) {
			$str = iconv( $dataCharset, $sendCharset, $str );
		}

		return '=?' . $sendCharset . '?B?' . base64_encode($str) . '?=';
	}


	// отправка письма
	function mailSend( $nameTo, $emailTo, $subject, $body, $nameFrom, $emailFrom, $plain = 'html', $dataCharset = 'windows-1251', $sendCharset = 'windows-1251' )
	{
		if( $dataCharset != $sendCharset ) {
			$body = iconv( $dataCharset, $sendCharset, $body );
		}

		if ( $plain == 'html' ) {
			$body = "<html><head><title>$subject</title></head><body>$body</body></html>";
		}

		$to = mailHeaderEncode( $nameTo, $dataCharset, $sendCharset ). ' <' . $emailTo . '>';
		$subject = mailHeaderEncode( $subject, $dataCharset, $sendCharset );
		$from =  mailHeaderEncode( $nameFrom, $dataCharset, $sendCharset ).' <' . $emailFrom . '>';
		$headers = "From: $from\r\n";
		$headers .= "Content-type: text/$plain; charset=$sendCharset\r\n";
		$headers .= "Mime-Version: 1.0\r\n";

		return mail( $to, $subject, $body, $headers );
	}

	// определение адреса сайта
	function urlGet( $urlPostfix = "" ) {
		$url = "http://".$_SERVER['SERVER_NAME'].dirname( $_SERVER['PHP_SELF'] );
		if( $url[ strlen( $url ) - 1 ] != "/" ) {
			$url .= "/";
		}
		$url .= $urlPostfix;
		$url = str_replace( "\\", "", $url );
		return $url;
	}

	// определение id раздело по алиасу
	function idGet( $db = 0, $page = '' ) {
		$result = $db -> fetch( "SELECT id FROM pages WHERE alias LIKE '$page' LIMIT 0, 1" );
		foreach( $result as $data ) {
			return $data['id'];
		}
		return 0;
	}

	// проверка корректности email
	function emailCheck( $email ) {
		if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			return false;
		} else {
			return true;
		}
	}

	// получение пароля
	function passwordGet(  ) {
		$masLow = array( 'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm' );
		$masUp = array( 'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M' );
		$masNum = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
		$masAll = array( 'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm', 'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
		return $masLow[ rand( 0, 25 ) ].$masAll[ rand( 0, 61 ) ].$masUp[ rand( 0, 25 ) ].$masNum[ rand( 0, 9 ) ].$masAll[ rand( 0, 61 ) ].$masNum[ rand( 0, 9 ) ].$masLow[ rand( 0, 25 ) ].$masUp[ rand( 0, 25 ) ];
	}

?>