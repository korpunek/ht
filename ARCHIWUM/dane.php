<?php
	session_start();

	$inumer = $_SESSION["numer"];
	$inazwa = $_SESSION["nazwa"];
	$ifirma = $_SESSION["firma"];
	$iemail = $_SESSION["email"];
  	$iprawa = $_SESSION["prawa"];
	
  	$dhost = $_SESSION["dbhost"];
	$dbaza = $_SESSION["dbbaza"];
	$dnazwa = $_SESSION["dbnazwa"];
	$dhaslo = $_SESSION["dbhaslo"];

	if ($inazwa == "" or $inumer == "") {header("Location: blad.htm");}

	include('include/head.php');
	include('include/obj_Header_Modern.php');

	
?>

	<script>


		function getOpcje(str) {
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
				xmlhttp.open("GET","opcja_get.php?q="+str,true);
				xmlhttp.send();

				scroll(0,0);

			}
		}


		function newOpcje()
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
					if (this.readyState == 4 && this.status == 200)
					{
						document.getElementById("popcje").innerHTML = this.responseText;
					}
				};
				xmlhttp.open("GET","opcja_new.php",true);
				xmlhttp.send();

				scroll(0,0);
		}


	</script>	

</HEAD>

<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=0 marginwidth="0" marginheight="0">

<div class="container">

<center>
	
<br><br><br><br>

<?php

  $hid = new Header_Modern;

  $hid->adddane("Użytkownik",$inazwa);
  $hid->adddane("Firma",$ifirma);
  $hid->adddane("Email",$iemail);
  $hid->pprawa = $iprawa;
  $hid->pozycja = 5;
  
  $hid->show();

?>

<br>


<div id="popcje" class="bg-light text-dark">


</div>

<br>

<div class="text-right"><a href="#" class="badge badge-warning" onClick="newOpcje();">Nowa opcja</a></div>

<div class="table-responsive">

<table class="table table-hover"> 
        <tr> 
            <td><b>NR</td> 
            <td><b>NAZWA</td> 
            <td><b>STRING</td> 
            <td><b>LICZBA</td> 
		</tr> 

<tbody> 
		
			
<?php

	$ile = 0;

	$tabela = 'opcje';
	$order = 'NAZWA';
	$zakres = "*";

	    $db = mysqli_connect($dhost, $dnazwa, $dhaslo, $dbaza);
    // mysql_select_db($dbaza, $db);

    $sql = "SELECT " . $zakres . " FROM " . $tabela;
    $sql .= " ORDER BY " . $order;
	
    $res = mysqli_query($db, $sql);  

    while( $row = mysqli_fetch_assoc($res))
    {	
        echo "<tr onClick=getOpcje(" . $row['LP'] . ")>" ;

			echo "<td>" . $row["LP"] . "</td>"; 
			echo "<td>" . $row["NAZWA"] . "</td>"; 
			echo "<td>" . $row["STRING"] . "</td>"; 
			echo "<td>" . $row["LICZBA"] . "</td>"; 

		echo "</tr>"; 

		$ile++;
	}

    mysqli_close($db);

    echo "</tbody>";

echo "</table>"; 
	
if($ile == 0){echo "<center><br><div>Brak danych do wyświetlenia.</div>";}else{echo "<center><br><div>Pozycji: " . $ile . "</div><br>";}

echo "<br>";
	
?>


</div>
 
</div>

<br>


</BODY>

</HTML>
