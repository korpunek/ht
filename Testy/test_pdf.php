<?php
 
include './mpdf/mpdf.php';
$mpdf=new mPDF(); 
$mpdf->WriteHTML("<h1 style='font-family: Arial;'>Siema to jest H1 w PDF'ie</h1>");
//$mpdf->Output();
$mpdf->Output("output.pdf","D");
exit; 
?>
