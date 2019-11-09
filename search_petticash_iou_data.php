<?php

session_start();

include_once './connection_sql.php';

if ($_GET["Command"] == "pass_quot") {
    $_SESSION["custno"] = $_GET['custno'];

    header('Content-Type: text/xml');
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";

    $cuscode = $_GET["custno"];


    $sql = "Select * from pettycash_iou where ref ='" . $cuscode . "'";


    $sql = $conn->query($sql);
    if ($row = $sql->fetch()) {


        $ResponseXML .= "<id><![CDATA[" . $row['ref'] . "]]></id>";
        $ResponseXML .= "<str_customername1><![CDATA[" . $row['amount'] . "]]></str_customername1>";
        $ResponseXML .= "<str_customername2><![CDATA[" . $row['pdate'] . "]]></str_customername2>";
        $ResponseXML .= "<str_customername3><![CDATA[" . $row['reason'] . "]]></str_customername3>";
        $ResponseXML .= "<str_customername4><![CDATA[" . $row['settledate'] . "]]></str_customername4>";

        $ResponseXML .= "<stname><![CDATA[" . $_GET['stname'] . "]]></stname>";
    }

    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}


if ($_GET["Command"] == "search_custom") {


    $ResponseXML = "";

    $ResponseXML .= "<table class=\"table table-bordered\">
                <tr>
                   <th>Ref No.</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>";


    $sql = "Select * from pettycash_iou where ref<> ''";

    if ($_GET['cusno'] != "") {
        $sql .= " and ref like '%" . $_GET['cusno'] . "%'";
    }
    if ($_GET['customername1'] != "") {
        $sql .= " and amount like '%" . $_GET['customername1'] . "%'";
    }
    if ($_GET['customername2'] != "") {
        $sql .= " and pdate like '%" . $_GET['customername2'] . "%'";
    }

    $sql .= " ORDER BY ref limit 50 ";



    foreach ($conn->query($sql) as $row) {
        $cuscode = $row['ref'];

        $stname = $_GET["stname"];

        $ResponseXML .= "<tr> 
                              <td onclick=\"custno('$cuscode', '$stname');\">" . $row['ref'] . "</a></td>
                              <td onclick=\"custno('$cuscode', '$stname');\">" . $row['amount'] . "</a></td>
                              <td onclick=\"custno('$cuscode', '$stname');\">" . $row['pdate'] . "</a></td>
                         </tr>";
    }

    $ResponseXML .= "   </table>";


    echo $ResponseXML;
}
?>
