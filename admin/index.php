<?php
	ERROR_REPORTING( E_ALL );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>

		<?php
			require_once "../cfg/config.php";
			require_once "../classes/db/db.class.php";
			require_once "../components/functions.php";
			$url = urlGet( $urlPostfix = "../" );
			$page = isset( $_GET['page'] ) ? stringSimple( $_GET['page'] ) : "";
			$db = DB::instance( $config );
			$result = $db -> fetch( "select title, keywords, description, view from pages where alias = '".$page."' order by id limit 0, 1" );
			if( count( $result ) == 0 ) {
				$result = $db -> fetch( "select title, keywords, description, view from pages order by id limit 0, 1" );
			}
			foreach( $result as $data ) {
				$title = $data['title'];
				$keywords = $data['keywords'];
				$description = $data['description'];
				$view = $data['view'];
			}
			$id = idGet( $db, $page );
		?>

		<meta http-equiv='content-type' content='text/html; charset=windows-1251' />
		<title><?php echo $title; ?></title>
		<base href='<?php echo $url; ?>' />

		<link rel='stylesheet' type='text/css' href='css/style.css' />
		<!--[if  IE]><link rel='stylesheet' type='text/css' href='css/styleIe.css'><![endif]-->

		<meta name='title' content='<?php echo $title; ?>' />
		<meta name='keywords' content='<?php echo $keywords; ?>' />
		<meta name='description' content='<?php echo $description; ?>' />

	</head>
	<body>



		<div id='shadow'></div>
		<div id='wrap' style='z-index: 0;'>
			<div id='header' class='admin'>
				<div class='logo'><a href='admin/' title='На главную'></a></div>
				<div class='ass'>Межрегиональная Некоммерческая<br />Организация Некоммерческое Партнерство<br />"Альянс Судебных Специалистов"</div>
				<div class='phone'></div>
				<div class='contacts'>
					<div>+7 (3462) <b>50-31-04, 37-42-42</b></div>
					<div><a href='mailto:welcome@center-acc.ru' title='Написать письмо'>welcome@center-acc.ru</a></div>
					<div>Ханты-Мансийский АО, г.Сургут,<br />ул.30 лет Победы д.39, вход № 2</div>
				</div>
				<div class='line'></div>
				&nbsp; <a href='admin/page/enter/' title='Примечания'>Примечания</a>
				&nbsp; <a href='admin/page/documents/' title='Документы'>Документы</a>
			</div>
			<div id='menuDiv'>
				<?php
					menuUser( $db, $razdel = 0, $activate = $id, $root = true, $prefix = 'admin/', $submenu = true );
				?>
			</div>
			<div id='content'>
				<div class='admin'>
					<form name='form' action='<?php echo $url; echo "admin/"; if( !empty( $page ) ) { echo "page/$page/"; } ?>' method='post' enctype='multipart/form-data'>
						<?php
							switch( $view ) {
	
								case "photogallerySimple":
									require_once "../components/photogallerySimple.php";
									photogallerySimpleAdmin( $db, $page );
									require_once "../components/title.php";
									titleAdmin( $db, $page );
									break;
	
								case "news":
									require_once "../components/news.php";
									newsAdmin( $db, $page );
									require_once "../components/title.php";
									titleAdmin( $db, $page );
									break;
	
								case "feedback":
									require_once "../components/feedback.php";
									feedbackAdmin( $db );
									require_once "../components/title.php";
									titleAdmin( $db, $page );
									break;
	
								case "guestbook":
									require_once "../components/guestbook.php";
									guestbookAdmin( $db, $page );
									require_once "../components/title.php";
									titleAdmin( $db, $page );
									break;
		
								case "files":
									require_once "../components/files.php";
									filesAdmin( $db, $page );
									require_once "../components/title.php";
									titleAdmin( $db, $page );
									break;
	
								case "find":
									require_once "../components/title.php";
									titleAdmin( $db, $page );
									break;
	
								case "photogallery":
									require_once "../components/photogallery.php";
									photogalleryAdmin( $db, $page );
									require_once "../components/title.php";
									titleAdmin( $db, $page );
									break;
	
								case "users":
									require_once "../components/users.php";
									usersAdmin( $db, $page );
									require_once "../components/title.php";
									titleAdmin( $db, $page );
									break;
	
								default:
									require_once "../components/pages.php";
									pagesAdmin( $db, $page );
									require_once "../components/title.php";
									titleAdmin( $db, $page );
					
							}
						?>
					</form>
				</div>
			</div>
			<div id='commercial' class='admin'><div class='lineCommercial'></div></div>
			<div id='footer'>
				<div class='development'>
					<div class='copyright'>2010-<?php echo date('Y'); ?> © МНОНП "Ассоциация Судебных Специалистов"</div>
					<div class='aSite'><a href='http://asite-studio.ru' title='Разработка сайта «А-сайт»'><img src='images/aSite.gif' alt='' /></a></div>
					<div class='site'>Разработка сайта</div>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="includes/jquery/jquery-1.7.js"></script>
		<script type="text/javascript" src="includes/filestyle/jquery.filestyle.js"></script>
		<script type='text/javascript' src='includes/calendar/calendar_ru.js'></script>

		<script type="text/javascript" src="includes/tinymce/tiny_mce.js"></script>
		<script type="text/javascript" src="includes/tinymce/tiny_mce_init.js"></script>
		<script src="includes/tinymce/plugins/tinybrowser/tb_tinymce.js.php" type="text/javascript"></script>



	</body>
</html>