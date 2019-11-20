<?php

session_start();


include_once("connectioni.php");




if ($_GET["Command"] == "pass_itno") {
    header('Content-Type: text/xml');
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";


    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";



    $sql = mysqli_query($GLOBALS['dbinv'], "Select * from itemmasterentry where itcode='" . $_GET['itno'] . "'") or die(mysqli_error());

    if ($row = mysqli_fetch_array($sql)) {


        $ResponseXML .= "<str_code><![CDATA[" . $row['itcode'] . "]]></str_code>";
        $ResponseXML .= "<str_description><![CDATA[" . $row['itname'] . "]]></str_description>";
        $ResponseXML .= "<actual_selling><![CDATA[" . $row['price'] . "]]></actual_selling>";

         
    }




    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}
?>
