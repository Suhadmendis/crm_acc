<?php
session_start();
header('Content-Type: text/xml'); 
if ($_GET['Command'] == "chk_sess") {
    
    
    
    
    if (!isset($_SESSION['UserName'])) {
        $action = "not";
        $_SESSION['UserName'] = "admin";
    }
    
    $action = "ok";
    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";
    $ResponseXML .= "<action><![CDATA[" . $_GET['action'] . "]]></action>";
    $ResponseXML .= "<form><![CDATA[" . $_GET['form'] . "]]></form>";
    $ResponseXML .= "<stat><![CDATA[" . $action . "]]></stat>";
    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}