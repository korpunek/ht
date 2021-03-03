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
	$dbObj->mongouser = $_SESSION["mongouser"];
	$dbObj->mongocollbaza = $_SESSION["mongocollbaza"];
	$dbObj->mongoadmin = $_SESSION["mongoadmin"];
	$dbObj->mongocolllang = $_SESSION["mongocolllang"];


	
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
					
			<p><h2>Tabela wpisów</h2></p><br>

			</center>


            <?php

				$ile = 0;

				$conn = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");


				$filter = ['data' => array( '$gt' => '2019-01-01', '$lt' => '2020-12-31' )];
				$option = ['sort' => ['data' => -1]];
				$read = new MongoDB\Driver\Query($filter, $option);
				$result = $conn->executeQuery("$dbObj->mongouser.$dbObj->mongocollbaza", $read);

//echo "<br><br>";
//echo "T: " . var_dump($result);
//echo "<br><br>";

				echo '<div class="table-responsive">';

				echo '<table class="table table-hover table-sm">';


				foreach ($result as $dokument)
				{

					echo "<tr><td>" . ($ile + 1) . "</td>";

					foreach( $dokument as $klucz => $wartosc)
					{

						switch($klucz)
						{

							case "_id":
								echo "<td><a href='results_correct.php?rekord=" . $wartosc ."'>" . $wartosc . "</a></td>";
								break;

							case "badanie":
								echo "<td>" . $wartosc . "</td>";
								break;
	
							case "data":
								echo "<td>" . $wartosc . "</td>";
								break;

							default:
								if( is_object($wartosc) )
								{
									echo "<td>" . "$wartosc->wynik" . "</td>";
								}
								else
								{
									if( is_array($wartosc) )
									{
										echo "<td>";
										for($i=0; $i<count($wartosc); $i++)
										{
											echo $wartosc[$i] . ",";	
										}
										echo "</td>";
									}
								}

								break;							

						}


/*

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
*/
					}

					echo "</tr>";

					$ile++;
				}

				echo '</table>';

				echo '</div>';


				if($ile == 0){echo "<center><br><div>Brak danych do wyświetlenia.</div>";}else{echo "<center><br><div>Pozycji: " . $ile . "</div><br>";}

				echo "<br>";

			?>

            </center>

        <div>

    </div>

</div>

<br><br>

</BODY>

</HTML>




