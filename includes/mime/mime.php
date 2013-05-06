<?php
	function mimeGet( $filesExtension ) {
		$mime = array(
			'doc'	=>	'application/msword',
			'xls'	=>	'application/vnd.ms-excel',
			'txt'	=>	'text/plain',
			'djvu'	=>	'image/vnd.djvu',
			'pdf'	=>	'application/pdf',
			'docx'	=>	'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'xlsx'	=>	'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'jpg'	=>	'image/jpeg',
			'gif'	=>	'image/gif',
			'png'	=>	'image/png',
			'bmp'	=>	'image/bmp',
			'rar'	=>	'application/octet-stream',
			'zip'	=>	'application/octet-stream',
			'exe'	=>	'application/octet-stream',
			'7z'	=>	'application/octet-stream',
			'swf'	=>	'application/x-shockwave-flash'
		);

		if( @$mime[ $filesExtension ] ) {
			return $mime[ $filesExtension ];
		} else {
			return "";
		}
	}
?>