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

    $sql = "Select * from vendor where code='" . $cuscode . "' ";

    $sql = $conn->query($sql);
    if ($row = $sql->fetch()) {


        $ResponseXML .= "<id><![CDATA[" . $row['CODE'] . "]]></id>";
        $ResponseXML .= "<str_customername><![CDATA[" . $row['NAME'] . "]]></str_customername>";

        $ResponseXML .= "<stname><![CDATA[" . $_GET['stname'] . "]]></stname>";
        $ResponseXML .= "<add><![CDATA[" .  $row['ADD1']  . "]]></add>";
        
        $i = 1;
        if (isset($_GET['stname'])) {
            if (($_GET['stname'] == "rec") or ($_GET['stname'] == "cont"))  {
                $tb = "<table class='table table-striped'>";
                $tb .= "<tr>";
                    $tb .= "<th>Ref. No</th>";
                    $tb .= "<th>Date</th>";
                    $tb .= "<th>Amount</th>";
                    $tb .= "<th>Paid</th>";
                     
                    $tb .= "<th>Balance</th>";
                    $tb .= "<th>Payment</th>";
                    $tb .= "<th>Running Bal.</th>";
                   
                    $tb .= "</tr>";
                
                $sql = "select * from  s_salma where c_code = '" . $cuscode . "' and GRAND_TOT > TOTPAY and cancell='0' order by sdate";
                // echo $sql;
                foreach ($conn->query($sql) as $row) {
                    $pay = "pay" .$i;
                    $refno = "refno" . $i;
                    $rate = "rate" .$i;
                    $bal = "bal" . $i;
                    $cashbal = "cashbal" . $i;
                    $tb .= "<tr>";
                    $tb .= "<td><input disabled=disabled type='text' id='" . $refno . "' value = '" . $row['REF_NO'] . "'></td>";
                    $tb .= "<td>" . $row['SDATE'] . "</td>";
                    $tb .= "<td>" . $row['curamo'] . "</td>";
                    $tb .= "<td>" . $row['curtotpay'] . "</td>";
                    
                    $tb .= "<td><input disabled=disabled type='text' id='" . $bal . "' value = '" . ($row['curamo'] - $row['curtotpay']) . "'></td>";
                    $tb .= "<td><input onkeyup='calamo();' type='text' id='" . $pay . "'></td>";
                    $tb .= "<td><input  disabled=disabled type='text' id='" . $cashbal . "'></td>";
                    $i = $i+1;
                    
                    $tb .= "</tr>";
                }
                
                $tb .= "</table>";
                
                $t = 1;    
                $tb1 = "<table class='table table-striped'>";
                $tb1 .= "<tr>";
                    $tb1 .= "<th>Ref. No</th>";
                    $tb1 .= "<th>Date</th>";
                    $tb1 .= "<th>Amount</th>";
                    $tb1 .= "<th>Paid</th>";                     
                    $tb1 .= "<th>Balance</th>";
                    $tb1 .= "<th>Payment</th>";
                   
                    $tb1 .= "</tr>";
                
                $sql = "select * from  c_bal where cuscode = '" . $cuscode . "' and curbal > 0.01 and cur='" . $_GET['cur'] . "' and cancell='0' order by sdate";
                foreach ($conn->query($sql) as $row) {
                    $pay = "cpay" .$t;
                    $refno = "crefno" . $t;
                    $rate = "crate" . $t;
                    $bal = "cbal" . $t;
                    
                    $tb1 .= "<tr>";
                    $tb1 .= "<td><input disabled=disabled type='text' id='" . $refno . "' value = '" . $row['REFNO'] . "'></td>";
                    $tb1 .= "<td>" . $row['SDATE'] . "</td>";
                    $tb1 .= "<td>" . $row['curamo'] . "</td>";
                    $tb1 .= "<td>" . ($row['curamo']-$row['curbal']) . "</td>";
                    
                    $tb1 .= "<td><input disabled=disabled type='text' id='" . $bal . "' value = '" . ($row['curbal']) . "'></td>";
                    $tb1 .= "<td><input onkeyup='calamo1();' type='text' id='" . $pay . "'></td>";
                  
                    $t = $t+1;
                    
                    $tb1 .= "</tr>";
                }
                
                $tb1 .= "</table>";
                $ResponseXML .= "<tb><![CDATA[" . $tb . "]]></tb>";
                $ResponseXML .= "<count><![CDATA[" . $i . "]]></count>";
                $ResponseXML .= "<tb1><![CDATA[" . $tb1 . "]]></tb1>";
                $ResponseXML .= "<count1><![CDATA[" . $t . "]]></count1>";
                
                
                
            }
        }
    }

     
    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}


if ($_GET["Command"] == "search_custom") {


    $ResponseXML = "";




    $ResponseXML .= "<table   class=\"table table-bordered\">
                            <tr>
                    <th width=\"121\"  >Customer No</th>
                    <th width=\"424\"  >Customer Name</th>
                    <th width=\"300\"  >Address</th>

                </tr>";

    if ($_GET["mstatus"] == "cusno") {
        $letters = $_GET['cusno'];
        //$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
        $sql = "select code,name,add1 from vendor where  code like '%$letters%' ";
    } else if ($_GET["mstatus"] == "customername") {
        $letters = $_GET['customername'];
        //$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
        $sql = "select code,name,add1 from vendor where  name like '%$letters%' ";
    } else {

        $letters = $_GET['customername'];
        //$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
        $sql = "select code,name,add1 from vendor where  name like '%$letters%' ";
    }
    $sql .= " ORDER BY code limit 50";


    foreach ($conn->query($sql) as $row) {
        $cuscode = $row['code'];
        $stname = $_GET["stname"];

        $ResponseXML .= "<tr>               
                              <td onclick=\"custno('$cuscode', '$stname');\">" . $row['code'] . "</a></td>
                              <td onclick=\"custno('$cuscode', '$stname');\">" . $row['name'] . "</a></td>
                              <td onclick=\"custno('$cuscode', '$stname');\">" . $row['add1'] . "</a></td>
                            </tr>";
    }


    $ResponseXML .= "   </table>";


    echo $ResponseXML;
}
?>
