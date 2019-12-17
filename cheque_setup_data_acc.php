<?php

session_start();

////////////////////////////////////////////// Database Connector /////////////////////////////////////////////////////////////
include('./connection_sql.php');

////////////////////////////////////////////// Write XML ////////////////////////////////////////////////////////////////////
header('Content-Type: text/xml');

date_default_timezone_set('Asia/Colombo');


if ($_GET["Command"] == "load_bank") {
    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";


    $sql = "select * from cheque_setup where bank_code='" . $_GET["com_bank"] . "'";
    foreach ($conn->query($sql) as $row) {

        $font_name = "font_name" . $row["id"];
        $font_size = "font_size" . $row["id"];
        $left_loc = "left_loc" . $row["id"];
        $top_loc = "top_loc" . $row["id"];

        $ResponseXML .= "<" . $font_name . "><![CDATA[" . $row["font_name"] . "]]></" . $font_name . ">";
        $ResponseXML .= "<" . $font_size . "><![CDATA[" . $row["font_size"] . "]]></" . $font_size . ">";
        $ResponseXML .= "<" . $left_loc . "><![CDATA[" . $row["left_loc"] . "]]></" . $left_loc . ">";
        $ResponseXML .= "<" . $top_loc . "><![CDATA[" . $row["top_loc"] . "]]></" . $top_loc . ">";
    }

    $ResponseXML .= " </salesdetails>";
    echo $ResponseXML;
}


if ($_GET["Command"] == "save_crec") {

    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        $sql = "delete from cheque_setup where bank_code='" . trim($_GET["com_bank"]) . "'";
        $result = $conn->query($sql);

        $i = 1;
        while ($i <= 14) {
            $left = "left" . $i;
            $top = "top" . $i;
            $font_name = "font_name" . $i;
            $fontsize = "fontsize" . $i;

            $sql = "insert into cheque_setup (bank_code, id, font_name, font_size, left_loc, top_loc) values ('" . trim($_GET["com_bank"]) . "', " . $i . ", '" . $_GET[$font_name] . "', '" . $_GET[$fontsize] . "', '" . $_GET[$left] . "', '" . $_GET[$top] . "')";
            $result = $conn->query($sql);

            $i = $i + 1;
        }
        $conn->commit();
        echo "Saved";
    } catch (Exception $e) {
        $conn->rollBack();
        echo $e;
    }
}
////////////		
?>