<?php
	session_start();

	$inumer = $_SESSION["numer"];
	$inazwa = $_SESSION["nazwa"];
	$ilang = $_SESSION["lang"];
		

	$dbObj->host = $_SESSION["dbhost"];
	$dbObj->baza = $_SESSION["dbbaza"];
	$dbObj->nazwa = $_SESSION["dbnazwa"];
	$dbObj->haslo = $_SESSION["dbhaslo"];

	$dbObj->mongohost = $_SESSION["mongohost"];
	$dbObj->mongoport = $_SESSION["mongoport"];
	$dbObj->mongouser = $_SESSION["mongouser"];
	$dbObj->mongocollbaza = $_SESSION["mongocollbaza"];
	$dbObj->mongocolltypy = $_SESSION["mongocolltypy"];	
	$dbObj->mongoadmin = $_SESSION["mongoadmin"];
	$dbObj->mongocolllang = $_SESSION["mongocolllang"];		


//var_dump($dbObj);


	
	if ($inazwa == "" or $inumer == "") {header("Location: error.htm");}

//	alert(date('n'));

	if($_POST['nrrok'] == 0)
	{
		$prok = date('Y');
		$firstmiesiac = date('n');
		$lastmiesiac = date('n');
		$pbadanie = 'Pogoda';
	}
	else
	{
		$prok = (int)$_POST['nrrok'];
		$firstmiesiac = (int)$_POST['nrfirstmiesiac'];
		$lastmiesiac = (int)$_POST['nrlastmiesiac'];
		$pbadanie = $_POST['typbadania'];
	}

	$connTypy = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");
	$filter = [];
	$option = ['sort' => ['badanie' => 1]];
	$readTypy = new MongoDB\Driver\Query($filter, $option);
	$resultTypy = $connTypy->executeQuery("$dbObj->mongouser.$dbObj->mongocolltypy", $readTypy);

	$tabTypy = [];
		
	foreach ($resultTypy as $dokument)
	{
		$buf = "";
		$tabObj = [];

		foreach( $dokument as $klucz => $wartosc)
		{
			if($klucz != "_id")
			{
				if($klucz == "badanie")
				{
					$buf = $wartosc;
				}
				else
				{
					if( is_object($wartosc) )
					{
						$tabObj[] = $klucz;
					}
				}
			}
		}

		$tabTypy[$buf] = $tabObj;	
	}

	//	echo "<br><br><br>";
	//	echo $prok . "<br>";
	//	echo $firstmiesiac . "<br>";
	//	echo $lastmiesiac . "<br>";
	//	echo $pbadanie . "<br>";



	$legend = "";
	$naglowek = "";

	foreach($tabTypy as $klucz => $wartosc)
	{
		if($klucz == $pbadanie)
		{
			$legend .= "['Dzień'";

			foreach($wartosc as $obj => $wynik)
			{
				$legend .= ", '" . $wynik . "'";
				$naglowek .= "<th scope='col' class='text-right'>" . $wynik . "</th>";

			}
			$legend .= "],";
		}
	}


	$buf = "";
	if($firstmiesiac<10){$buf = '0';}
	$firstdata = $prok . '-' . $buf . $firstmiesiac . '-01';
	$buf = "";
	if($lastmiesiac<10){$buf = '0';}
	$lastdata = $prok . '-' . $buf . $lastmiesiac . '-31';


	$conn = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");
	$filter = ['data' => array( '$gt' => $firstdata, '$lt' => $lastdata), 'badanie' => $pbadanie];

	$option = ['sort' => ['data' => 1]];
	$read = new MongoDB\Driver\Query($filter, $option);
	$result = $conn->executeQuery("$dbObj->mongouser.$dbObj->mongocollbaza", $read);

	$tabmiesiace[1] = 'styczeń';
	$tabmiesiace[2] = 'luty';
	$tabmiesiace[3] = 'marzec';
	$tabmiesiace[4] = 'kwiecień';
	$tabmiesiace[5] = 'maj';
	$tabmiesiace[6] = 'czerwiec';
	$tabmiesiace[7] = 'lipiec';
	$tabmiesiace[8] = 'sierpień';
	$tabmiesiace[9] = 'wrzesień';
	$tabmiesiace[10] = 'październik';
	$tabmiesiace[11] = 'listopad';
	$tabmiesiace[12] = 'grudzień';


	include('obj_config.php');	
	include('obj_crypt.php');
	include('head.php');

	function alert($msg)
	{
		echo "<script type='text/javascript'>alert('$msg');</script>";
	}


?>


  <script type="text/javascript">

		function msg_dane(title, str, bad)
		{

			//alert("inne_badania.php?data="+str+", badanie="+bad);

				if (window.XMLHttpRequest)
				{
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				}
				else
				{
					// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200)
					{
						document.getElementById("exampleModalLabel").innerHTML = title;
						document.getElementById("modal_text").innerHTML = this.responseText;
						$('#exampleModal').modal();
					}
				};
				xmlhttp.open("GET","inne_badania.php?data="+str+"&badanie="+bad,true);
				xmlhttp.send();

				scroll(0,0);
		}


		function msg_view(title,body)
        {
                document.getElementById("exampleModalLabel").innerHTML = title;
                document.getElementById("modal_text").innerHTML = body;
				$('#exampleModal').modal();
        }


		function wykonaj()
		{
			forma.nrrok.value = forma.rok.value; 
			forma.nrfirstmiesiac.value = forma.fmiesiac.selectedIndex + 1;
			forma.nrlastmiesiac.value = forma.lmiesiac.selectedIndex + 1;
			forma.typbadania.value = forma.tbadania.value;
			forma.submit();
		}


		function start()
		{
			$('#pliktab a').on('click', function (e)
				{
					e.preventDefault()
					$(this).tab('show')
				}
			)	
	
//			document.getElementById(lista).innerHTML = "";	
		}

	</script>

	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  
	<script type="text/javascript">
    
		google.charts.load('current', {'packages':['corechart']});
    	google.charts.setOnLoadCallback(drawChart);

    	function drawChart()
	  	{
        	var data = google.visualization.arrayToDataTable([
 
					<?php

						$btitle = "Inne badania tego dnia";

						echo $legend;

						$ile = 0;
 						
						$buf = "<table class='table table-hover table-sm'><thead class='thead-light'><tr><th scope='col'>LP</th><th scope='col'>DATA</th>" . $naglowek . "</tr></thead><tbody>";

						foreach ($result as $dokument)
						{
							echo "[";
							$buf .= "<tr>" . "<th scope='row'>" . $ile . "</th>";

							//	echo "<tr" . $kolor . " OnClick='msg_view(" . '"' . $btitle . '","' . $bbody . '");' . "'>";

							foreach( $dokument as $klucz => $wartosc)
							{
								if($klucz != "_id")
								{

									if($klucz == 'data')
									{
										echo "'" . substr($wartosc,0,10) . "'";

										$buf .= "<td" . " OnClick='msg_dane(" . '"' . $btitle . '","' . $wartosc . '","' . $pbadanie  .'");' . "'>" . $wartosc . "</td>";
									}

									if( is_object($wartosc) )
									{
										foreach($wartosc as $obj => $wynik)
										{
											if($obj == 'wynik')
											{
												echo "," . $wynik;
												$buf .= "<td class='text-right'>" . $wynik . "</td>";
											}
										}
									}
								}

							}

							echo "],";
							$buf .= "</tr>";

							$ile++;

						}

						$buf .= "</tbody></table>";

						
						//echo "['Year', 'Fales', 'trpes', 'Expenses'],";
						//echo "['2004',  1000,  700,    400],";
						//echo "['2005',  1170,  990,    460],";
						//echo "['2006',  2660,  1500,     1120],";
						//echo "['2007',  1030,  800,    540]";
						

					?>	
	
		    ]);

        	var options = {
          		title: 'Wykres wyników  - <?php echo $pbadanie; ?>',
          		curveType: 'function',
          		legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart1'));

        <?php if($ile>0){echo "chart.draw(data, options);";}else{$buf="Brak danych w tym okresie czasu.";} ?>

      }

	</script>








</HEAD>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginwidth="0" marginheight="0" onLoad="start()">


<div class="container">

	<?php
		$pozycja = 3;
		include('nav.php');
	?>

	<br><br><br>

	<FORM NAME="forma" METHOD="POST" ACTION="results_chart_badanie.php" class="form-horizontal">
			<INPUT TYPE="HIDDEN" NAME="nrrok" VALUE=0>
			<INPUT TYPE="HIDDEN" NAME="nrfirstmiesiac" VALUE=0>
			<INPUT TYPE="HIDDEN" NAME="nrlastmiesiac" VALUE=0>
			<INPUT TYPE="HIDDEN" NAME="typbadania" VALUE="">	
	
		<div class="row">
		
			<div class="col-0 col-lg-1"></div>		

			<div class="col-4 col-lg-2">
				<?php
					echo '<SELECT NAME="fmiesiac" class="form-control" id="fmiesiac">';

					for($i=1;$i<13;$i++)
					{
						echo '	<OPTION'; 
							if($firstmiesiac==$i){ echo ' SELECTED';}
						echo '>' . $tabmiesiace[$i] . '</OPTION>';
					}
					echo '</SELECT>';
				?>
			</div>

			<div class="col-4 col-lg-2">
				<?php
					echo '<SELECT NAME="lmiesiac" class="form-control" id="lmiesiac">';

					for($i=1;$i<13;$i++)
					{
						echo '	<OPTION'; 
							if($lastmiesiac==$i){ echo ' SELECTED';}
						echo '>' . $tabmiesiace[$i] . '</OPTION>';
					}
					echo '</SELECT>';
				?>
			</div>

			<div class="col-4 col-lg-2">
				<?php
					echo '<SELECT NAME="rok" class="form-control" id="tenrok">';

					for($i=date('Y');$i>2010;$i--)
					{
						echo '	<OPTION'; 
							if($prok==$i){ echo ' SELECTED';}
						echo '>' . $i . '</OPTION>';
					}
					echo '</SELECT>';
				?>
			</div>

			<div class="col-8 col-lg-3">
				<select name="tbadania" class="form-control" id="tbadania">

					<?php
						$connTypy = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");
						$filter = [];
						$option = ['sort' => ['badanie' => 1]];
						$readTypy = new MongoDB\Driver\Query($filter, $option);
						$resultTypy = $connTypy->executeQuery("$dbObj->mongouser.$dbObj->mongocolltypy", $readTypy);

						foreach ($resultTypy as $dokument)
						{
							foreach( $dokument as $klucz => $wartosc)
							{
								if($klucz != "_id")
								{
									if($klucz == "badanie")
									{
										echo '<option value="' . $wartosc . '"';
										if($pbadanie==$wartosc){ echo ' SELECTED';}
										echo '>' . $wartosc . '</option>';
									}
								}	
							}
						}

					?>

					<!--option value="1" <?php if($pbadanie==0){ echo ' SELECTED';} ?>>Tlen, Puls</option-->
					<!--option value="2" <?php if($pbadanie==1){ echo ' SELECTED';} ?>>Ciśnienie</option-->
					<!--option value="4" <?php if($pbadanie==2){ echo ' SELECTED';} ?>>Live Index</option-->
				</select>
			</div>

			<!--div class="col"></div-->

			<div class="col-4 col-lg-2">
				<button class="btn btn-warning d-print-none" OnClick="wykonaj()">Pokaż dane</button>
			</div>

		</div>


	</FORM>


	<!--div id="curve_chart1" style="width: 1400px; height: 500px"></div-->
	<div id="curve_chart1" style="width: 1700; height: 500px"></div>

	<br>

	<center>
	<div class="row col-12 col-lg-8">				
		<div class="table-responsive">
			<?php echo $buf; ?>
		</div>
	</div>
	</center>

</div>

<br><br>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-dark" id="exampleModalLabel"><b>Title</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body bg-warning" id="modal_text">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
      </div>
    </div>
  </div>
</div>

<br><br>



</BODY>

</HTML>