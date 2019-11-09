<?php

session_start();

////////////////////////////////////////////// Database Connector /////////////////////////////////////////////////////////////
require_once ("connection_sql.php");

////////////////////////////////////////////// Write XML ////////////////////////////////////////////////////////////////////
header('Content-Type: text/xml');

date_default_timezone_set('Asia/Colombo');

if ($_GET["Command"] == "save_item") {

    try {

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();


        $sql = "delete from lcodes where c_code = '" . $_GET['txt_entno'] . "'";
        $conn->exec($sql);
        $ctype = "";


        if ($_GET['bank'] == "on") {
            $ctype = "B";
        }

        $sql_lcode = "Insert Into lcodes(c_code, c_name, c_type, paccno, c_opbal, C_DATE ,c_remark,cur,rate,C_SUBGRO1,cat,C_SUBGRO2) Values "
                . "('" . trim($_GET["txt_entno"]) . "', '" . trim($_GET["txt_gl_name"]) . "', '" . $_GET['acctype'] . "','" . $_GET['paccno'] . "','" . $_GET['txt_Opening'] . "','" . $_GET["dtpOpenDate"] . "','" . $_GET["txt_remarks"] . "','" . $_GET['currency'] . "','" . $_GET['rate'] . "','" . $_GET['acType'] . "','" . $ctype . "','" . $_GET['acType1'] . "')";

        $conn->exec($sql_lcode);

        $mrefno = "BF/" . trim($_GET["txt_entno"]);
        $sql = "delete from ledger where l_refno = '" . $mrefno . "' and l_flag ='OPB' and ComCode = '" .$_SESSION['COMCODE']. "'";
        $conn->exec($sql);

        $amo = $_GET['txt_Opening'] * $_GET["rate"];
        if ($_GET['txt_Opening'] < 0) {
            $mflag = "CRE";
        } else {
            $mflag = "DEB";
        }
require_once './gl_posting.php';
    $ayear = ac_year($_GET["dtpOpenDate"]);
    
    
        $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo,comcode,acyear) value
                   ('" . $mrefno . "','" . $_GET["dtpOpenDate"] . "','" . trim($_GET["txt_entno"]) . "','" . $amo . "','OPB','" . $mflag . "','Opening Balance','" . $_GET["currency"] . "','" . $_GET["rate"] . "','" . $_GET['txt_Opening'] . "','" . $_SESSION['COMCODE'] . "','" . $ayear . "')";
        $conn->exec($sql);


        $conn->commit();
        echo "Saved";
    } catch (Exception $e) {
        $conn->rollBack();
        echo $e;
    }
}


if ($_GET["Command"] == "search_custom") {


    $ResponseXML = "";




    $ResponseXML .= "<table   class=\"table table-bordered\">
                            <tr>
                              <th width=\"121\">Account Code</th>
                              <th width=\"424\">Account Name</th>
                           
   							</tr>";

    if ($_GET["mstatus"] == "cusno") {
        $letters = $_GET['cusno'];
        //$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
        $sql = "select c_code,c_name from lcodes where  c_code like '%$letters%' ORDER BY c_code";
    } else if ($_GET["mstatus"] == "customername") {
        $letters = $_GET['customername'];
        //$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
        $sql = "select c_code,c_name from lcodes where  c_name like '%$letters%' ORDER BY c_code";
    } else {

        $letters = $_GET['customername'];
        //$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
        $sql = "select c_code,c_name from lcodes where  c_name like '%$letters%' ORDER BY c_code";
    }



    foreach ($conn->query($sql) as $row) {
        $cuscode = $row["c_code"];
        $stname = $_GET["stname"];

        $ResponseXML .= "<tr>
                              <td onclick=\"ledgno('$cuscode', '$stname');\">" . $row['c_code'] . "</a></td>
                              <td onclick=\"ledgno('$cuscode', '$stname');\">" . $row['c_name'] . "</a></td>
                                            	
                            </tr>";
    }


    $ResponseXML .= "   </table>";


    echo $ResponseXML;
}



if ($_GET["Command"] == "pass_cash_rec") {

    header('Content-Type: text/xml');
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";

   
    $sql = "select * from c_bal where REFNO ='" . $_GET["custno"] . "'";
    

    $result = $conn->query($sql);
    if ($row = $result->fetch()) {
        $ResponseXML .= "<obj><![CDATA[" . json_encode($row) . "]]></c_code>";
    }
    
    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}


if ($_GET["Command"] == "get_list") {
    
    
    $result = array();
    $sql = "select c_code,c_name from lcodes where  c_name like '%". $_GET['term'] . "%' ORDER BY c_code limit 10";
    foreach ($conn->query($sql) as $items) {
        array_push($result, array("id" => $items['c_code'], "label" => $items['c_code'] . '-' . $items['c_name'], "name" => $items['c_name']));
//array_push($result, array("id"=>$value, "label"=>$key, "value" => strip_tags($key)));
    }

// json_encode is available in PHP 5.2 and above, or you can install a PECL module in earlier versions
    echo json_encode($result);
}



 