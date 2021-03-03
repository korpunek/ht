<?php

    session_start();

    $inumer = $_SESSION["numer"];
    $inazwa = $_SESSION["nazwa"];

    if ($inazwa == "" or $inumer == "") {header("Location: blad.htm");}


    $dbObj->mongohost = $_SESSION["mongohost"];
	$dbObj->mongoport = $_SESSION["mongoport"];
	$dbObj->mongoadmin = $_SESSION["mongoadmin"];

	$dbObj->mongocolllang = $_SESSION["mongocolllang"];
	$dbObj->mongocolllogi = $_SESSION["mongocolllogi"];
    $dbObj->mongocollmess = $_SESSION["mongocollmess"];

    $prekord = $_REQUEST['rekord'];

    $connection = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");	
	$bulk = new MongoDB\Driver\BulkWrite;	 	
	$bulk->update(['_id'=>new MongoDB\BSON\ObjectID($prekord)], ['$set' => ['status' => '1']], ['multi' => false, 'upsert' => false]);	
	$result = $connection->executeBulkWrite("$dbObj->mongoadmin.$dbObj->mongocollmess", $bulk);	

	if($result->getModifiedCount())
	{
        $flag = str_repeat("<br>", 10); 
	}
	else
	{
		$flag = "Błąd zapisu w bazie komunikatów !";
	}

    echo $flag;

?>
