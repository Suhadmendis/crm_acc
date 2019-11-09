<?php

session_start();

require_once ("./connection_sql.php");

header('Content-Type: text/xml');

date_default_timezone_set('Asia/Colombo');

if ($_GET["Command"] == "getdt") {

    $tb = "";
    $tb .= "<table class='table table-hover'>";


    $sql = "select * from mastercurrancy order by id desc limit 50";



    $tb .= "<tr>";
    $tb .= "<td style=\"width: 350px;\"></td>";
    $tb .= "<td style=\"width: 350px;\"></td>";
    $tb .= "<td style=\"width: 350px;\"></td>";
    $tb .= "<td style=\"width: 350px;\"></td>";
    $tb .= "<td style=\"width: 350px;\"></td>";
    $tb .= "</tr>";

    foreach ($conn->query($sql) as $row) {
        $tb .= "<tr>";
        $tb .= "<td onclick=\"getcode('" . $row['currancy'] . "','" . $row['byexrate'] . "','" . $row['loclclientsell'] . "','" . $row['agentsellrate'] . "','" . $row['todayrate'] . "')\">" . $row['currancy'] . "</td>";
        $tb .= "<td onclick=\"getcode('" . $row['currancy'] . "','" . $row['byexrate'] . "','" . $row['loclclientsell'] . "','" . $row['agentsellrate'] . "','" . $row['todayrate'] . "')\">" . $row['byexrate'] . "</td>";
        $tb .= "<td onclick=\"getcode('" . $row['currancy'] . "','" . $row['byexrate'] . "','" . $row['loclclientsell'] . "','" . $row['agentsellrate'] . "','" . $row['todayrate'] . "')\">" . $row['loclclientsell'] . "</td>";
        $tb .= "<td onclick=\"getcode('" . $row['currancy'] . "','" . $row['byexrate'] . "','" . $row['loclclientsell'] . "','" . $row['agentsellrate'] . "','" . $row['todayrate'] . "')\">" . $row['agentsellrate'] . "</td>";
        $tb .= "<td onclick=\"getcode('" . $row['currancy'] . "','" . $row['byexrate'] . "','" . $row['loclclientsell'] . "','" . $row['agentsellrate'] . "','" . $row['todayrate'] . "')\">" . $row['todayrate'] . "</td>";
        $tb .= "</tr>";
    }
    $tb .= "</table>";

    echo $tb;
}

if ($_GET["Command"] == "getcode") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";

    $sql = "select * from mastercurrancy where currancy = '" . Trim($_GET["code"]) . "' limit 1";


    $result = $conn->query($sql);
    IF ($row = $result->fetch()) {
        $ResponseXML .= "<code><![CDATA[" . trim($row['currancy']) . "]]></code>";
        $ResponseXML .= "<rate><![CDATA[" . $row['byexrate'] . "]]></rate>";
    }
    $ResponseXML .= "</salesdetails>";

    echo $ResponseXML;
}

if ($_GET["Command"] == "get_rate") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";

    $sql = "select * from mastercurrancy where currancy = '" . Trim($_GET["code"]) . "' limit 1";


    $result = $conn->query($sql);
    IF ($row = $result->fetch()) {
        $ResponseXML .= "<code><![CDATA[" . trim($row['currancy']) . "]]></code>";
        $ResponseXML .= "<rate><![CDATA[" . $row['byexrate'] . "]]></rate>";
    }
    $ResponseXML .= "</salesdetails>";

    echo $ResponseXML;
}


if ($_GET["Command"] == "save_item") {


    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();
        $sql = "delete from mastercurrancy where currancy = '" . $_GET['txt_currency'] . "'";
        $result = $conn->query($sql);
        


        $sql = "Insert into mastercurrancy (currancy, byexrate, loclclientsell, agentsellrate,todayrate)values 
    ('" . trim($_GET['txt_currency']) . "', '" . $_GET['txt_byexrate'] . "', " . $_GET['txt_localclient'] . ",'" . $_GET['txt_gentsell'] . "','" . $_GET['txt_totalrate'] . "') ";

         $result = $conn->query($sql);
        $conn->commit();
        echo "Saved";
    } catch (Exception $e) {
        $conn->rollBack();
        echo $e;
    }
}
?>