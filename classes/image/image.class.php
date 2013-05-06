<?php


	/* ����� ��� ��������� ����������� */
	class IMAGE {


		public $format;
		public $image;
		public $filename;


		public $width;
		public $height;
		public $path;
		public $name;
		public $quality;
		public $size;
		public $watermark;
		public $prefix;


		// �����������
		function __construct( $filename ) {

			$this -> filename = $filename;


			try {
				if( !file_exists( $_FILES[ $this -> filename ][ "tmp_name" ] ) ) {
					throw new Exception("������� �������� �� ������������ ���� �����������");
				}

				$fileinfo = getimagesize( $_FILES[ $this -> filename ][ "tmp_name" ] );

				// ����������� mime ���� ������������ �����
				switch( strtolower( $fileinfo['mime'] ) ) {
					case "image/jpeg":
						$this -> format = "jpg";
						if( !$this -> image = imagecreatefromjpeg( $_FILES[ $this -> filename ][ "tmp_name" ] ) ) {
							throw new Exception("������� �������� *.jpeg �����������");
						}
						break;	
	
					case "image/gif":
						$this -> format = "gif";
						if( !$this -> image = imagecreatefromgif( $_FILES[ $this -> filename ][ "tmp_name" ] ) ) {
							throw new Exception("������� �������� *.gif �����������");
						}
						break;	
	
					case "image/png":
						$this -> format = "png";
						if( !$this -> image = imagecreatefrompng( $_FILES[ $this -> filename ][ "tmp_name" ] ) ) {
							throw new Exception("������� �������� *.png �����������");
						}
						break;	
	
					default:
						throw new Exception("������� �������� �� ���������� ������ ����������� (����������� *.jpg, *.jpeg, *.png, *.gif)");
				}
			} catch ( Exception $error ) {
				$this -> image = 0;
				echo "<div class='statusCancel'>������ ������ IMAGE: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}



		}



		// ��������� �����������
		function save( $params ) {


			if( !empty( $this -> image ) ) {

				$this -> width = isset( $params["width"] ) ? $params["width"] : 150;
				$this -> height = isset( $params["height"] ) ? $params["height"] : 150;
				$this -> path = isset( $params["path"] ) ? $params["path"] : "../uploadfiles/images/";
				$this -> name = isset( $params["name"] ) ? preg_replace( "/\.".$this -> format."$/", "", $params["name"] ) : strtotime("now")."-".rand( 1000000000, 9999999999 );
				$this -> quality = isset( $params["quality"] ) ? $params["quality"] : 100;
				$this -> size = isset( $params["size"] ) ? $params["size"] : "proportion";
				$this -> watermark = isset( $params["watermark"] ) ? $params["watermark"] : "";
				$this -> prefix = isset( $params["prefix"] ) ? $params["prefix"] : "";

				if( !is_dir( $this -> path ) ) {
					if( mkdir( $this -> path, "0777", true ) ) {
						echo "<div class='statusOk'>���������� '".$this -> path."' ���� �������</div>";
					} else {
						echo "<div class='statusCancel'>������ �������� ���������� '".$this -> path."'</div>";
					}
				}

				switch( $this -> size ) {
					case "proportion":
						return $this -> saveProportion();
						break;

					case "fixed":
						return $this -> saveFixed();
						break;

					case "crop":
						return $this -> saveCrop();
						break;

					case "noresize":
						return $this -> saveNoresize();
						break;

					case "square" :
						return $this -> saveSquare();
						break;

					default :
						return $this -> image = 0;
				}
			}


		}


		// ��������� ��������������� ��������
		function saveProportion() {
			list($width,$height)=getimagesize( $_FILES[ $this -> filename ][ "tmp_name" ] );
			if( $width >= $height ) {
				$this -> height = $height / $width * $this -> width;
			} else {
				$this -> width = $width / $height * $this -> height;
			}
			$image=imagecreatetruecolor($this -> width, $this -> height);
			imagecopyresampled( $image, $this -> image, 0, 0, 0, 0, $this -> width, $this -> height, $width, $height );

			$this -> setWatermark( $image );


			try {
				switch ( $this -> format ) {
	
					case "jpg" :
						if( !imagejpeg( $image, $this -> path.$this -> prefix.$this -> name.".".$this -> format, $this -> quality ) ) {
							throw new Exception("������ ���������� *.jpg �����������");
						}
						break;
	
					case "gif" :
						if( !imagegif( $image, $this -> path.$this -> prefix.$this -> name.".".$this -> format, $this -> quality ) ) {
							throw new Exception("������ ���������� *.gif �����������");
						}
						break;
	
					case "png" :
						if( !imagepng( $image, $this -> path.$this -> prefix.$this -> name.".".$this -> format, $this -> quality ) ) {
							throw new Exception("������ ���������� *.png �����������");
						}
						break;
	
				}

				return $this -> prefix.$this -> name.".".$this -> format;

			} catch( Exception $error ) {
				return 0;
				echo "<div class='statusCancel'>������ ������ IMAGE: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
		}


		function saveFixed() {
			list($width,$height)=getimagesize( $_FILES[ $this -> filename ][ "tmp_name" ] );
			if( $width >= $height ) {
				//$this -> height = $height / $width * $this -> width;
			} else {
				//$this -> width = $width / $height * $this -> height;
			}
			$image=imagecreatetruecolor($this -> width, $this -> height);
			imagecopyresampled( $image, $this -> image, 0, 0, 0, 0, $this -> width, $this -> height, $width, $height );

			$this -> setWatermark( $image );


			try {
				switch ( $this -> format ) {
	
					case "jpg" :
						if( !imagejpeg( $image, $this -> path.$this -> prefix.$this -> name.".".$this -> format, $this -> quality ) ) {
							throw new Exception("������ ���������� *.jpg �����������");
						}
						break;
	
					case "gif" :
						if( !imagegif( $image, $this -> path.$this -> prefix.$this -> name.".".$this -> format, $this -> quality ) ) {
							throw new Exception("������ ���������� *.gif �����������");
						}
						break;
	
					case "png" :
						if( !imagepng( $image, $this -> path.$this -> prefix.$this -> name.".".$this -> format, $this -> quality ) ) {
							throw new Exception("������ ���������� *.png �����������");
						}
						break;
	
				}

				return $this -> prefix.$this -> name.".".$this -> format;

			} catch( Exception $error ) {
				return 0;
				echo "<div class='statusCancel'>������ ������ IMAGE: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
		}


		// ��������� ������� �����������
		function saveCrop() {
			list($width,$height)=getimagesize( $_FILES[ $this -> filename ][ "tmp_name" ] );
			$image=imagecreatetruecolor($this -> width, $this -> height);
			imagecopyresampled( $image, $this -> image, 0, 0, ( $width - $this -> width ) / 2, ( $height - $this -> height ) / 2, $this -> width, $this -> height, $this -> width, $this -> height );

			$this -> setWatermark( $image );


			try {
				switch ( $this -> format ) {
	
					case "jpg" :
						if( !imagejpeg( $image, $this -> path.$this -> prefix.$this -> name.".".$this -> format, $this -> quality ) ) {
							throw new Exception("������ ���������� *.jpg �����������");
						}
						break;
	
					case "gif" :
						if( !imagegif( $image, $this -> path.$this -> prefix.$this -> name.".".$this -> format, $this -> quality ) ) {
							throw new Exception("������ ���������� *.gif �����������");
						}
						break;
	
					case "png" :
						if( !imagepng( $image, $this -> path.$this -> prefix.$this -> name.".".$this -> format, $this -> quality ) ) {
							throw new Exception("������ ���������� *.png �����������");
						}
						break;
	
				}

				return $this -> prefix.$this -> name.".".$this -> format;

			} catch( Exception $error ) {
				return 0;
				echo "<div class='statusCancel'>������ ������ IMAGE: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
		}


		// ��������� ��� ��������� �������� ���������
		function saveNoresize() {
			list($width,$height)=getimagesize( $_FILES[ $this -> filename ][ "tmp_name" ] );
			$this -> height = $height;
			$this -> width = $width;

			$image=imagecreatetruecolor($this -> width, $this -> height);
			imagecopyresampled( $image, $this -> image, 0, 0, 0, 0, $this -> width, $this -> height, $width, $height );

			$this -> setWatermark( $image );


			try {
				switch ( $this -> format ) {
	
					case "jpg" :
						if( !imagejpeg( $image, $this -> path.$this -> prefix.$this -> name.".".$this -> format, $this -> quality ) ) {
							throw new Exception("������ ���������� *.jpg �����������");
						}
						break;
	
					case "gif" :
						if( !imagegif( $image, $this -> path.$this -> prefix.$this -> name.".".$this -> format, $this -> quality ) ) {
							throw new Exception("������ ���������� *.gif �����������");
						}
						break;
	
					case "png" :
						if( !imagepng( $image, $this -> path.$this -> prefix.$this -> name.".".$this -> format, $this -> quality ) ) {
							throw new Exception("������ ���������� *.png �����������");
						}
						break;
	
				}

				return $this -> prefix.$this -> name.".".$this -> format;

			} catch( Exception $error ) {
				return 0;
				echo "<div class='statusCancel'>������ ������ IMAGE: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
		}


		// ��������� �������� ��������� �� ������� �������
		function saveSquare() {
			list($width,$height)=getimagesize( $_FILES[ $this -> filename ][ "tmp_name" ] );
			if( $width >= $height ) {
				$this -> width = $this -> height;
				$this -> width2 = $height;
				$this -> height2 = $height;
			} else {
				$this -> height = $this -> width;
				$this -> width2 = $width;
				$this -> height2 = $width;
			}

			$image=imagecreatetruecolor($this -> width, $this -> height);
			imagecopyresampled( $image, $this -> image, 0, 0, ( $width - $this -> width2 ) / 2, ( $height - $this -> height2 ) / 2, $this -> width, $this -> height, $this -> width2, $this -> height2 );

			$this -> setWatermark( $image );


			try {
				switch ( $this -> format ) {
	
					case "jpg" :
						if( !imagejpeg( $image, $this -> path.$this -> prefix.$this -> name.".".$this -> format, $this -> quality ) ) {
							throw new Exception("������ ���������� *.jpg �����������");
						}
						break;
	
					case "gif" :
						if( !imagegif( $image, $this -> path.$this -> prefix.$this -> name.".".$this -> format, $this -> quality ) ) {
							throw new Exception("������ ���������� *.gif �����������");
						}
						break;
	
					case "png" :
						if( !imagepng( $image, $this -> path.$this -> prefix.$this -> name.".".$this -> format, $this -> quality ) ) {
							throw new Exception("������ ���������� *.png �����������");
						}
						break;
	
				}

				return $this -> prefix.$this -> name.".".$this -> format;

			} catch( Exception $error ) {
				return 0;
				echo "<div class='statusCancel'>������ ������ IMAGE: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
			}
		}


		function setWatermark( $image ) {


			if( !empty( $this -> watermark ) ) {
				try {
					if( !file_exists( $this -> watermark ) ) {
						throw new Exception("������� �������� �� ������������ ���� ����������� �������� �����");
					}

					$watermark_fileinfo = getimagesize( $this -> watermark );

					switch( strtolower( $watermark_fileinfo['mime'] ) ) {
						case "image/jpeg":
							if( !$watermark_image = imagecreatefromjpeg( $this -> watermark ) ) {
								throw new Exception("������� �������� *.jpeg ����������� �������� �����");
							}
							break;	
		
						case "image/gif":
							if( !$watermark_image = imagecreatefromgif( $this -> watermark ) ) {
								throw new Exception("������� �������� *.jpeg ����������� �������� �����");
							}
							break;	
		
						case "image/png":
							if( !$watermark_image = imagecreatefrompng( $this -> watermark ) ) {
								throw new Exception("������� �������� *.jpeg ����������� �������� �����");
							}
							break;	
		
						default:
							throw new Exception("������� �������� �� ���������� ������ ����������� �������� ����� (����������� *.jpg, *.jpeg, *.png, *.gif)");
					}

					imagecopy( $image, $watermark_image, ( $this -> width - $watermark_fileinfo[0] ) - 10, ( $this -> height - $watermark_fileinfo[1] ) - 10, 0, 0, $watermark_fileinfo[0], $watermark_fileinfo[1] );


				} catch( Exception $error ) {
					echo "<div class='statusCancel'>������ ������ IMAGE: ������ � ����� <b>".$error -> getFile()."</b> � ".$error -> getLine()." ������<br />".$error -> getMessage()."</div>";
				}
			}


		}



		// ����������
		function __destruct() {
		}


	}
?>