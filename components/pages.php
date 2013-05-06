<?php

	// текстовая страница. административная часть
	function pagesAdmin( $db = 0, $page = '' ) {

		if( isset( $_POST['pagesEdit'] ) ) {
			$result = $db -> query( "UPDATE pages SET content = '".$_POST['pagesContent']."' WHERE alias LIKE '$page' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>Запись успешно изменена</div>";
			}
		}

		$result = $db -> fetch( "SELECT name, content FROM pages WHERE alias LIKE '$page' " );
		foreach( $result as $data ) {
			echo"<div class='left'>Контент</div>
			<div><textarea name='pagesContent' class='mceEditor'>".$data['content']."</textarea></div>
			<div class='line'></div>
			<div class='left'>&nbsp;</div>
			<div><input type='submit' name='pagesEdit' value='Изменить' /></div>
			<div class='line block'></div>";
		}

	}


	// текстовая страница. пользовательская часть
	function pagesUser( $db = 0, $page = '' ) {

		$result = $db -> fetch( "SELECT name, content FROM pages WHERE alias LIKE '$page' " );
		foreach( $result as $data ) {
			echo "<h1 class='heading'>".$data['name']."</h1><br />";
			echo $data['content'];

			if( $page == "contacts" ) {
				echo "<br />";
				echo "<div id='ymaps-map-id_134502815656475515579' style='width: 100%; height: 500px; box-shadow:3px 3px 3px gray;'></div>
				<script type='text/javascript'>function fid_134502815656475515579(ymaps) {var map = new ymaps.Map('ymaps-map-id_134502815656475515579', {center: [73.43850499999992, 61.256601610472714], zoom: 16, type: 'yandex#map'});map.controls.add('zoomControl').add('mapTools').add(new ymaps.control.TypeSelector(['yandex#map', 'yandex#satellite', 'yandex#hybrid', 'yandex#publicMap']));map.geoObjects.add(new ymaps.Placemark([73.438505, 61.255982], {balloonContent: 'Ассоциация судебных специалистов<br />тел.: +7 (3462) 50-31-04, 37-42-42<br />ХМАО, г. Сургут, ул. 30 лет Победы д. 39, вход № 2'}, {preset: 'twirl#blueDotIcon'}));};</script>
				<script type='text/javascript' src='http://api-maps.yandex.ru/2.0/?coordorder=longlat&load=package.full&wizard=constructor&lang=ru-RU&onload=fid_134502815656475515579'></script>";
			}
		}

	}


	function pagesAliasCheck( $db = 0, $pagesAlias = "" ) {

		$result = $db -> fetch( "SELECT id FROM pages WHERE alias LIKE '$pagesAlias' ORDER BY id LIMIT 0, 1" );
		if( count( $result ) > 0 ) {
			$pagesAlias .= "-".rand( 0, 9999 );
			$pagesAlias = pagesAliasCheck( $db, $pagesAlias );
		}
		return $pagesAlias;

	}
?>