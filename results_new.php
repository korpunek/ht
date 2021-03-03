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
	$dbObj->mongoadmin = $_SESSION["mongoadmin"];
	$dbObj->mongouser = $_SESSION["mongouser"];	

	$dbObj->mongocollusers = $_SESSION["mongocollusers"];
	$dbObj->mongocolllang = $_SESSION["mongocolllang"];
	$dbObj->mongocollbaza = $_SESSION["mongocollbaza"];
	$dbObj->mongocolltypy = $_SESSION["mongocolltypy"];
	$dbObj->mongocolllogi = $_SESSION["mongocolllogi"];
	



	include('obj_config.php');	
	include('obj_crypt.php');
	include('head.php');


	function alert($msg)
	{
		echo "<script type='text/javascript'>alert('$msg');</script>";
	}
	

?>

  <script type="text/javascript">

		function getDane(str) {
			if (str == "") {
				document.getElementById("popcje").innerHTML = "";
				return;
			} else { 
				if (window.XMLHttpRequest) {
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else {
					// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						document.getElementById("popcje").innerHTML = this.responseText;
					}
				};
				xmlhttp.open("GET","dane_get.php?q="+str,true);
				xmlhttp.send();

				scroll(0,0);

			}
		}


		function newDane(str)
		{

//			alert(str);

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
						document.getElementById("new_dane_form").innerHTML = this.responseText;
					}
				};
				xmlhttp.open("GET","results_new_input.php?typ="+str,true);
				xmlhttp.send();

				scroll(0,0);
		}



		let rwsk=0;
		let twsk=0;

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

		function speech()
		{

			window.SpeechRecognition = window.webkitSpeechRecognition || window.SpeechRecognition;

			if ('SpeechRecognition' in window)
			{
  				alert("speech recognition API supported");
			}
			else
			{
				alert("speech recognition API not supported !");
			}

		}

		function recstart()
		{
			if(rwsk==0)
			{
				recognition.start();
				rwsk=1;
				document.getElementById("micro").src="img/microfon_32red.png";			
			}
			else
			{
				recognition.stop();
				rwsk=0;
				document.getElementById("micro").src="img/microfon_32.png"				
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

//			speech();

		}





		window.SpeechRecognition = window.webkitSpeechRecognition || window.SpeechRecognition;
		let finalTranscript = '';

/*
		const recognition = new window.SpeechRecognition();
recognition.onresult = (event) => {
  const speechToText = event.results[0][0].transcript;

  document.getElementById("inputWynik").value = speechToText;
}
recognition.start()
*/


		let recognition = new window.SpeechRecognition();
		recognition.interimResults = false;
		recognition.maxAlternatives = 10;
		recognition.continuous = true;
		recognition.onresult = (event) => 
		{
			let interimTranscript = '';
			for (let i = event.resultIndex, len = event.results.length; i < len; i++)
			{
				let transcript = event.results[i][0].transcript;

				let slowo = transcript.trim();


				if(slowo == 'zapisz')
				{
					sprawdz();
				}
				else
				{

					if(twsk == 0)
					{
						for(ityp=0; ityp<document.getElementById("tentyp").length; ityp++)
						{	
							document.getElementById("tentyp").selectedIndex = ityp;
							if(slowo == document.getElementById("tentyp").value)
							{
								twsk = 1;
								break;	
							}
						}
					}	

					document.getElementById("inputWynik").value = transcript;	

				}

			}

		}

//		recognition.start();

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
			<br><center>
			<p><h2>Nowy wpis</h2></p><img id="micro" src="img/microfon_32.png" onClick="recstart()" title="Włącz / wyłącz dyktowanie"><br>
			</center><br>			
		</div>
	</div>

	<div class="row">

		<div class="col-12 col-lg-6">

			<div class="card text-black bg-light">

				<div class="card-header">
					<div class="row">
						<div class="col-12">					
							<center><h3>Typy danych</h3></center>
						</div>
					</div>	
				</div>

				<div class="card-body">
					<div id="nowe badanie">

						<div class="row">
			
							<table class='table table-sm table-hover' style='cursor:pointer'><tbody>
								<?php
									$conn = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");
									// $filter = ['data' => array( '$gt' => '2019-01-11', '$lt' => '2020-04-22' )];
									$filter = [];
									$option = ['sort' => ['badanie' => 1]];
									$read = new MongoDB\Driver\Query($filter, $option);
									$result = $conn->executeQuery("$dbObj->mongouser.$dbObj->mongocolltypy", $read);
									foreach ($result as $dokument)
									{
										foreach( $dokument as $klucz => $wartosc)
										{
											if($klucz != "_id")
											{
												if($klucz == "badanie")
												{
													//echo "<tr onClick=newDane('" . $wartosc . "');><td>" . $wartosc . "</td></tr>";
													echo '<tr onClick="newDane(' . "'" . $wartosc . "'" . ');"><td>' . $wartosc . "</td></tr>";
												}
											}	
										}
									}
								?>
							</tbody></table>

						</div>

					</div>
				</div>		
			</div>

		</div>
		

		<div class="col-12 col-lg-6">

			<div id="new_dane_form">

			</div>

		</div>

	</div>

</div>




<br>

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
