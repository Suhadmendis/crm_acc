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
    $sql = "select PAYCHEQ from dep_mas  where current_year = '" . $ayear . "'";
    $result = $conn->query($sql);
    $row = $result->fetch();
    $tmpinvno = "00000" . $row["PAYCHEQ"];
    $lenth = strlen($tmpinvno);
    $invno = trim("PCH-") . substr($ayear,2) . "/" . substr(($ayear+1),2) . "-" . substr($tmpinvno, $lenth - 5);

    return $invno;
}


if ($_GET["Command"] == "getno") {

    require_once './gl_posting.php';
    $ayear = ac_year($_GET['sdate']);

    $invno = getno($_GET['sdate']);
    $sdate = $_GET['sdate'];

    $sql = "select REFNO,bdate from paymas where tmp_no ='" . $_GET['tmpno'] . "'";
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

if ($_GET["Command"] == "pass_bal") {


    $sql = "select * from c_bal where refno = '" . $_GET['refno'] . "'";
    $result = $conn->query($sql);
    if ($row = $result->fetch()) {


        $ResponseXML = "";
        $ResponseXML .= "<salesdetails>";
        $ResponseXML .= "<refno><![CDATA[" . $row['REFNO'] . "]]></refno>";
        $ResponseXML .= "<id><![CDATA[" . $row['ID'] . "]]></id>";
        $ResponseXML .= "<curamo><![CDATA[" . $row['curamo'] . "]]></curamo>";
        $ResponseXML .= "<curbal><![CDATA[" . $row['curbal'] . "]]></curbal>";


        $ResponseXML .= "</salesdetails>";
        echo $ResponseXML;
    }
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




if ($_GET["Command"] == "add_bal") {



    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";


    if (!isset($_GET['com1'])) {
        $sql = "delete from tmp_chepay where prefno ='" . $_GET['txt_pref'] . "'";
        $result = $conn->query($sql);

        $sql = "Insert into tmp_chepay (prefno, pinvo, pamo, pbal,ppay,tmp_no)values
			('" . $_GET['txt_pref'] . "', '" . $_GET['txt_pinv'] . "', " . $_GET['txt_pamo'] . ",'" . $_GET['txt_pbal'] . "','" . $_GET['txt_ppay'] . "','" . $_GET['tmpno'] . "') ";
        $result = $conn->query($sql);
    } else {
        $sql = "delete from tmp_chepay where id ='" . $_GET['code'] . "'";
        $result = $conn->query($sql);
    }


    $ResponseXML .= "<sales_table><![CDATA[<table class=\"table\"><tr>
                         <th style=\"width: 80px;\">Ref No</th>
                            <th style=\"width: 80px;\">Invoice No</th>
                            <th style=\"width: 80px;\">Amount</th>
                            <th style=\"width: 80px;\">Balance</th>
                            <th style=\"width: 80px;\">Payment</th>
                            <th style=\"width: 1px;\"></th>
                            <th style=\"width: 1px;\"></th>";

    $mtot = 0;
    $sql = "Select * from tmp_chepay where tmp_no='" . $_GET['tmpno'] . "'";
    foreach ($conn->query($sql) as $row) {
        $ResponseXML .= "<tr>
                         <td>" . $row['prefno'] . "</td>
                         <td>" . $row['pinvo'] . "</td>
                         <td>" . number_format($row['pamo'], 2, ".", ",") . "</td>
                         <td>" . number_format($row['pbal'], 2, ".", ",") . "</td>
                         <td>" . number_format($row['ppay'], 2, ".", ",") . "</td>
                         <td><a class=\"btn btn-danger btn-xs\" onClick=\"del_bal('" . $row['id'] . "')\"> <span class='fa fa-remove'></span></a></td>
                         </tr>";
        $mtot = $mtot + $row['ppay'];
    }



    $ResponseXML .= "</table>]]></sales_table>";
    $ResponseXML .= "<subtot><![CDATA[" . number_format($mtot, 2, ".", ",") . "]]></subtot>";
    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}



if ($_GET["Command"] == "add_tmp") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";



    if ($_GET['itemCode'] != "") {
        $sql = "Insert into tmp_che_data (code, descript, amount, tmp_no,form_no,descript1)values
			('" . $_GET['itemCode'] . "', '" . $_GET['itemDesc'] . "', " . $_GET['itemPrice'] . ",'" . $_GET['tmpno'] . "','PCH','" . $_GET['itemDesc1'] . "') ";
    }

    $result = $conn->query($sql);
    if (!$result) {
        echo $sql . "<br>";
        echo mysqli_error($GLOBALS['dbinv']);
    }

    $ResponseXML .= "<sales_table><![CDATA[<table class=\"table\"><tr>
                        <th style=\"width: 120px;\"></th>
                        <th style=\"width: 420px;\"></th>
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
                         <td>" . $row['descript1'] . "</td>
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
    $ResponseXML .= "<subtot><![CDATA[" . number_format($mtot, 2, ".", "") . "]]></subtot>";
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
                        <th style=\"width: 420px;\"></th>
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
                         <td>" . $row['descript1'] . "</td>
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

    require_once './gl_posting.php';
    $ayear = ac_year($_GET["invdate"]);




    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        $sql = "select REFNO from paymas where tmp_no ='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {
            $invno = $row['REFNO'];
            $sql = "delete from paymas where REFNO = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from ledger where l_refno = '" . $invno . "'";
            $conn->exec($sql);


            $sql = "Select * from pay_invdetails where refno='" . $invno . "'";
            foreach ($conn->query($sql) as $row1) {
                $sql = "update c_bal set balance = balance + '" . $row1['PAID'] . "',curbal=curbal + '" . $row1["Currencypaid"] . "' where refno = '" . $row1["INVNO"] . "'";
                $conn->exec($sql);
            }

            $sql = "delete from pay_invdetails where refno = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from cbal_sttr where st_refno = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from c_master where ref_no1 = '" . $invno . "'";
            $conn->exec($sql);
        } else {
            $invno = getno($_GET["invdate"]);
            $sql = "update dep_mas set PAYCHEQ=PAYCHEQ+1 where current_year = '" . $ayear . "'";
            $conn->exec($sql);
        }

        if ($_GET["acpay"] == "1") {
            $mTick = 1;
        } else {
            $mTick = 0;
        }

        if ($_GET['txt_svat'] == "") {
            $msvat = 0;
        } else {
            $msvat = floatval($_GET['txt_svat']);
        }

        if ($_GET['txt_bankamo'] == "") {
            $bank_rate = 0;
        } else {
            $bank_rate = floatval($_GET['txt_bankamo']);
        }

        $sql = "Insert into paymas (chq_barer,refno, bdate, cheno, code, name, barer, naration, heading, amount, type, cancel, ac_payee,Currency,rate,Currency1,rate1,SVAT,tmp_no,bank_rate,ven_code) "
                . "Values ('" . $_GET["chq_barer"] . "','" . trim($invno) . "', '" . $_GET["invdate"] . "', '" . $_GET['cheq_no'] . "', '" . $_GET['bank'] . "', '', '" . $_GET['customername'] . "', '" . trim($_GET['txt_narration']) . "', '" . trim($_GET['txt_narration']) . "','" . $_GET['txt_payments'] . "','B','0','" . $mTick . "','" . $_GET["currency"] . "','" . $_GET["txt_rate"] . "','" . $_GET["currency1"] . "','" . $_GET["txt_rate1"] . "','" . $msvat . "','" . $_GET['tmpno'] . "','" . $bank_rate . "','" . $_GET['customercode'] . "')";
        $conn->exec($sql);

        $amo = $_GET['txt_payments'] * $_GET["txt_rate"];
        $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo,chno,acyear) value
                   ('" . $invno . "','" . $_GET["invdate"] . "','" . $_GET["bank"] . "','" . $amo . "','CAP','CRE','" . $_GET['txt_narration'] . "','" . $_GET["currency"] . "','" . $_GET["txt_rate"] . "','" . $_GET['txt_payments'] . "','" . $_GET['cheq_no'] . "','" . $ayear . "')";
        $conn->exec($sql);

        $sql = "Select * from tmp_che_data where tmp_no='" . $_GET['tmpno'] . "' AND form_no='PCH'";
        foreach ($conn->query($sql) as $row) {
            $amo = $row['amount'] * $_GET["txt_rate"];
            if ($row['descript1'] !="") {
            $lmem = $row['descript1'];
            } else {
            $lmem = $_GET['txt_narration'];
            }
            $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo,acyear) value
                   ('" . $invno . "','" . $_GET["invdate"] . "','" . $row['code'] . "','" . $amo . "','CAP','DEB','" . $lmem . "','" . $_GET["currency"] . "','" . $_GET["txt_rate"] . "','" . $row['amount'] . "','" . $ayear . "')";
            $conn->exec($sql);
        }


        $sql = "Select sum(ppay) as ppay from tmp_chepay where tmp_no='" . $_GET['tmpno'] . "'";
        $result_c = $conn->query($sql);
        if ($row_c = $result->fetch()) {
            $totcur = $row_c["ppay"];
        }

        $sql = "Select * from tmp_chepay where tmp_no='" . $_GET['tmpno'] . "'";
        foreach ($conn->query($sql) as $row) {


            $bb = 0;
            if ($_GET["currency"] == $_GET["currency1"]) {
                $bb = $row["ppay"] * $_GET["txt_rate"];
            } else {
                $bb = $row["ppay"] / $_GET['txt_bankamo'];
                $bb = $row["ppay"] / $bb;
                $bb = $bb * $_GET["txt_rate1"];
            }


            $sql = "insert into cbal_sttr (ST_REFNO, ST_DATE, ST_INVONO, ST_PAID, Currencypaid,cur,rate) values
	  ('" . $invno . "', '" . $_GET["invdate"] . "', '" . $row["prefno"] . "', " . $bb . ", '" . $row["ppay"] . "','" . $_GET["currency1"] . "','" . $_GET["txt_rate"] . "')";
            $conn->exec($sql);

            $sql = "insert into pay_invdetails (REFNO, INVNO, AMOUNT,presntbalance, PAID, Currencypaid,cur,rate,invoice) values
	  ('" . $invno . "', '" . $row["prefno"] . "','" . $row["pamo"] . "','" . $row["pbal"] . "', " . $bb . ", '" . $row["ppay"] . "','" . $_GET["currency1"] . "','" . $_GET["txt_rate"] . "','" . $row["pinvo"] . "')";
            $conn->exec($sql);

            $sql = "insert into c_master (ref_no, ref_no1, sdate, c_code, amo,cur,rate,type) values
	   ('" . $row["prefno"] . "', '" . $invno . "', '" . $_GET["invdate"] . "','" . trim($_GET['customercode']) . "', '" . $row["ppay"] . "','" . $_GET["currency1"] . "','" . $_GET["txt_rate"] . "','CRE')";
            $conn->exec($sql);

            $sql = "update c_bal set balance = balance - '" . $bb . "',curbal=curbal - '" . $row["ppay"] . "' where refno = '" . $row["prefno"] . "'";
            $conn->exec($sql);
        }


        $conn->commit();
        echo "Saved";
    } catch (Exception $e) {
        $conn->rollBack();
        echo $e;
    }
}


if ($_GET["Command"] == "pass_rec") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";
    $sql = "Select * from paymas where REFNO='" . $_GET['refno'] . "'";
    $result = $conn->query($sql);

    if ($row = $result->fetch()) {
        $ResponseXML .= "<C_REFNO><![CDATA[" . $row["REFNO"] . "]]></C_REFNO>";
        $ResponseXML .= "<C_DATE><![CDATA[" . $row["BDATE"] . "]]></C_DATE>";

        $ResponseXML .= "<chq_barere><![CDATA[" . $row["chq_barer"] . "]]></chq_barere>";


        $ResponseXML .= "<txt_remarks><![CDATA[" . $row["NARATION"] . "]]></txt_remarks>";

        $ResponseXML .= "<currency><![CDATA[" . $row["Currency"] . "]]></currency>";
        $ResponseXML .= "<txt_rate><![CDATA[" . $row["rate"] . "]]></txt_rate>";


        $ResponseXML .= "<currency1><![CDATA[" . $row["Currency1"] . "]]></currency1>";
        $ResponseXML .= "<txt_rate1><![CDATA[" . $row["rate1"] . "]]></txt_rate1>";


        $ResponseXML .= "<tmp_no><![CDATA[" . $row["tmp_no"] . "]]></tmp_no>";

        $ResponseXML .= "<C_CODE><![CDATA[" . $row["VEN_CODE"] . "]]></C_CODE>";
        $ResponseXML .= "<name><![CDATA[" . $row["Barer"] . "]]></name>";
        $ResponseXML .= "<cheq_no><![CDATA[" . $row["CHENO"] . "]]></cheq_no>";
        $ResponseXML .= "<svat><![CDATA[" . $row['svat'] . "]]></svat>";
        $ResponseXML .= "<txt_bankamo><![CDATA[" . $row['bank_rate'] . "]]></txt_bankamo>";

        $ResponseXML .= "<amount><![CDATA[" . number_format($row['AMOUNT'], 2, ".", "")  . "]]></amount>";
        $ResponseXML .= "<code><![CDATA[" . $row['CODE'] . "]]></code>";
        
         
        $ResponseXML .= "<ac_payee><![CDATA[" . $row['AC_Payee'] . "]]></ac_payee>";

 
        $msg = "";
        if ($row['Cancel'] == "1") {
            $msg = "Cancelled";
        }
        $ResponseXML .= "<msg><![CDATA[" . $msg . "]]></msg>";


        $ResponseXML .= "<sales_table1><![CDATA[<table class=\"table\"><tr>
                         <th style=\"width: 80px;\">Ref No</th>
                            <th style=\"width: 80px;\">Invoice No</th>
                            <th style=\"width: 80px;\">Amount</th>
                            <th style=\"width: 80px;\">Balance</th>
                            <th style=\"width: 80px;\">Payment</th>
                            <th style=\"width: 1px;\"></th>
                            <th style=\"width: 1px;\"></th>";

        $sql = "delete from tmp_chepay where  tmp_no='" . $row["tmp_no"] . "'";
        $result = $conn->query($sql);

        $sql = "Select * from pay_invdetails where refno='" . $row["REFNO"] . "'";
        foreach ($conn->query($sql) as $row1) {
            $sql = "Insert into tmp_chepay (prefno, pinvo, pamo, pbal,ppay,tmp_no)values
			('" . $row1['INVNO'] . "', '" . $row1['invoice'] . "', " . $row1['AMOUNT'] . ",'" . $row1['presntbalance'] . "','" . $row1['Currencypaid'] . "','" . $row['tmp_no'] . "') ";
            $result = $conn->query($sql);
        }
        $sql = "Select * from tmp_chepay where tmp_no='" . $row["tmp_no"] . "'";
        foreach ($conn->query($sql) as $row1) {
            $ResponseXML .= "<tr>
                         <td>" . $row1['prefno'] . "</td>
                         <td>" . $row1['pinvo'] . "</td>
                         <td>" . number_format($row1['pamo'], 2, ".", ",") . "</td>
                         <td>" . number_format($row1['pbal'], 2, ".", ",") . "</td>
                         <td>" . number_format($row1['ppay'], 2, ".", ",") . "</td>
                         <td><a class=\"btn btn-danger btn-xs\" onClick=\"del_bal('" . $row1['id'] . "')\"> <span class='fa fa-remove'></span></a></td>
                         </tr>";
        }



        $ResponseXML .= "</table>]]></sales_table1>";


        $sql = "delete from tmp_che_data where  tmp_no='" . $row["tmp_no"] . "'";
        $result = $conn->query($sql);

        $sql = "Select L_CODE,C_NAME,curamo,L_LMEM from view_ledger where l_refno='" . $row["REFNO"] . "' AND l_flag1='DEB'";
        foreach ($conn->query($sql) as $row1) {
            $sql = "Insert into tmp_che_data (code, descript, amount, tmp_no,form_no,descript1)values
			('" . $row1['L_CODE'] . "', '" . $row1['C_NAME'] . "', " . $row1['curamo'] . ",'" . $row['tmp_no'] . "','PCH','" . $row1['L_LMEM'] ."') ";
            $result = $conn->query($sql);
        }


        $ResponseXML .= "<sales_table><![CDATA[<table class=\"table\"><tr>
                        <th style=\"width: 120px;\"></th>
                        <th style=\"width: 420px;\"></th>
                        <th></th>
                        <th style=\"width: 10px;\"></th>
                        <th style=\"width: 120px;\"></th>
                        <th style=\"width: 10px;\"></th>
                    </tr>";
        $i = 1;
        $mtot = 0;
        $sql = "Select * from tmp_che_data where tmp_no='" . $row["tmp_no"] . "' AND form_no='PCH'";
        foreach ($conn->query($sql) as $row1) {
            $ResponseXML .= "<tr>
                         <td>" . $row1['code'] . "</td>
                         <td>" . $row1['descript'] . "</td>
                         <td>" . $row1['descript1'] . "</td>
                         <td>" . number_format($row1['amount'], 2, ".", ",") . "</td>
                         <td><a class=\"btn btn-danger btn-xs\" onClick=\"del_item('" . $row1['id'] . "','CN1')\"> <span class='fa fa-remove'></span></a></td>
                         </tr>";
            $mtot = $mtot + $row1['amount'];
            $i = $i + 1;
        }
        $ResponseXML .= "</table>]]></sales_table>";
        $ResponseXML .= "<subtot><![CDATA[" . number_format($mtot, 2, ".", ",") . "]]></subtot>";


        $prev = "";

        $sql = "select * from docs where refno = '" . $row["REFNO"] . "'";
        foreach ($conn->query($sql) as $row2) {

            $filetype = pathinfo($row2['loc'], PATHINFO_EXTENSION);
            $filetype = "application/" . $filetype;

            $prev .= "<div data-fileindex='3' width='160px' height='160px' id='preview-1474208198337-3' class='col-sm-2'>


                    <object width='160px' height='160px' type='" . $filetype . "' data='" . $row2['loc'] . "'>
                        <div  class='file-preview-other'>
                            <span  class='file-icon-4x'><i    class='glyphicon glyphicon-king'></i></span>
                        </div>
                    </object>

                    <div width='160px' class='file-thumbnail-footer'>
                        <div  title='" . $row2['file_name'] . "'  class='file-footer-caption'>" . $row2['file_name'] . "</div>

                        <div  class='file-actions'>
                            <div class='file-footer-buttons'>
                                <a href='" . $row2['loc'] . "' download='" . $row2['file_name'] . "'><i class='glyphicon glyphicon-circle-arrow-down'></i></a>
                                <button title='Remove file' class='kv-file-remove btn btn-xs btn-default' type='button'><i class='glyphicon glyphicon-trash text-danger'></i></button>
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




if ($_GET["Command"] == "update_list") {
    $ResponseXML = "";
    $ResponseXML .= "<table class=\"table\">
	            <tr>
                        <th width=\"121\">Reference No</th>
                        <th width=\"121\">Date</th>

                        <th width=\"200\">Naration</th>
                        <th width=\"121\">Amount</th>
                    </tr>";


    $sql = "select REFNO, BDATE,NARATION,ID,AMOUNT from paymas where REFNO <> ''";
    if ($_GET['refno'] != "") {
        $sql .= " and REFNO like '%" . $_GET['refno'] . "%'";
    }
    if ($_GET['cusname'] != "") {
        $sql .= " and NARATION like '%" . $_GET['cusname'] . "%'";
    }
    $stname = $_GET['stname'];

    $sql .= " ORDER BY ID desc limit 50";

    foreach ($conn->query($sql) as $row) {
        $cuscode = $row["REFNO"];


        $ResponseXML .= "<tr>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['REFNO'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['BDATE'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['NARATION'] . "</a></td>

                                      <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['AMOUNT'] . "</a></td>
                            </tr>";
    }
    $ResponseXML .= "</table>";
    echo $ResponseXML;
}



if ($_GET["Command"] == "del_inv") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";
    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        $sql = "select REFNO,Cancel from paymas where tmp_no ='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {
            if ($row['Cancel'] != "0") {
                echo "Already Cancelled";
                exit();
            }








            $invno = $row['REFNO'];
            $sql = "update paymas set Cancel='1'  where REFNO = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from ledger where l_refno = '" . $invno . "'";
            $conn->exec($sql);

            $sql = "Select * from pay_invdetails where refno='" . $invno . "'";
            foreach ($conn->query($sql) as $row1) {
                $sql = "update c_bal set balance = balance + '" . $row1['PAID'] . "',curbal=curbal + '" . $row1["Currencypaid"] . "' where refno = '" . $row1["INVNO"] . "'";
                $conn->exec($sql);
            }

            $sql = "delete from pay_invdetails where refno = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from cbal_sttr where st_refno = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from c_master where ref_no1 = '" . $invno . "'";
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
?>
