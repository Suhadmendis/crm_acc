<?php

session_start();

////////////////////////////////////////////// Database Connector /////////////////////////////////////////////////////////////
require_once ("connection_sql.php");

////////////////////////////////////////////// Write XML ////////////////////////////////////////////////////////////////////
header('Content-Type: text/xml');

date_default_timezone_set('Asia/Colombo');

/////////////////////////////////////// GetValue //////////////////////////////////////////////////////////////////////////
///////////////////////////////////// Registration ////////////////////////////////////////////////////////////////////////

if ($_GET["Command"] == "new_inv") {


    $invno = getno(date('Y-m-d'));

    $sql = "Select CRENO from tmpinvpara_acc";
    $result = $conn->query($sql);
    $row = $result->fetch();

    $tono = $row['CRENO'];

    $sql = "delete from tmp_che_data where tmp_no='" . $tono . "' and form_no ='CN'";
    $result = $conn->query($sql);

    $sql = "update tmpinvpara_acc set CRENO=CRENO+1";
    $result = $conn->query($sql);


    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";
    $ResponseXML .= "<invno><![CDATA[" . $invno . "]]></invno>";
    $ResponseXML .= "<tmpno><![CDATA[" . $tono . "]]></tmpno>";
    $ResponseXML .= "<dt><![CDATA[" . date('Y-m-d') . "]]></dt>";
    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}


if ($_GET["Command"] == "getno") {

    require_once './gl_posting.php';
    $ayear = ac_year($_GET['sdate']);

    $invno = getno($_GET['sdate']);
    $sdate = $_GET['sdate'];

    $sql = "select REF_NO,curamo,curtotpay,CANCELL,sdate from s_salma where tmp_no ='" . $_GET['tmpno'] . "'";
    $result = $conn->query($sql);
    if ($row = $result->fetch()) {
        $invno = $row['REF_NO'];

        if ($ayear != ac_year($row['sdate'])) {
           $sdate = $row['sdate'];
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

if ($_GET["Command"] == "add_tmp") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";



    $sql = "Insert into tmp_che_data (code, descript, amount, tmp_no,form_no)values
			('" . $_GET['itemCode'] . "', '" . $_GET['itemDesc'] . "', " . $_GET['itemPrice'] . ",'" . $_GET['tmpno'] . "','" . $_GET['form'] . "') ";

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
    $sql = "Select * from tmp_che_data where tmp_no='" . $_GET['tmpno'] . "' AND form_no='" . $_GET['form'] . "'";
    foreach ($conn->query($sql) as $row) {
        $ResponseXML .= "<tr>
                         <td>" . $row['code'] . "</td>
                         <td>" . $row['descript'] . "</td>
                         <td></td>
                         <td>" . number_format($row['amount'], 2, ".", ",") . "</td>
                         <td><a class=\"btn btn-danger btn-xs\" onClick=\"del_item('" . $row['id'] . "','" . $_GET['form'] . "')\"> <span class='fa fa-remove'></span></a></td>
                         </tr>";
        $mtot = $mtot + $row['amount'];
        $i = $i + 1;
    }

    $ResponseXML .= "</table>]]></sales_table>";
    $ResponseXML .= "<form><![CDATA[" . $_GET['form'] . "]]></form>";
    $ResponseXML .= "<item_count><![CDATA[" . $i . "]]></item_count>";
    $ResponseXML .= "<subtot><![CDATA[" . number_format($mtot, 2, ".", ",") . "]]></subtot>";
    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}

if ($_GET["Command"] == "del_inv") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";
    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        $sql = "select REF_NO,curamo,curtotpay,CANCELL from s_salma where REF_NO ='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {
            if ($row['CANCELL'] != "0") {
                echo "Already Cancelled";
                exit();
            }

            if ($row['curtotpay'] > 0) {
                echo "Already Paid";
                exit();
            } else {

                $sql = "delete from ledger where l_refno = '" . $row['REF_NO'] . "'";
                $conn->exec($sql);
                $sql = "update deb set cancell='1' where c_refno = '" . $row['REF_NO'] . "'";
                $conn->exec($sql);

                $sql = "delete from s_salma where REF_NO = '" . $row['REF_NO'] . "'";
                $conn->exec($sql);


                $sql = "delete from c_master where ref_no1 = '" . $row['REF_NO'] . "'";
                $conn->exec($sql);

                echo "ok";
                $conn->commit();
            }
        } else {
            echo "Entry Not Found";
        }
    } catch (Exception $e) {
        $conn->rollBack();
        echo $e;
    }
}

if ($_GET["Command"] == "del_item") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";

    $sql = "delete from tmp_che_data where id='" . $_GET['code'] . "' and tmp_no='" . $_GET['invno'] . "' AND form_no='" . $_GET['form'] . "'";
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
    $sql = "Select * from tmp_che_data where tmp_no='" . $_GET['invno'] . "' AND form_no='" . $_GET['form'] . "'";
    foreach ($conn->query($sql) as $row) {
        $ResponseXML .= "<tr>
                         <td>" . $row['code'] . "</td>
                         <td>" . $row['descript'] . "</td>
                         <td></td>
                         <td>" . number_format($row['amount'], 2, ".", ",") . "</td>
                         <td><a  class=\"btn btn-danger btn-xs\" onClick=\"del_item('" . $row['id'] . "','" . $_GET['form'] . "')\"> <span class='fa fa-remove'></span></a></td>
                         </tr>";
        $mtot = $mtot + $row['amount'];
        $i = $i + 1;
    }

    $ResponseXML .= "</table>]]></sales_table>";
    $ResponseXML .= "<item_count><![CDATA[" . $i . "]]></item_count>";
    $ResponseXML .= "<form><![CDATA[" . $_GET['form'] . "]]></form>";
    $ResponseXML .= "<subtot><![CDATA[" . number_format($mtot, 2, ".", ",") . "]]></subtot>";
    $ResponseXML .= " </salesdetails>";
    echo $ResponseXML;
}

if ($_GET["Command"] == "save_item") {

    require_once './gl_posting.php';
    $ayear = ac_year($_GET["crndate"]);


    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        $sql = "select REF_NO,curamo,curtotpay,CANCELL from s_salma where tmp_no ='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {

            if ($row['CANCELL'] != "0") {
                echo "Already Cancelled";
                exit();
            }

            if ($row['curtotpay'] > 0) {
                echo "Already Paid";
                exit();
            } else {
                $invno = $row['REF_NO'];
            }
            $sql = "delete from deb where c_refno = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from s_salma where REF_NO = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from ledger where l_refno = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from c_master where ref_no1 = '" . $invno . "'";
            $conn->exec($sql);
        } else {
            $invno = getno($_GET["crndate"]);
            $sql = "update dep_mas set DEBITNOTE=DEBITNOTE+1 where current_year = '" . $ayear . "'";
            $conn->exec($sql);
        }
        $remarks = $_GET['remark'];
        $sql = "Insert into deb (C_REFNO, C_DATE,  C_CODE, C_PAYMENT, C_REMARK,c_name,cur,curamo,Rate,Auto,vatlin,tmp_no) values
		('" . $invno . "', '" . $_GET["crndate"] . "', '" . $_GET["cus_code"] . "', '" . $_GET["amount_lkr"] . "', '" . $remarks . "','" . $_GET["c_name"] . "','" . $_GET["cur"] . "','" . $_GET["amount"] . "','" . $_GET["Rate"] . "','0','" . $_GET["Vat_link"] . "','" . $_GET['tmpno'] . "') ";
        $conn->exec($sql);

        $sql = "Insert into s_salma (REF_NO, SDATE, trn_type, C_CODE, GRAND_TOT, TOTPAY,CUS_NAME,cur,curamo,curtotpay,Rate,tmp_no) values
			('" . $invno . "', '" . $_GET["crndate"] . "', 'CNT', '" . $_GET["cus_code"] . "', '" . $_GET["amount_lkr"] . "', '0','" . $_GET["c_name"] . "','" . $_GET["cur"] . "','" . $_GET["amount"] . "','0','" . $_GET["Rate"] . "','" . $_GET['tmpno'] . "') ";
        $conn->exec($sql);

        $sql = "insert into c_master (ref_no,ref_no1,sdate,c_code,amo,cur,rate,type) values
                ('" . $invno . "','" . $invno . "','" . $_GET["crndate"] . "','" . $_GET["cus_code"] . "','" . $_GET["amount"] . "','" . trim($_GET["cur"]) . "','" . $_GET["Rate"] . "','DEB')";
        $conn->exec($sql);


        $sql = "Select * from tmp_che_data where tmp_no='" . $_GET['tmpno'] . "' AND form_no='CN1'";
        foreach ($conn->query($sql) as $row) {
            $amo = $row['amount'] * $_GET["Rate"];
            $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo,acyear) value
                   ('" . $invno . "','" . $_GET["crndate"] . "','" . $row['code'] . "','" . $amo . "','CRN','DEB','" . $remarks . "','" . $_GET["cur"] . "','" . $_GET["Rate"] . "','" . $row['amount'] . "','" . $ayear . "')";

            $conn->exec($sql);
        }

        $sql = "Select * from tmp_che_data where tmp_no='" . $_GET['tmpno'] . "' AND form_no='CN2'";
        foreach ($conn->query($sql) as $row) {
            $amo = $row['amount'] * $_GET["Rate"];
            $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo,acyear) value
                   ('" . $invno . "','" . $_GET["crndate"] . "','" . $row['code'] . "','" . $amo . "','CRN','CRE','" . $remarks . "','" . $_GET["cur"] . "','" . $_GET["Rate"] . "','" . $row['amount'] . "','" . $ayear . "')";

            $conn->exec($sql);
        }


        $conn->commit();
        echo "Saved";
    } catch (Exception $e) {
        $conn->rollBack();
        echo $e;
    }
}

function getno($sdate) {

    require_once './gl_posting.php';
    $ayear = ac_year($sdate);


    include './connection_sql.php';
    $sql = "select DEBITNOTE from dep_mas  where current_year = '" . $ayear . "'";
    $result = $conn->query($sql);
    $row = $result->fetch();
    $tmpinvno = "00000" . $row["DEBITNOTE"];
    $lenth = strlen($tmpinvno);
    $invno = trim("DBN-") . substr($ayear,2) . "/" . substr(($ayear+1),2) . "-" . substr($tmpinvno, $lenth - 5);

    return $invno;
}

if ($_GET["Command"] == "pass_rec") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";
    $sql = "Select * from deb where C_REFNO='" . $_GET['refno'] . "'";
    $result = $conn->query($sql);

    if ($row = $result->fetch()) {
        $ResponseXML .= "<C_REFNO><![CDATA[" . $row["C_REFNO"] . "]]></C_REFNO>";
        $ResponseXML .= "<C_DATE><![CDATA[" . $row["C_DATE"] . "]]></C_DATE>";
        $ResponseXML .= "<C_CODE><![CDATA[" . $row["C_CODE"] . "]]></C_CODE>";
        $ResponseXML .= "<name><![CDATA[" . $row["c_name"] . "]]></name>";
        $ResponseXML .= "<txt_remarks><![CDATA[" . $row["C_REMARK"] . "]]></txt_remarks>";
        $ResponseXML .= "<currency><![CDATA[" . $row["cur"] . "]]></currency>";
        $ResponseXML .= "<txt_rate><![CDATA[" . $row["rate"] . "]]></txt_rate>";
        $ResponseXML .= "<txt_amount><![CDATA[" . $row["C_PAYMENT"] . "]]></txt_amount>";
        $ResponseXML .= "<txt_amount_lkr><![CDATA[" . $row["curamo"] . "]]></txt_amount_lkr>";
        $ResponseXML .= "<Vat_link><![CDATA[" . $row["vatlin"] . "]]></Vat_link>";
        $ResponseXML .= "<C_REMARK><![CDATA[" . $row["C_REMARK"] . "]]></C_REMARK>";
        $ResponseXML .= "<tmp_no><![CDATA[" . $row["tmp_no"] . "]]></tmp_no>";

        $msg = "";
        if ($row['cancell'] == "1") {
            $msg = "Cancelled";
        }
        $ResponseXML .= "<msg><![CDATA[" . $msg . "]]></msg>";

        $sql = "delete from tmp_che_data where  tmp_no='" . $row["C_REFNO"] . "'";
        $result = $conn->query($sql);


        $sql = "Select L_CODE,C_NAME,L_AMOUNT from view_ledger where l_refno='" . $row["C_REFNO"] . "' AND l_flag1='DEB'";
        foreach ($conn->query($sql) as $row1) {
            $sql = "Insert into tmp_che_data (code, descript, amount, tmp_no,form_no)values
			('" . $row1['L_CODE'] . "', '" . $row1['C_NAME'] . "', " . $row1['L_AMOUNT'] . ",'" . $row['C_REFNO'] . "','CN1') ";
            $result = $conn->query($sql);
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
        $sql = "Select * from tmp_che_data where tmp_no='" . $row["C_REFNO"] . "' AND form_no='CN1'";
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
        $ResponseXML .= "<subtot><![CDATA[" . number_format($mtot, 2, ".", ",") . "]]></subtot>";




        $ResponseXML .= "<sales_table1><![CDATA[<table class=\"table\">
	            <tr>
                        <th style=\"width: 120px;\"></th>
                        <th></th>
                        <th style=\"width: 10px;\"></th>
                        <th style=\"width: 120px;\"></th>
                        <th style=\"width: 10px;\"></th>
                    </tr>";
        $i = 1;
        $mtot = 0;
        $sql = "Select L_CODE,C_NAME,L_AMOUNT,ID from view_ledger where l_refno='" . $row["C_REFNO"] . "' AND l_flag1='CRE'";

        $sql = "Select L_CODE,C_NAME,L_AMOUNT from view_ledger where l_refno='" . $row["C_REFNO"] . "' AND l_flag1='cre'";
        foreach ($conn->query($sql) as $row1) {
            $sql = "Insert into tmp_che_data (code, descript, amount, tmp_no,form_no)values
			('" . $row1['L_CODE'] . "', '" . $row1['C_NAME'] . "', " . $row1['L_AMOUNT'] . ",'" . $row['C_REFNO'] . "','CN2') ";
            $result = $conn->query($sql);
        }
        $sql = "Select * from tmp_che_data where tmp_no='" . $row["C_REFNO"] . "' AND form_no='CN2'";
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
        $ResponseXML .= "</table>]]></sales_table1>";
        $ResponseXML .= "<subtot1><![CDATA[" . number_format($mtot, 2, ".", ",") . "]]></subtot1>";

        $prev = "";

        $sql = "select * from docs where refno = '" . $row["C_REFNO"] . "'";
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




//           $prev .= " </div>
//        </div>
//    </div>
//</div>";


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
                        <th width=\"100\">Code</th>
                        <th width=\"200\">Name</th>
                        <th width=\"121\">Amount</th>
                    </tr>";


    $sql = "select C_REFNO, C_DATE,C_CODE,c_name,C_PAYMENT,ID from deb where c_refno <> ''";
    if ($_GET['refno'] != "") {
        $sql .= " and c_refno like '%" . $_GET['refno'] . "%'";
    }
    if ($_GET['cusname'] != "") {
        $sql .= " and c_name like '%" . $_GET['cusname'] . "%'";
    }
    $stname = $_GET['stname'];

    $sql .= " ORDER BY ID desc limit 50";

    foreach ($conn->query($sql) as $row) {
        $cuscode = $row["C_REFNO"];


        $ResponseXML .= "<tr>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['C_REFNO'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['C_DATE'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['C_CODE'] . "</a></td>
                                  <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['c_name'] . "</a></td>
                                      <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['C_PAYMENT'] . "</a></td>
                            </tr>";
    }
    $ResponseXML .= "</table>";
    echo $ResponseXML;

}
?>
