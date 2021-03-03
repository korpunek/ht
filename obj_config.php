<?php

$def_host = 'localhost';
$def_baza = 'ht';
$def_nazwa = 'root';
$def_haslo = 'Kiszka%Winieta';

$mongo_host = 'localhost';
$mongo_port = '27017';

$mongo_name_admin = 'admin';
$mongo_coll_mess = 'messages';
$mongo_coll_users = 'users';
$mongo_coll_lang = 'languages';

$mongo_name_user = '';
$mongo_coll_baza = 'badania';
$mongo_coll_typy = 'typy';
$mongo_coll_logi = 'logi';


/*
function lang($key,$language,$dbfObj)
{
    $fdb = mysqli_connect($dbfObj->host, $dbfObj->nazwa, $dbfObj->haslo, $dbfObj->baza);
    $fsql = "SELECT * FROM languages WHERE lkey = '" . $key . "'";
    $fres = mysqli_query($fdb, $fsql);
    if (! $fres)
    {
        echo "ERROR " . mysqli_errno();
        echo mysqli_error();
        echo "<PRE>$fsql</PRE>";
        mysqli_close($fdb);
 	    exit;
    }  
    if( $frow = mysqli_fetch_assoc($fres)){$fret = $frow[$language];}
    else{$fret = "* undefined *";}
    mysqli_close($fdb);
    return $fret;
//    return $fdb;

}
*/


function lang($ukey,$language,$dbfObj)
{

    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $filter  = ['lkey' => $ukey];
    $options = [];

    $query = new \MongoDB\Driver\Query($filter, $options);

//    alert($dbfObj->mongocolllang);
    
    $rows = $mongo->executeQuery("$dbfObj->mongoadmin.$dbfObj->mongocolllang", $query);
    
    foreach ($rows as $document)
    {
        $zwrot = $document->$language;
    }     
    
    if ($zwrot == "")
    {
        $zwrot = '* undefined *';
    }   

    return $zwrot;
}


function dateChange($date)
{


//    2020-04-27T16:21

    $byear = substr($date,0,4);
    $bmonth = substr($date,5,2);
    $bday = substr($date,8,2);
    $btime = substr($date,11,5);
    $retdate = $byear . '-' . $bmonth . '-' . $bday . ' ' . $btime . ':00';
    return $retdate;
}





?>
