<?php

	// фотогалерея простая. административная часть
	function photogallerySimpleAdmin( $db = 0, $page = '' ) {

		$url = urlGet( $urlPostfix = "" );
		$id = idGet( $db, $page );

		// если не сужествует переменной, то присвоить ей 0
		if( !isset( $_REQUEST['photogallerySimpleRazdel'] ) ) {
			$_REQUEST['photogallerySimpleRazdel'] = 0;
		}

		// добавление записи
		if( isset( $_POST["photogallerySimpleAdd"] ) ) {
			for( $i = 0; $i < 3; $i++ ) {
				if( !empty( $_FILES['photogallerySimplePicture_'.$i]['name'] ) ) {
					require_once "../classes/image/image.class.php";
					$image = new IMAGE( 'photogallerySimplePicture_'.$i );
					$picture = $image -> save( array( 'size' => 'square', 'width' => 90, 'height' => 90, 'quality' => 100, 'path' => '../uploadfiles/photogallerySimple/' ) );
					if( !empty( $picture ) ) {
						$image -> save( array( 'width' => 500, 'height' => 500, 'name' => "b".$picture, 'quality' => 100, 'path' => '../uploadfiles/photogallerySimple/' ) );
						$result = $db -> query( "insert into photogallerySimple values( NULL, '$id', '$picture', '".$_POST['photogallerySimpleName_'.$i]."' )" );
						if( !$result['error'] ) {
							echo "<div class='statusOk'>Запись ".($i+1)." успешно добавлена</div>";
						}
					}
				}
			}
		}

		// изменение записи
		if( isset( $_POST['photogallerySimpleEdit'] ) ) {

			$result = $db -> query( "update photogallerySimple set  name='".$_POST['photogallerySimpleName2']."' where id='".$_REQUEST['photogallerySimpleRazdel']."' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>Запись успешно изменена</div>";
			}

			if( !empty( $_FILES['photogallerySimplePicture2']['name'] ) ) {
				require_once "../classes/image/image.class.php";
				$image = new IMAGE( 'photogallerySimplePicture2' );
				$picture = $image -> save( array( 'size' => 'square', 'width' => 90, 'height' => 90, 'quality' => 100, 'path' => '../uploadfiles/photogallerySimple/' ) );
				if( !empty( $picture ) ) {
					$image -> save( array( 'width' => 500, 'height' => 500, 'name' => "b".$picture, 'quality' => 100, 'path' => '../uploadfiles/photogallerySimple/' ) );

					$result = $db -> fetch( "select picture from photogallerySimple where id='".$_REQUEST['photogallerySimpleRazdel']."' " );
					foreach( $result as $data ) {
						if( file_exists( "../uploadfiles/photogallerySimple/".$data['picture'] ) ) {
							unlink( "../uploadfiles/photogallerySimple/".$data['picture'] );
						}
		
						if( file_exists( "../uploadfiles/photogallerySimple/b".$data['picture'] ) ) {
							unlink( "../uploadfiles/photogallerySimple/b".$data['picture'] );
						}
					}

					$result = $db -> query( "update photogallerySimple set picture='$picture' where id='".$_REQUEST['photogallerySimpleRazdel']."' " );
				}
			}
		}

		// удаление записи
		if( isset( $_GET['photogallerySimpleDelete'] ) ) {

			$result = $db -> fetch( "select picture from photogallerySimple where id='".$_GET['photogallerySimpleDelete']."' " );
			foreach( $result as $data ) {
				if( file_exists( "../uploadfiles/photogallerySimple/".$data['picture'] ) ) {
					unlink( "../uploadfiles/photogallerySimple/".$data['picture'] );
				}

				if( file_exists( "../uploadfiles/photogallerySimple/b".$data['picture'] ) ) {
					unlink( "../uploadfiles/photogallerySimple/b".$data['picture'] );
				}
			}

			$result = $db -> query( "delete from photogallerySimple where id='".$_GET['photogallerySimpleDelete']."' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>Запись успешно удалена</div>";
			}
		}

		for( $i = 0; $i < 3; $i++ ) {
			echo "<div class='left'>Картинка ".( $i + 1 )."</div>
			<div><input type='file' name='photogallerySimplePicture_$i' /></div>
			<div class='line'></div>
			<div class='left'>Описание ".( $i + 1 )."</div>
			<div><input type='text' name='photogallerySimpleName_$i' /></div>
			<div class='line'></div>";
			if( $i < 2 ) {
				echo "<br />";
			}
		}

		echo "<div class='left'>&nbsp;</div>
		<div><input type='submit' name='photogallerySimpleAdd' value='Добавить' /></div>
		<div class='line block'></div>

		<div class='left'>Запись</div>
		<div>
			<select name='photogallerySimpleRazdel' onchange='document.form.submit();'>
				<option value='0'>Выберите запись для редактирования</option>";
				$result = $db -> fetch( "select id, name from photogallerySimple where pages = '$id' order by id desc " );
				$i = count( $result );
				foreach( $result as $data ) {
					echo "<option value='".$data['id']."' "; if( $_REQUEST['photogallerySimpleRazdel'] == $data['id'] ){ echo "selected='selected'"; } echo">$i. ".$data['name']."</option>";
					$i--;
				}
			echo "</select>
		</div>
		<div class='line'></div>";

		if( !empty( $_REQUEST['photogallerySimpleRazdel'] ) ) {
			$result = $db -> fetch( "select id, picture, name from photogallerySimple where id='".$_REQUEST['photogallerySimpleRazdel']."' " );
			foreach( $result as $data ) {
				echo "<div class='left'>Картинка</div>
				<div><input type='file' name='photogallerySimplePicture2' /></div>
				<div class='line'></div>
				<div class='left'>Название</div>
				<div><input type='text' name='photogallerySimpleName2' value='".$data['name']."' /></div>
				<div class='line'></div>
				<div class='left'>&nbsp;</div>
				<div>
					<input type='submit' name='photogallerySimpleEdit' value='Изменить' />
					<input type='button' value='Удалить' onclick='if( window.confirm( \"Вы действительно хотите удалить данную запись?\" ) ){ window.location.href=\"$url"; if( !empty( $page ) ){ echo "page/$page/"; } echo "photogallerySimpleDelete/".$data['id']."/\";}' />";

					if( !empty( $data['picture'] ) ) {
						echo "<img src='uploadfiles/photogallerySimple/".$data['picture']."' align='right'>";
					}

				echo "</div>";
			}
		}

		echo "<div class='line block'></div>";

	}


	// фотогалерея простая. пользовательская часть
	function photogallerySimpleUser( $db = 0, $page = '', $navigate = 0, $limit = 10 ) {

		$query = "SELECT photogallerySimple.picture AS picture, photogallerySimple.name AS name FROM photogallerySimple, pages WHERE pages.id = photogallerySimple.pages AND pages.alias LIKE '$page' order by photogallerySimple.id DESC LIMIT $navigate, $limit";
		$result = $db -> fetch( $query );
		echo "<div id='gallery'>";
			foreach( $result as $data ) {
				if( empty( $data['picture'] ) ) {
					$data['picture'] = "../../images/noImage.jpg";
				}
				echo "<div class='preview'>";
					echo "<a href='uploadfiles/photogallerySimple/b".$data['picture']."' title='".$data['name']."'><img src='images/transparent.gif' alt='Превью' style='background-image: url(uploadfiles/photogallerySimple/".$data['picture'].");' /></a>";
				echo "</div>";
			}
			echo "<div class='line block'></div>";
		echo "</div>";

		navigate( $db, $query, $navigate, $limit, $page, $extra = "" );

	}
?>