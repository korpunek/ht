
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
	$dbObj->mongocolltypy = $_SESSION["mongocolltypy"];	
	$dbObj->mongoadmin = $_SESSION["mongoadmin"];
	$dbObj->mongocolllang = $_SESSION["mongocolllang"];


	include('obj_config.php');	
	include('obj_crypt.php');
	include('head.php');

	$urekord = $_REQUEST['rekord'];

	//	alert($urekord);	

	$id = new MongoDB\BSON\ObjectId($urekord);

	function alert($msg)
	{
		echo "<script type='text/javascript'>alert('$msg');</script>";
	}


?>

  <script type="text/javascript">

		function sprawdz()
		{

			var i = 0;
/*
			if(length(document.getElementById("inputWynik").value) < 1)
			{
				i = 1;
			}
*/
			if( i == 0 )
			{
//				alert("OK");
				forma.submit();				
			}
			else
			{
				alert("Pole wartości nie zostały prawidłowo wypełnione !");
			}

		}


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

<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginwidth="0" marginheight="0" onLoad="start()">


	<div class="container">

		<?php
			$pozycja = 1;
			include('nav.php');
		?>

		<br>

		<div class="row">

			<div class="col-12 col-lg-12">

				<br>

				<center>
						
				<p><h2>Poprawianie wpisu</h2></p>

				</center>			

				<?php
					echo '<div class="card text-white bg-info">';
					echo    '<div class="card-header">';
					echo         '<div class="row">';
					echo              '<div class="col-12">';					
					echo                   '<center><h4>' . $id . '</h4></center>';
					echo              '</div>';
					echo         '</div>';	
					echo    '</div>';
					echo    '<div class="card-body">';
					echo         '<div id="nowe badanie">';


					echo '<FORM NAME="forma" METHOD="POST" ACTION="results_update.php?id=' . $urekord . '" class="form-horizontal">';


					$conn = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");
					$filter = ['_id' => $id];
					$option = [];
					$read = new MongoDB\Driver\Query($filter, $option);
					$result = $conn->executeQuery("$dbObj->mongouser.$dbObj->mongocollbaza", $read);

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
													echo "<input type='text' class='form-control' name='data' id='data' placeholder='' VALUE='$wartosc' SIZE='10'>";
													echo "Możesz zmienić datę badania<br><br>";
													break;

											default:

													$buf = "";

													foreach($wartosc as $obj => $wynik)
													{
															if($obj != "oid")
																if($obj == "wynik")
																{
																	echo "<b>" . $klucz . "</b> ";
																	echo "<input type='text' class='form-control' name='wartosc" . $licz . "' id='wartosc" . $licz . "' placeholder='' VALUE='$wynik' SIZE='10'>";
																	$licz++;
																}
																else
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
					//echo            '<center><button type="submit" class="btn btn-warning">Dodaj badanie</button></center>';

					echo			'<center><button class="btn btn-warning" OnClick="sprawdz()">Zapisz wynik</button></center>';
					echo        '</div>';
					echo    '</div>';

					echo '</FORM>';

					echo         '</div>';
					echo    '</div>';
					echo '</div>';
		


				?>

			</div>

		</div>

		<br>

	</div>

	<br><br>

	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-danger" id="exampleModalLabel"><b>Ostrzeżenie</b></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
			<div class="modal-body bg-warning" id="modal_text">

			</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Zrozumiałem</button>
				</div>
			</div>
		</div>
	</div>


</BODY>

</HTML>
