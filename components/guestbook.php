<?php

	// гостевая книга. административная часть
	function guestbookAdmin( $db = 0, $page = '' ) {

		$url = urlGet( $urlPostfix = "" );

		// если не сужествует переменной, то присвоить ей 0
		if( !isset( $_REQUEST['guestbookRazdel'] ) ) {
			$_REQUEST['guestbookRazdel'] = 0;
		}

		// добавление записи
		if( isset( $_POST["guestbookAdd"] ) ) {

			if( !isset( $_POST['guestbookChecked'] ) ) {
				$_POST['guestbookChecked'] = 0;
			}

			$result = $db -> query( "INSERT INTO guestbook VALUES( NULL, '".$_POST['guestbookName']."', '".$_POST['guestbookDate']."', '".$_POST['guestbookMessage']."', '".$_POST['guestbookEmail']."', '".$_POST['guestbookChecked']."' )" );
			if( !$result['error'] ) {
				echo "<div class='statusOk'>Запись успешно добавлена</div>";
			}
		}

		// изменение записи
		if( isset( $_POST['guestbookEdit'] ) ) {

			if( !isset( $_POST['guestbookChecked2'] ) ) {
				$_POST['guestbookChecked2'] = 0;
			}

			$result = $db -> query( "UPDATE guestbook SET  name = '".$_POST['guestbookName2']."', email = '".$_POST['guestbookEmail2']."', message = '".$_POST['guestbookMessage2']."', checked = '".$_POST['guestbookChecked2']."' WHERE id='".$_REQUEST['guestbookRazdel']."' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>Запись успешно изменена</div>";
			}
		}

		// удаление записи
		if( isset( $_GET['guestbookDelete'] ) ) {

			$result = $db -> query( "DELETE FROM guestbook WHERE id='".$_GET['guestbookDelete']."' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>Запись успешно удалена</div>";
			}
		}

		if( isset( $_POST['configEdit'] ) ) {
			$result = $db -> query( "UPDATE configEmail SET email = '".$_POST['configEmail']."' WHERE view LIKE 'guestbook' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>Запись успешно изменена</div>";
			}
		}

		$result = $db -> fetch( "SELECT email FROM configEmail WHERE view LIKE 'guestbook' " );
		foreach( $result as $data ) {
			echo"<div class='left'>Электронная почта</div>
			<div><input type='text' name='configEmail' value='".$data['email']."' /></div>
			<div class='line'></div>
			<div class='left'>&nbsp;</div>
			<div><input type='submit' name='configEdit' value='Изменить' /></div>
			<div class='line block'></div>";
		}

		echo "<div class='left'>Имя</div>
		<div><input type='text' name='guestbookName' /></div>
		<div class='line'></div>
		<div class='left'>Электронная почта</div>
		<div><input type='text' name='guestbookEmail' /></div>
		<div class='line'></div>
		<div class='left'>Сообщение</div>
		<div><textarea name='guestbookMessage'></textarea></div>
		<div class='line'></div>
		<div class='left'>Дата, время</div>
		<div><input type='text' name='guestbookDate' value='".date( "Y-m-d H:i:s" )."' readonly='readonly' /></div>
		<div class='line'></div>
		<div class='left'>Проверено</div>
		<div><fieldset><input type='checkbox' name='guestbookChecked' value='1' checked='checked' /></fieldset></div>
		<div class='line'></div>
		<div class='left'>&nbsp;</div>
		<div><input type='submit' name='guestbookAdd' value='Добавить' /></div>
		<div class='line block'></div>

		<div class='left'>Запись</div>
		<div>
			<select name='guestbookRazdel' onchange='document.form.submit();'>
				<option value='0'>Выберите запись для редактирования</option>";
				$result = $db -> fetch( "SELECT id, name, date FROM guestbook ORDER BY date DESC, id DESC " );
				foreach( $result as $data ) {
					echo "<option value='".$data['id']."' "; if( $_REQUEST['guestbookRazdel'] == $data['id'] ){ echo "selected='selected'"; } echo">".date_format( date_create( $data['date'] ), 'd.m.Y' )." ".$data['name']."</option>";
				}
			echo "</select>
		</div>
		<div class='line'></div>";

		if( !empty( $_REQUEST['guestbookRazdel'] ) ) {
			$result = $db -> fetch( "SELECT id, name, email, date, message, checked FROM guestbook WHERE id='".$_REQUEST['guestbookRazdel']."' " );
			foreach( $result as $data ) {
				echo "<div class='left'>Имя</div>
				<div><input type='text' name='guestbookName2' value='".$data['name']."' /></div>
				<div class='line'></div>
				<div class='left'>Электронная почта</div>
				<div><input type='text' name='guestbookEmail2' value='".$data['email']."' /></div>
				<div class='line'></div>
				<div class='left'>Сообщение</div>
				<div><textarea name='guestbookMessage2'>".$data['message']."</textarea></div>
				<div class='line'></div>
				<div class='left'>Дата</div>
				<div><input type='text' name='guestbookDate2' value='".$data['date']."' readonly='readonly' /></div>
				<div class='line'></div>
				<div class='left'>Проверено</div>
				<div><fieldset><input type='checkbox' name='guestbookChecked2' value='1' "; if( $data['checked'] ) { echo "checked='checked'"; } echo " /></fieldset></div>
				<div class='line'></div>
				<div class='left'>&nbsp;</div>
				<div>
					<input type='submit' name='guestbookEdit' value='Изменить' />
					<input type='button' value='Удалить' onclick='if( window.confirm( \"Вы действительно хотите удалить данную запись?\" ) ){ window.location.href=\"$url"; if( !empty( $page ) ){ echo "page/$page/"; } echo "guestbookDelete/".$data['id']."/\";}' />";

					if( !empty( $data['picture'] ) ) {
						echo "<img src='uploadfiles/guestbook/".$data['picture']."' align='right'>";
					}

				echo "</div>";
			}
		}

		echo "<div class='line block'></div>";

	}


	// гостевая книга. пользовательская часть
	function guestbookUser( $db = 0, $page = '', $navigate = 0, $limit = 10 ) {

		$usersFlag = false;
		if( isset( $_COOKIE['usersLogin'] ) && isset( $_COOKIE['usersPassword'] ) ) {
			$users = usersGet( $db, $_COOKIE['usersLogin'], $_COOKIE['usersPassword'] );
			if( $users['flag'] ) {
				$usersFlag = true;
			}
		}

		if( !isset( $_POST['guestbookName'] ) ) {
			if( $usersFlag ) {
				$_POST['guestbookName'] = $users['name'];
			} else {
				$_POST['guestbookName'] = 'Имя';
			}
		} else {
			$_POST['guestbookName'] = stringSimple( $_POST['guestbookName'] );
		}

		if( !isset( $_POST['guestbookEmail'] ) ) {
			if( $usersFlag ) {
				$_POST['guestbookEmail'] = $users['mail'];
			} else {
				$_POST['guestbookEmail'] = 'Электронная почта';
			}
		} else {
			$_POST['guestbookEmail'] = stringSimple( $_POST['guestbookEmail'] );
		}

		if( !isset( $_POST['guestbookMessage'] ) ) {
			$_POST['guestbookMessage'] = 'Сообщение';
		} else {
			$_POST['guestbookMessage'] = stringSimple( $_POST['guestbookMessage'] );
		}

		if( isset( $_POST['guestbookAdd'] ) ) {
			$guestbookFlag = true;

			if( $_POST['guestbookName'] == 'Имя' ) {
				$guestbookFlag = false;
				echo "<div class='statusCancel'>Не корректно заполнено поле 'Имя'</div>";
			}

			if( !emailCheck( $_POST['guestbookEmail'] ) ) {
				$guestbookFlag = false;
				echo "<div class='statusCancel'>Не корректно заполнено поле 'Электронная почта'</div>";
			}

			if( $_POST['guestbookMessage'] == 'Сообщение' ) {
				$guestbookFlag = false;
				echo "<div class='statusCancel'>Не корректно заполнено поле 'Сообщение'</div>";
			}

			if( stringSimple( $_POST['guestbookCode'] ) != stringSimple( $_SESSION['secpic'] ) ) {
				$guestbookFlag = false;
				echo "<div class='statusCancel'>Не корректно заполнено поле 'Проверочный код'</div>";
			}


			if( $guestbookFlag ) {

				$result = $db -> query( "INSERT INTO guestbook VALUES( NULL, '".$_POST['guestbookName']."', '".date( "Y-m-d H:i:s" )."', '".$_POST['guestbookMessage']."', '".$_POST['guestbookEmail']."', FALSE )" );
				if( empty( $result['error'] ) ) {
					echo "<div class='statusOk'>Запись успешно добавлена, она будет отображена после проверки администратором.</div>";
					$_POST['guestbookMessage'] = 'Сообщение';

					$result = $db -> fetch( "SELECT email FROM configEmail WHERE view LIKE 'guestbook' " );
					foreach( $result as $data ) {

						$guestbookTheme = "Новое сообщение в гостевой книге на сайте ".urlGet();
						$guestbookBody = "<b>Добавлено новое сообщение в гостевую книгу.</b><br /><br />";
	
						if( mailSend( "Служба технической поддержки", $data['email'], $guestbookTheme, $guestbookBody, "Служба технической поддержки", $data['email'] ) ) {
							echo "<div class='statusOk'>Уведомление о сообщении успешно отправлено администратору</div>";
						} else {
							echo "<div class='statusCancel'>Ошибка отправки уведомления администратору о сообщении</div>";
						}
					}

				}

			}
		}

		echo "<form name='guestbookForm' method='post' action='"; if( !empty( $page ) ) { echo "page/$page/"; } echo "'>
			<div><input type='text' name='guestbookName' value='".$_POST['guestbookName']."' onFocus='if( this.value == \"Имя\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"Имя\";' /></div>
			<div class='line'></div>
			<div><input type='text' name='guestbookEmail' value='".$_POST['guestbookEmail']."' onFocus='if( this.value == \"Электронная почта\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"Электронная почта\";' /></div>
			<div class='line'></div>
			<div><textarea name='guestbookMessage' onFocus='if( this.value == \"Сообщение\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"Сообщение\";'>".$_POST['guestbookMessage']."</textarea></div>
			<div class='line'></div>
			<div>
				<img src='includes/secpic/secpic.php' onClick='this.src=\"includes/secpic/secpic.php?\"+Math.random()' alt='' title='Нажмите для смены изображения проверочного кода' class='captchaImage' />
				<input type='text' name='guestbookCode' value='Проверочный код' onFocus='if( this.value == \"Проверочный код\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"Проверочный код\";' class='captchaCode' /><br />
				<input type='submit' name='guestbookAdd' value='Отправить' />
			</div>
			<div class='line block'></div>
		</form>";

		$query = "SELECT name, date, message FROM guestbook WHERE checked ORDER BY date DESC, id DESC LIMIT $navigate, $limit";
		$result = $db -> fetch( $query );
		foreach( $result as $data ) {
			echo $data['name']."<br />".date_format( date_create( $data['date'] ), "H:i - d.m.Y" )."<br />".$data['message']."<div class='line block'></div>";
		}

		navigate( $db, $query, $navigate, $limit, $page, $extra = "" );

	}

?>