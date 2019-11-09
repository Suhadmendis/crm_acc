<?php

session_start();

////////////////////////////////////////////// Database Connector /////////////////////////////////////////////////////////////
require_once ("./connection_sql.php");

////////////////////////////////////////////// Write XML ////////////////////////////////////////////////////////////////////
header('Content-Type: text/xml');

date_default_timezone_set('Asia/Colombo');

/////////////////////////////////////// GetValue //////////////////////////////////////////////////////////////////////////
///////////////////////////////////// Registration /////////////////////////////////////////////////////////////////////////


function getno() {

    include './connection_sql.php';
    $sql = "select CHE_RET from dep_mas";
    $result = $conn->query($sql);
    $row = $result->fetch();
    $tmpinvno = "00000" . $row["CHE_RET"];
    $lenth = strlen($tmpinvno);
    $invno = trim("RCH/") . substr($tmpinvno, $lenth - 5);

    return $invno;
}

if ($_GET["Command"] == "new_inv") {



    $invno = getno();

    $sql = "Select CHENO from tmpinvpara_acc";
    $result = $conn->query($sql);
    $row = $result->fetch();
    $tono = $row['CHENO'];

    $sql = "delete from tmp_che_data where tmp_no='" . $tono . "'";
    $result = $conn->query($sql);

    $sql = "update tmpinvpara_acc set CHENO=CHENO+1";
    $result = $conn->query($sql);

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";
    $ResponseXML .= "<invno><![CDATA[" . $invno . "]]></invno>";
    $ResponseXML .= "<tmpno><![CDATA[" . $tono . "]]></tmpno>";
    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}

if ($_GET["Command"] == "add_tmp") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";




    $sql = "Insert into tmp_che_data (code, descript, amount, tmp_no,form_no)values 
			('" . $_GET['itemCode'] . "', '" . $_GET['itemDesc'] . "', " . $_GET['itemPrice'] . ",'" . $_GET['tmpno'] . "','PCH') ";

    $result = $conn->query($sql);
    if (!$result) {
        echo $sql . "<br>";
        echo mysqli_error($GLOBALS['dbinv']);
    }

    $ResponseXML .= "<sales_table><![CDATA[<table class=\"table\"><tr>
                        <th style=\"width: 120px;\"></th>
                        <th></th>
                        <th style=\"width: 10px;\"></th>
                        <th style=\"width: 120px;\"></th>
                        <th style=\"width: 10px;\"></th>
                    </tr>";


    $i = 1;
    $mtot = 0;
    $sql = "Select * from tmp_che_data where tmp_no='" . $_GET['tmpno'] . "' AND form_no='PCH'";
    foreach ($conn->query($sql) as $row) {
        $ResponseXML .= "<tr>                              
                         <td>" . $row['code'] . "</td>
                         <td>" . $row['descript'] . "</td>
                         <td></td>
                         <td>" . number_format($row['amount'], 2, ".", ",") . "</td>
                         <td><a class=\"btn btn-danger btn-xs\" onClick=\"del_item('" . $row['id'] . "','PCH')\"> <span class='fa fa-remove'></span></a></td>
                         </tr>";
        $mtot = $mtot + $row['amount'];
        $i = $i + 1;
    }

    $ResponseXML .= "</table>]]></sales_table>";
    $ResponseXML .= "<form><![CDATA[PCH]]></form>";
    $ResponseXML .= "<item_count><![CDATA[" . $i . "]]></item_count>";
    $ResponseXML .= "<subtot><![CDATA[" . number_format($mtot, 2, ".", ",") . "]]></subtot>";
    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}

if ($_GET["Command"] == "del_item") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";


    $sql = "delete from tmp_che_data where id='" . $_GET['code'] . "' and tmp_no='" . $_GET['invno'] . "' AND form_no='PCH'";
    $result = $conn->query($sql);


    $ResponseXML .= "<sales_table><![CDATA[<table class=\"table\">
	            <tr>
                        <th style=\"width: 120px;\"></th>
                        <th></th>
                        <th style=\"width: 10px;\"></th>
                        <th style=\"width: 120px;\"></th>
                        <th style=\"width: 10px;\"></th>
                    </tr>";

    $i = 1;
    $mtot = 0;
    $sql = "Select * from tmp_che_data where tmp_no='" . $_GET['invno'] . "' AND form_no='PCH'";
    foreach ($conn->query($sql) as $row) {
        $ResponseXML .= "<tr>                              
                         <td>" . $row['code'] . "</td>
                         <td>" . $row['descript'] . "</td>
                         <td></td>
                         <td>" . number_format($row['amount'], 2, ".", ",") . "</td>
                         <td><a  class=\"btn btn-danger btn-xs\" onClick=\"del_item('" . $row['id'] . "','PCH')\"> <span class='fa fa-remove'></span></a></td>
                         </tr>";
        $mtot = $mtot + $row['amount'];
        $i = $i + 1;
    }

    $ResponseXML .= "</table>]]></sales_table>";
    $ResponseXML .= "<item_count><![CDATA[" . $i . "]]></item_count>";
    $ResponseXML .= "<form><![CDATA[PCH]]></form>";
    $ResponseXML .= "<subtot><![CDATA[" . number_format($mtot, 2, ".", ",") . "]]></subtot>";
    $ResponseXML .= " </salesdetails>";
    echo $ResponseXML;
}

if ($_GET["Command"] == "save_item") {

    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        $sql = "select CR_REFNO from s_cheq where tmp_no ='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {
            $invno = $row['CR_REFNO'];
            $sql = "delete from s_cheq where CR_REFNO = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from ledger where l_refno = '" . $invno . "'";
            $conn->exec($sql);
        } else {
            $invno = getno();
            $sql = "update dep_mas set CHE_RET=CHE_RET+1";
            $conn->exec($sql);
        }

        



        $sql = "Insert into s_cheq (cr_refno, cr_date, cheno, code, name, barer, naration, heading, amount, type, cancel,Currency,rate,tmp_no) "
                . "Values ('" . trim($invno) . "', '" . $_GET["entrydate"] . "', '" . $_GET['txt_chequeno'] . "', '" . $_GET['bank'] . "', '', '" . $_GET['customername'] . "', '" . trim($_GET['txt_narration']) . "', '" . trim($_GET['txt_narration']) . "','" . $_GET['txt_payments'] . "','B','0','" . $_GET["currency"] . "','" . $_GET["txt_rate"] . "','" . $_GET['tmpno'] . "')";
        $conn->exec($sql);

        $amo = $_GET['txt_payments'] * $_GET["txt_rate"];
        $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo,chno) value
                   ('" . $invno . "','" . $_GET["entrydate"] . "','" . $_GET["bank"] . "','" . $amo . "','CAP','CRE','" . $_GET['txt_narration'] . "','" . $_GET["currency"] . "','" . $_GET["txt_rate"] . "','" . $_GET['txt_payments'] . "','" . $_GET['txt_chequeno'] . "')";
        $conn->exec($sql);

        $sql = "Select * from tmp_che_data where tmp_no='" . $_GET['tmpno'] . "' AND form_no='PCH'";
        foreach ($conn->query($sql) as $row) {
            $amo = $row['amount'] * $_GET["txt_rate"];
            $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo) value
                   ('" . $invno . "','" . $_GET["entrydate"] . "','" . $row['code'] . "','" . $amo . "','CAP','DEB','" . $_GET['txt_narration'] . "','" . $_GET["currency"] . "','" . $_GET["txt_rate"] . "','" . $row['amount'] . "')";
            $conn->exec($sql);
        }

        $conn->commit();
        echo "Saved";
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Failed";
    }
}
?>