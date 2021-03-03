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
        
	$qrekord = $_REQUEST['nrekord'];

	$istring = $_POST['pstring'];	
	$iliczba = (int)$_POST['pliczba'];

	if( strlen($istring) < 1 ){ $istring = "*"; }
 
    $db = mysqli_connect($dhost, $dnazwa, $dhaslo, $dbaza);
    // mysql_select_db($dbaza, $db);


    $sql = "UPDATE opcje SET ";
    $sql .= "STRING = " . "'" . str_clear($istring) . "',";
    $sql .= "LICZBA = " . $iliczba;
    $sql .= " WHERE LP = " . $qrekord;

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