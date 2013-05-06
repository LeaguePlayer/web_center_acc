<?php

	// �������� �����. ���������������� �����
	function guestbookAdmin( $db = 0, $page = '' ) {

		$url = urlGet( $urlPostfix = "" );

		// ���� �� ���������� ����������, �� ��������� �� 0
		if( !isset( $_REQUEST['guestbookRazdel'] ) ) {
			$_REQUEST['guestbookRazdel'] = 0;
		}

		// ���������� ������
		if( isset( $_POST["guestbookAdd"] ) ) {

			if( !isset( $_POST['guestbookChecked'] ) ) {
				$_POST['guestbookChecked'] = 0;
			}

			$result = $db -> query( "INSERT INTO guestbook VALUES( NULL, '".$_POST['guestbookName']."', '".$_POST['guestbookDate']."', '".$_POST['guestbookMessage']."', '".$_POST['guestbookEmail']."', '".$_POST['guestbookChecked']."' )" );
			if( !$result['error'] ) {
				echo "<div class='statusOk'>������ ������� ���������</div>";
			}
		}

		// ��������� ������
		if( isset( $_POST['guestbookEdit'] ) ) {

			if( !isset( $_POST['guestbookChecked2'] ) ) {
				$_POST['guestbookChecked2'] = 0;
			}

			$result = $db -> query( "UPDATE guestbook SET  name = '".$_POST['guestbookName2']."', email = '".$_POST['guestbookEmail2']."', message = '".$_POST['guestbookMessage2']."', checked = '".$_POST['guestbookChecked2']."' WHERE id='".$_REQUEST['guestbookRazdel']."' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>������ ������� ��������</div>";
			}
		}

		// �������� ������
		if( isset( $_GET['guestbookDelete'] ) ) {

			$result = $db -> query( "DELETE FROM guestbook WHERE id='".$_GET['guestbookDelete']."' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>������ ������� �������</div>";
			}
		}

		if( isset( $_POST['configEdit'] ) ) {
			$result = $db -> query( "UPDATE configEmail SET email = '".$_POST['configEmail']."' WHERE view LIKE 'guestbook' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>������ ������� ��������</div>";
			}
		}

		$result = $db -> fetch( "SELECT email FROM configEmail WHERE view LIKE 'guestbook' " );
		foreach( $result as $data ) {
			echo"<div class='left'>����������� �����</div>
			<div><input type='text' name='configEmail' value='".$data['email']."' /></div>
			<div class='line'></div>
			<div class='left'>&nbsp;</div>
			<div><input type='submit' name='configEdit' value='��������' /></div>
			<div class='line block'></div>";
		}

		echo "<div class='left'>���</div>
		<div><input type='text' name='guestbookName' /></div>
		<div class='line'></div>
		<div class='left'>����������� �����</div>
		<div><input type='text' name='guestbookEmail' /></div>
		<div class='line'></div>
		<div class='left'>���������</div>
		<div><textarea name='guestbookMessage'></textarea></div>
		<div class='line'></div>
		<div class='left'>����, �����</div>
		<div><input type='text' name='guestbookDate' value='".date( "Y-m-d H:i:s" )."' readonly='readonly' /></div>
		<div class='line'></div>
		<div class='left'>���������</div>
		<div><fieldset><input type='checkbox' name='guestbookChecked' value='1' checked='checked' /></fieldset></div>
		<div class='line'></div>
		<div class='left'>&nbsp;</div>
		<div><input type='submit' name='guestbookAdd' value='��������' /></div>
		<div class='line block'></div>

		<div class='left'>������</div>
		<div>
			<select name='guestbookRazdel' onchange='document.form.submit();'>
				<option value='0'>�������� ������ ��� ��������������</option>";
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
				echo "<div class='left'>���</div>
				<div><input type='text' name='guestbookName2' value='".$data['name']."' /></div>
				<div class='line'></div>
				<div class='left'>����������� �����</div>
				<div><input type='text' name='guestbookEmail2' value='".$data['email']."' /></div>
				<div class='line'></div>
				<div class='left'>���������</div>
				<div><textarea name='guestbookMessage2'>".$data['message']."</textarea></div>
				<div class='line'></div>
				<div class='left'>����</div>
				<div><input type='text' name='guestbookDate2' value='".$data['date']."' readonly='readonly' /></div>
				<div class='line'></div>
				<div class='left'>���������</div>
				<div><fieldset><input type='checkbox' name='guestbookChecked2' value='1' "; if( $data['checked'] ) { echo "checked='checked'"; } echo " /></fieldset></div>
				<div class='line'></div>
				<div class='left'>&nbsp;</div>
				<div>
					<input type='submit' name='guestbookEdit' value='��������' />
					<input type='button' value='�������' onclick='if( window.confirm( \"�� ������������� ������ ������� ������ ������?\" ) ){ window.location.href=\"$url"; if( !empty( $page ) ){ echo "page/$page/"; } echo "guestbookDelete/".$data['id']."/\";}' />";

					if( !empty( $data['picture'] ) ) {
						echo "<img src='uploadfiles/guestbook/".$data['picture']."' align='right'>";
					}

				echo "</div>";
			}
		}

		echo "<div class='line block'></div>";

	}


	// �������� �����. ���������������� �����
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
				$_POST['guestbookName'] = '���';
			}
		} else {
			$_POST['guestbookName'] = stringSimple( $_POST['guestbookName'] );
		}

		if( !isset( $_POST['guestbookEmail'] ) ) {
			if( $usersFlag ) {
				$_POST['guestbookEmail'] = $users['mail'];
			} else {
				$_POST['guestbookEmail'] = '����������� �����';
			}
		} else {
			$_POST['guestbookEmail'] = stringSimple( $_POST['guestbookEmail'] );
		}

		if( !isset( $_POST['guestbookMessage'] ) ) {
			$_POST['guestbookMessage'] = '���������';
		} else {
			$_POST['guestbookMessage'] = stringSimple( $_POST['guestbookMessage'] );
		}

		if( isset( $_POST['guestbookAdd'] ) ) {
			$guestbookFlag = true;

			if( $_POST['guestbookName'] == '���' ) {
				$guestbookFlag = false;
				echo "<div class='statusCancel'>�� ��������� ��������� ���� '���'</div>";
			}

			if( !emailCheck( $_POST['guestbookEmail'] ) ) {
				$guestbookFlag = false;
				echo "<div class='statusCancel'>�� ��������� ��������� ���� '����������� �����'</div>";
			}

			if( $_POST['guestbookMessage'] == '���������' ) {
				$guestbookFlag = false;
				echo "<div class='statusCancel'>�� ��������� ��������� ���� '���������'</div>";
			}

			if( stringSimple( $_POST['guestbookCode'] ) != stringSimple( $_SESSION['secpic'] ) ) {
				$guestbookFlag = false;
				echo "<div class='statusCancel'>�� ��������� ��������� ���� '����������� ���'</div>";
			}


			if( $guestbookFlag ) {

				$result = $db -> query( "INSERT INTO guestbook VALUES( NULL, '".$_POST['guestbookName']."', '".date( "Y-m-d H:i:s" )."', '".$_POST['guestbookMessage']."', '".$_POST['guestbookEmail']."', FALSE )" );
				if( empty( $result['error'] ) ) {
					echo "<div class='statusOk'>������ ������� ���������, ��� ����� ���������� ����� �������� ���������������.</div>";
					$_POST['guestbookMessage'] = '���������';

					$result = $db -> fetch( "SELECT email FROM configEmail WHERE view LIKE 'guestbook' " );
					foreach( $result as $data ) {

						$guestbookTheme = "����� ��������� � �������� ����� �� ����� ".urlGet();
						$guestbookBody = "<b>��������� ����� ��������� � �������� �����.</b><br /><br />";
	
						if( mailSend( "������ ����������� ���������", $data['email'], $guestbookTheme, $guestbookBody, "������ ����������� ���������", $data['email'] ) ) {
							echo "<div class='statusOk'>����������� � ��������� ������� ���������� ��������������</div>";
						} else {
							echo "<div class='statusCancel'>������ �������� ����������� �������������� � ���������</div>";
						}
					}

				}

			}
		}

		echo "<form name='guestbookForm' method='post' action='"; if( !empty( $page ) ) { echo "page/$page/"; } echo "'>
			<div><input type='text' name='guestbookName' value='".$_POST['guestbookName']."' onFocus='if( this.value == \"���\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"���\";' /></div>
			<div class='line'></div>
			<div><input type='text' name='guestbookEmail' value='".$_POST['guestbookEmail']."' onFocus='if( this.value == \"����������� �����\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"����������� �����\";' /></div>
			<div class='line'></div>
			<div><textarea name='guestbookMessage' onFocus='if( this.value == \"���������\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"���������\";'>".$_POST['guestbookMessage']."</textarea></div>
			<div class='line'></div>
			<div>
				<img src='includes/secpic/secpic.php' onClick='this.src=\"includes/secpic/secpic.php?\"+Math.random()' alt='' title='������� ��� ����� ����������� ������������ ����' class='captchaImage' />
				<input type='text' name='guestbookCode' value='����������� ���' onFocus='if( this.value == \"����������� ���\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"����������� ���\";' class='captchaCode' /><br />
				<input type='submit' name='guestbookAdd' value='���������' />
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