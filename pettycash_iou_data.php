<?php

session_start();

////////////////////////////////////////////// Database Connector /////////////////////////////////////////////////////////////
require_once ("connection_sql.php");

////////////////////////////////////////////// Write XML ////////////////////////////////////////////////////////////////////
header('Content-Type: text/xml');
date_default_timezone_set('Asia/Colombo');

if ($_GET["Command"] == "getdt") {
    header('Content-Type: text/xml');
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

    $ResponseXML = "";
    $ResponseXML .= "<new>";
    $sql = "SELECT pcash_iou_code FROM invpara";
    $result = $conn->query($sql);
    $row = $result->fetch();
    $no = $row['pcash_iou_code'];
    $uniq = uniqid();
    $tmpinvno = "000000" . $row["pcash_iou_code"];
    $lenth = strlen($tmpinvno);
    $no = trim("PTI/") . substr($tmpinvno, $lenth - 7);

    $ResponseXML .= "<id><![CDATA[$no]]></id>";
    $ResponseXML .= "<uniq><![CDATA[$uniq]]></uniq>";



    $ResponseXML .= "</new>";


    echo $ResponseXML;
}



if ($_GET["Command"] == "save_content") {


    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

         $saveContentSql = "Insert into pettycash_iou(ref,amount,pdate,reason,settledate,uniq,person,type)values
                 ('" .  $_GET['ref'] . "','" .  $_GET['amount'] . "','" .  $_GET['pdate'] . "','" .  $_GET['reason'] . "','" .  $_GET['settledate'] . "','" .  $_GET['uniq'] . "','" .  $_GET['person'] . "','IOU') ";
         $result = $conn->query($saveContentSql);

         
        $sql = "SELECT pcash_iou_code FROM invpara";
        $result = $conn->query($sql);
        $row = $result->fetch();
        $no = $row['pcash_iou_code'];
        
         
        $no2 = $no + 1;
        
        
        $sql = "update invpara set pcash_iou_code = $no2 where pcash_iou_code = $no";
        $result = $conn->query($sql);

        $conn->commit();
        echo "Saved";
    } catch (Exception $e) {
        $conn->rollBack();
        echo $e;
    }
}









