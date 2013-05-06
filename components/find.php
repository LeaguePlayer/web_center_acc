<?php

	// поиск. пользовательская часть
	function findUser( $db = 0, $page = '', $navigate, $limit ) {

		$_GET['findSearch'] = isset( $_GET['findSearch'] ) ? stringSimple( $_GET['findSearch'] ) : "";
		if( strlen( $_GET['findSearch'] ) > 3 ) {

			$i = 1;
			$query = "
				( 
					SELECT 
						'' AS item,
						pages.alias AS alias,
						CONCAT( 'Раздел «', pages.name, '»' ) AS name, 
						CONCAT( pages.name, '. ', pages.content ) AS findText 
					FROM 
						pages 
					WHERE 
						view LIKE 'pages' 
						AND CONCAT( pages.name, pages.content ) LIKE '%".$_GET['findSearch']."%' 
					ORDER BY 
						pages.position, 
						pages.id
				) 
				UNION
				(
					SELECT 
						news.id AS item,
						pages.alias AS alias,
						CONCAT( 'Раздел «', pages.name, ': ', news.name, '»' ) AS name, 
						CONCAT( DATE_FORMAT( date, '%d.%m.%Y' ), '. ', news.name, '. ', news.content ) AS findText 
					FROM 
						news,
						pages 
					WHERE 
						news.pages = pages.id 
						AND CONCAT( DATE_FORMAT( date, '%d.%m.%Y' ),news.name, news.content ) LIKE '%".$_GET['findSearch']."%' 
					ORDER BY 
						news.date, 
						news.id
				)
				UNION
				(
					SELECT 
						files.id AS item,
						pages.alias AS alias,
						CONCAT( 'Раздел «', pages.name, ': ', files.name, '»' ) AS name, 
						CONCAT( DATE_FORMAT( date, '%d.%m.%Y' ), '. ', files.name, '. ', files.description ) AS findText 
					FROM 
						files,
						pages 
					WHERE 
						files.pages = pages.id 
						AND CONCAT( DATE_FORMAT( date, '%d.%m.%Y' ),files.name, files.description ) LIKE '%".$_GET['findSearch']."%' 
					ORDER BY 
						files.date, 
						files.id
				)
				LIMIT $navigate, $limit
			";
			$result = $db -> fetch( $query );
			echo "Результаты поиска по запросу «".$_GET['findSearch']."» - ".count( $result )."<div class='line block'></div>";
			foreach( $result as $data ) {
				echo "$i. <a href='"; if( !empty( $data['alias'] ) ) { echo "page/".$data['alias']."/"; } if( !empty( $data['item'] ) ) { echo "item/".$data['item']."/"; } echo "' title='".$data['name']."'>".$data['name']."</a> - ".stringSearch( $_GET['findSearch'], stringSimple( $data['findText'] ) )."<div class='line block'></div>";
				$i++;
			}

			navigate( $db, $query, $navigate, $limit, $page, $extra = "findSearch/".urlencode( $_GET['findSearch'] )."/" );

		} else {
			echo "<div class='statusCancel'>Фрагмент искомого текста должен быть больше 3 символов</div>";
		}

	}

	function findSearchUser( $page = '' ) {
		echo "<script type='text/javascript' src='includes/urlencode/urlencode.js'></script>";
		$url = urlGet();
		$_GET['findSearch'] = isset( $_GET['findSearch'] ) ? stringSimple( $_GET['findSearch'] ) : "";
		echo "<div>
			<input type='text' maxlength='30' name='findSearch' value='".$_GET['findSearch']."' onKeyPress='if ( event.keyCode == 13 ) { var url = \"\"; if( $page != \"\" ) { url += \"page/$page/\"; } if( this.value != \"\" ) { url += \"findSearch/\" + urlencode( this.value ) + \"/\"; } window.location.href = \"$url\" + url; }' />
		</div>
		<div class='line block'></div>";
	}

?>