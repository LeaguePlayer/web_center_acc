<?php

	// �������� �����. ���������������� �����
	function feedbackAdmin( $db = 0 ) {

		require_once "../components/pages.php";
		pagesAdmin( $db, $page = "extracts" );

		if( isset( $_POST['configEdit'] ) ) {
			$result = $db -> query( "UPDATE configEmail SET email = '".$_POST['configEmail']."' WHERE view LIKE 'feedback' " );
			if( empty( $result['error'] ) ) {
				echo "<div class='statusOk'>������ ������� ��������</div>";
			}
		}

		$result = $db -> fetch( "SELECT email FROM configEmail WHERE view LIKE 'feedback' " );
		foreach( $result as $data ) {
			echo"<div class='left'>����������� �����</div>
			<div><input type='text' name='configEmail' value='".$data['email']."' /></div>
			<div class='line'></div>
			<div class='left'>&nbsp;</div>
			<div><input type='submit' name='configEdit' value='��������' /></div>
			<div class='line block'></div>";
		}

	}


	// �������� �����. ���������������� �����
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
				$_POST['feedbackName'] = '���� �.�.�.';
			}
		} else {
			$_POST['feedbackName'] = stringSimple( $_POST['feedbackName'] );
		}

		if( !isset( $_POST['feedbackRequired'] ) ) {
			$_POST['feedbackRequired'] = '�.�.�. ��������������';
		} else {
			$_POST['feedbackRequired'] = stringSimple( $_POST['feedbackRequired'] );
		}

		if( !isset( $_POST['feedbackNumber'] ) ) {
			$_POST['feedbackNumber'] = '���������� �����';
		} else {
			$_POST['feedbackNumber'] = stringSimple( $_POST['feedbackNumber'] );
		}

		if( !isset( $_POST['feedbackEmail'] ) ) {
			if( $usersFlag ) {
				$_POST['feedbackEmail'] = $users['mail'];
			} else {
				$_POST['feedbackEmail'] = '��� ����� ����������� �����';
			}
		} else {
			$_POST['feedbackEmail'] = stringSimple( $_POST['feedbackEmail'] );
		}

		if( !isset( $_POST['feedbackMessage'] ) ) {
			$_POST['feedbackMessage'] = '��� ����� ����� ������';
		} else {
			$_POST['feedbackMessage'] = stringSimple( $_POST['feedbackMessage'] );
		}

		if( isset( $_POST['feedbackAdd'] ) ) {
			$feedbackFlag = true;

			if( $_POST['feedbackName'] == '���� �.�.�.' ) {
				$feedbackFlag = false;
				echo "<div class='statusCancel'>�� ��������� ��������� ���� '���� �.�.�.'</div>";
			}

			if( $_POST['feedbackRequired'] == '�.�.�. ��������������' ) {
				$feedbackFlag = false;
				echo "<div class='statusCancel'>�� ��������� ��������� ���� '�.�.�. ��������������'</div>";
			}

			if( $_POST['feedbackNumber'] == '���������� �����' ) {
				$feedbackFlag = false;
				echo "<div class='statusCancel'>�� ��������� ��������� ���� '���������� �����'</div>";
			}

			if( !emailCheck( $_POST['feedbackEmail'] ) ) {
				$feedbackFlag = false;
				echo "<div class='statusCancel'>�� ��������� ��������� ���� '��� ����� ����������� �����'</div>";
			}

			if( $_POST['feedbackMessage'] == '��� ����� ����� ������' ) {
				$feedbackFlag = false;
				echo "<div class='statusCancel'>�� ��������� ��������� ���� '��� ����� ����� ������'</div>";
			}

			if( stringSimple( $_POST['feedbackCode'] ) != stringSimple( $_SESSION['secpic'] ) ) {
				$feedbackFlag = false;
				echo "<div class='statusCancel'>�� ��������� ��������� ���� '����������� ���'</div>";
			}


			if( $feedbackFlag ) {

				$result = $db -> fetch( "SELECT email FROM configEmail WHERE view LIKE 'feedback' " );
				foreach( $result as $data ) {

					$feedbackTheme = "��������� � ����� ".urlGet();
					$feedbackBody = "<b>".$feedbackTheme."</b><br /><br />";
					$feedbackBody .= "<u>���� �.�.�.:</u> ".$_POST['feedbackName']."<br />";
					$feedbackBody .= "<u>�.�.�. ��������������:</u> ".$_POST['feedbackRequired']."<br />";
					$feedbackBody .= "<u>���������� �����:</u> ".$_POST['feedbackNumber']."<br />";
					$feedbackBody .= "<u>��� ����� ����������� �����:</u> ".$_POST['feedbackEmail']."<br />";
					$feedbackBody .= "<u>��� ����� ����� ������:</u> ".$_POST['feedbackMessage']."<br />";


					if( mailSend( "������ ����������� ���������", $data['email'], $feedbackTheme, $feedbackBody, $_POST['feedbackName'], $_POST['feedbackEmail'] ) ) {
						echo "<div class='statusOk'>��������� ������� ����������</div>";
					} else {
						echo "<div class='statusCancel'>������ �������� ���������</div>";
					}

					$_POST['feedbackRequired'] = '�.�.�. ��������������';
					$_POST['feedbackNumber'] = '���������� �����';
					$_POST['feedbackMessage'] = '���������';
				}
			}
		}

		echo "<form name='feedbackForm' method='post' action='"; if( !empty( $page ) ) { echo "page/$page/"; } echo "'>
			<div><input type='text' name='feedbackName' value='".$_POST['feedbackName']."' onFocus='if( this.value == \"���� �.�.�.\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"���� �.�.�.\";' /></div>
			<div class='line'></div>
			<div><input type='text' name='feedbackRequired' value='".$_POST['feedbackRequired']."' onFocus='if( this.value == \"�.�.�. ��������������\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"�.�.�. ��������������\";' /></div>
			<div class='line'></div>
			<div><input type='text' name='feedbackNumber' value='".$_POST['feedbackNumber']."' onFocus='if( this.value == \"���������� �����\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"���������� �����\";' /></div>
			<div class='line'></div>
			<div><input type='text' name='feedbackEmail' value='".$_POST['feedbackEmail']."' onFocus='if( this.value == \"��� ����� ����������� �����\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"��� ����� ����������� �����\";' /></div>
			<div class='line'></div>
			<div><textarea name='feedbackMessage' onFocus='if( this.value == \"��� ����� ����� ������\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"��� ����� ����� ������\";'>".$_POST['feedbackMessage']."</textarea></div>
			<div class='line'></div>
			<div>
				<img src='includes/secpic/secpic.php' onClick='this.src=\"includes/secpic/secpic.php?\"+Math.random()' alt='' title='������� ��� ����� ����������� ������������ ����' class='captchaImage' />
				<input type='text' name='feedbackCode' value='����������� ���' onFocus='if( this.value == \"����������� ���\" ) this.value = \"\";' onBlur='if( this.value == \"\" ) this.value = \"����������� ���\";' class='captchaCode' /><br />
				<input type='submit' name='feedbackAdd' value='���������' />
			</div>
			<div class='line block'></div>
		</form>";

	}

?>