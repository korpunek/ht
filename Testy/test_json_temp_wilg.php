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
	$dbObj->mongoname = $_SESSION["mongoname"];
	$dbObj->mongocoll = $_SESSION["mongocoll"];	
	
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

	<br><br><br>

	<div class="row">

		<div class="col-12 col-lg-6">

			<div class="card bg-info">
				<div class="card-header">
					<div class="row">
						<div class="col col-3 text-white">						
							TEST
						</div>
						<div class="col text-right vtext"></div>
					</div>	
				</div>
				<div class="card-body bg-white text-black">
					<div id="lista_rodzaj">

<?php

	// echo "* początek *<br><br>";

	/*	
	$connection1 = new MongoDB\Driver\Manager("mongodb://$dbObj1->mongohost:$dbObj1->mongoport");
	$filter = ['badanie' => $iklucz];
	$option = [];
	$read = new MongoDB\Driver\Query($filter, $option);
	$result1 = $connection1->executeQuery("$dbObj1->mongoname.$dbObj1->mongocoll1", $read);

	$buf = [];

	foreach ($result1 as $dokument)
	{

		$licz = 0;

		foreach( $dokument as $klucz => $wartosc)
		{
			switch ($klucz)
			{
				case "_id":
					break;
				case "badanie":
					$buf[$klucz] = $ibadanie;
					break;
				case "data":
					$buf[$klucz] = $idata;					
					break;
				default:

					$licz++;
					$tab = [];

					foreach($wartosc as $obj => $wynik)
					{
						if($obj != "oid")
						{
							if($obj == "wynik")
							{
								$wyn = $_POST["wartosc$licz"];
								settype($wyn, gettype($wynik));
								$tab[$obj] = $wyn; 
							}
							else
							{
								$tab[$obj] = $wynik;
							}
						}
					}

					$buf[$klucz] = $tab;
					break;
			}

		}
	}

	*/


/*

	$tabb = json_decode(file_get_contents('POGODA.JSON'));

	echo "* początek wczytywania pogody *<br><br>";

	$licz = 0;

	foreach ($tabb as $dane)
	{

		$connection = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");

		$bulk = new MongoDB\Driver\BulkWrite;

		
		$buf = [];
		$buf['badanie'] = 'Pogoda';
		$buf['data'] = $dane->date . " 12:00:00";
	
		$tab = [];
		$tab['wynik'] = (double)$dane->{"Daily Mean Station Level Pressure"};
		$tab['jednostka'] = "hPa";
		$tab['referencja'] = (double)1000;
		$tab['min'] = (double)800;
		$tab['max'] = (double)1100;
	
		$buf['Ciśnienie atmosferyczne'] = $tab;

		$tab = [];
		$tab['wynik'] = (double)$dane->{"Daily Mean Temperature"};
		$tab['jednostka'] = "St. Celcjusza";
		$tab['referencja'] = (double)20;
		$tab['min'] = (double)-75;
		$tab['max'] = (double)75;
			
		$buf['Temperatura powietrza'] = $tab;

		$tab = [];
		$tab['wynik'] = (double)$dane->{"Daily Mean Relative Humidity"};
		$tab['jednostka'] = "%";
		$tab['referencja'] = (double)60;
		$tab['min'] = (double)0;
		$tab['max'] = (double)100;
			
		$buf['Wilgotność powietrza'] = $tab;

		$licz++;

//		if($licz > 3){break;}

		$bulk->insert($buf);

		$result = $connection->executeBulkWrite("$dbObj->mongoname.$dbObj->mongocoll", $bulk);
	}


	echo "* koniec wczytywania pogody, wczytano - " . $licz . " obiektów<br><br>";
   
	if($result->getInsertedCount())
	{
		$flag = "Zapisano";
	}
	else
	{
		$flag = "Błąd";
		header("Location: error.php?error=" . $flag );
	}

//	header("Location: results_list.php");


*/


/*

$tabb = json_decode(file_get_contents('POGODA.JSON'));
echo $tabb . '<br><br>';
//var_dump($tabb);

	foreach ($tabb as $dane)
	{
		echo $dane->date . ' - ' . $dane->{"Daily Mean Station Level Pressure"} . ' - ' .$dane->{"Daily Mean Temperature"} . ' - ' .$dane->{"Daily Mean Relative Humidity"} .  '<br><br>';
	}

*/


?>





</div>


<br><br>


</BODY>

</HTML>
