<?php

	// пользователи. административная часть
	function usersAdmin( $db = 0, $page = '' ) {

		$url = urlGet( $urlPostfix = "" );

		// если не сужествует переменной, то присвоить ей 0
		if( !isset( $_REQUEST['usersRazdel'] ) ) {
			$_REQUEST['usersRazdel'] = 0;
		}

		// добавление записи
		if( isset( $_POST["usersAdd"] ) ) {

			if( !isset( $_POST['usersChecked'] ) ) {
				$_POST['usersChecked'] = 0;
			}

			$result = $db -> query( "INSERT INTO users VALUES( NULL, '".$_POST['usersName']."', '".$_POST['usersDate']."', '".$_POST['usersMessage']."', '".$_POST['usersEmail']."', '".$_POST['usersChecked']."' )" );
			if( !$result['error'] ) {
				echo "<div class='statusOk'>Запись успешно добавлена</div>";
			}
		}

		// изменение записи
		if( isset( $_POST['usersEdit'] ) ) {

			if( !isset( $_POST['usersChecked2'] ) ) {
				$_POST['usersChecked2'] = 0;
			}

			$result = $db -> query( "UPDATE users SET  name = '".$_POST['usersName2']."', email = '".$_POST['usersEmail2']."', message = '".$_POST['usersMessage2']."', checked = '".$_POST['usersChecked2']."' WHERE id='".$_REQUEST['usersRazdel']."' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>Запись успешно изменена</div>";
			}
		}

		// удаление записи
		if( isset( $_GET['usersDelete'] ) ) {

			$result = $db -> query( "DELETE FROM users WHERE id='".$_GET['usersDelete']."' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>Запись успешно удалена</div>";
			}
		}

		echo "<div class='left'>Имя</div>
		<div><input type='text' name='usersName' /></div>
		<div class='line'></div>
		<div class='left'>Электронная почта</div>
		<div><input type='text' name='usersEmail' /></div>
		<div class='line'></div>
		<div class='left'>Сообщение</div>
		<div><textarea name='usersMessage'></textarea></div>
		<div class='line'></div>
		<div class='left'>Дата, время</div>
		<div><input type='text' name='usersDate' value='".date( "Y-m-d H:i:s" )."' readonly='readonly' /></div>
		<div class='line'></div>
		<div class='left'>Проверено</div>
		<div><fieldset><input type='checkbox' name='usersChecked' value='1' checked='checked' /></fieldset></div>
		<div class='line'></div>
		<div class='left'>&nbsp;</div>
		<div><input type='submit' name='usersAdd' value='Добавить' /></div>
		<div class='line block'></div>

		<div class='left'>Запись</div>
		<div>
			<select name='usersRazdel' onchange='document.form.submit();'>
				<option value='0'>Выберите запись для редактирования</option>";
				$result = $db -> fetch( "SELECT id, name, date FROM users ORDER BY date DESC, id DESC " );
				foreach( $result as $data ) {
					echo "<option value='".$data['id']."' "; if( $_REQUEST['usersRazdel'] == $data['id'] ){ echo "selected='selected'"; } echo">".date_format( date_create( $data['date'] ), 'd.m.Y' )." ".$data['name']."</option>";
				}
			echo "</select>
		</div>
		<div class='line'></div>";

		if( !empty( $_REQUEST['usersRazdel'] ) ) {
			$result = $db -> fetch( "SELECT id, name, email, date, message, checked FROM users WHERE id='".$_REQUEST['usersRazdel']."' " );
			foreach( $result as $data ) {
				echo "<div class='left'>Имя</div>
				<div><input type='text' name='usersName2' value='".$data['name']."' /></div>
				<div class='line'></div>
				<div class='left'>Электронная почта</div>
				<div><input type='text' name='usersEmail2' value='".$data['email']."' /></div>
				<div class='line'></div>
				<div class='left'>Сообщение</div>
				<div><textarea name='usersMessage2'>".$data['message']."</textarea></div>
				<div class='line'></div>
				<div class='left'>Дата</div>
				<div><input type='text' name='usersDate2' value='".$data['date']."' readonly='readonly' /></div>
				<div class='line'></div>
				<div class='left'>Проверено</div>
				<div><fieldset><input type='checkbox' name='usersChecked2' value='1' "; if( $data['checked'] ) { echo "checked='checked'"; } echo " /></fieldset></div>
				<div class='line'></div>
				<div class='left'>&nbsp;</div>
				<div>
					<input type='submit' name='usersEdit' value='Изменить' />
					<input type='button' value='Удалить' onclick='if( window.confirm( \"Вы действительно хотите удалить данную запись?\" ) ){ window.location.href=\"$url"; if( !empty( $page ) ){ echo "page/$page/"; } echo "usersDelete/".$data['id']."/\";}' />";

					if( !empty( $data['picture'] ) ) {
						echo "<img src='uploadfiles/users/".$data['picture']."' align='right'>";
					}

				echo "</div>";
			}
		}

		echo "<div class='line block'></div>";

	}


	// пользователи. пользовательская часть
	function usersUser( $db = 0, $page = '', $item = 0 ) {

		echo "<script type='text/javascript' src='includes/jscookies/cookies.js'></script>";
		echo "<script type='text/javascript' src='includes/jsmd5/md5.js'></script>";

		$url = urlGet( $urlPostfix = '' );

		$flag = true;

		if( isset( $_COOKIE['usersEmail'] ) && isset( $_COOKIE['usersPassword'] ) ) {

			$users = usersGet( $db, stringSimple( $_COOKIE['usersEmail'] ), stringSimple( $_COOKIE['usersPassword'] ) );
			if( $users['flag'] ) {
				$flag = false;
				echo "<div class='usersBlock'>
					<div class='statusOk'>";
						echo $users['mail']."<br />
						<a href='' onClick='document.getElementById( \"usersFilesForm\" ).style.display=\"block\"; return false;' title='Закачать файла'>Закачать файл</a> |
						<a href='' onClick='cookieCreate( \"usersEmail\", \"\", -366 ); cookieCreate( \"usersPassword\", \"\", -366 ); window.location.href=\"$url"; if( !empty( $page ) ) { echo "page/$page/"; } echo "\"; return false;' title='Выход из системы'>Выход</a>
						<form id='usersFilesForm' name='usersForm' method='post' action='"; if( !empty( $page ) ) { echo "page/$page/"; } if( !empty( $item ) ) { echo "item/$item/"; } echo "' enctype='multipart/form-data'>
							<div class='line block'></div>
							<div><input type='text' name='filesName' value='Имя файла' onFocus='if( this.value==\"Имя файла\" ) { this.value=\"\"; }' onBlur='if( this.value==\"\" ) { this.value=\"Имя файла\"; }' /></div>
							<div class='line'></div>
							<div class='usersFile'><input type='file' name='filesPath' /></div>
							<div class='line'></div>
							<div><textarea name='filesDescription' onFocus='if( this.value==\"Описание файла\" ) { this.value=\"\"; }' onBlur='if( this.value==\"\" ) { this.value=\"Описание файла\"; }'>Описание файла</textarea></div>
							<div class='line'></div>
							<div><input type='submit' name='filesAdd' value='Закачать файл' /></div>
						</form>
					</div>";


					// добавление записи
					if( isset( $_POST["filesAdd"] ) ) {

						$_POST['filesName'] = stringSimple( $_POST['filesName'] );
						$_POST['filesDescription'] = stringSimple( $_POST['filesDescription'] );
			
						$path = filesUpload( "filesPath", $filesDirectory = "uploadfiles/files/", $filesExtensionAllowed = "doc xls txt pdf docx xlsx exe rar" );
				
						if( !empty( $path ) ) {
							$result = $db -> query( "INSERT INTO files VALUES( NULL, '".$_POST['filesName']."', '".date( 'Y-m-d H:i:s' )."', '$path', '".$_POST['filesDescription']."', '23', '0', '".$users['id']."' )" );
							if( !$result['error'] ) {
								echo "<div class='statusOk'>Запись успешно добавлена</div>";
							}
						}
					}



					require_once "components/files.php";
					filesFormUser( $db, $page, $users['id'], $limit = 5 );

				echo "</div>";
			} else {
				echo "<script>cookieCreate( \"usersEmail\", \"\", -366 ); cookieCreate( \"usersPassword\", \"\", -366 );</script>";
			}

		}

		if( $flag ) {

			$flagRegister = false;
            $recoveryRequest = false;

			if( isset( $_POST['usersEnter'] ) ) {
				$flagRegister = true;
			}
            if ( isset( $_POST['recoveryEnter'] ) || isset($_GET['recovery_key']) ) {
                $recoveryRequest = true;
            }
            
			echo "<form name='usersForm' method='post' action='"; if( !empty( $page ) ) { echo "page/$page/"; } if( !empty( $item ) ) { echo "item/$item/"; } echo "'>
				<div id='usersRegister' class='users' "; if( $flagRegister || $recoveryRequest ) { echo "style='display: none;'"; } echo ">
					<a href='' onClick='getElementById(\"usersRegister\").style.display=\"none\"; getElementById(\"usersEnter\").style.display=\"block\"; return false;' title='Войти в систему'>Вход для партнеров</a><br /><br />
					<div class='usersTop'></div>
					<div class='usersMiddle'>
						<div class='usersContent'>";
	
							$_POST['usersRegisterName'] = isset( $_POST['usersRegisterName'] ) ? stringSimple( $_POST['usersRegisterName'] ) : '';
							$_POST['usersRegisterEmail'] = isset( $_POST['usersRegisterEmail'] ) ? stringSimple( $_POST['usersRegisterEmail'] ) : '';
	
							if( isset( $_POST['usersRegister'] ) ) {
	
								$usersFlag = true;
	
								if( empty( $_POST['usersRegisterName'] ) ) {
									$usersFlag = false;
									echo "<div class=statusCancel>Вы ввели некорректное имя</div>";
								}
	
								if( !emailCheck( $_POST['usersRegisterEmail'] ) ) {
									$usersFlag = false;
									echo "<div class=statusCancel>Вы ввели некорректный адрес эл. почты</div>";
								}
	
								if( $usersFlag ) {
									$result = $db -> fetch( "SELECT id FROM users WHERE mail LIKE '".$_POST['usersRegisterEmail']."' " );
									if( count( $result ) > 0 ) {
										$usersFlag = false;
										echo "<div class=statusCancel>Пользователь с данным адресом эл. почты уже существует в базе</div>";
									}
								}
	
								if( $usersFlag ) {
	
									$usersPassword = passwordGet();
                                    
									$result = $db -> query( "INSERT INTO users VALUES( NULL, '', '".md5( $usersPassword )."', '".$_POST['usersRegisterName']."', '".date( 'Y-m-d' )."', '1', '2', '".$_POST['usersRegisterEmail']."', '' )" );
									
                                    
                                    if( !$result['error'] ) {
	
										$result = $db -> fetch( "SELECT email FROM configEmail WHERE view LIKE 'users' " );
                                        
                                        $supportEmail = ( !$result['error'] ) ? $result[0]['email'] : 'no-repeat@'.urlGet();
                                        
                                        $usersTheme = "Регистрация пользователя на сайте ".urlGet();
										$usersBody = "<b>Регистрация пользователя</b><br /><br />Вы зарегестрировались на сайте <a href='".urlGet()."'>".urlGet()."</a><br />
    										эл. почта: ".$_POST['usersRegisterEmail']."<br />
    										пароль: $usersPassword";
                                        if( mailSend( $_POST['usersRegisterName'], $_POST['usersRegisterEmail'], $usersTheme, $usersBody, "Служба технической поддержки", $supportEmail ) )
                                        {
                                            echo "<div class='statusOk'>На введенный Вами адрес эл. почты отправлено письмо с паролем</div>";
                                            $usersBody = "Пользователь зарегестрировался на сайте <a href='".urlGet()."'>".urlGet()."</a><br />
        										имя: ".$_POST['usersRegisterName']."<br />
                                                эл. почта: ".$_POST['usersRegisterEmail'];
											mailSend( 'Служба технической поодержки', $supportEmail, $usersTheme, $usersBody, "Служба автоматической рассылки", 'no-repeat@'.urlGet());
                                            
                                            //$usersBody = "Почта заказчика {$_POST['usersRegisterName']} <{$_POST['usersRegisterEmail']}>";
                                            //mailSend( "Студия разработки мобильных приложений", "info@amobile-studio.ru", $usersTheme, $usersBody, "Служба автоматической рассылки", 'no-repeat@'.urlGet() );
                                        } else {
											echo "<div class='statusCancel'>Ошибка отправки письма с паролем. Попробуйте повторить попытку позже.</div>";
										}
									}
	
									$_POST[ 'usersRegisterName' ] = '';
									$_POST[ 'usersRegisterEmail' ] = '';
	
								}
	
							}
                            
							echo "<div class='line'></div>
							<div class='clickPicture'>
								<a href='' onClick='getElementById(\"usersRegister\").style.display=\"none\"; getElementById(\"usersEnter\").style.display=\"block\"; return false;' title='Вступить в членство за один клик'><img src='images/door.jpg' alt=''></a></div>
							<div class='clickText'>
								<a href='' onClick='getElementById(\"usersRegister\").style.display=\"none\"; getElementById(\"usersEnter\").style.display=\"block\"; return false;' title='Вступить в членство за один клик'>вступить<br />в членство<br />за один клик</a>
							</div>
							<div class='line'></div>";

							echo "<div class='label'>имя</div>
							<div><input type='text' name='usersRegisterName' value='".$_POST['usersRegisterName']."' class='usersInput' /></div>
							<div class='label'>эл. почта</div>
							<div><input type='text' name='usersRegisterEmail' value='".$_POST['usersRegisterEmail']."' class='usersInput' /></div>
							<div><input type='submit' name='usersRegister' value='вступить' class='usersInput' /></div>
							<div class='usersLine'></div>
							<div><a href='$url/page/enter/' title='Примечание'>Примечания</a></div>
                            ";
						echo "</div>
					</div>
					<div class='usersBottom'></div>
				</div>	
				<div id='usersEnter' class='users' "; if( !$flagRegister || $recoveryRequest ) { echo "style='display: none;'"; } echo ">
					<a href='' onClick='getElementById(\"usersEnter\").style.display=\"none\"; getElementById(\"usersRegister\").style.display=\"block\"; return false;' title='Стать партнером'>Стать партнером</a><br /><br />
					<div class='usersTop'></div>
					<div class='usersMiddle'>
						<div class='usersContent'>";

							if( isset( $users['flag'] ) && !$users['flag'] ) {
								echo "<div class='statusCancel'>Вы ввели некорректно пару логин-пароль</div>";
							}
	
							echo "<div class='label'>эл. почта</div>
							<div><input type='text' name='usersEnterEmail' class='usersInput' onKeyPress='if( event.keyCode == 13 ) { return false; }' /></div>
							<div class='label'>пароль</div>
							<div><input type='password' name='usersEnterPassword' class='usersInput' onKeyPress='if( event.keyCode == 13 ) { return false; }' /></div>
							<div><input type='submit' name='usersEnter' value='войти' class='usersInput' onClick='cookieCreate( \"usersEmail\", document.usersForm.usersEnterEmail.value, 366 ); cookieCreate( \"usersPassword\", hex_md5( document.usersForm.usersEnterPassword.value ), 366 );' /></div>
                            <div class='usersLine'></div>
                            <div><a href='"; if( !empty( $page ) ) { echo "page/$page?recovery_pass=1"; } if( !empty( $item ) ) { echo "item/$item?recovery_pass=1"; } echo "' onClick='getElementById(\"usersEnter\").style.display=\"none\"; getElementById(\"usersRecovery\").style.display=\"block\"; return false;'>Забыли пароль?</a></div>
                            ";
    
						echo "</div>
					</div>
					<div class='usersBottom'></div>
				</div>
                <div id='usersRecovery' class='users' "; if( !$recoveryRequest ) { echo "style='display: none;'"; } echo ">
                    <a href='' onClick='getElementById(\"usersRecovery\").style.display=\"none\"; getElementById(\"usersRegister\").style.display=\"block\"; return false;' title='Стать партнером'>Стать партнером</a><br /><br />
					<div class='usersTop'></div>
					<div class='usersMiddle'>
						<div class='usersContent'>";
                        
                            if ( isset($_POST['recoveryPass']['email']) ) {
                                $recoveryEmail = $_POST['recoveryPass']['email'];
                                $success = true;
                                if( !emailCheck( $recoveryEmail ) ) {
                                    $success = false;
                                    $cancelError = "Вы ввели некорректный адрес эл. почты";
								}
                                if ($success) {
                                    $result = $db -> fetch( "SELECT id FROM users WHERE mail LIKE '".$recoveryEmail."' " );
                                    if( count( $result ) < 1 ) {
                                        $success = false;
                                        $cancelError = "Пользователя с таким адресом эл. почты не существует в базе";
									}
                                }
                                if ($success) {
                                    
                                    $usersPassword = passwordGet();
                                    $result = $db -> query( "UPDATE users SET password = '". md5($usersPassword) ."' WHERE mail LIKE '$recoveryEmail'" );
                                    
                                    if( !$result['error'] ) {
                                        
                                        $usersTheme = "Восстановление пароля на сайте ".urlGet();
                                        $usersBody = "<b>Восстановление пароля</b><br /><br />У Вас изменился пароль на сайте <a href='".urlGet()."'>".urlGet()."</a><br />
											эл. почта: ".$recoveryEmail."<br />
											новый пароль: $usersPassword";
                                        if( mailSend( $recoveryEmail, $recoveryEmail, $usersTheme, $usersBody, "Служба технической поддержки", $data['email'] ) ) {
        								    echo "<div class='statusOk'>На введенный Вами адрес эл. почты отправлено письмо с новым паролем</div>";
										} else {
                                            $success = false;
											echo "<div class='statusCancel'>Ошибка отправки письма с паролем. Попробуйте повторить попытку позже.</div>";
										}
                                        
									} else {
                                        $success = false;
                                        echo "<div class='statusCancel'>Неизвестная ошибка. Попробуйте повторить попытку позже.</div>";
									}
                                } else {
                                    echo "<div class='statusCancel'>$cancelError</div>";
                                }
                            }
                            
							echo "<div class='label'>эл. почта</div>
							<div><input type='text' name='recoveryPass[email]' class='usersInput' value='";if (!$success) echo $recoveryEmail; echo "' onKeyPress='if( event.keyCode == 13 ) { return false; }' /></div>
							<div><input type='submit' name='recoveryEnter' value='Восстановить' class='usersInput' onClick='' /></div>
                            <div class='usersLine'></div>
                            <div><a href='"; if( !empty( $page ) ) { echo "page/$page?recovery_pass=1"; } if( !empty( $item ) ) { echo "item/$item?recovery_pass=1"; } echo "' onClick='getElementById(\"usersRecovery\").style.display=\"none\"; getElementById(\"usersEnter\").style.display=\"block\"; return false;'>Отмена</a></div>
                            ";
    
						echo "</div>
					</div>
					<div class='usersBottom'></div>
				</div>
			</form>";

		}

		return $flag;

	}

?>