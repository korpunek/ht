<?php

	session_start();
	
	$inumer = $_SESSION["numer"];
	$inazwa = $_SESSION["nazwa"];
	$ifirma = $_SESSION["firma"];
	$iemail = $_SESSION["email"];
  
    $dhost = $_SESSION["dbhost"];
	$dbaza = $_SESSION["dbbaza"];
	$dnazwa = $_SESSION["dbnazwa"];
    $dhaslo = $_SESSION["dbhaslo"];
    
	if ($inazwa == "" or $inumer == "") {header("Location: blad.htm");}

    function str_clear($tekst)
    {
        $wynik = str_replace('"', '',str_replace("'", "", $tekst));
        return $wynik;
    }

	$onazwa = $_POST['pnazwa'];	
	$ostring = $_POST['pstring'];	
    $oliczba = (int)$_POST['pliczba'];

	if( strlen($ostring) < 1 ){ $ostring = "*"; }
    if( strlen($onazwa) < 1 ){ $onazwa = "*"; }

    $db = mysqli_connect($dhost, $dnazwa, $dhaslo, $dbaza);
    // mysql_select_db($dbaza, $db);


    $sql = "INSERT INTO opcje SET ";
    $sql .= "NAZWA = " . "'" . str_clear($onazwa) . "',";
    $sql .= "STRING = " . "'" . str_clear($ostring) . "',";
    $sql .= "LICZBA = " . $oliczba;

    if( ! mysqli_query($db, $sql))
    {
        $ERR_NO = mysql_errno();
        $ERR_OP = mysql_error();
        mysqli_close($db);
        header("Location: blad.php?blad=" . "BŁĄD ZAPISU DO BAZY ZGŁOSZEŃ ! <br>" . $sql . '<br>NR BŁĘDU : ' . $ERR_NO . '<br>OPIS BŁĘDU : ' . $ERR_OP);
    }
    else
    {
        mysqli_close($db);

        header("Location: opcje.php");
   
    }  

?>