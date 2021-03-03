<?php
	session_start();

	$inumer = $_SESSION["numer"];
	$inazwa = $_SESSION["nazwa"];
	$ilang = $_SESSION["lang"];
		
	if ($inazwa == "" or $inumer == "") {header("Location: error.htm");}
	
	$dbObj->host = $_SESSION["dbhost"];
	$dbObj->baza = $_SESSION["dbbaza"];
	$dbObj->nazwa = $_SESSION["dbnazwa"];
	$dbObj->haslo = $_SESSION["dbhaslo"];
		
	$dbObj->mongohost = $_SESSION["mongohost"];
	$dbObj->mongoport = $_SESSION["mongoport"];
	$dbObj->mongoname = $_SESSION["mongoname"];
	$dbObj->mongocoll = $_SESSION["mongocoll"];		


	include('obj_config.php');	
	include('obj_crypt.php');
	include('head.php');


	function alert($msg)
	{
		echo "<script type='text/javascript'>alert('$msg');</script>";
	}


?>


    <script>


		function start()
		{
			$('#pliktab a').on('click', function (e)
				{
					e.preventDefault()
					$(this).tab('show')
				}
			)	
		}

	</script>



</HEAD>

<BODY text=#000000 bgColor=#d3d3d3 leftMargin="0" topMargin="0" marginwidth="0" marginheight="0" onLoad="start()">


<div class="container">

	<?php
		$pozycja = 2;
		include('nav.php');
	?>

	<br>

	<div class="row bg-light">

		<div class="col-12 col-lg-12">

			<br>

			<center>
					
			<p><h2>Dokument</h2></p><br>

			</center>


            <?php

				$ile = 0;


//                $id = new MongoDB\BSON\ObjectID("5ea2d3071cf1843b34002202");

/*
				$conn = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");


				$filter = ['_id'=>new MongoDB\BSON\ObjectID($id)];
				$option = [];
				$read = new MongoDB\Driver\Query($filter, $option);
//				$result = $conn->executeQuery("$dbObj->mongoname.$dbObj->mongocoll", $read);

//                $result = $colection->findone( array('_id' => $id ));

*/

$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$id           = new \MongoDB\BSON\ObjectId("5ea2d3071cf1843b34002202");
$filter      = ['_id' => $id];
$options = [];
$query = new \MongoDB\Driver\Query($filter, $options);
$rows = $mongo->executeQuery('badania.testy1', $query);

echo "<br>3: ";

foreach ($rows as $document)
 {
//    $T1 = $document->{'badanie'};
    $T1 = $document->badanie;

    $T2 = $document->SYS->wynik;

    $T3 = $document->{'SYS'}->{'jednostka'};


/*    
    foreach( $document as $klucz => $wartosc)
    {
        echo $wartosc;
    }    
*/

}              

if($T1 == "")
{
    echo "Puste";
}
else
{
    echo $T1;
    echo "<br>";
    echo $T2;
    echo "<br>";
    echo $T3;
}



/*
echo "<br><br>";
echo "T: " . var_dump($result);
echo "<br><br>";
*/

/*
				foreach ($result as $dokument)
				{
					echo '<div class="card col-12 col-lg-6">';
					echo '<div class="card-body">';

					foreach( $dokument as $klucz => $wartosc)
					{
						if($klucz != "_id")
						{
							if( is_object($wartosc) )
							{
								echo "<b>" . $klucz . "</b><br>";
								foreach($wartosc as $obj => $wynik)
								{
									echo "&nbsp;&nbsp;&nbsp;" . $obj . " " . $wynik . "<br>";
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
						else
						{
							echo "<a href='results_correct.php?rekord=" . $wartosc ."'>";
							echo $klucz . " - " . $wartosc . "<br>";
							echo "</a>";	
						}	

					}

					echo '</div>';
					echo '</div>';	

					echo "<br>";

					$ile++;
				}

				if($ile == 0){echo "<center><br><div>Brak danych do wyświetlenia.</div>";}else{echo "<center><br><div>Pozycji: " . $ile . "</div><br>";}

                echo "<br>";
                
*/


			?>

            </center>

        <div>

    </div>

</div>

<br><br>

</BODY>

</HTML>




