<?php

$iblad = 0;

include('obj_config.php');

// include('obj_crypt.php');

/*
$db = mysqli_connect($def_host, $def_nazwa, $def_haslo, $def_baza);
// mysql_select_db($def_baza, $db);

$sql = "SELECT * FROM users WHERE name='" . $_POST["name"] . "' AND active = 1";

$res = mysqli_query($db, $sql);

if (!$res)
{
	echo "ERROR " . mysql_errno();
	echo "<PRE>$sql</PRE>";
	echo mysql_error();
	exit;
}

*/


$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$filter  = ['nick' => $_POST["name"]];
$options = [];
$query = new \MongoDB\Driver\Query($filter, $options);
$rows = $mongo->executeQuery("$mongo_name_admin.$mongo_coll_users", $query);

echo "<br>3: ";

foreach ($rows as $document)
 {

	$inumer = $document->_id;
//	$inumer = 1;
	$inazwa = $document->nick;
	$ihaslo = $document->password;
	$iprawa = $document->prawa;
	$ibaza = $document->baza;
	$iaktywny = $document->aktywny;
	$iplan = $document->plan;
	$ilang = $document->language;

	$mongo_name_user = $ibaza;

//    $T2 = $document->SYS->wynik;
//    $T3 = $document->{'SYS'}->{'jednostka'};

}     

if ($inumer != "")
{
	if($iblokada > 3)
	{
		$iblad = 2;
	}
	else
	{

//		$tpass = hash('sha256', $_POST["pass"]);
//		$tkey = substr($tpass, 15, 32);



		if ($ihaslo == $_POST["pass"])
		{

			if($iaktywny == false)
			{
				$iblad = 4;
			}
			else
			{
				session_start();

				$_SESSION["dbhost"] = $def_host;
				$_SESSION["dbbaza"] = $def_baza;
				$_SESSION["dbnazwa"] = $def_nazwa;
				$_SESSION["dbhaslo"] = $def_haslo;

				$_SESSION["mongohost"] = $mongo_host;
				$_SESSION["mongoport"] = $mongo_port;
				$_SESSION["mongoadmin"] = $mongo_name_admin;
				$_SESSION["mongouser"] = $mongo_name_user;
				
				$_SESSION["mongocollusers"] = $mongo_coll_users;
				$_SESSION["mongocolllang"] = $mongo_coll_lang;
				$_SESSION["mongocollbaza"] = $mongo_coll_baza;
				$_SESSION["mongocolltypy"] = $mongo_coll_typy;
				$_SESSION["mongocolllogi"] = $mongo_coll_logi;
				$_SESSION["mongocollmess"] = $mongo_coll_mess;

				$_SESSION["tshark"] = $tkey;

				$_SESSION["numer"] = $inumer;
				$_SESSION["nazwa"] = $inazwa;			
				$_SESSION["prawa"] = $iprawa;
				$_SESSION["lang"] = $ilang;							

/*
				numkod = Session.SessionId & now()

				SQL = "INSERT INTO logi(KOD, USER, FIRMA, WEJSCIE, IP) "
				SQL = SQL & "VALUES ("
				SQL = SQL & "'" & numkod & "',"
				SQL = SQL & "'" & inazwa & "',"
				SQL = SQL & inumer & ","
				SQL = SQL & "'" & Now() & "',"
				SQL = SQL & "'" & Request.ServerVariables("REMOTE_ADDR") & "')"
				Conn.Execute(SQL)
*/
			}
		}	
		else
		{
			$iblad = 3;
		}

	}

}
else
{
	$iblad = 1;
}


mysqli_close($db);


If ($iblad > 0)
{
	$imessage = "ERROR NUMBER " . $iblad;

	header("Location: error.php?error={$imessage}");
}
else
{

		header("Location:ht_view.php");
	
}


?>
