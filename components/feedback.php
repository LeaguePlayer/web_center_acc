<?php

	// обратная связь. административная часть
	function feedbackAdmin( $db = 0 ) {

		require_once "../components/pages.php";
		pagesAdmin( $db, $page = "extracts" );

		if( isset( $_POST['configEdit'] ) ) {
			$result = $db -> query( "UPDATE configEmail SET email = '".$_POST['configEmail']."' WHERE view LIKE 'feedback' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>Запись успешно изменена</div>";
			}
		}

		$result = $db -> fetch( "SELECT email FROM configEmail WHERE view LIKE 'feedback' " );
		foreach( $result as $data ) {
			echo"<div class='left'>Электронная почта</div>
			<div><input type='text' name='configEmail' value='".$data['email']."' /></div>
			<div class='line'></div>
			<div class='left'>&nbsp;</div>
			<div><input type='submit' name='configEdit' value='Изменить' /></div>
			<div class='line block'></div>";
		}

	}


	// обратная связь. пользовательская часть
	function feedbackUser( $db = 0, $page = '' ) {

		require_once "components/pages.php";
		pagesUser( $db, $page );
		echo "<br />";

		$usersFlag = false;
		if( isset( $_COOKIE['usersLogin'] ) && isset( $_COOKIE['usersPassword'] ) ) {
			$users = usersGet( $db, $_COOKIE['usersLogin'], $_COOKIE['usersPassword'] );
			if( $users['flag'] ) {
				$usersFlag = true;
			}
		}

		if( !isset( $_POST['feedbackName'] ) ) {
			if( $usersFlag ) {
				$_POST['feedbackName'] = $users['name'];
			} else {
				$_POST['feedbackName'] = 'Ваше Ф.И.О.';
			}
		} else {
			$_POST['feedbackName'] = stringSimple( $_POST['feedbackName'] );
		}

		if( !isset( $_POST['feedbackRequired'] ) ) {
			$_POST['feedbackRequired'] = 'Ф.И.О. запрашиваемого';
		} else {
			$_POST['feedbackRequired'] = stringSimple( $_POST['feedbackRequired'] );
		}

		if( !isset( $_POST['feedbackNumber'] ) ) {
			$_POST['feedbackNumber'] = 'Реестровый номер';
		} else {
			$_POST['feedbackNumber'] = stringSimple( $_POST['feedbackNumber'] );
		}

		if( !isset( $_POST['feedbackEmail'] ) ) {
			if( $usersFlag ) {
				$_POST['feedbackEmail'] = $users['mail'];
			} else {
				$_POST['feedbackEmail'] = 'Ваш адрес электронной почты';
			}
		} else {
			$_POST['feedbackEmail'] = stringSimple( $_POST['feedbackEmail'] );
		}

		if( !isset( $_POST['feedbackMessage'] ) ) {
			$_POST['feedbackMessage'] = 'Для каких целей запрос';
		} else {
			$_POST['feedbackMessage'] = stringSimple( $_POST['feedbackMessage'] );
		}

		if( isset( $_POST['feedbackAdd'] ) ) {
			$feedbackFlag = true;

			if( $_POST['feedbackName'] == 'Ваше Ф.И.О.' ) {
				$feedbackFlag = false;
				echo "<div class='statusCancel'>Не корректно заполнено поле 'Ваше Ф.И.О.'</div>";
			}

			if( $_POST['feedbackRequired'] == 'Ф.И.О. запрашиваемого' ) {
				$feedbackFlag = false;
				echo "<div class='statusCancel'>Не корректно заполнено поле 'Ф.И.О. запрашиваемого'</div>";
			}

			if( $_POST['feedbackNumber'] == 'Реестровый номер' ) {
				$feedbackFlag = false;
				echo "<div class='statusCancel'>Не корректно заполнено поле 'Реестровый номер'</div>";
			}

			if( !emailCheck( $_POST['feedbackEmail'] ) ) {
				$feedbackFlag = false;
				echo "<div class='statusCancel'>Не корректно заполнено поле 'Ваш адрес электронной почты'</div>";
			}

			if( $_POST['feedbackMessage'] == 'Для каких целей запрос' ) {
				$feedbackFlag = false;
				echo "<div class='statusCancel'>Не корректно заполнено поле 'Для каких целей запрос'</div>";
			}
            
            if( stringSimple( $_POST['feedbackEml'] ) != '') {
				$feedbackFlag = false;
			}

			if( stringSimple( $_POST['feedbackCode'] ) != stringSimple( $_SESSION['secpic'] ) ) {
				$feedbackFlag = false;
				echo "<div class='statusCancel'>Не корректно заполнено поле 'Проверочный код'</div>";
			}


			if( $feedbackFlag ) {

				$result = $db -> fetch( "SELECT email FROM configEmail WHERE view LIKE 'feedback' " );
				foreach( $result as $data ) {

					$feedbackTheme = "Сообщение с сайта ".urlGet();
					$feedbackBody = "<b>".$feedbackTheme."</b><br /><br />";
					$feedbackBody .= "<u>Ваше Ф.И.О.:</u> ".$_POST['feedbackName']."<br />";
					$feedbackBody .= "<u>Ф.И.О. запрашиваемого:</u> ".$_POST['feedbackRequired']."<br />";
					$feedbackBody .= "<u>Реестровый номер:</u> ".$_POST['feedbackNumber']."<br />";
					$feedbackBody .= "<u>Ваш адрес электронной почты:</u> ".$_POST['feedbackEmail']."<br />";
					$feedbackBody .= "<u>Для каких целей запрос:</u> ".$_POST['feedbackMessage']."<br />";


					if( mailSend( "Служба технической поддержки", $data['email'], $feedbackTheme, $feedbackBody, $_POST['feedbackName'], $_POST['feedbackEmail'] ) ) {
						echo "<div class='statusOk'>Сообщение успешно отправлено</div>";
					} else {
						echo "<div class='statusCancel'>Ошибка отправки сообщения</div>";
					}

					$_POST['feedbackRequired'] = 'Ф.И.О. запрашиваемого';
					$_POST['feedbackNumber'] = 'Реестровый номер';
					$_POST['feedbackMessage'] = 'Сообщение';
				}
			}
		}

		echo "<form name='feedbackForm' method='post' action='"; if( !empty( $page ) ) { echo "page/$page/"; } echo "'>
			<div><input type='text' name='feedbackName' value='".$_POST['feedbackName']."' onFocus='if( this.value == \"Ваше Ф.И.О.\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"Ваше Ф.И.О.\";' /></div>
			<div class='line'></div>
			<div><input type='text' name='feedbackRequired' value='".$_POST['feedbackRequired']."' onFocus='if( this.value == \"Ф.И.О. запрашиваемого\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"Ф.И.О. запрашиваемого\";' /></div>
			<div class='line'></div>
			<div><input type='text' name='feedbackNumber' value='".$_POST['feedbackNumber']."' onFocus='if( this.value == \"Реестровый номер\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"Реестровый номер\";' /></div>
			<div class='line'></div>
			<div><input type='text' name='feedbackEmail' value='".$_POST['feedbackEmail']."' onFocus='if( this.value == \"Ваш адрес электронной почты\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"Ваш адрес электронной почты\";' /></div>
			<div class='line'></div>
			<div><textarea name='feedbackMessage' onFocus='if( this.value == \"Для каких целей запрос\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"Для каких целей запрос\";'>".$_POST['feedbackMessage']."</textarea></div>
			<div class='line'></div>
            <input type='text' name='feedbackEml' class=fieldeml' value='".$_POST['feedbackEml']."'/>
			<div>
				<img src='includes/secpic/secpic.php' onClick='this.src=\"includes/secpic/secpic.php?\"+Math.random()' alt='' title='Нажмите для смены изображения проверочного кода' class='captchaImage' />
				<input type='text' name='feedbackCode' value='Проверочный код' onFocus='if( this.value == \"Проверочный код\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"Проверочный код\";' class='captchaCode' /><br />
				<input type='submit' name='feedbackAdd' value='Отправить' />
			</div>
			<div class='line block'></div>
		</form>";

	}

?>