<?php

	// файлы. административная часть
	function filesAdmin( $db = 0, $page = '' ) {

		$url = urlGet( $urlPostfix = "" );
		$id = idGet( $db, $page );

		// если не сужествует переменной, то присвоить ей 0
		if( !isset( $_REQUEST['filesRazdel'] ) ) {
			$_REQUEST['filesRazdel'] = 0;
		}

		// добавление записи
		if( isset( $_POST["filesAdd"] ) ) {

			$path = filesUpload( "filesPath", $filesDirectory = "../uploadfiles/files/", $filesExtensionAllowed = "doc xls txt pdf docx xlsx exe rar" );

			if( !empty( $path ) ) {
				$result = $db -> query( "INSERT INTO files VALUES( NULL, '".$_POST['filesName']."', '".$_POST['filesDate']."', '$path', '".$_POST['filesDescription']."', '$id', '".$_POST['filesDownloads']."', '1' )" );
				if( !$result['error'] ) {
					echo "<div class='statusOk'>Запись успешно добавлена</div>";
				}
			}
		}

		// изменение записи
		if( isset( $_POST['filesEdit'] ) ) {

			$path = filesUpload( "filesPath2", $filesDirectory = "../uploadfiles/files/", $filesExtensionAllowed = "doc xls txt pdf docx xlsx exe rar" );
			if( !empty( $path ) ) {
				$result = $db -> fetch( "SELECT path FROM files WHERE id = '".$_REQUEST['filesRazdel']."' AND pages = '$id' " );
				foreach( $result as $data ) {
					if( file_exists( "../uploadfiles/files/".$data['path'] ) ) {
						unlink( "../uploadfiles/files/".$data['path'] );
					}
				}
				$result = $db -> query( "UPDATE files SET  path = '$path' WHERE id='".$_REQUEST['filesRazdel']."' AND pages = '$id' " );
			}

			$result = $db -> query( "UPDATE files SET  name = '".$_POST['filesName2']."', description = '".$_POST['filesDescription2']."', downloads = '".$_POST['filesDownloads2']."' WHERE id='".$_REQUEST['filesRazdel']."' AND pages = '$id' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>Запись успешно изменена</div>";
			}
		}

		// удаление записи
		if( isset( $_GET['filesDelete'] ) ) {

			$result = $db -> fetch( "SELECT path FROM files WHERE id = '".$_GET['filesDelete']."' AND pages = '$id' " );
			foreach( $result as $data ) {
				if( file_exists( "../uploadfiles/files/".$data['path'] ) ) {
					unlink( "../uploadfiles/files/".$data['path'] );
				}
			}

			$result = $db -> query( "DELETE FROM files WHERE id='".$_GET['filesDelete']."' AND pages = '$id' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>Запись успешно удалена</div>";
			}
		}

		echo "<div class='left'>Имя</div>
		<div><input type='text' name='filesName' /></div>
		<div class='line'></div>
		<div class='left'>Дата</div>
		<div><input type='text' name='filesDate' value='".date( "Y-m-d H:i:s" )."' readonly='readonly' /></div>
		<div class='line'></div>
		<div class='left'>Файл</div>
		<div><input type='file' name='filesPath' /></div>
		<div class='line'></div>
		<div class='left'>Описание</div>
		<div><textarea name='filesDescription'></textarea></div>
		<div class='line'></div>
		<div class='left'>Скачиваний</div>
		<div><input type='text' name='filesDownloads' value='0' /></div>
		<div class='line'></div>
		<div class='left'>&nbsp;</div>
		<div><input type='submit' name='filesAdd' value='Добавить' /></div>
		<div class='line block'></div>

		<div class='left'>Запись</div>
		<div>
			<select name='filesRazdel' onchange='document.form.submit();'>
				<option value='0'>Выберите запись для редактирования</option>";
				$result = $db -> fetch( "SELECT id, name, date FROM files WHERE pages = '$id' ORDER BY date DESC, id DESC " );
				foreach( $result as $data ) {
					echo "<option value='".$data['id']."' "; if( $_REQUEST['filesRazdel'] == $data['id'] ){ echo "selected='selected'"; } echo">".date_format( date_create( $data['date'] ), 'd.m.y' )." ".$data['name']."</option>";
				}
			echo "</select>
		</div>
		<div class='line'></div>";

		if( !empty( $_REQUEST['filesRazdel'] ) ) {
			$result = $db -> fetch( "SELECT files.id AS id, files.name AS name, files.date AS date, files.path AS path, files.description AS description, files.downloads AS downloads, users.mail AS mail FROM files LEFT JOIN users ON ( files.users = users.id ) WHERE files.id='".$_REQUEST['filesRazdel']."' AND files.pages = '$id' " );
			foreach( $result as $data ) {

				$filesInfo = pathinfo( $data['path'] );
				switch( $filesInfo['extension'] ) {
					case "jpg": case "gif": case "png": case "bmp":
						$filesImage = "<img src='images/iconJpg.gif' style='float: right;' />";
						break;

					case "doc": case "docx":
						$filesImage = "<img src='images/iconDoc.gif' style='float: right;' />";
						break;

					case "xls": case "xlsx":
						$filesImage = "<img src='images/iconXls.gif' style='float: right;' />";
						break;

					case "txt":
						$filesImage = "<img src='images/iconTxt.gif' style='float: right;' />";
						break;

					case "pdf":
						$filesImage = "<img src='images/iconPdf.gif' style='float: right;' />";
						break;

					case "swf":
						$filesImage = "<img src='images/iconSwf.gif' style='float: right;' />";
						break;

					case "djvu":
						$filesImage = "<img src='images/iconDjvu.gif' style='float: right;' />";
						break;

					case "exe":
						$filesImage = "<img src='images/iconExe.gif' style='float: right;' />";
						break;

					case "zip": case "rar": case "7z":
						$filesImage = "<img src='images/iconZip.gif' style='float: right;' />";
						break;

					default:
						$filesImage = "<img src='images/iconUnknown.gif' style='float: right;' />";
				}

				echo "<div class='left'>Имя</div>
				<div><input type='text' name='filesName2' value='".$data['name']."' /></div>
				<div class='line'></div>
				<div class='left'>Дата</div>
				<div><input type='text' name='filesDate2' value='".$data['date']."' readonly='readonly' /></div>
				<div class='line'></div>
				<div class='left'>Файл</div>
				<div><input type='file' name='filesPath2' /></div>
				<div class='line'></div>
				<div class='left'>Описание</div>
				<div><textarea name='filesDescription2'>".$data['description']."</textarea></div>
				<div class='line'></div>
				<div class='left'>Скачиваний</div>
				<div><input type='text' name='filesDownloads2' value='".$data['downloads']."' /></div>
				<div class='line'></div>
				<div class='left'>Пользователь</div>
				<div><input type='text' value='".$data['mail']."' readonly='readonly' /></div>
				<div class='line'></div>
				<div class='left'>&nbsp;</div>
				<div>
					<input type='submit' name='filesEdit' value='Изменить' />
					<input type='button' value='Удалить' onclick='if( window.confirm( \"Вы действительно хотите удалить данную запись?\" ) ){ window.location.href=\"$url"; if( !empty( $page ) ){ echo "page/$page/"; } echo "filesDelete/".$data['id']."/\";}' />";

					if( !empty( $data['picture'] ) ) {
						echo "<img src='uploadfiles/files/".$data['picture']."' align='right'>";
					}

				echo "</div>";

				echo "<a href='uploadfiles/files/".$data['path']."' target='_blank' title='Посмотреть файл'>$filesImage</a>";
			}
		}

		echo "<div class='line block'></div>";

	}

	function filesFormUser( $db = 0, $page = '', $usersId = '-1', $limit = 5 ) {

		$url = urlGet( $urlPostfix = "" );

		// удаление записи
		if( isset( $_GET['filesDelete'] ) ) {

			$_GET['filesDelete'] = stringSimple( $_GET['filesDelete'] );

			$result = $db -> fetch( "SELECT path FROM files WHERE id = '".$_GET['filesDelete']."' AND users = '$usersId' " );
			foreach( $result as $data ) {

				if( file_exists( "uploadfiles/files/".$data['path'] ) ) {
					unlink( "uploadfiles/files/".$data['path'] );
				}
			}

			$result = $db -> query( "DELETE FROM files WHERE id='".$_GET['filesDelete']."' AND users='$usersId' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>Запись успешно удалена</div>";
			}
		}

		$result = $db -> fetch( "SELECT id, name, date, path, description, downloads, users FROM files ORDER BY date DESC, id DESC LIMIT 0, $limit" );
		echo "<div class='line block'></div>";
		foreach( $result as $data ) {
			echo "<div class='files'>
				<div class='filesDownloads'>
					<a href='downloads.php?table=files&name=name&path=path&downloads=downloads&id=".$data['id']."&dir=uploadfiles/files/' onClick=' var downloads = $( \"#filesDownloads".$data['id']."\" ); downloads.text( parseInt( downloads.html() ) + 1 ); ' target='_blank' title='Скачать файл'></a>
					<div id='filesDownloads".$data['id']."'>".$data['downloads']."</div>
				</div>
				<div class='filesName'>";
					echo $data['name'];
					if( $data['users'] == $usersId ) {
						echo "<br /><a href='' title='Удалить файл' onclick='if( window.confirm( \"Вы действительно хотите удалить данную запись?\" ) ){ window.location.href=\"$url"; if( !empty( $page ) ){ echo "page/$page/"; } echo "filesDelete/".$data['id']."/\";} return false;'>Удалить файл</a>";
					}
				echo "</div>
			</div>
			<div class='line'></div>";
		}

		echo "<div class='line block'></div>
		<div class='href'><a href='page/documents/' title='Посмотреть все'>посмотреть все</a></div>";
	}


	// файлы. пользовательская часть
	function filesUser( $db = 0, $page = '', $navigate = 0, $limit = 10, $item = 0, $flagAccess = true ) {

		if( !$flagAccess ) {

			$params = "";
			if( !empty( $item ) ) {
				$params = "AND files.id = '$item' ";
			}
	
			echo "<h1 class='heading'>Документы</h1><br />";
	
			$query = "SELECT files.id AS id, files.name AS name, files.date AS date, files.path AS path, files.description AS description, files.downloads AS downloads FROM files, pages WHERE pages.id = files.pages $params ORDER BY files.date DESC, files.id DESC LIMIT $navigate, $limit";
			$result = $db -> fetch( $query );

			if( count( $result ) == 0 ) {
				echo "<p>В данном разделе пока еще нет записей...</p>";
			}

			foreach( $result as $data ) {
				echo $data['name']."<br />";
				echo date_format( date_create( $data['date'] ), "H:i - d.m.Y" )."<br />";
				echo "(<span id='downloads".$data['id']."'>".$data['downloads']."</span>)<br />";
				echo $data['description']."<br />";
				echo "<a href='downloads.php?table=files&name=name&path=path&downloads=downloads&id=".$data['id']."&dir=uploadfiles/files/' target='_blank' title='Скачать файл' onClick=' var downloads = $( \"#downloads".$data['id']."\" ); downloads.text( parseInt( downloads.html() ) + 1 ); '>Скачать</a><br />";
				echo "<div class='line block'></div>";
			}
	
			navigate( $db, $query, $navigate, $limit, $page, $extra = "" );

		} else {
			echo "<h1 class='heading'>Документы</h1><br />
			<div class='statusCancel'>У Вас нет прав на просмотр данной страницы...</div>";
		}

	}

?>