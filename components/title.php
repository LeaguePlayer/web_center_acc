<?php

	// title, keywords, description. административная часть
	function titleAdmin( $db = 0, $page = '' ) {

		if( isset( $_POST['titleEdit'] ) ) {
			$result = $db -> query( "UPDATE pages SET title = '".$_POST['titleTitle']."', keywords = '".$_POST['titleKeywords']."', description = '".$_POST['titleDescription']."' WHERE alias LIKE '$page' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>Запись успешно изменена</div>";
			}
		}

		$result = $db -> fetch( "SELECT title, keywords, description FROM pages WHERE alias LIKE '$page' " );
		foreach( $result as $data ) {
			echo"<div class='left'>Title</div>
			<div><input type='text' name='titleTitle' value='".$data['title']."' class='title' /></div>
			<div class='line'></div>
			<div class='left'>Keywords</div>
			<div><input type='text' name='titleKeywords' value='".$data['keywords']."' class='title' /></div>
			<div class='line'></div>
			<div class='left'>Description</div>
			<div><input type='text' name='titleDescription' value='".$data['description']."' class='title' /></div>
			<div class='line'></div>
			<div class='left'>&nbsp;</div>
			<div><input type='submit' name='titleEdit' value='Изменить' /></div>
			<div class='line block'></div>";
		}

	}
?>