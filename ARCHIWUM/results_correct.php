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


	$urekord = $_REQUEST['rekord'];

	$conn = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");

//	alert($urekord);	

	$licztyp = 0;
	$filter = [];
	$option = [];
	$read = new MongoDB\Driver\Query($filter, $option);
	$result = $conn->executeQuery("$dbObj->mongoname.typy", $read);
	foreach ($result as $user)
	{
		$typObj[$licztyp] = $user->typ;
		$licztyp++;
	}

	
	$liczklucz = 0;
	$id = new MongoDB\BSON\ObjectId($urekord);
	$filter = ['_id' => $id];
	$option = [];
	$read = new MongoDB\Driver\Query($filter, $option);
	$result = $conn->executeQuery("$dbObj->mongoname.$dbObj->mongocoll", $read);

	foreach ($result as $dokument)
	{

		foreach( $dokument as $klucz => $wartosc)
		{
			if($klucz != "_id")
			{
				if($klucz == "data")
				{
					$udata = $wartosc;
				}
				else
				{
					$uwynik = $wartosc;
					$utyp = $klucz;

					$kluczObj[$liczklucz] = $klucz;
					$wartoscObj[$liczklucz] = $wartosc;
					$liczklucz++;

				}
			}	
		}
	}



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
					
			<p><h2>Poprawianie wpisu</h2></p><br>

			</center>			

		
			<FORM NAME="forma" METHOD="POST" ACTION="results_update.php" class="form-horizontal">
				<INPUT TYPE="HIDDEN" NAME="ilekluczy" size=5 VALUE="<?php echo $liczklucz; ?>">
				
				<div class="bg-light">
					<br>

					<div class="form-group row align-items-center">
						<label for="inputID" class="col-12 col-lg-2 control-label">Rekord</label>
						<div class="col-12 col-lg-5">
							<input type="text" class="form-control" name="id" id="inputID" placeholder="" value="<?php echo $urekord;?>" readonly>
						</div>
						<label for="inputID" class="col-12 col-lg-5 control-label">
							ID poprawianego rekordu.
						</label>
					</div>	

					<div class="form-group row align-items-center">
						<label for="inputDate" class="col-12 col-lg-2 control-label">Data wpisu</label>
						<div class="col-12 col-lg-5">
							<input type="text" class="form-control" name="date" id="inputDate" placeholder="" value="<?php echo $udata;?>">
						</div>
						<label for="inputDate" class="col-12 col-lg-5 control-label">
							Bieżąca data wpisywana jest automatycznie, jeżeli data ma być inna zmień ją.
						</label>
					</div>	


					<?php

						for($j=0; $j<$liczklucz; $j++)
						{
							echo '<div class="form-group row">';
								echo '<label for="tentyp" class="col-12 col-lg-2 control-label">Typy danych</label>';
								echo '<div class="col-12 col-lg-5">';	
									echo '<SELECT NAME="typ' . $j . '" class="form-control" id="tentyp" readonly>';

											for($i=0; $i<$licztyp; $i++)
											{
												echo "<OPTION";
													if($typObj[$i] == $kluczObj[$j]){echo " SELECTED";}
												echo ">" . $typObj[$i] . "</OPTION>";		
											}

									echo '</SELECT>';
								echo '</div>';
								echo '<label for="tentyp" class="col-12 col-lg-5 control-label">Wybierz typ danych.</label>';
							echo '</div>';	
							
							echo '<div class="form-group row">';
								echo '<label for="inputWynik" class="col-12 col-lg-2 control-label">Wynik</label>';
								echo '<div class="col-12 col-lg-5">';
									echo '<input type="text" class="form-control" name="wynik' . $j . '" id="inputWynik" placeholder="" value="' . $wartoscObj[$j] . '" autofocus>';
								echo '</div>';
								echo '<label for="inputWynik" class="col-12 col-lg-5 control-label">Wpisz wynik.</label>';
							echo '</div>';	
						
						}
					?>

					<br>

				</div>

				<br>
				
			</FORM>

		</div>

	</div>

	<center>

	<br>

	<div>
		<div class="col-12">
			<button class="btn btn-warning" OnClick="sprawdz()">Zapisz wynik</button>
		</div>
	</div>

	<br><br>


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
