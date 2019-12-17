<?php

session_start();


require_once ("connection_sql.php");

header('Content-Type: text/xml');

date_default_timezone_set('Asia/Colombo');

if ($_GET["Command"] == "getdt") {

    $tb = "";
    $tb .= "<table class='table table-hover'>";


    $sql = "select * from s_mas order by id desc";




    $tb .= "<tr>";
    $tb .= "<th style=\"width: 350px;\">Item Code</th>";
    $tb .= "<th style=\"width: 500px;\">Description</th>";
    $tb .= "<th style=\"width: 350px;\">Amount</th>";
    $tb .= "</tr>";

    foreach ($conn->query($sql) as $row) {
        $tb .= "<tr>";
        $tb .= "<td onclick=\"getcode1('" . $row['STK_NO'] . "','" . $row['DESCRIPT'] . "','" . $row['SELLING'] . "','')\">" . $row['STK_NO'] . "</td>";
        $tb .= "<td onclick=\"getcode1('" . $row['STK_NO'] . "','" . $row['DESCRIPT'] . "','" . $row['SELLING'] . "','')\">" . $row['DESCRIPT'] . "</td>";
        $tb .= "<td onclick=\"getcode1('" . $row['STK_NO'] . "','" . $row['DESCRIPT'] . "','" . $row['SELLING'] . "','')\">" . number_format($row['SELLING'], "2", ".", ",") . "</td>";
        $tb .= "</tr>";
    }
    $tb .= "</table>";

    echo $tb;
}

if ($_GET["Command"] == "update_list") {
    $ResponseXML = "";
    $ResponseXML .= "<table class=\"table\">
	            <tr>
                        <th width=\"121\">Item No</th>
                        <th width=\"424\"> Item Description </th>
                        
                        <th width=\"121\">Amount</th>  
                    </tr>";


    $sql = "SELECT * from s_mas where STK_NO <> ''";
    if ($_GET['refno'] != "") {
        $sql .= " and STK_NO like '%" . $_GET['refno'] . "%'";
    }
    if ($_GET['cusname'] != "") {
        $sql .= " and DESCRIPT like '%" . $_GET['cusname'] . "%'";
    }
    $stname = $_GET['stname'];

    $sql .= " ORDER BY STK_NO limit 50";

    foreach ($conn->query($sql) as $row) {
        $cuscode = $row["STK_NO"];


        $ResponseXML .= "<tr>               
                              <td onclick=\"itno_undeliver('$cuscode', '$stname');\">" . $row['STK_NO'] . "</a></td>
                              <td onclick=\"itno_undeliver('$cuscode', '$stname');\">" . $row['DESCRIPT'] . "</a></td>
                              <td onclick=\"itno_undeliver('$cuscode', '$stname');\">" . $row['SELLING'] . "</a></td>
                            </tr>";
    }
    $ResponseXML .= "</table>";
    echo $ResponseXML;
}


if ($_GET["Command"] == "save_item") {


    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();
        $sql = "delete from itemmasterentry where itcode = '" . $_GET['txt_itcode'] . "'";
        $result = $conn->query($sql);



        $sql = "Insert into itemmasterentry (itcode, itname, price, lockitem)values 
    ('" . $_GET['txt_itcode'] . "', '" . $_GET['txt_description'] . "', " . $_GET['txt_amount'] . ",'" . $_GET['lockitem'] . "') ";

        $result = $conn->query($sql);
        $conn->commit();
        echo "Saved";
    } catch (Exception $e) {
        $conn->rollBack();
        echo $e;
    }
}


if ($_GET["Command"] == "pass_itno") {
    header('Content-Type: text/xml');
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";


    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";



    $sql = "Select * from s_mas where stk_no='" . $_GET['itno'] . "'";

    $result = $conn->query($sql);
    if ($row = $result->fetch()) {
        $ResponseXML .= "<str_code><![CDATA[" . $row['STK_NO'] . "]]></str_code>";
        $ResponseXML .= "<str_description><![CDATA[" . $row['DESCRIPT'] . "]]></str_description>";
        $ResponseXML .= "<actual_selling><![CDATA[" . $row['SELLING'] . "]]></actual_selling>";
    }




    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}
?>