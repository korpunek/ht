<?php

	session_start();
	
	$inumer = $_SESSION["numer"];
	$inazwa = $_SESSION["nazwa"];

	if ($inazwa == "" or $inumer == "") {header("Location: errror.htm");}

	$iid = $_REQUEST['id'];
	$ibadanie = $_POST["badanie"];
	$idata =$_POST["data"];

	$dbObj->mongohost = $_SESSION["mongohost"];
	$dbObj->mongoport = $_SESSION["mongoport"];
	$dbObj->mongouser = $_SESSION["mongouser"];
	$dbObj->mongocollbaza = $_SESSION["mongocollbaza"];
	$dbObj->mongocolltypy = $_SESSION["mongocolltypy"];	
	$dbObj->mongoadmin = $_SESSION["mongoadmin"];
	$dbObj->mongocolllang = $_SESSION["mongocolllang"];

	
	// TABLICA ze schematami - pobranie schematu, utworzenie obiektu $buf

	$connection1 = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");
	$filter = ['badanie' => $ibadanie];
	$option = [];
	$read1 = new MongoDB\Driver\Query($filter, $option);
	$result1 = $connection1->executeQuery("$dbObj->mongouser.$dbObj->mongocolltypy", $read1);

	$buf = [];
 
	foreach ($result1 as $dokument)
	{
 
	 	$licz = 0;
 
	 	foreach( $dokument as $klucz => $wartosc)
	 	{
			switch ($klucz)
			{
				case "_id":
					//$buf[$klucz] = $iid;
					break;
				case "badanie":
					//$buf[$klucz] = $ibadanie;
					break;
				case "data":
					$buf[$klucz] = $idata;					
					//$bulk->update(['_id'=>new MongoDB\BSON\ObjectID($iid)], ['$set' =>['data' => $idata ]], ['multi' => false, 'upsert' => false]);
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
								//$bulk->update(['_id'=>new MongoDB\BSON\ObjectID($iid)], ['$set' =>[$ityp[$i] => $iwynik[$i]]], ['multi' => false, 'upsert' => false]);
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


	// TABLICA DOCELOWA - zmiana wpisu
	
	$connection = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");	
	$bulk = new MongoDB\Driver\BulkWrite;	 	
	$bulk->update(['_id'=>new MongoDB\BSON\ObjectID($iid)], ['$set' => $buf], ['multi' => false, 'upsert' => false]);	
	$result = $connection->executeBulkWrite("$dbObj->mongouser.$dbObj->mongocollbaza", $bulk);	

	if($result->getModifiedCount())
	{
		$flag = "Zapisano";
	}
	else
	{
		$flag = "Błąd";
		header("Location: error.php?error=" . $flag );
	}

	header("Location: results_list.php");


?>

