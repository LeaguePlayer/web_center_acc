<?php

	// ������������. ���������������� �����
	function usersAdmin( $db = 0, $page = '' ) {

		$url = urlGet( $urlPostfix = "" );

		// ���� �� ���������� ����������, �� ��������� �� 0
		if( !isset( $_REQUEST['usersRazdel'] ) ) {
			$_REQUEST['usersRazdel'] = 0;
		}

		// ���������� ������
		if( isset( $_POST["usersAdd"] ) ) {

			if( !isset( $_POST['usersChecked'] ) ) {
				$_POST['usersChecked'] = 0;
			}

			$result = $db -> query( "INSERT INTO users VALUES( NULL, '".$_POST['usersName']."', '".$_POST['usersDate']."', '".$_POST['usersMessage']."', '".$_POST['usersEmail']."', '".$_POST['usersChecked']."' )" );
			if( !$result['error'] ) {
				echo "<div class='statusOk'>������ ������� ���������</div>";
			}
		}

		// ��������� ������
		if( isset( $_POST['usersEdit'] ) ) {

			if( !isset( $_POST['usersChecked2'] ) ) {
				$_POST['usersChecked2'] = 0;
			}

			$result = $db -> query( "UPDATE users SET  name = '".$_POST['usersName2']."', email = '".$_POST['usersEmail2']."', message = '".$_POST['usersMessage2']."', checked = '".$_POST['usersChecked2']."' WHERE id='".$_REQUEST['usersRazdel']."' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>������ ������� ��������</div>";
			}
		}

		// �������� ������
		if( isset( $_GET['usersDelete'] ) ) {

			$result = $db -> query( "DELETE FROM users WHERE id='".$_GET['usersDelete']."' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>������ ������� �������</div>";
			}
		}

		echo "<div class='left'>���</div>
		<div><input type='text' name='usersName' /></div>
		<div class='line'></div>
		<div class='left'>����������� �����</div>
		<div><input type='text' name='usersEmail' /></div>
		<div class='line'></div>
		<div class='left'>���������</div>
		<div><textarea name='usersMessage'></textarea></div>
		<div class='line'></div>
		<div class='left'>����, �����</div>
		<div><input type='text' name='usersDate' value='".date( "Y-m-d H:i:s" )."' readonly='readonly' /></div>
		<div class='line'></div>
		<div class='left'>���������</div>
		<div><fieldset><input type='checkbox' name='usersChecked' value='1' checked='checked' /></fieldset></div>
		<div class='line'></div>
		<div class='left'>&nbsp;</div>
		<div><input type='submit' name='usersAdd' value='��������' /></div>
		<div class='line block'></div>

		<div class='left'>������</div>
		<div>
			<select name='usersRazdel' onchange='document.form.submit();'>
				<option value='0'>�������� ������ ��� ��������������</option>";
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
				echo "<div class='left'>���</div>
				<div><input type='text' name='usersName2' value='".$data['name']."' /></div>
				<div class='line'></div>
				<div class='left'>����������� �����</div>
				<div><input type='text' name='usersEmail2' value='".$data['email']."' /></div>
				<div class='line'></div>
				<div class='left'>���������</div>
				<div><textarea name='usersMessage2'>".$data['message']."</textarea></div>
				<div class='line'></div>
				<div class='left'>����</div>
				<div><input type='text' name='usersDate2' value='".$data['date']."' readonly='readonly' /></div>
				<div class='line'></div>
				<div class='left'>���������</div>
				<div><fieldset><input type='checkbox' name='usersChecked2' value='1' "; if( $data['checked'] ) { echo "checked='checked'"; } echo " /></fieldset></div>
				<div class='line'></div>
				<div class='left'>&nbsp;</div>
				<div>
					<input type='submit' name='usersEdit' value='��������' />
					<input type='button' value='�������' onclick='if( window.confirm( \"�� ������������� ������ ������� ������ ������?\" ) ){ window.location.href=\"$url"; if( !empty( $page ) ){ echo "page/$page/"; } echo "usersDelete/".$data['id']."/\";}' />";

					if( !empty( $data['picture'] ) ) {
						echo "<img src='uploadfiles/users/".$data['picture']."' align='right'>";
					}

				echo "</div>";
			}
		}

		echo "<div class='line block'></div>";

	}


	// ������������. ���������������� �����
	function usersUser( $db = 0, $page = '', $item = 0 ) {

    $result = $db -> fetch( "SELECT email FROM configEmail WHERE view LIKE 'users' " );
    $supportEmail = ( !$result['error'] ) ? $result[0]['email'] : 'no-repeat@'.urlGet();

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
						<a href='' onClick='document.getElementById( \"usersFilesForm\" ).style.display=\"block\"; return false;' title='�������� �����'>�������� ����</a> |
						<a href='' onClick='cookieCreate( \"usersEmail\", \"\", -366 ); cookieCreate( \"usersPassword\", \"\", -366 ); window.location.href=\"$url"; if( !empty( $page ) ) { echo "page/$page/"; } echo "\"; return false;' title='����� �� �������'>�����</a>
						<form id='usersFilesForm' name='usersForm' method='post' action='"; if( !empty( $page ) ) { echo "page/$page/"; } if( !empty( $item ) ) { echo "item/$item/"; } echo "' enctype='multipart/form-data'>
							<div class='line block'></div>
							<div><input type='text' name='filesName' value='��� �����' onFocus='if( this.value==\"��� �����\" ) { this.value=\"\"; }' onBlur='if( this.value==\"\" ) { this.value=\"��� �����\"; }' /></div>
							<div class='line'></div>
							<div class='usersFile'><input type='file' name='filesPath' /></div>
							<div class='line'></div>
							<div><textarea name='filesDescription' onFocus='if( this.value==\"�������� �����\" ) { this.value=\"\"; }' onBlur='if( this.value==\"\" ) { this.value=\"�������� �����\"; }'>�������� �����</textarea></div>
							<div class='line'></div>
							<div><input type='submit' name='filesAdd' value='�������� ����' /></div>
						</form>
					</div>";


					// ���������� ������
					if( isset( $_POST["filesAdd"] ) ) {

						$_POST['filesName'] = stringSimple( $_POST['filesName'] );
						$_POST['filesDescription'] = stringSimple( $_POST['filesDescription'] );
			
						$path = filesUpload( "filesPath", $filesDirectory = "uploadfiles/files/", $filesExtensionAllowed = "doc xls txt pdf docx xlsx exe rar" );
				
						if( !empty( $path ) ) {
							$result = $db -> query( "INSERT INTO files VALUES( NULL, '".$_POST['filesName']."', '".date( 'Y-m-d H:i:s' )."', '$path', '".$_POST['filesDescription']."', '23', '0', '".$users['id']."' )" );
							if( !$result['error'] ) {
								echo "<div class='statusOk'>������ ������� ���������</div>";
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
					<a href='' onClick='getElementById(\"usersRegister\").style.display=\"none\"; getElementById(\"usersEnter\").style.display=\"block\"; return false;' title='����� � �������'>���� ��� ���������</a><br /><br />
					<div class='usersTop'></div>
					<div class='usersMiddle'>
						<div class='usersContent'>";
	
							$_POST['usersRegisterName'] = isset( $_POST['usersRegisterName'] ) ? stringSimple( $_POST['usersRegisterName'] ) : '';
							$_POST['usersRegisterEmail'] = isset( $_POST['usersRegisterEmail'] ) ? stringSimple( $_POST['usersRegisterEmail'] ) : '';
	
							if( isset( $_POST['usersRegister'] ) ) {
	
								$usersFlag = true;                                                       
	
								if( empty( $_POST['usersRegisterName'] ) ) {
									$usersFlag = false;
									echo "<div class=statusCancel>�� ����� ������������ ���</div>";
								}
	
								if( !emailCheck( $_POST['usersRegisterEmail'] ) ) {
									$usersFlag = false;
									echo "<div class=statusCancel>�� ����� ������������ ����� ��. �����</div>";
								}
                                
                                include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
                                $securimage = new Securimage();
                                if ($securimage->check($_POST['captcha_code']) == false) {
                                    $usersFlag = false;
									echo "<div class=statusCancel>������������ ����������� ���</div>";
                                }
	
								if( $usersFlag ) {
									$result = $db -> fetch( "SELECT id FROM users WHERE mail LIKE '".$_POST['usersRegisterEmail']."' " );
									if( count( $result ) > 0 ) {
										$usersFlag = false;
										echo "<div class=statusCancel>������������ � ������ ������� ��. ����� ��� ���������� � ����</div>";
									}
								}
	
								if( $usersFlag ) {
	
									$usersPassword = passwordGet();
                                    
									$result = $db -> query( "INSERT INTO users VALUES( NULL, '', '".md5( $usersPassword )."', '".$_POST['usersRegisterName']."', '".date( 'Y-m-d' )."', '1', '2', '".$_POST['usersRegisterEmail']."', '' )" );
									
                                    
                                    if( !$result['error'] ) {
                                        
                                        $usersTheme = "����������� ������������ �� ����� ".urlGet();
										$usersBody = "<b>����������� ������������</b><br /><br />�� ������������������ �� ����� <a href='".urlGet()."'>".urlGet()."</a><br />
    										��. �����: ".$_POST['usersRegisterEmail']."<br />
    										������: $usersPassword";
                                        if( mailSend( $_POST['usersRegisterName'], $_POST['usersRegisterEmail'], $usersTheme, $usersBody, "������ ����������� ���������", $supportEmail ) )
                                        {
                                            echo "<div class='statusOk'>�� ��������� ���� ����� ��. ����� ���������� ������ � �������</div>";
                                            $usersBody = "������������ ����������������� �� ����� <a href='".urlGet()."'>".urlGet()."</a><br />
        										���: ".$_POST['usersRegisterName']."<br />
                                                ��. �����: ".$_POST['usersRegisterEmail'];
											mailSend( '������ ����������� ���������', $supportEmail, $usersTheme, $usersBody, "������ �������������� ��������", 'no-repeat@'.urlGet());
                                            
                                            //$usersBody = "����� ��������� {$_POST['usersRegisterName']} <{$_POST['usersRegisterEmail']}>";
                                            //mailSend( "������ ���������� ��������� ����������", "info@amobile-studio.ru", $usersTheme, $usersBody, "������ �������������� ��������", 'no-repeat@'.urlGet() );
                                        } else {
											echo "<div class='statusCancel'>������ �������� ������ � �������. ���������� ��������� ������� �����.</div>";
										}
									}
	
									$_POST[ 'usersRegisterName' ] = '';
									$_POST[ 'usersRegisterEmail' ] = '';
	
								}
	
							}
                            
							echo "<div class='line'></div>
							<div class='clickPicture'>
								<a href='' onClick='getElementById(\"usersRegister\").style.display=\"none\"; getElementById(\"usersEnter\").style.display=\"block\"; return false;' title='�������� � �������� �� ���� ����'><img src='images/door.jpg' alt=''></a></div>
							<div class='clickText'>
								<a href='' onClick='getElementById(\"usersRegister\").style.display=\"none\"; getElementById(\"usersEnter\").style.display=\"block\"; return false;' title='�������� � �������� �� ���� ����'>��������<br />� ��������<br />�� ���� ����</a>
							</div>
							<div class='line'></div>";

							echo "<div class='label'>���</div>
							<div><input type='text' name='usersRegisterName' value='".$_POST['usersRegisterName']."' class='usersInput' /></div>
							<div class='label'>��. �����</div>
							<div><input type='text' name='usersRegisterEmail' value='".$_POST['usersRegisterEmail']."' class='usersInput' /></div>
                            <div class='label'>����������� ���</div>
                            <div><img id='captcha' src='/securimage/securimage_show.php' alt='CAPTCHA Image' onclick=\"document.getElementById('captcha').src = '/securimage/securimage_show.php?' + Math.random(); return false\" style='cursor: pointer;' /></div>
                            <div><input type='text' name='captcha_code' class='usersInput' /></div>
							<div><input type='submit' name='usersRegister' value='��������' class='usersInput' /></div>
							<div class='usersLine'></div>
							<div><a href='$url/page/enter/' title='����������'>����������</a></div>
                            ";
						echo "</div>
					</div>
					<div class='usersBottom'></div>
				</div>	
				<div id='usersEnter' class='users' "; if( !$flagRegister || $recoveryRequest ) { echo "style='display: none;'"; } echo ">
					<a href='' onClick='getElementById(\"usersEnter\").style.display=\"none\"; getElementById(\"usersRegister\").style.display=\"block\"; return false;' title='����� ���������'>����� ���������</a><br /><br />
					<div class='usersTop'></div>
					<div class='usersMiddle'>
						<div class='usersContent'>";

							if( isset( $users['flag'] ) && !$users['flag'] ) {
								echo "<div class='statusCancel'>�� ����� ����������� ���� �����-������</div>";
							}
	
							echo "<div class='label'>��. �����</div>
							<div><input type='text' name='usersEnterEmail' class='usersInput' onKeyPress='if( event.keyCode == 13 ) { return false; }' /></div>
							<div class='label'>������</div>
							<div><input type='password' name='usersEnterPassword' class='usersInput' onKeyPress='if( event.keyCode == 13 ) { return false; }' /></div>
							<div><input type='submit' name='usersEnter' value='�����' class='usersInput' onClick='cookieCreate( \"usersEmail\", document.usersForm.usersEnterEmail.value, 366 ); cookieCreate( \"usersPassword\", hex_md5( document.usersForm.usersEnterPassword.value ), 366 );' /></div>
                            <div class='usersLine'></div>
                            <div><a href='"; if( !empty( $page ) ) { echo "page/$page?recovery_pass=1"; } if( !empty( $item ) ) { echo "item/$item?recovery_pass=1"; } echo "' onClick='getElementById(\"usersEnter\").style.display=\"none\"; getElementById(\"usersRecovery\").style.display=\"block\"; return false;'>������ ������?</a></div>
                            ";
    
						echo "</div>
					</div>
					<div class='usersBottom'></div>
				</div>
                <div id='usersRecovery' class='users' "; if( !$recoveryRequest ) { echo "style='display: none;'"; } echo ">
                    <a href='' onClick='getElementById(\"usersRecovery\").style.display=\"none\"; getElementById(\"usersRegister\").style.display=\"block\"; return false;' title='����� ���������'>����� ���������</a><br /><br />
					<div class='usersTop'></div>
					<div class='usersMiddle'>
						<div class='usersContent'>";
                        
                            if ( isset($_POST['recoveryPass']['email']) ) {
                                $recoveryEmail = $_POST['recoveryPass']['email'];
                                $success = true;
                                if( !emailCheck( $recoveryEmail ) ) {
                                    $success = false;
                                    $cancelError = "�� ����� ������������ ����� ��. �����";
								}
                                if ($success) {
                                    $result = $db -> fetch( "SELECT id FROM users WHERE mail LIKE '".$recoveryEmail."' " );
                                    if( count( $result ) < 1 ) {
                                        $success = false;
                                        $cancelError = "������������ � ����� ������� ��. ����� �� ���������� � ����";
									}
                                }
                                if ($success) {
                                    
                                    $usersPassword = passwordGet();
                                    $result = $db -> query( "UPDATE users SET password = '". md5($usersPassword) ."' WHERE mail LIKE '$recoveryEmail'" );
                                    
                                    if( !$result['error'] ) {
                                        
                                        $usersTheme = "�������������� ������ �� ����� ".urlGet();
                                        $usersBody = "<b>�������������� ������</b><br /><br />� ��� ��������� ������ �� ����� <a href='".urlGet()."'>".urlGet()."</a><br />
											��. �����: ".$recoveryEmail."<br />
											����� ������: $usersPassword";
                                        if( mailSend( $recoveryEmail, $recoveryEmail, $usersTheme, $usersBody, "������ ����������� ���������", $data['email'] ) ) {
        								    echo "<div class='statusOk'>�� ��������� ���� ����� ��. ����� ���������� ������ � ����� �������</div>";
										} else {
                                            $success = false;
											echo "<div class='statusCancel'>������ �������� ������ � �������. ���������� ��������� ������� �����.</div>";
										}
                                        
									} else {
                                        $success = false;
                                        echo "<div class='statusCancel'>����������� ������. ���������� ��������� ������� �����.</div>";
									}
                                } else {
                                    echo "<div class='statusCancel'>$cancelError</div>";
                                }
                            }
                            
							echo "<div class='label'>��. �����</div>
							<div><input type='text' name='recoveryPass[email]' class='usersInput' value='";if (!$success) echo $recoveryEmail; echo "' onKeyPress='if( event.keyCode == 13 ) { return false; }' /></div>
							<div><input type='submit' name='recoveryEnter' value='������������' class='usersInput' onClick='' /></div>
                            <div class='usersLine'></div>
                            <div><a href='"; if( !empty( $page ) ) { echo "page/$page?recovery_pass=1"; } if( !empty( $item ) ) { echo "item/$item?recovery_pass=1"; } echo "' onClick='getElementById(\"usersRecovery\").style.display=\"none\"; getElementById(\"usersEnter\").style.display=\"block\"; return false;'>������</a></div>
                            ";
    
						echo "</div>
					</div>
					<div class='usersBottom'></div>
				</div>
			</form>";
			
            echo "<div class='userActions'><a class='userButton animationButton' href='/page/sro/'><span data-labels='���������� � ������������ | � ��������� ��� | ������ | ������� | ���������'>���������� � ������������ ���</span></a></div>";
            echo "<div class='userActions'><a class='userButton animationButton' href='/page/qualup/'><span style='height:37px;' data-labels='������������� ����� ��������� ������������: | ������������ ������� � �������� | ������������ ���������� ���������� | ������������ ������������ ��������� ���� | ������������ ����: ��������� ������� � ������ | ������������ ���������� ������'>������������� ����� ��������� ������������:</span></a></div>";
            echo "<div class='userActions'><a class='userButton animationButton' href='/page/sertification/'><span style='height:37px;' data-labels='������������ ������������ ����������� �������� | ������������� ������ - ������� 20%'>������������ ������������ ����������� ��������</span></a></div>";
        
        		if ( $_GET['page'] == 'forappraisers' ) {
      				$damageValues = array(
	              'model' => '',
	              'year' => '',
	              'date' => '',
	              'place' => '',
	              'parts_damaged' => '',
	              'lost_commodity' => '',
	              'email' => '',
	            );
	            $damageErrors = array();
	            if ( isset($_POST['Damage']) ) {
	              if ( empty($_POST['Damage']['model']) || empty($_POST['Damage']['year']) || empty($_POST['Damage']['date']) || empty($_POST['Damage']['place']) || empty($_POST['Damage']['parts_damaged']) ) {
	                $damageErrors[] = "��������� �� ��� ����.";
	              }
	              if ( !emailCheck( $_POST['Damage']['email'] ) ) {
	                $damageErrors[] = "������ ������������ E-mail.";
	              }
	              if ( empty($damageErrors) ) {
	                $mailSubject = "������ �� ������ ��������� ������ ����� ���";
	                $mailBody = "<b>��������� ������ �� ������� ��������� ������ ������</b><br>";
	                $mailBody .= "<b>�����, ������: </b>".$_POST['Damage']['model']."<br>";
	                $mailBody .= "<b>����� � ��� �������: </b>".$_POST['Damage']['year']."<br>";
	                $mailBody .= "<b>���� ���: </b>".$_POST['Damage']['date']."<br>";
	                $mailBody .= "<b>����� ���: </b>".$_POST['Damage']['place']."<br>";
	                $mailBody .= "<b>����� ������ ����������: </b>".$_POST['Damage']['parts_damaged']."<br>";
	                $mailBody .= "<b>������ �������� ���������: </b>".($_POST['Damage']['lost_commodity'] ? "��" : "���")."<br>";
	                $mailBody .= "<b>E-mail �����������: </b>".$_POST['Damage']['email']."<br>";
	                if ( mailSend( "center-acc.ru", 'welcome@center-acc.ru', $mailSubject, $mailBody, "������ ����������� ���������", 'no-repeat@'.urlGet() ) ) {
	                  $damageOk = "���� ������ ������� � ����� ���������� � ��������� �����";
	                }
	              } else {
	                $damageValues = array(
	                  'model' => $_POST['Damage']['model'],
	                  'year' => $_POST['Damage']['year'],
	                  'date' => $_POST['Damage']['date'],
	                  'place' => $_POST['Damage']['place'],
	                  'parts_damaged' => $_POST['Damage']['parts_damaged'],
	                  'lost_commodity' => $_POST['Damage']['lost_commodity'],
	                  'email' => $_POST['Damage']['email'],
	                );
	              }
	            }
	            echo "<form id='expertForm' method='POST' action='".$_SERVER['REQUEST_URI']."#expertForm'>".
	              "<div id='usersExpert' class='users' style='display: block;'>".
	                "<div class='usersTop'></div>".
	                "<div class='usersMiddle'>".
	                  "<div class='usersContent'>".

	                    "<h3 class='caption'>������ ������� ��������� ����� �� ������ ����</h3>";

	                    foreach ( $damageErrors as $error ) {
	                      echo "<div class='statusCancel'>".$error."</div>";
	                    }

	                    if ( $damageOk ) {
	                      echo "<div class='statusOk'>".$damageOk."</div>";
	                    }

	                    echo "<div><input placeholder='�����, ������' type='text' value='".$damageValues['model']."' name='Damage[model]' class='usersInput' onkeypress='if( event.keyCode == 13 ) { return false; }'></div>".

	                    "<div><input placeholder='�����, ��� �������' type='text' value='".$damageValues['year']."' name='Damage[year]' class='usersInput' onkeypress='if( event.keyCode == 13 ) { return false; }'></div>".

	                    "<div><input placeholder='���� ���' type='text' value='".$damageValues['date']."' name='Damage[date]' class='usersInput' onkeypress='if( event.keyCode == 13 ) { return false; }'></div>".

	                    "<div><input placeholder='����� ���' type='text' value='".$damageValues['place']."' name='Damage[place]' class='usersInput' onkeypress='if( event.keyCode == 13 ) { return false; }'></div>".

	                    "<div><textarea placeholder='����� ������ ����������' name='Damage[parts_damaged]' rows='3' class='usersInput' onkeypress='if( event.keyCode == 13 ) { return false; }'>".$damageValues['parts_damaged']."</textarea></div>".

	                    "<div class='label'>������ �������� ���������</div>".
	                    "<div>".
	                    	"<input type='checkbox' name='Damage[lost_commodity]' ".($damageValues['lost_commodity'] ? "checked='checked'" : "").">".
	                    	"<abbr title='���������� ��������� ������������� ��������, ��������� ��������������� ���������� ��������� (��������) ���� ���������� � ��� ���������������� ������� ���������� ��� � ������������ �������'>[?]</abbr>".
                    	"</div>".

	                    "<div><input placeholder='E-mail' type='text' value='".$damageValues['email']."' name='Damage[email]' class='usersInput' onkeypress='if( event.keyCode == 13 ) { return false; }'></div>".

	                    "<div><input type='submit' name='usersExpert' value='���������' class='usersInput'></div>".

	                  "</div>".
	                "</div>".
	                "<div class='usersBottom'></div>".
	              "</div>".
	          	"</form>";
        		}
        }

		return $flag;

	}

?>