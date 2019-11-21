<?php

session_start();

////////////////////////////////////////////// Database Connector /////////////////////////////////////////////////////////////
require_once ("./connection_sql.php");

////////////////////////////////////////////// Write XML ////////////////////////////////////////////////////////////////////
header('Content-Type: text/xml');

date_default_timezone_set('Asia/Colombo');

/////////////////////////////////////// GetValue //////////////////////////////////////////////////////////////////////////
///////////////////////////////////// Registration /////////////////////////////////////////////////////////////////////////


function getno($sdate) {


    require_once './gl_posting.php';
    $ayear = ac_year($sdate);

    include './connection_sql.php';
    $sql = "select RECEDIRECT from dep_mas  where current_year = '" . $ayear . "'";
    $result = $conn->query($sql);
    $row = $result->fetch();
    $tmpinvno = "00000" . $row["RECEDIRECT"];
    $lenth = strlen($tmpinvno);
    $invno = trim("DRE-") . substr($ayear,2) . "/" . substr(($ayear+1),2) . "-" . substr($tmpinvno, $lenth - 5);

    return $invno;
}




if ($_GET["Command"] == "new_inv") {



    $invno = getno(date('Y-m-d'));

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
    $ResponseXML .= "<dt><![CDATA[" . date('Y-m-d') . "]]></dt>";
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
                         <td><a class=\"btn btn-default btn-xs\" onClick=\"del_item('" . $row['id'] . "','PCH')\"> <span class='fa fa-remove'></span></a></td>
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
                         <td><a  class=\"btn btn-danger btn-sm\" onClick=\"del_item('" . $row['id'] . "','PCH')\"> <span class='fa fa-remove'></span></a></td>
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


if ($_GET["Command"] == "getno") {

    require_once './gl_posting.php';
    $ayear = ac_year($_GET['sdate']);

    $invno = getno($_GET['sdate']);
    $sdate = $_GET['sdate'];


    $sql = "select REFNO,bdate from recmas where tmp_no ='" . $_GET['tmpno'] . "'";
    $result = $conn->query($sql);
    if ($row = $result->fetch()) {
        $invno = $row['REFNO'];

        if ($ayear != ac_year($row['bdate'])) {
           $sdate = $row['bdate'];
        }
    }

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";
    $ResponseXML .= "<invno><![CDATA[" . $invno . "]]></invno>";
    $ResponseXML .= "<tmpno><![CDATA[" . $_GET['tmpno'] . "]]></tmpno>";
    $ResponseXML .= "<dt><![CDATA[" . $sdate . "]]></dt>";
    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}


if ($_GET["Command"] == "save_item") {


        require_once './gl_posting.php';
    $ayear = ac_year($_GET["entrydate"]);
    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        $sql = "select REFNO from recmas where tmp_no ='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {
            $invno = $row['REFNO'];
            $sql = "delete from recmas where REFNO = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from ledger where l_refno = '" . $invno . "'";
            $conn->exec($sql);
        } else {
            $invno = getno($_GET["entrydate"]);
            $sql = "update dep_mas set RECEDIRECT=RECEDIRECT+1  where current_year = '" . $ayear . "'";
            $conn->exec($sql);
        }





        $sql = "Insert into recmas (refno, bdate, cheno, code, name, barer, naration, heading, amount, type, cancel,Currency,rate,tmp_no,ven_code) "
                . "Values ('" . trim($invno) . "', '" . $_GET["entrydate"] . "', '" . $_GET['txt_chequeno'] . "', '" . $_GET['bank'] . "', '', '" . $_GET['customername'] . "', '" . trim($_GET['txt_narration']) . "', '" . trim($_GET['txt_narration']) . "','" . $_GET['txt_payments'] . "','B','0','" . $_GET["currency"] . "','" . $_GET["txt_rate"] . "','" . $_GET['tmpno'] . "','" . $_GET['customercode'] . "')";
        $conn->exec($sql);

        $amo = $_GET['txt_payments'] * $_GET["txt_rate"];
        $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo,chno,acyear) value
                   ('" . $invno . "','" . $_GET["entrydate"] . "','" . $_GET["bank"] . "','" . $amo . "','CAP','DEB','" . $_GET['txt_narration'] . "','" . $_GET["currency"] . "','" . $_GET["txt_rate"] . "','" . $_GET['txt_payments'] . "','" . $_GET['txt_chequeno'] . "','" . $ayear . "')";
        $conn->exec($sql);

        $sql = "Select * from tmp_che_data where tmp_no='" . $_GET['tmpno'] . "' AND form_no='PCH'";
        foreach ($conn->query($sql) as $row) {
            $amo = $row['amount'] * $_GET["txt_rate"];
            $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo,acyear) value
                   ('" . $invno . "','" . $_GET["entrydate"] . "','" . $row['code'] . "','" . $amo . "','CAP','CRE','" . $_GET['txt_narration'] . "','" . $_GET["currency"] . "','" . $_GET["txt_rate"] . "','" . $row['amount'] . "','" . $ayear . "')";
            $conn->exec($sql);
        }

        $conn->commit();
        echo "Saved";
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Failed";
    }
}


if ($_GET["Command"] == "pass_rec") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";
    $sql = "Select * from recmas where REFNO='" . $_GET['refno'] . "'";
    $result = $conn->query($sql);

    if ($row = $result->fetch()) {
        $ResponseXML .= "<C_REFNO><![CDATA[" . $row["REFNO"] . "]]></C_REFNO>";
        $ResponseXML .= "<C_DATE><![CDATA[" . $row["BDATE"] . "]]></C_DATE>";
        $ResponseXML .= "<C_CODE><![CDATA[" . $row["VEN_CODE"] . "]]></C_CODE>";
        $ResponseXML .= "<C_NAME><![CDATA[" . $row["BARER"] . "]]></C_NAME>";

        $ResponseXML .= "<BANK><![CDATA[" . $row["CODE"] . "]]></BANK>";


        $ResponseXML .= "<paytype><![CDATA[" . $row["paytype"] . "]]></paytype>";


        $ResponseXML .= "<currency><![CDATA[" . $row["Currency"] . "]]></currency>";
        $ResponseXML .= "<txt_rate><![CDATA[" . $row["rate"] . "]]></txt_rate>";
        $ResponseXML .= "<txt_amount><![CDATA[" . $row["AMOUNT"] . "]]></txt_amount>";

        $ResponseXML .= "<txt_heading><![CDATA[" . $row["HEADING"] . "]]></txt_heading>";
        $ResponseXML .= "<txt_narration><![CDATA[" . $row["NARATION"] . "]]></txt_narration>";




        $ResponseXML .= "<txt_amount_lkr><![CDATA[" . ($row["AMOUNT"] * $row["rate"]) . "]]></txt_amount_lkr>";

        $ResponseXML .= "<tmp_no><![CDATA[" . $row["tmp_no"] . "]]></tmp_no>";
        $msg = "";
        if ($row['CANCEL'] == "1") {
            $msg = "Cancelled";
        }
        $ResponseXML .= "<msg><![CDATA[" . $msg . "]]></msg>";



        $ResponseXML .= "<cheq_no><![CDATA[" . $row["CHENO"] . "]]></cheq_no>";
        $ResponseXML .= "<cheq_date><![CDATA[" . $row["iNVDATE"] . "]]></cheq_date>";









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
        $sql = "delete from tmp_che_data where  tmp_no='" . $row["tmp_no"] . "'";
        $result = $conn->query($sql);
        $sql = "Select L_CODE,C_NAME,L_AMOUNT from view_ledger where l_refno='" . $row["REFNO"] . "' AND l_flag1='cre'";
        foreach ($conn->query($sql) as $row1) {
            $sql = "Insert into tmp_che_data (code, descript, amount, tmp_no,form_no)values
			('" . $row1['L_CODE'] . "', '" . $row1['C_NAME'] . "', " . $row1['L_AMOUNT'] . ",'" . $row['tmp_no'] . "','PCH') ";
            $result = $conn->query($sql);
        }
        $sql = "Select * from tmp_che_data where tmp_no='" . $row["tmp_no"] . "' AND form_no='PCH'";
        foreach ($conn->query($sql) as $row1) {
            $ResponseXML .= "<tr>
                         <td>" . $row1['code'] . "</td>
                         <td>" . $row1['descript'] . "</td>
                         <td></td>
                         <td>" . number_format($row1['amount'], 2, ".", ",") . "</td>
                         <td><a class=\"btn btn-danger btn-xs\" onClick=\"del_item('" . $row1['id'] . "','CN1')\"> <span class='fa fa-remove'></span></a></td>
                         </tr>";
            $mtot = $mtot + $row1['amount'];
            $i = $i + 1;
        }
        $ResponseXML .= "</table>]]></sales_table>";
        $ResponseXML .= "<subtot1><![CDATA[" . number_format($mtot, 2, ".", ",") . "]]></subtot1>";

        $prev = "";

        $sql = "select * from docs where refno = '" . $row["REFNO"] . "'";
        foreach ($conn->query($sql) as $row2) {

            $filetype = pathinfo($row2['loc'], PATHINFO_EXTENSION);
            $filetype = "application/" . $filetype;

            $prev .= "<div data-fileindex='3' width='160px' height='160px' id='" . $row2['id'] . "'  class='col-sm-2'>


                    <object width='160px' height='160px' type='" . $filetype . "' data='" . $row2['loc'] . "'>
                        <div  class='file-preview-other'>
                            <span  class='file-icon-4x'><i class='glyphicon glyphicon-king'></i></span>
                        </div>
                    </object>

                    <div width='160px' class='file-thumbnail-footer'>
                        <div  title='" . $row2['file_name'] . "'  class='file-footer-caption'>" . $row2['file_name'] . "</div>

                        <div  class='file-actions'>
                            <div class='file-footer-buttons'>

                                <a href='" . $row2['loc'] . "' download='" . $row2['file_name'] . "'><i class='glyphicon glyphicon glyphicon-save-file'></i></a>
                                <a onclick='removefile(" . $row2['id'] . ")'><i class='glyphicon glyphicon glyphicon-trash'></i></a>

                            </div>
                            <div class='clearfix'></div>
                        </div>
                    </div>
                </div> ";
        }

        $ResponseXML .= "<filebox><![CDATA[" . $prev . "]]></filebox>";
    }

    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}




if ($_GET["Command"] == "del_inv") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";
    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        $sql = "select REFNO,CANCEL from recmas where tmp_no ='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {
            if ($row['CANCEL'] != "0") {
                echo "Already Cancelled";
                exit();
            }



            $sql = "delete from ledger where l_refno = '" . $row['REFNO'] . "'";
            $conn->exec($sql);
            $sql = "update recmas set CANCEL='1' where refno = '" . $row['REFNO'] . "'";
            $conn->exec($sql);



            echo "ok";
            $conn->commit();
        } else {
            echo "Entry Not Found";
        }
    } catch (Exception $e) {
        $conn->rollBack();
        echo $e;
    }
}

if ($_GET["Command"] == "update_list") {
    $ResponseXML = "";
    $ResponseXML .= "<table class=\"table\">
	            <tr>
                        <th width=\"121\">Reference No</th>
                        <th width=\"121\">Date</th>

                        <th width=\"200\">Remarks</th>

                    </tr>";


    $sql = "select REFNO, BDATE,BARER,AMOUNT,ID from recmas where REFNO <> ''";
    if ($_GET['refno'] != "") {
        $sql .= " and REFNO like '%" . $_GET['refno'] . "%'";
    }
    if ($_GET['cusname'] != "") {
        $sql .= " and BARER like '%" . $_GET['cusname'] . "%'";
    }
    $stname = $_GET['stname'];

    $sql .= " ORDER BY ID desc limit 50";

    foreach ($conn->query($sql) as $row) {
        $cuscode = $row["REFNO"];


        $ResponseXML .= "<tr>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['REFNO'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['BDATE'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['BARER'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['AMOUNT'] . "</a></td>
                            </tr>";
    }
    $ResponseXML .= "</table>";
    echo $ResponseXML;
}

?>
