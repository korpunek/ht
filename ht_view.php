<?php
	session_start();

	$inumer = $_SESSION["numer"];
	$inazwa = $_SESSION["nazwa"];
	$ilang = $_SESSION["lang"];
	$tkey = $_SESSION["tshark"];	

	if ($inazwa == "" or $inumer == "") {header("Location: blad.htm");}

	$dbObj->mongohost = $_SESSION["mongohost"];
	$dbObj->mongoport = $_SESSION["mongoport"];
	$dbObj->mongoadmin = $_SESSION["mongoadmin"];
	$dbObj->mongouser = $_SESSION["mongouser"];	

	$dbObj->mongocollusers = $_SESSION["mongocollusers"];
	$dbObj->mongocolllang = $_SESSION["mongocolllang"];
	$dbObj->mongocollbaza = $_SESSION["mongocollbaza"];
	$dbObj->mongocolltypy = $_SESSION["mongocolltypy"];
	$dbObj->mongocolllogi = $_SESSION["mongocolllogi"];
	$dbObj->mongocollmess = $_SESSION["mongocollmess"];

	include('obj_crypt.php');
	include('obj_config.php');
	
	/*
		$db = mysqli_connect($dbObj->host, $dbObj->nazwa, $dbObj->haslo, $dbObj->baza);
	
		$sql = "SELECT * FROM users WHERE id = " . $inumer;
		$res = mysqli_query($db, $sql);
	
		if (! $res)
		{
				echo "ERROR " . mysqli_errno();
				echo mysqli_error();
				echo "<PRE>$sql</PRE>";
		}

		if( $row = mysqli_fetch_assoc($res))
		{
			$ifirstname = decrypt($row['firstname'], $tkey);
			$ilastname = decrypt($row['lastname'], $tkey);
			$inicname = decrypt($row['nickname'], $tkey);
			$icitizenship = $row['citizenship'];
			$ibirth_date = $row['date_of_birth'];
			$irank = decrypt($row['rank'], $tkey);
			$iid_number = decrypt($row['idnumber'], $tkey);
			$istatus = decrypt($row['status'], $tkey);
		
		}

	*/
	
	
	$id = new MongoDB\BSON\ObjectId($inumer);
	$conn = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");
	$filter = ['_id' => $id];
	$option = [];
	$read = new MongoDB\Driver\Query($filter, $option);
	$result = $conn->executeQuery("$dbObj->mongoadmin.$dbObj->mongocollusers", $read);

	foreach ($result as $dokument)
	{
		$inickname = $dokument->nick;
		$ifirstname = $dokument->firstname;
		$ilastname =  $dokument->lastname;
		$ibirthdate = $dokument->dataurodzenia;
		$iadres =  $dokument->adres;
		$iplan = $dokument->plan;
		$iemail = $dokument->email;
		$imobile = $dokument->mobile;
		$idoctor =  $dokument->doctor;
		$idoctoremail =  $dokument->doctoremail;
	}

	$conn1 = new MongoDB\Driver\Manager("mongodb://$dbObj->mongohost:$dbObj->mongoport");
	$filter = ['status' => '0', '$or' => [['typ' => "1"],['target' => "$inumer"]]];
	$option = [];
	$read1 = new MongoDB\Driver\Query($filter, $option);
	$result1 = $conn1->executeQuery("$dbObj->mongoadmin.$dbObj->mongocollmess", $read1);

	//var_dump($result1);

	include('head.php');


	function alert($msg)
	{
		echo "<script type='text/javascript'>alert('$msg');</script>";
	}


?>

	<script>


		function przeczytane(str)
		{

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
					if (this.readyState == 4 && this.status == 200) {
						document.getElementById("lista_rodzaj").innerHTML = this.responseText;
					}
				};
				xmlhttp.open("GET","msg_set.php?rekord="+str,true);
				xmlhttp.send();
		}


		function mover(obiekt)
		{
			obiekt.style.cursor = 'copy';
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
		$pozycja = 4;
		include('nav.php');
	?>

	<br><br><br>

	<div class="row">


	<?php

		echo '<div class="col-12 col-lg-6">';



				foreach ($result1 as $dokument1)
				{
					$ddd = $dokument1->_id;

					echo '<div class="card bg-info">';
					echo '	<div class="card-header">';
					echo '		<div class="row">';
					echo '			<div class="col text-white">	';					
					echo '				<h4>' . $dokument1->title_pl . '</h4>';
					echo '			</div>';
					echo '			<div class="col text-right vtext">';
//					echo '				<img src="img/check_white_18dp.png" OnMouseOver="mover(this)" OnClick=' . przeczytane($ddd) . '/>';
					echo "				<img src='img/check_white_18dp.png' OnMouseOver='mover(this)' OnClick='przeczytane(" . '"' . $ddd . '");' . "' />";

					echo '			</div>';
					echo '		</div>';	
					echo '	</div>';
					echo '	<div class="card-body bg-white text-black">';
					echo '		<div id="lista_rodzaj">';
					echo 			$dokument1->body_pl . '<br><br>' . $dokument1->autor;
					echo '		</div>';
					echo '	</div>';
					echo '</div>';
					echo '<br>';
				}

		echo '</div>';

?>



		<div class="col-12 col-lg-6">

			<div class="card text-black bg-light">
				<div class="card-header">
					<div class="row">
						<div class="col">						
						<h4><?php echo lang('txt_patient',$ilang,$dbObj) . " [" . $inazwa . "]"; ?></h4>
						</div>
						<div class="col text-right vtext">	

						</div>
					</div>	
				</div>
				<div class="card-body bg-white">
					<div id="lista_rodzaj"></div>


						<div class="row">
							<div class="col"><?php echo lang('txt_photo',$ilang,$dbObj); ?></div>
							<div class="col"><img src="img/photo/<?php echo $inumer; ?>.png" width="100"></div>
						</div>
						<div class="row">
							<div class="col"><?php echo lang('txt_firstname',$ilang,$dbObj); ?></div>
							<div class="col"><?php echo $ifirstname; ?></div>
						</div>
						<div class="row">
							<div class="col"><?php echo lang('txt_lastname',$ilang,$dbObj); ?></div>
							<div class="col"><?php echo $ilastname; ?></div>
						</div>
						<div class="row">
							<div class="col"><?php echo lang('txt_adress',$ilang,$dbObj); ?></div>
							<div class="col"><?php echo $iadres; ?></div>
						</div>
						<div class="row">
							<div class="col"><?php echo lang('txt_birth_date',$ilang,$dbObj); ?></div>
							<div class="col"><?php echo $ibirthdate; ?></div>
						</div>
						<div class="row">
							<div class="col"><?php echo lang('txt_plan',$ilang,$dbObj); ?></div>
							<div class="col"><?php echo $iplan; ?></div>
						</div>
						<div class="row">
							<div class="col"><?php echo lang('txt_email',$ilang,$dbObj); ?></div>
							<div class="col"><?php echo $iemail; ?></div>
						</div>
						<div class="row">
							<div class="col"><?php echo lang('txt_phone',$ilang,$dbObj); ?></div>
							<div class="col"><?php echo $imobile; ?></div>
						</div>
						<div class="row">
							<div class="col"><?php echo lang('txt_doctor',$ilang,$dbObj); ?></div>
							<div class="col"><?php echo $idoctor; ?></div>
						</div>
						<div class="row">
							<div class="col"><?php echo lang('txt_doctoremail',$ilang,$dbObj); ?></div>
							<div class="col"><?php echo $idoctoremail; ?></div>
						</div>

				</div>

			</div>

			<br>

		</div>

	</div>

	<center>
	<div class="alert alert-secondary" role="alert">
		

	</div>
	</center>

</div>


<br><br>


</BODY>

</HTML>
