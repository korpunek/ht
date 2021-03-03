<?php

    session_start();

    $dhost = $_SESSION["dbhost"];
    $dbaza = $_SESSION["dbbaza"];
    $dnazwa = $_SESSION["dbnazwa"];
    $dhaslo = $_SESSION["dbhaslo"];

    $q = $_GET['q'];

	$ile = 0;
	$kolor = "";

	$tabela = 'opcje';
	$order = 'NAZWA';
	$zakres = "*";

    $db = mysqli_connect($dhost, $dnazwa, $dhaslo, $dbaza);
    // mysql_select_db($dbaza, $db);

    $sql = "SELECT " . $zakres . " FROM " . $tabela . " WHERE LP = " . $q;
  	
    $res = mysqli_query($db, $sql);  



    if( $row = mysqli_fetch_assoc($res))
    {	
        echo "<br><div class='col-12'><h3><b> " . $row["NAZWA"] . " [" . $row["LP"] . "]" . "</b></h3></div>";

        echo '<FORM NAME="forma" METHOD="POST" ACTION="opcje_set.php?nrekord=' . $row["LP"] . '" class="form-horizontal">';

        echo    '<div class="row form-group">';
        echo        '<label for="inputString" class="col-12 col-lg-2 control-label">String</label>';   
        echo        '<div class="col-12 col-lg-9">';
        echo            '<input type="text" class="form-control" name="pstring" id="inputString" placeholder="" VALUE="' . $row["STRING"] . '" autofocus>';
        echo        '</div>';
        echo    '</div>';	
            
        echo    '<div class="row form-group">';
        echo        '<label for="inputLiczba" class="col-12 col-lg-2 control-label">Liczba</label>';         
        echo        '<div class="col-12 col-lg-9">';
        echo        '<input type="text" class="form-control" name="pliczba" id="inputLiczba" placeholder="" VALUE="' . $row["LICZBA"] . '">';
        echo        '</div>';
        echo    '</div>';	

        echo    '<div class="row form-group">';
        echo        '<div class="col-12">';
        echo            '<button type="submit" class="btn btn-warning">Zapisz opcję</button>';
        echo        '</div>';
        echo    '</div>';



        echo '</FORM>';



		$ile++;
	}

    mysqli_close($db);

	
if($ile == 0){echo "<center><br><div>Brak danych do wyświetlenia.</div>";}

echo "<br>";
	
?>