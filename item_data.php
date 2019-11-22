<?php

session_start();

 
require_once ("connection_sql.php");

header('Content-Type: text/xml');

date_default_timezone_set('Asia/Colombo');

if ($_GET["Command"] == "getdt") {

    $tb = "";
    $tb .= "<table class='table table-hover'>";


    $sql = "select * from itemmasterentry order by id desc";


   
    
    $tb .= "<tr>";
    $tb .= "<th style=\"width: 350px;\">Item Code</th>";
    $tb .= "<th style=\"width: 500px;\">Description</th>";
    $tb .= "<th style=\"width: 350px;\">Amount</th>";
    $tb .= "</tr>";

    foreach ($conn->query($sql) as $row) {
        $tb .= "<tr>";
        $tb .= "<td onclick=\"getcode('" . $row['itcode'] . "','" . $row['itname'] . "','" . $row['price'] . "','" . trim($row['lockitem'])  . "')\">" . $row['itcode'] . "</td>";
        $tb .= "<td onclick=\"getcode('" . $row['itcode'] . "','" . $row['itname'] . "','" . $row['price'] . "','" . trim($row['lockitem'])  . "')\">" . $row['itname'] . "</td>";
        $tb .= "<td onclick=\"getcode('" . $row['itcode'] . "','" . $row['itname'] . "','" . $row['price'] . "','" . trim($row['lockitem'])  . "')\">" . number_format($row['price'], "2", ".", ",") . "</td>";
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


    $sql = "SELECT * from itemmasterentry where itcode <> ''";
    if ($_GET['refno'] != "") {
        $sql .= " and itcode like '%" . $_GET['refno'] . "%'";
    }
    if ($_GET['cusname'] != "") {
        $sql .= " and itname like '%" . $_GET['cusname'] . "%'";
    }
    $stname = $_GET['stname'];

    $sql .= " ORDER BY itcode limit 50";

    foreach ($conn->query($sql) as $row) {
        $cuscode = $row["itcode"];


        $ResponseXML .= "<tr>               
                              <td onclick=\"itno_undeliver('$cuscode', '$stname');\">" . $row['itcode'] . "</a></td>
                              <td onclick=\"itno_undeliver('$cuscode', '$stname');\">" . $row['itname'] . "</a></td>
                              <td onclick=\"itno_undeliver('$cuscode', '$stname');\">" . $row['price'] . "</a></td>
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




?>