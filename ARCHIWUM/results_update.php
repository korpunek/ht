<?php

	session_start();
	
	$inumer = $_SESSION["numer"];
	$inazwa = $_SESSION["nazwa"];

	if ($inazwa == "" or $inumer == "") {header("Location: errror.htm");}

	$iid =  $_POST["id"];
	$idata =$_POST["date"];
	$iilekluczy = $_POST["ilekluczy"];

	for($j=0; $j<$iilekluczy; $j++)
	{
		$ityp[$j] = $_POST["typ$j"];
		$iwynik[$j] = $_POST["wynik$j"];
	}

echo $iid . "<BR>";
echo $ityp[1] . "<BR>";
echo $iwynik[1] . "<BR>";
echo $idata . "<BR>";

	$dbObj->mongohost = $_SESSION["mongohost"];
	$dbObj->mongoport = $_SESSION["mongoport"];
	$dbObj->mongoname = $_SESSION["mongoname"];
	$dbObj->mongocoll = $_SESSION["mongocoll"];	


	$connection = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");
	$bulk = new MongoDB\Driver\BulkWrite;
//	$bulk->insert(['data' => $idata, $ityp => $iwynik]);
//	$bulk->insert(['data' => $idata, 'ciśnienie' => array('skurczowe' => '231', 'rozkurczowe' => '99')]);
//	$bulk->insert(['data' => $idata, 'ciśnienie' => array(777, 432)]);

//  $bulk->update(['_id'=>new MongoDB\BSON\ObjectID($iid)], ['$set' =>['data' => $idata, $ityp => $iwynik]], ['multi' => false, 'upsert' => false]);

	$bulk->update(['_id'=>new MongoDB\BSON\ObjectID($iid)], ['$set' =>['data' => $idata ]], ['multi' => false, 'upsert' => false]);


	//$result = $connection->executeBulkWrite("$dbObj->mongoname.$dbObj->mongocoll", $bulk);

	for($i=0; $i<$iilekluczy; $i++)
	{
		$bulk->update(['_id'=>new MongoDB\BSON\ObjectID($iid)], ['$set' =>[$ityp[$i] => $iwynik[$i]]], ['multi' => false, 'upsert' => false]);

//		$result = $connection->executeBulkWrite("$dbObj->mongoname.$dbObj->mongocoll", $bulk);
	}



	$result = $connection->executeBulkWrite("$dbObj->mongoname.$dbObj->mongocoll", $bulk);
 
	if($result->getInsertedCount())
	{
	 $flag = 3;
	}
	else
	{
	 $flag = 2;
	}

	header("Location: error.php?error=" . $flag );

	 //	 header("Location: results_list.php");


?>
