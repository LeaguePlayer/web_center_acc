<?php

	// новости. административная часть
	function newsAdmin( $db = 0, $page = '' ) {

		$url = urlGet( $urlPostfix = "" );
		$id = idGet( $db, $page );

		// если не сужествует переменной, то присвоить ей 0
		if( !isset( $_REQUEST['newsRazdel'] ) ) {
			$_REQUEST['newsRazdel'] = 0;
		}

		// добавление записи
		if( isset( $_POST["newsAdd"] ) ) {

			$picture = "";
			if( !empty( $_FILES['newsPicture']['name'] ) ) {
				require_once "../classes/image/image.class.php";
				$image = new IMAGE( 'newsPicture' );
				$picture = $image -> save( array( 'size' => 'fixed', 'width' => 122, 'height' => 83, 'quality' => 100, 'path' => '../uploadfiles/news/' ) );
				if( !empty( $picture ) ) {
					$image -> save( array( 'width' => 500, 'height' => 500, 'name' => "b".$picture, 'quality' => 100, 'path' => '../uploadfiles/news/' ) );
					echo "<div class='statusOk'>Картинка успешно добавлена</div>";					
				}
			}
			$result = $db -> query( "INSERT INTO news VALUES( NULL, '".$_POST['newsName']."', '".$_POST['newsDate']."', '".$_POST['newsContent']."', '$picture', '$id' )" );
			if( !$result['error'] ) {
				echo "<div class='statusOk'>Запись успешно добавлена</div>";
			}
		}

		// изменение записи
		if( isset( $_POST['newsEdit'] ) ) {

			$result = $db -> query( "UPDATE news SET  name = '".$_POST['newsName2']."', date = '".$_POST['newsDate2']."', content = '".$_POST['newsContent2']."' WHERE id='".$_REQUEST['newsRazdel']."' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>Запись успешно изменена</div>";
			}

			if( !empty( $_FILES['newsPicture2']['name'] ) ) {
				require_once "../classes/image/image.class.php";
				$image = new IMAGE( 'newsPicture2' );
				$picture = $image -> save( array( 'size' => 'fixed', 'width' => 122, 'height' => 83, 'quality' => 100, 'path' => '../uploadfiles/news/' ) );
				if( !empty( $picture ) ) {
					$image -> save( array( 'width' => 500, 'height' => 500, 'name' => "b".$picture, 'quality' => 100, 'path' => '../uploadfiles/news/' ) );

					$result = $db -> fetch( "SELECT picture FROM news WHERE id='".$_REQUEST['newsRazdel']."' " );
					foreach( $result as $data ) {
						if( file_exists( "../uploadfiles/news/".$data['picture'] ) ) {
							unlink( "../uploadfiles/news/".$data['picture'] );
						}
		
						if( file_exists( "../uploadfiles/news/b".$data['picture'] ) ) {
							unlink( "../uploadfiles/news/b".$data['picture'] );
						}
					}

					$result = $db -> query( "UPDATE news SET picture='$picture' WHERE id='".$_REQUEST['newsRazdel']."' " );
				}
			}
		}

		// удаление записи
		if( isset( $_GET['newsDelete'] ) ) {

			$result = $db -> fetch( "SELECT picture FROM news WHERE id='".$_GET['newsDelete']."' " );
			foreach( $result as $data ) {
				if( file_exists( "../uploadfiles/news/".$data['picture'] ) ) {
					unlink( "../uploadfiles/news/".$data['picture'] );
				}

				if( file_exists( "../uploadfiles/news/b".$data['picture'] ) ) {
					unlink( "../uploadfiles/news/b".$data['picture'] );
				}
			}

			$result = $db -> query( "DELETE FROM news WHERE id='".$_GET['newsDelete']."' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>Запись успешно удалена</div>";
			}
		}

		// удаление превью записи
		if( isset( $_GET['newsPreviewDelete'] ) ) {

			$result = $db -> fetch( "SELECT picture FROM news WHERE id='".$_REQUEST['newsRazdel']."' " );
			foreach( $result as $data ) {
				if( file_exists( "../uploadfiles/news/".$data['picture'] ) ) {
					unlink( "../uploadfiles/news/".$data['picture'] );
				}

				if( file_exists( "../uploadfiles/news/b".$data['picture'] ) ) {
					unlink( "../uploadfiles/news/b".$data['picture'] );
				}
			}

			$result = $db -> query( "UPDATE news SET picture = '' WHERE id='".$_REQUEST['newsRazdel']."' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>Запись успешно удалена</div>";
			}
		}

		echo "<div class='left'>Заголовок</div>
		<div><input type='text' name='newsName' /></div>
		<div class='line'></div>
		<div class='left'>Содержание</div>
		<div><textarea name='newsContent' class='mceEditor'></textarea></div>
		<div class='line'></div>
		<div class='left'>Дата</div>
		<div><input type='text' name='newsDate' value='".date( "Y-m-d" )."' readonly='readonly' class='date' /></div>
		<div class='line'></div>
		<div class='left'>Превью</div>
		<div><input type='file' name='newsPicture' /></div>
		<div class='line'></div>
		<div class='left'>&nbsp;</div>
		<div><input type='submit' name='newsAdd' value='Добавить' /></div>
		<div class='line block'></div>

		<div class='left'>Запись</div>
		<div>
			<select name='newsRazdel' onchange='document.form.submit();'>
				<option value='0'>Выберите запись для редактирования</option>";
				$result = $db -> fetch( "SELECT id, name FROM news WHERE pages = '$id' ORDER BY date DESC, id DESC " );
				$i = count( $result );
				foreach( $result as $data ) {
					echo "<option value='".$data['id']."' "; if( $_REQUEST['newsRazdel'] == $data['id'] ){ echo "selected='selected'"; } echo">$i. ".$data['name']."</option>";
					$i--;
				}
			echo "</select>
		</div>
		<div class='line'></div>";

		if( !empty( $_REQUEST['newsRazdel'] ) ) {
			$result = $db -> fetch( "SELECT id, name, date, content, picture FROM news WHERE id='".$_REQUEST['newsRazdel']."' AND pages = '$id' " );
			foreach( $result as $data ) {
				echo "<div class='left'>Заголовок</div>
				<div><input type='text' name='newsName2' value='".$data['name']."' /></div>
				<div class='line'></div>
				<div class='left'>Содержание</div>
				<div><textarea name='newsContent2' class='mceEditor'>".$data['content']."</textarea></div>
				<div class='line'></div>
				<div class='left'>Дата</div>
				<div><input type='text' name='newsDate2' value='".$data['date']."' readonly='readonly' class='date' /></div>
				<div class='line'></div>
				<div class='left'>Превью</div>
				<div>";
					echo "<input type='file' name='newsPicture2' /> ";
					if( !empty( $data['picture'] ) ) {
						echo "<a href='' onClick='if( window.confirm( \"Вы действительно хотите удалить данную запись?\" ) ){ window.location.href=\"$url"; if( !empty( $page ) ){ echo "page/$page/"; } echo "newsRazdel/".$_REQUEST['newsRazdel']."/newsPreviewDelete/1/\";} return false;'>Удалить превью</a>";
					}
				echo "</div>
				<div class='line'></div>
				<div class='left'>&nbsp;</div>
				<div>
					<input type='submit' name='newsEdit' value='Изменить' />
					<input type='button' value='Удалить' onclick='if( window.confirm( \"Вы действительно хотите удалить данную запись?\" ) ){ window.location.href=\"$url"; if( !empty( $page ) ){ echo "page/$page/"; } echo "newsDelete/".$data['id']."/\";}' />";

					if( !empty( $data['picture'] ) ) {
						echo "<img src='uploadfiles/news/".$data['picture']."' align='right'>";
					}

				echo "</div>";
			}
		}

		echo "<div class='line block'></div>";

	}


	// новости. пользовательская часть
	function newsUser( $db = 0, $page = '', $navigate = 0, $limit = 10, $item = 0 ) {

		$url = urlGet( $urlPostfix = "" );

		echo "<h1 class='heading'>Новости</h1><br />";

		if( !empty( $item ) ) {
			$newsParams = "AND news.id = '$item' ";
		} else {
			$newsParams = "";
		}

		$query = "SELECT news.id AS id, news.name AS name, news.date AS date, news.content AS content, news.picture AS picture FROM news, pages WHERE pages.id = news.pages AND pages.alias LIKE '$page' $newsParams ORDER BY news.date DESC, news.id DESC LIMIT $navigate, $limit";
		$result = $db -> fetch( $query );
		foreach( $result as $data ) {
			if( empty( $data['picture'] ) || !file_exists( "uploadfiles/news/b".$data['picture'] ) ) {
				$data['picture'] = "../../images/noImage.jpg";
			}

			if( empty( $item ) ) {
				echo "<div class='preview'>";
					echo "<a href='$url"; if( !empty( $page ) ) { echo "page/$page/"; } echo "item/".$data['id']."/' title='".$data['name']."'><img src='images/transparent.gif' alt='Превью' style='background-image: url(uploadfiles/news/".$data['picture'].");' /></a>";
				echo "</div>";
			} else {
				if( $data['picture'] != "../../images/noImage.jpg" ) {
					echo "<div class='preview lightbox'>";
						echo "<a href='uploadfiles/news/b".$data['picture']."' title='".$data['name']."'><img src='images/transparent.gif' alt='Превью' style='background-image: url( uploadfiles/news/".$data['picture']." );' /></a>";
					echo "</div>";
				}
			}

			echo "<a href='$url"; if( !empty( $page ) ) { echo "page/$page/"; } echo "item/".$data['id']."/' class='name' title='".$data['name']."'>".$data['name']."</a> <span class='date'>".date_format( date_create( $data['date'] ), "d.m.Y" )."</span><br />";
			if( empty( $item ) ) {
				echo stringTruncate( stringSimple( $data['content'] ), 200, "..." )."<br />";
				echo "<div class='href'><a href='$url"; if( !empty( $page ) ) { echo "page/$page/"; } echo "item/".$data['id']."/' title='Читать далее'>читать далее</a></div>";
			} else {
				echo $data['content']."<br />";
				echo "<div class='line'></div>
				<div class='href'><a href='' onClick = 'window.history.back(); return false;' title='Вернуться назад'>вернуться</a></div>";
			}
			echo "<div class='line block'></div>";
		}

		if( count( $result ) == 0 ) {
			echo "<p>В данном разделе пока еще нет записей...</p>";
		}


		navigate( $db, $query, $navigate, $limit, $page, $extra = "" );

	}
?>