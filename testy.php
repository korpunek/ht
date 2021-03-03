<?php
	session_start();

	$inumer = $_SESSION["numer"];
	$inazwa = $_SESSION["nazwa"];
	$ilang = $_SESSION["lang"];
	
	$dbObj->host = $_SESSION["dbhost"];
	$dbObj->baza = $_SESSION["dbbaza"];
	$dbObj->nazwa = $_SESSION["dbnazwa"];
	$dbObj->haslo = $_SESSION["dbhaslo"];

	$dbObj->mongohost = $_SESSION["mongohost"];
	$dbObj->mongoport = $_SESSION["mongoport"];
	$dbObj->mongouser = $_SESSION["mongouser"];
	$dbObj->mongocollbaza = $_SESSION["mongocollbaza"];
	$dbObj->mongocolltypy = $_SESSION["mongocolltypy"];	
	$dbObj->mongoadmin = $_SESSION["mongoadmin"];
	$dbObj->mongocolllang = $_SESSION["mongocolllang"];		

	
	if ($inazwa == "" or $inumer == "") {header("Location: blad.htm");}

	include('obj_config.php');	
	include('obj_crypt.php');
	include('head.php');

	function alert($msg)
	{
		echo "<script type='text/javascript'>alert('$msg');</script>";
	}

?>

    <script>

		function start()
		{
			$('#pliktab a').on('click', function (e)
				{
					e.preventDefault()
					$(this).tab('show')
				}
			)	
		}

	</script>

</HEAD>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginwidth="0" marginheight="0" onLoad="start()">

<div class="container">

	<?php
		$pozycja = 4;
		include('nav.php');
	?>

	<br><br><br><br><br>


	<div class="row">

		<div class="col-12 col-lg-6">

			<div class="row">

				<a href="Testy/test_xml.php" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true">Wczytanie pliku XML</a>

			</div>

			<br><br>

			<div class="row">

				<a href="Testy/test_pdf.php" class="btn btn-success btn-lg active" role="button" aria-pressed="true">Generowanie pliku PHP</a>

			</div>

			<br><br>

			<div class="row">

				<a href="Testy/test_json_cisn_atm.php" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Wczytanie JSON - ciśnienie atm.</a>

			</div>
			
			<br><br>

			<div class="row">

				<a href="Testy/test_json_temp_wilg.php" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Wczytanie JSON - temp. / wilg.</a>

			</div>
			
			<br><br>

			<div class="row">

				<a href="Testy/czytaj.php" class="btn btn-warning btn-lg active" role="button" aria-pressed="true">Wczytanie rekordu</a>

			</div>
			
			<br><br>


		</div>


		<div class="col-12 col-lg-6">

			<div class="row">

				<a href="Testy/info.php" class="btn btn-warning btn-lg active" role="button" aria-pressed="true">Info</a>

			</div>
			
			<br><br>

			<div class="row">

				<a href="Testy/test_szyfrowanie.php" class="btn btn-info btn-lg active" role="button" aria-pressed="true">Szyfrowanie</a>

			</div>

			<br><br>

			<div class="row">

				<a href="Testy/test_reszyfr.php" class="btn btn-danger btn-lg active" role="button" aria-pressed="true">Reszyfrowanie</a>

			</div>

			<br><br>

			<div class="row">

				<a href="Testy/test_speech.html" class="btn btn-warning btn-lg active" role="button" aria-pressed="true">Speech</a>

			</div>

			<br><br>

		</div>

	</div>


</div>

</BODY>

</HTML>
