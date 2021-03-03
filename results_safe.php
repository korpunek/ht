<?php

	session_start();
	
	$inumer = $_SESSION["numer"];
	$inazwa = $_SESSION["nazwa"];

	if ($inazwa == "" or $inumer == "") {header("Location: errror.htm");}

	include('obj_config.php');

	$iklucz = $_REQUEST['typ'];

	$ibadanie = $_POST["badanie"];

	$idata = dateChange($_POST["data"]);

	//	$iwynik = $_POST["wartosc1"];
	
	$dbObj->mongohost = $_SESSION["mongohost"];
	$dbObj->mongoport = $_SESSION["mongoport"];
	$dbObj->mongouser = $_SESSION["mongouser"];
	$dbObj->mongocolltypy = $_SESSION["mongocolltypy"];		
	$dbObj->mongocollbaza = $_SESSION["mongocollbaza"];	

	$connection1 = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");

	//	$conn = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");
	$filter = ['badanie' => $iklucz];
	$option = [];
	$read = new MongoDB\Driver\Query($filter, $option);
	$result1 = $connection1->executeQuery("$dbObj->mongouser.$dbObj->mongocolltypy", $read);

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

	
	//'badanie' => 'Ciśnienie tętnicze','data' => '2020-04-22 20:54:00','SYS' => (object)array('wynik' => (integer)88, 'jednostka' => (string)mmHg, 'referencja' => (integer)80, 'min' => (integer)50, 'max' => (integer)150,), 'DIA' => (object)array('wynik' => (integer)88, 'jednostka' => (string)mmHg, 'referencja' => (integer)120, 'min' => (integer)60, 'max' => (integer)220,), 

	$connection = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");

	$bulk = new MongoDB\Driver\BulkWrite;
	$bulk->insert($buf);

//		'badanie' => $ibadanie,
//		'data' => $idata,
//		'wartosc' => (object)array('wynik' => (int)$iwynik, 'min' => 0)]);

	$result = $connection->executeBulkWrite("$dbObj->mongouser.$dbObj->mongocollbaza", $bulk);

   
	if($result->getInsertedCount())
	{
		$flag = "Zapisano";
	}
	else
	{
		$flag = "Błąd";
		header("Location: error.php?error=" . $flag );
	}

	header("Location: results_new.php");



?>
