<?php
	SESSION_START();
	ERROR_REPORTING( E_ALL );

	require_once "cfg/config.php";
	require_once "classes/db/db.class.php";
	require_once "components/functions.php";
	$url = urlGet( $urlPostfix = "" );
	$page = isset( $_GET['page'] ) ? stringSimple( $_GET['page'] ) : "";
	$db = DB::instance( $config );
	$result = $db -> fetch( "select title, keywords, description, view from pages where alias = '".$page."' " );
	if( count( $result ) == 0 ) {
		$result = $db -> fetch( "select title, keywords, description, view from pages order by id limit 0, 1" );
	}
	foreach( $result as $data ) {
		$title = $data['title'];
		$keywords = $data['keywords'];
		$description = $data['description'];
		$view = $data['view'];
	}
	$navigate = isset( $_GET['navigate'] ) ? stringSimple( $_GET['navigate'] ) : 0;
	$item = isset( $_GET['item'] ) ? stringSimple( $_GET['item'] ) : 0;
	$id = idGet( $db, $page );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv='content-type' content='text/html; charset=windows-1251' />
		<title>Межрегиональная Некоммерческая Организация Некоммерческое Партнерство "Альянс Судебных Специалистов" | <?php echo $title; ?></title>
		<base href='<?php echo $url; ?>' />

		<link rel='stylesheet' type='text/css' href='css/style.css?v=4' />
		<!--[if  IE]><link rel='stylesheet' type='text/css' href='css/styleIe.css'><![endif]-->
		<link rel='stylesheet' type='text/css' href='includes/lightbox/css/jquery.lightbox-0.5.css' media='screen' />

		<link href='favicon.ico' rel='shortcut icon' type='image/x-icon' />

		<meta name='title' content='Межрегиональная Некоммерческая Организация Некоммерческое Партнерство "Альянс Судебных Специалистов" | <?php echo $title; ?>' />
		<meta name='keywords' content='<?php echo $keywords; ?>' />
		<meta name='description' content='<?php echo $description; ?>' />

		<script type="text/javascript" src="includes/jquery/jquery-1.7.js"></script>

		<link rel="stylesheet" href="includes/slides/css/global.css">
		<script src="includes/slides/js/slides.min.jquery.js"></script>
		<script src='includes/slides/js/slides.init.js'></script>
        <script src='includes/animations/animation.js?v=3'></script>

	</head>
	<body>

		<div id='shadow'></div>
		<div id='wrap'>
			<div id='header'>
				<div class='logo'><a href='' title='На главную'></a></div>
				<div class='ass'>Межрегиональная Некоммерческая <br />Организация Некоммерческое Партнерство<br />"Альянс Судебных Специалистов"</div>
				<div class='phone'></div>
				<div class='contacts'>
					<div>+7 (3462) <b>51-01-05, 37-42-42</b></div>
					<div><a href='mailto:welcome@center-acc.ru' title='Написать письмо'>welcome@center-acc.ru</a></div>
					<div>Ханты-Мансийский АО, г.Сургут,<br />ул.30 лет Победы д.39, вход № 2</div>
				</div>
				<div class='line'></div>
				<div id='slider'></div>
				<div id='container'>
					<div id='example'>
						<div id='slides'>
							<div class='slides_container'>
								<a href='' onClick='return false;' title='slide 01'><img src='images/slide01.jpg' alt='slide 01'></a>
								<a href='' onClick='return false;' title='slide 02'><img src='images/slide02.jpg' alt='slide 02'></a>
								<a href='' onClick='return false;' title='slide 03'><img src='images/slide03.jpg' alt='slide 03'></a>
							</div>
							<a href='#' class='prev' title='Назад'></a>
							<a href='#' class='next' title='Вперед'></a>
						</div>
						<img src='images/slides.png' alt='slides' id='frame'>
					</div>
				</div>
			</div>
			<div id='menuDiv'>
				<?php
					menuUser( $db, $razdel = 0, $activate = $id, $root = true, $prefix = '', $submenu = true );
				?>
			</div>
			<div id='content'>
				<div class='contentBlock'></div>
				<div id='contentEnter'>
					<?php
						require_once "components/users.php";
						$flagAccess = usersUser( $db, $page, $item );
					?>
				</div>
				<div id='contentMain'>
					<div class='lineCommercialTop'></div>
					<div class='contentMainText'>
						<?php
							switch( $view ) {
		
								case "photogallerySimple":
									require_once "components/photogallerySimple.php";
									photogallerySimpleUser( $db, $page, $navigate, $limit = 2 );
									break;
		
								case "news":
									require_once "components/news.php";
									newsUser( $db, $page, $navigate, $limit = 5, $item );
									break;
			
								case "feedback":
									require_once "components/feedback.php";
									feedbackUser( $db, $page );
									break;

								case "guestbook":
									require_once "components/guestbook.php";
									guestbookUser( $db, $page, $navigate, $limit = 2 );
									break;

								case "files":
									require_once "components/files.php";
									filesUser( $db, $page, $navigate, $limit = 5, $item, $flagAccess );
									break;
		
								case "find":
									require_once "components/find.php";
									findSearchUser( $page );
									require_once "components/find.php";
									findUser( $db, $page, $navigate, $limit = 2 );
									break;
		
								case "photogallery":
									require_once "components/photogallery.php";
									photogalleryUser( $db, $page, $navigate, $limit = 2, $item );
									break;
			
								case "users":
									require_once "components/users.php";
									usersUser( $db, $page, $item );
									break;
			
								default:
									require_once "components/pages.php";
									pagesUser( $db, $page );
					
							}
						?>
					</div>
				</div>
				<div class='line'><div class='lineLine'></div></div>
			</div>
			<div id='commercial'>
				<div><img src='images/lineCommercial.jpg' alt='' class='lineCommercial' /></div>
			</div>
			<div id='footer'>
				<div class='development'>
					<div class='copyright'>2010-<?php echo date('Y'); ?> © МНОНП "Альянс Судебных Специалистов"</div>
					<div class='aSite'><a href='http://asite-studio.ru' title='Разработка сайта «А-сайт»'><img src='images/aSite.gif' alt='' /></a></div>
					<div class='site'>Разработка сайта</div>
				</div>
			</div>
		</div>
		<div id='footerShadow'></div>

		<script type="text/javascript" src="includes/filestyle/jquery.filestyle.js"></script>

		<script type='text/javascript' src='includes/lightbox/js/jquery.lightbox-0.5.js'></script>
		<script type='text/javascript' src='includes/lightbox/js/jquery.lightbox-0.5.init.js'></script>



		<!-- Yandex.Metrika counter -->
		<script type="text/javascript">
		(function (d, w, c) {
		    (w[c] = w[c] || []).push(function() {
		        try {
		            w.yaCounter16460074 = new Ya.Metrika({id:16460074, enableAll: true});
		        } catch(e) { }
		    });
		    
		    var n = d.getElementsByTagName("script")[0],
		        s = d.createElement("script"),
		        f = function () { n.parentNode.insertBefore(s, n); };
		    s.type = "text/javascript";
		    s.async = true;
		    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";
		
		    if (w.opera == "[object Opera]") {
		        d.addEventListener("DOMContentLoaded", f);
		    } else { f(); }
		})(document, window, "yandex_metrika_callbacks");
		</script>
		<noscript><div><img src="//mc.yandex.ru/watch/16460074" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- /Yandex.Metrika counter -->




	</body>
</html>