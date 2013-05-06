<?php

	// фотогалерея простая. административная часть
	function photogalleryAdmin( $db = 0, $page = '' ) {

		$url = urlGet( $urlPostfix = "" );
		$id = idGet( $db, $page );

		// если не сужествует переменной, то присвоить ей 0
		if( !isset( $_REQUEST['photogalleryRazdel'] ) ) {
			$_REQUEST['photogalleryRazdel'] = 0;
		}
		if( !isset( $_REQUEST['photogalleryPictureRazdel'] ) ) {
			$_REQUEST['photogalleryPictureRazdel'] = 0;
		}

		// добавление записи
		if( isset( $_POST['photogalleryAdd'] ) ) {
			$picture = "";
			if( !empty( $_FILES['photogalleryPicture']['name'] ) ) {
				require_once "../classes/image/image.class.php";
				$image = new IMAGE( 'photogalleryPicture' );
				$picture = $image -> save( array( 'size' => 'square', 'width' => 90, 'height' => 90, 'quality' => 100, 'path' => '../uploadfiles/photogallery/' ) );
				if( !empty( $picture ) ) {
					echo "<div class='statusOk'>Картинка успешно добавлена</div>";
				}
			}
			$result = $db -> query( "INSERT INTO photogallery VALUES( NULL, '$id', '$picture', '".$_POST['photogalleryName']."', '".$_POST['photogalleryDate']."', '".$_POST['photogalleryDescription']."' )" );
			if( !$result['error'] ) {
				echo "<div class='statusOk'>Запись успешно добавлена</div>";
			}
		}

		// изменение записи
		if( isset( $_POST['photogalleryEdit'] ) ) {
			if( !empty( $_FILES['photogalleryPicture2']['name'] ) ) {
				require_once "../classes/image/image.class.php";
				$image = new IMAGE( 'photogalleryPicture2' );
				$picture = $image -> save( array( 'size' => 'square', 'width' => 90, 'height' => 90, 'quality' => 100, 'path' => '../uploadfiles/photogallery/' ) );
				if( !empty( $picture ) ) {
					$result = $db -> fetch( "SELECT picture FROM photogallery WHERE id = '".$_REQUEST['photogalleryRazdel']."' " );
					foreach( $result as $data ) {
						if( file_exists( "../uploadfiles/photogallery/".$data['picture'] ) ) {
							unlink( "../uploadfiles/photogallery/".$data['picture'] );
						}
					}
					$result = $db -> query( "UPDATE photogallery SET picture = '$picture' WHERE id = '".$_REQUEST['photogalleryRazdel']."' " );
				}
			}

			$result = $db -> query( "UPDATE photogallery SET name = '".$_POST['photogalleryName2']."', date = '".$_POST['photogalleryDate2']."', description = '".$_POST['photogalleryDescription2']."' WHERE id = '".$_REQUEST['photogalleryRazdel']."' " );
			if( !$result['error'] ) {
				echo "<div class='statusOk'>Запись успешно изменена</div>";
			}
		}

		// удаление записи
		if( isset( $_GET['photogalleryDelete'] ) ) {
			$result = $db -> fetch( "SELECT picture FROM photogallery WHERE id = '".$_GET['photogalleryDelete']."' " );
			foreach( $result as $data ) {
				if( file_exists( "../uploadfiles/photogallery/".$data['picture'] ) ) {
					unlink( "../uploadfiles/photogallery/".$data['picture'] );
				}
			}
			$result = $db -> query( "DELETE FROM photogallery WHERE id = '".$_GET['photogalleryDelete']."' " );
			if( !$result['error'] ) {
				echo "<div class='statusOk'>Запись успешно удалена</div>";
			}
		}

		// удаление превью записи
		if( isset( $_GET['photogalleryPreviewDelete'] ) ) {
			$result = $db -> fetch( "SELECT picture FROM photogallery WHERE id = '".$_REQUEST['photogalleryRazdel']."' " );
			foreach( $result as $data ) {
				if( file_exists( "../uploadfiles/photogallery/".$data['picture'] ) ) {
					unlink( "../uploadfiles/photogallery/".$data['picture'] );
				}
			}
			$result = $db -> query( "UPDATE photogallery SET picture = '0' WHERE id = '".$_REQUEST['photogalleryRazdel']."' " );
			if( !$result['error'] ) {
				echo "<div class='statusOk'>Запись успешно изменена</div>";
			}
		}

		// добавление записи
		if( isset( $_POST['photogalleryPictureAdd'] ) ) {
			for( $i = 0; $i < 3; $i++ ) {
				if( !empty( $_FILES['photogalleryPicturePicture_'.$i]['name'] ) ) {
					require_once "../classes/image/image.class.php";
					$image = new IMAGE( 'photogalleryPicturePicture_'.$i );
					$picture = $image -> save( array( 'size' => 'square', 'width' => 90, 'height' => 90, 'quality' => 100, 'path' => '../uploadfiles/photogallery/' ) );
					if( !empty( $picture ) ) {
						$image -> save( array( 'width' => 500, 'height' => 500, 'name' => "b".$picture, 'quality' => 100, 'path' => '../uploadfiles/photogallery/' ) );
						$result = $db -> query( "INSERT INTO photogalleryPicture VALUES( NULL, '".$_REQUEST['photogalleryRazdel']."', '$picture', '".$_POST['photogalleryPictureName_'.$i]."' )" );
						if( !$result['error'] ) {
							echo "<div class='statusOk'>Запись ".($i+1)." успешно добавлена</div>";
						}
					}
				}
			}
		}

		// изменение записи
		if( isset( $_POST['photogalleryPictureEdit'] ) ) {

			$result = $db -> fetch( "SELECT picture FROM photogalleryPicture WHERE id = '".$_REQUEST['photogalleryPictureRazdel']."' " );
			foreach( $result as $data ) {
				if( file_exists( "../uploadfiles/photogallery/".$data['picture'] ) ) {
					unlink( "../uploadfiles/photogallery/".$data['picture'] );
				}
				if( file_exists( "../uploadfiles/photogallery/b".$data['picture'] ) ) {
					unlink( "../uploadfiles/photogallery/b".$data['picture'] );
				}
			}			

			if( !empty( $_FILES['photogalleryPicturePicture2']['name'] ) ) {
				require_once "../classes/image/image.class.php";
				$image = new IMAGE( 'photogalleryPicturePicture2' );
				$picture = $image -> save( array( 'size' => 'square', 'width' => 90, 'height' => 90, 'quality' => 100, 'path' => '../uploadfiles/photogallery/' ) );
				if( !empty( $picture ) ) {
					$image -> save( array( 'width' => 500, 'height' => 500, 'name' => "b".$picture, 'quality' => 100, 'path' => '../uploadfiles/photogallery/' ) );
					$result = $db -> query( "UPDATE photogalleryPicture SET picture = '$picture' WHERE id = '".$_REQUEST['photogalleryPictureRazdel']."' " );
				}
			}
			$result = $db -> query( "UPDATE photogalleryPicture SET name = '".$_POST['photogalleryPictureName2']."' WHERE id = '".$_REQUEST['photogalleryPictureRazdel']."' " );
			if( !$result['error'] ) {
				echo "<div class='statusOk'>Запись успешно изменена</div>";
			}
		}

		// удаление записи
		if( isset( $_GET['photogalleryPictureDelete'] ) ) {
			$result = $db -> fetch( "SELECT picture FROM photogalleryPicture WHERE id = '".$_GET['photogalleryPictureDelete']."' " );
			foreach( $result as $data ) {
				if( file_exists( "../uploadfiles/photogallery/".$data['picture'] ) ) {
					unlink( "../uploadfiles/photogallery/".$data['picture'] );
				}
				if( file_exists( "../uploadfiles/photogallery/b".$data['picture'] ) ) {
					unlink( "../uploadfiles/photogallery/b".$data['picture'] );
				}
			}
			$result = $db -> query( "DELETE FROM photogalleryPicture WHERE id = '".$_GET['photogalleryPictureDelete']."' " );
			if( !$result['error'] ) {
				echo "<div class='statusOk'>Запись успешно удалена</div>";
			}
		}

		echo "<div class='left'>Заголовок</div>
		<div><input type='text' name='photogalleryName' /></div>
		<div class='line'></div>
		<div class='left'>Дата</div>
		<div><input type='text' name='photogalleryDate' value='".date('Y-m-d')."' readonly='readonly' class='date' /></div>
		<div class='line'></div>
		<div class='left'>Описание</div>
		<div><textarea name='photogalleryDescription' class='mceEditor'></textarea></div>
		<div class='line'></div>
		<div class='left'>Превью</div>
		<div><input type='file' name='photogalleryPicture' /></div>
		<div class='line'></div>
		<div class='left'>&nbsp;</div>
		<div><input type='submit' name='photogalleryAdd' value='Добавить' /></div>
		<div class='line block'></div>

		<div class='left'>Запись</div>
		<div>
			<select name='photogalleryRazdel' onChange='document.form.submit();'>
				<option value='0'>Выберите запись для редактирования</option>";
				$result = $db -> fetch( "SELECT id, name, date FROM photogallery WHERE pages = '$id' ORDER BY date DESC, id DESC" );
				foreach( $result as $data ) {
					echo "<option value='".$data['id']."' "; if( $_REQUEST['photogalleryRazdel'] == $data['id'] ) { echo "selected='selected' "; } echo ">".date_format( date_create( $data['date'] ), "d.m.y" )." ".$data['name']."</option>";
				}
			echo "</select>
		</div>";

		if( !empty( $_REQUEST['photogalleryRazdel'] ) ) {
			$result = $db -> fetch( "SELECT id, name, date, description, picture FROM photogallery WHERE pages = '$id' AND id = '".$_REQUEST['photogalleryRazdel']."' " );
			foreach( $result as $data ) {
				echo "<div class='left'>Заголовок</div>
				<div><input type='text' name='photogalleryName2' value='".$data['name']."' /></div>
				<div class='line'></div>
				<div class='left'>Дата</div>
				<div><input type='text' name='photogalleryDate2' value='".$data['date']."' readonly='readonly' class='date' /></div>
				<div class='line'></div>
				<div class='left'>Описание</div>
				<div><textarea name='photogalleryDescription2' class='mceEditor'>".$data['description']."</textarea></div>
				<div class='line'></div>
				<div class='left'>Превью</div>
				<div>";
					echo "<input type='file' name='photogalleryPicture2' /> ";
					if( !empty( $data['picture'] ) ) {
						echo "<a href='' onClick='if( window.confirm( \"Вы действительно хотите удалить данную запись?\" ) ){ window.location.href=\"$url"; if( !empty( $page ) ){ echo "page/$page/"; } echo "photogalleryRazdel/".$_REQUEST['photogalleryRazdel']."/photogalleryPreviewDelete/1/\";} return false;'>Удалить превью</a>";
					}
				echo "</div>
				<div class='line'></div>
				<div class='left'>&nbsp;</div>
				<div>
					<input type='submit' name='photogalleryEdit' value='Изменить' />
					<input type='button' value='Удалить' onclick='if( window.confirm( \"Вы действительно хотите удалить данную запись?\" ) ){ window.location.href=\"$url"; if( !empty( $page ) ){ echo "page/$page/"; } echo "photogalleryDelete/".$data['id']."/\";}' />";

					if( !empty( $data['picture'] ) ) {
						echo "<img src='uploadfiles/photogallery/".$data['picture']."' align='right'>";
					}

				echo "</div>
				<div class='line block'></div>";

				for( $i = 0; $i < 3; $i++ ) {
					echo "<div class='left'>Фото ".( $i + 1 )."</div>
					<div><input type='file' name='photogalleryPicturePicture_$i' /></div>
					<div class='line'></div>
					<div class='left'>Описание ".( $i + 1 )."</div>
					<div><input type='text' name='photogalleryPictureName_$i' /></div>
					<div class='line'></div>";
					if( $i < 2 ) {
						echo "<br />";
					}
				}
				echo "<div class='left'>&nbsp;</div>
				<div><input type='submit' name='photogalleryPictureAdd' value='Добавить' /></div>
				<div class='line block'></div>

				<div class='left'>Запись</div>
				<div>
					<select name='photogalleryPictureRazdel' onChange='document.form.submit();'>
						<option value='0'>Выберите запись для редактирования</option>";
						$resultPicture = $db -> fetch( "SELECT id, name FROM photogalleryPicture WHERE photogallery = '".$_REQUEST['photogalleryRazdel']."' ORDER BY id DESC" );
						foreach( $resultPicture as $dataPicture ) {
							echo "<option value='".$dataPicture['id']."' "; if( $_REQUEST['photogalleryPictureRazdel'] == $dataPicture['id'] ) { echo "selected = 'selected' "; } echo ">".$dataPicture['id'].". ".$dataPicture['name']."</option>";
						}
					echo "</select>
				</div>
				<div class='line'></div>";

				$resultPicture = $db -> fetch( "SELECT id, name, picture FROM photogalleryPicture WHERE id = '".$_REQUEST['photogalleryPictureRazdel']."' AND photogallery = '".$_REQUEST['photogalleryRazdel']."' " );
				foreach( $resultPicture as $dataPicture ) {
					echo "<div class='left'>Фото</div>
					<div><input type='file' name='photogalleryPicturePicture2' /></div>
					<div class='line'></div>
					<div class='left'>Описание</div>
					<div><input type='text' name='photogalleryPictureName2' value='".$dataPicture['name']."' /></div>
					<div class='line'></div>
					<div class='left'>&nbsp;</div>
					<div>
						<input type='submit' name='photogalleryPictureEdit' value='Изменить' />
						<input type='button' value='Удалить' onclick='if( window.confirm( \"Вы действительно хотите удалить данную запись?\" ) ){ window.location.href=\"$url"; if( !empty( $page ) ){ echo "page/$page/"; } echo "photogalleryRazdel/".$_REQUEST['photogalleryRazdel']."/photogalleryPictureDelete/".$dataPicture['id']."/\";}' />";
						if( !empty( $dataPicture['picture'] ) ) {
							echo "<img src='uploadfiles/photogallery/".$dataPicture['picture']."' align='right'>";
						}
					echo "</div>
					<div class='line'></div>";
				}
			}
		}

		echo "<div class='line block'></div>";
	}


	// фотогалерея. пользовательская часть
	function photogalleryUser( $db = 0, $page = '', $navigate = 0, $limit = 10, $item = 0 ) {

		$url = urlGet( $urlPostfix = "" );

		if( !empty( $item ) ) {
			$photogalleryParams = "AND photogallery.id = '$item' ";
		} else {
			$photogalleryParams = "";
		}

		$query = "SELECT photogallery.id AS id, photogallery.name AS name, photogallery.picture AS picture, photogallery.date AS date, photogallery.description AS description FROM photogallery, pages WHERE pages.id = photogallery.pages AND pages.alias LIKE '$page' $photogalleryParams ORDER BY photogallery.date DESC, photogallery.id DESC LIMIT $navigate, $limit";
		$result = $db -> fetch( $query );
		foreach( $result as $data ) {
			if( empty( $data['picture'] ) || !file_exists( "uploadfiles/photogallery/".$data['picture'] ) ) {
				$data['picture'] = "../../images/noImage.jpg";
			}

			if( empty( $item ) ) {
				echo "<div class='preview'>";
					echo "<a href='$url"; if( !empty( $page ) ) { echo "page/$page/"; } echo "item/".$data['id']."' title='".$data['name']."'><img src='images/transparent.gif' alt='Превью' style='background-image: url(uploadfiles/photogallery/".$data['picture'].");' /></a>";
				echo "</div>";
			} else {
				if( $data['picture'] != "../../images/noImage.jpg" ) {
					echo "<img src='uploadfiles/photogallery/b".$data['picture']."' title='".$data['name']."' style='float: right; margin: 0 0 10px 10px;' />";
				}
			}

			echo "<a href='$url"; if( !empty( $page ) ) { echo "page/$page/"; } echo "item/".$data['id']."' title='".$data['name']."'>".date_format( date_create( $data['date'] ), "d.m.Y" )." - ".$data['name']."</a><br />";
			if( empty( $item ) ) {
				echo stringTruncate( stringSimple( $data['description'] ), 500, "..." )."<br />";
			} else {
				echo $data['description']."<br />";

				$resultPicture = $db -> fetch( "SELECT picture, name FROM photogalleryPicture WHERE photogallery = '$item' ORDER BY id DESC" );
				echo "<div id='gallery'>";
					foreach( $resultPicture as $dataPicture ) {
						if( empty( $data['picture'] ) ) {
							$data['picture'] = "../../images/noImage.jpg";
						}
						echo "<div class='preview'>";
							echo "<a href='uploadfiles/photogallery/b".$dataPicture['picture']."' title='".$dataPicture['name']."'><img src='images/transparent.gif' alt='Превью' style='background-image: url(uploadfiles/photogallery/".$dataPicture['picture'].");' /></a>";
						echo "</div>";
					}
					echo "<div class='line block'></div>";
				echo "</div>";

				echo "<div class='line'><a href='' onClick = 'window.history.back(); return false;'>Вернуться</a></div>";
			}
			echo "<div class='line block'></div>";
		}


		navigate( $db, $query, $navigate, $limit, $page, $extra = "" );

	}
?>