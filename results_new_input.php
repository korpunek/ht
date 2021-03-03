
<?php

        session_start();

        $inumer = $_SESSION["numer"];
        $inazwa = $_SESSION["nazwa"];
        $ilang = $_SESSION["lang"];
                
        if ($inazwa == "" or $inumer == "") {header("Location: error.htm");}

        $dbObj->mongohost = $_SESSION["mongohost"];
        $dbObj->mongoport = $_SESSION["mongoport"];
	$dbObj->mongouser = $_SESSION["mongouser"];
	$dbObj->mongocolltypy = $_SESSION["mongocolltypy"];		

        include('obj_config.php');	
                
        $iklucz = $_REQUEST['typ'];

        //       $idata = date('Y-m-d H:i:s');

        $idata = date('Y-m-d') . 'T' . date('H:i');

        echo '<div class="card text-white bg-info">';
        echo    '<div class="card-header">';
        echo         '<div class="row">';
        echo              '<div class="col-12">';					
        echo                   '<center><h3>' . $iklucz . '</h3></center>';
        echo              '</div>';
        echo         '</div>';	
        echo    '</div>';
        echo    '<div class="card-body">';
        echo         '<div id="nowe badanie">';


        echo '<FORM NAME="forma" METHOD="POST" ACTION="results_safe.php?typ=' . $iklucz . '" class="form-horizontal">';


        $conn = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");
        $filter = ['badanie' => $iklucz];
        $option = [];
        $read = new MongoDB\Driver\Query($filter, $option);
        $result = $conn->executeQuery("$dbObj->mongouser.$dbObj->mongocolltypy", $read);

        $licz = 1;

        foreach ($result as $dokument)
        {
                foreach( $dokument as $klucz => $wartosc)
                {
                        switch ($klucz)
                        {
                                case "_id":
                                        break;

                                case "badanie":
                                        echo "<b>" . $klucz . "</b> ";
                                        echo "<input type='text' class='form-control' name='badanie' id='badanie' placeholder='' VALUE='" . $wartosc . "' SIZE='10' readonly><br>";
                                        break;
 
                                case "data":
                                        echo "<b>" . $klucz . "</b> ";
 //                                       echo "<input type='datetime-local' class='form-control' name='data' id='data' placeholder='' VALUE='2020-04-03T12:34' SIZE='10' min='1000-01-01' max='3000-12-31' required pattern='\d{4}-\d{2}-\d{2}'>";
                                        echo "<input type='datetime-local' class='form-control' name='data' id='data' placeholder='' VALUE= '" . $idata . "' SIZE='10' min='1000-01-01' max='3000-12-31' required pattern='\d{4}-\d{2}-\d{2}'>";
 
                                        echo "Możesz zmienić datę badania<br><br>";

                                        break;

                                default:
                                        echo "<b>" . $klucz . "</b> ";
                                        echo "<input type='text' class='form-control' name='wartosc" . $licz . "' id='wartosc" . $licz . "' placeholder='' VALUE='' SIZE='10'>";
                                        $licz++;

                                        $buf = "";

                                        foreach($wartosc as $obj => $wynik)
                                        {
                                                if($obj != "oid" and $obj != "wynik")
                                                {
                                                        $buf .= $obj . " " . $wynik . "&nbsp;&nbsp;&nbsp;";
                                                }
                                        }
                                        
                                        echo $buf . "<br><br>";

                                        break;
                        }

                }
        }

        echo "<br>";

        echo    '<div class="row form-group">';
        echo        '<div class="col-12">';
        echo            '<center><button type="submit" class="btn btn-warning">Dodaj badanie</button></center>';
        echo        '</div>';
        echo    '</div>';

        echo '</FORM>';

        echo         '</div>';
        echo    '</div>';
        echo '</div>';
        
/*

        for($licz=1;$licz<10;$licz++)
        {

                        if($licz>1)
                        {
                                echo '<div class="row mb-3 ml-1 mr-1 bg-light" id="towary_l' . $licz . '" style="display:none;";>';
                        }
                        else
                        {
                                echo '<div class="row mb-3 ml-1 mr-1 bg-light" id="towary_l' . $licz . '" style="display:\'\';";>';
                        }		

                        echo '<div class="px-0 col-12 col-lg-4"><small id="ns" class="form-text text-muted text-left">&nbsp;<b>' . $licz . '.</b>&nbsp;&nbsp;towar / usługa</small><input type="text" class="form-control" name="towar_' . $licz . '" id="towar_l' . $licz . '" placeholder="" VALUE="" SIZE="80"></div>';
                        echo '<div class="px-0 col-2 col-lg-1 d-none d-md-block"><small id="ns" class="form-text text-muted text-center">sww</small><input type="text" class="form-control text-center" name="sww_' . $licz . '" id="sww_l' . $licz . '" placeholder="" VALUE="" SIZE="5"></div>';
                        echo '<div class="px-0 col-3 col-lg-1"><small id="ns" class="form-text text-muted text-right">ilość</small><input type="text" class="form-control text-right" name="ilosc_' . $licz . '" id="ilosc_l' . $licz . '" placeholder="" VALUE="1" SIZE="5" OnChange="wylicz()"></div>';
                        echo '<div class="px-0 col-2 col-lg-1 d-none d-md-block"><small id="ns" class="form-text text-muted text-center">jm</small><input type="text" class="form-control text-center" name="jm_' . $licz . '" id="jm_l' . $licz . '" placeholder="" VALUE="szt." SIZE="5"></div>';
                        echo '<div class="px-0 col-4 col-lg-2"><small id="ns" class="form-text text-muted text-right">netto</small><input type="text" class="form-control text-right" name="netto_' . $licz . '" id="netto_l' . $licz . '" placeholder="" VALUE="0.0" SIZE="12" OnChange="wylicz()"></div>';
                        echo '<div class="px-0 col-4 col-lg-2"><small id="ns" class="form-text text-muted text-right">vat</small><input type="text" class="form-control text-right" name="vat_' . $licz . '" id="vat_l' . $licz . '" placeholder="" VALUE="23" SIZE="2" OnChange="wylicz()"></div>';
                        echo '<div class="px-0 col-1 col-lg-1 text-center"><small id="ns" class="form-text text-muted text-center">&nbsp;</small><button type="button" class="btn btn-outline-danger" OnClick="ukryte(' . $licz . ')">-</button></div>';
                
                        echo '</div>';

        }
*/        



?>