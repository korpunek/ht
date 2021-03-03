<?php
	session_start();

	$inumer = $_SESSION["numer"];
	$inazwa = $_SESSION["nazwa"];
	$ilang = $_SESSION["lang"];
		
	if ($inazwa == "" or $inumer == "") {header("Location: error.htm");}

    $dbObj->mongohost = $_SESSION["mongohost"];
	$dbObj->mongoport = $_SESSION["mongoport"];
	$dbObj->mongouser = $_SESSION["mongouser"];
	$dbObj->mongocollbaza = $_SESSION["mongocollbaza"];
	$dbObj->mongocolltypy = $_SESSION["mongocolltypy"];	
	$dbObj->mongoadmin = $_SESSION["mongoadmin"];
	$dbObj->mongocolllang = $_SESSION["mongocolllang"];		

	$tadata =  substr($_REQUEST['data'],0,10);
	$firstdata = $tadata . ' 00:00:00';
	$lastdata = $tadata . ' 23:59:59';

	$tobadanie = $_REQUEST['badanie'];
?>


</HEAD>

<BODY text=#000000 bgColor=#ffc107 leftMargin="0" topMargin="0" marginwidth="0" marginheight="0">


<div class="container">

	<div class="row bg-warning">

		<div class="col-12 col-lg-12">

            <?php

				$ile = 0;

				$conn = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");


				$filter = ['data' => array( '$gt' => $firstdata, '$lt' => $lastdata), 'badanie' => ['$ne' => $tobadanie]];
				$option = [];
				$read = new MongoDB\Driver\Query($filter, $option);
				$result = $conn->executeQuery("$dbObj->mongouser.$dbObj->mongocollbaza", $read);


				foreach ($result as $dokument)
				{
					echo '<div class="card col-12 col-lg-12">';
					echo '<div class="card-body">';

					foreach( $dokument as $klucz => $wartosc)
					{
						if($klucz != "_id")
						{
							if( is_object($wartosc) )
							{
								echo "<b>" . $klucz . "</b> - ";
								foreach($wartosc as $obj => $wynik)
								{
									if($obj == 'wynik')
									{
										echo $obj . " " . $wynik . " ";
									}
									if($obj == 'jednostka')
									{
										echo $wynik . "<br>";
									}
								}
							}
							else
							{
								if( is_array($wartosc) )
								{
									echo "<b>" . $klucz . "</b><br>";
									for($i=0; $i<count($wartosc); $i++)
									{
										echo $i+1 . " : " . $wartosc[$i] . "<br>";	
									}
								}
								else
								{
									echo $klucz . " - " . $wartosc . "<br>";
								}
							}
						}
					}

					echo '</div>';
					echo '</div>';	

					echo "<br>";

					$ile++;
				}
				
				if($ile == 0){echo "<center><div>Brak danych do wyświetlenia.</div>";}else{echo "<center><div>Pozycji: " . $ile . "</div>";}

			?>

            </center>

        <div>

    </div>

</div>

</BODY>

</HTML>




