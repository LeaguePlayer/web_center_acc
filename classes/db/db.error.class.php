<?php
	/* расширенный класс соединения DBERROR для работы при отсутствии подходящего драйвера */


	class DBERROR extends DBConnection {


		function connect() {
			echo "<div class='statusCancel'>ОШИБКА КЛАССА DB: Ошибочный драйвер соединения с базой данных</div>";
		}


	}
?>