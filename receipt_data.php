<?php
//ini_set("display_errors", "1");

session_start();


////////////////////////////////////////////// Database Connector /////////////////////////////////////////////////////////////
require_once ("connection_sql.php");

////////////////////////////////////////////// Write XML ////////////////////////////////////////////////////////////////////
header('Content-Type: text/xml');

date_default_timezone_set('Asia/Colombo');

/////////////////////////////////////// GetValue //////////////////////////////////////////////////////////////////////////
///////////////////////////////////// Registration /////////////////////////////////////////////////////////////////////////
function getno($sdate) {

    require_once './gl_posting.php';
    $ayear = ac_year($sdate);

    include './connection_sql.php';
    $sql = "select RECCABOOK from dep_mas where current_year = '" . $ayear . "'";
    $result = $conn->query($sql);
    $row = $result->fetch();
    $tmpinvno = "00000" . $row["RECCABOOK"];
    $lenth = strlen($tmpinvno);
    $invno = trim("REC-") . substr($ayear,2) . "/" . substr(($ayear+1),2) . "-" . substr($tmpinvno, $lenth - 5);

    return $invno;
}


if ($_GET["Command"] == "getno") {

    require_once './gl_posting.php';
    $ayear = ac_year($_GET['sdate']);

    $invno = getno($_GET['sdate']);
    $sdate = $_GET['sdate'];


     $sql = "select CA_REFNO,CA_DATE,CANCELL from s_crec where tmp_no ='" . $_GET['tmpno'] . "'";
    $result = $conn->query($sql);
    if ($row = $result->fetch()) {
        $invno = $row['CA_REFNO'];

        if ($ayear != ac_year($row['CA_DATE'])) {
           $sdate = $row['CA_DATE'];
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




if (isset($_POST["Command"])) {
    
    if ($_POST["Command"] == "save_item") {

        require_once './gl_posting.php';
        $ayear = ac_year($_POST["crndate"]);


        try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->beginTransaction();

            $sql = "select CA_REFNO,CANCELL from s_crec where tmp_no ='" . $_POST['tmpno'] . "'";
            $result = $conn->query($sql);
            if ($row = $result->fetch()) {
                if ($row['CANCELL'] != "0") {
                    echo "Already Cancelled";
                    exit();
                }
                echo "Already Enterd";
                exit();
            } else {
                $invno = getno($_POST["crndate"]);
                $sql = "update dep_mas set RECCABOOK=RECCABOOK+1 where current_year = '" . $ayear . "'";
                $conn->exec($sql);
            }

            $txtoverpay = 0;
            if ($_POST['cheq_no'] == "Cash") {
                $paytype = "Cash";
                $ST_flag = "CAS";
            } else {
                $paytype = "Cheque";
                $ST_flag = "CHK";
            }



            $i = 1;
            $mpaid = 0;
            $count = $_POST['count'];
            while ($_POST["count"] > $i) {
                $refno = "refno" . $i;
                $pay = "pay" . $i;
                if (is_numeric($_POST[$pay])) {


                    $bb = 0;
                    if ($_POST["cur"] != $_POST["cur1"]) {
                        $bb = $_POST[$pay] * $_POST["txt_rate1"];
                    } else {
                        $ss = $_POST[$pay] / $_POST[$pay];
                        $bb = $_POST[$pay] * $ss;
                        $bb = $bb * $_POST["Rate"];
                    }



                    $sql = "insert into s_sttr(ST_REFNO, ST_DATE, ST_INVONO, ST_PAID, ST_CHNO, st_chdate, ST_FLAG, st_chbank, cus_code,curamo,cur,rate) values
	  ('" . $invno . "', '" . $_POST["crndate"] . "', '" . $_POST[$refno] . "', " . $bb . ", '" . $_POST["cheq_no"] . "', '" . $_POST['cheq_date'] . "', '" . $ST_flag . "', '" . $row["chbank"] . "', '" . $_POST["cus_code"] . "','" . $_POST[$pay] . "','" . $_POST["cur1"] . "','" . $_POST["txt_rate1"] . "')";
                    // echo $sql;
                    $conn->exec($sql);


                    $sql = "insert into c_master (ref_no, ref_no1, sdate, c_code, amo,cur,rate,type) values
	   ('" . $_POST[$refno] . "', '" . $invno . "', '" . $_POST["crndate"] . "','" . trim($_POST['cus_code']) . "', '" . ($_POST[$pay] * -1) . "','" . $_POST["cur1"] . "','" . $_POST["txt_rate1"] . "','DEB')";
                    $conn->exec($sql);

                    $sql = "update s_salma set totpay = totpay + '" . $bb . "',curtotpay=curtotpay + '" . $_POST[$pay] . "' where ref_no = '" . $_POST[$refno] . "'";
                    $conn->exec($sql);
                    $mpaid = $mpaid + $_POST[$pay];
                }
                $i = $i + 1;
            }

            $txtoverpay = $_POST["amount_lkr"] - $mpaid;
            $txtoverpay1 = $txtoverpay / $_POST["txt_rate1"];
            $sql = "insert into s_crec(CA_REFNO, CA_DATE, CA_CODE, CA_AMOUNT, overpay, FLAG, pay_type,  CANCELL, tmp_no,curamo,cur,rate,paycur,payrate) values
	  ('" . $invno . "', '" . $_POST["crndate"] . "', '" . $_POST["cus_code"] . "', " . $_POST["amount_lkr"] . ", " . $txtoverpay . ", 'REC', '" . $paytype . "', '0', '" . $_POST['tmpno'] . "', '" . $_POST['amount'] . "','" . $_POST["cur1"] . "','" . $_POST["txt_rate1"] . "','" . $_POST["cur"] . "','" . $_POST["Rate"] . "')";
            $conn->exec($sql);

            if ($txtoverpay > 0) {
                $sql = "Insert into c_bal (REFNO, SDATE, trn_type, CUSCODE, AMOUNT, BALANCE,c_name,cur,curamo,curbal,Rate,tmp_no) values
			('" . $invno . "', '" . $_POST["crndate"] . "', 'CNT', '" . $_POST["cus_code"] . "', '" . $txtoverpay . "', '" . $txtoverpay . "','" . $_POST["c_name"] . "','" . $_POST["cur1"] . "','" . $txtoverpay1 . "','" . $txtoverpay1 . "','" . $_POST["txt_rate1"] . "','" . $_POST['tmpno'] . "') ";
                $conn->exec($sql);

                $sql = "insert into c_master (ref_no,ref_no1,sdate,c_code,amo,cur,rate,type) values
                        ('" . $invno . "','" . $invno . "','" . $_POST["crndate"] . "','" . $_POST["cus_code"] . "','" . ($txtoverpay * -1) . "','" . trim($_POST["cur1"]) . "','" . trim($_POST["txt_rate1"]) . "','CRE')";
                $conn->exec($sql);


            }


            $sql = "insert into s_invcheq (refno,sdate,cus_code,cus_name,cheque_no,che_date,che_amount,cur,rate,trn_type) values
                   ('" . $invno . "','" . $_POST["crndate"] . "','" . $_POST["cus_code"] . "','" . $_POST['c_name'] . "','" . $_POST["cheq_no"] . "','" . $_POST["cheq_date"] . "','" . $_POST["amount"] . "','" . $_POST["cur1"] . "','" . $_POST["txt_rate1"] . "','REC') ";
            $conn->exec($sql);


            $sql = "Select * from tmp_che_data where tmp_no='" . $_POST['tmpno'] . "' AND form_no='CN1'";
            foreach ($conn->query($sql) as $row) {
                $amo = $row['amount'] * $_POST["Rate"];
                $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo,acyear) value
                   ('" . $invno . "','" . $_POST["crndate"] . "','" . $row['code'] . "','" . $amo . "','CRN','DEB','','" . $_POST["cur"] . "','" . $_POST["Rate"] . "','" . $row['amount'] . "','" . $ayear . "')";

                $conn->exec($sql);
            }

            $sql = "Select * from tmp_che_data where tmp_no='" . $_POST['tmpno'] . "' AND form_no='CN2'";
            foreach ($conn->query($sql) as $row) {
                $amo = $row['amount'] * $_POST["Rate"];
                $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo,acyear) value
                   ('" . $invno . "','" . $_POST["crndate"] . "','" . $row['code'] . "','" . $amo . "','CRN','CRE','','" . $_POST["cur"] . "','" . $_POST["Rate"] . "','" . $row['amount'] . "','" . $ayear . "')";

                $conn->exec($sql);
            }


            $conn->commit();
            echo "Saved";
        } catch (Exception $e) {
            $conn->rollBack();
            echo $e;
        }
    }
} else {

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


            $sql = "select CA_REFNO,CANCELL from s_crec where tmp_no ='" . $_GET['tmpno'] . "'";
            $result = $conn->query($sql);
            if ($row = $result->fetch()) {
                if ($row['CANCELL'] != "0") {
                    echo "Already Cancelled";
                    exit();
                }

                $sql = "Select * from s_sttr where st_refno='" . $row['CA_REFNO'] . "'";
                foreach ($conn->query($sql) as $row1) {
                    $sql = "update s_salma set totpay = totpay - '" . $row1['ST_PAID'] . "',curtotpay=curtotpay - '" . $row1["curamo"] . "' where ref_no = '" . $row1["ST_INVONO"] . "'";
                    $conn->exec($sql);
                }

                $sql = "delete from ledger where l_refno = '" . $row['CA_REFNO'] . "'";
                $conn->exec($sql);

                $sql = "delete from c_master where ref_no1 = '" . $row['CA_REFNO'] . "'";
                $conn->exec($sql);


                $sql = "delete from c_bal where REFNO = '" . $row['CA_REFNO'] . "'";
                $conn->exec($sql);

                $sql = "update s_crec set CANCELL ='1'  where CA_REFNO = '" . $row['CA_REFNO'] . "'";
                $conn->exec($sql);

                $sql = "update s_sttr set CANCELL ='1'  where ST_REFNO = '" . $row['CA_REFNO'] . "'";
                $conn->exec($sql);

                $conn->commit();
                echo "ok";
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



    if ($_GET["Command"] == "pass_rec") {

        $ResponseXML = "";
        $ResponseXML .= "<salesdetails>";
        $sql = "Select * from s_crec where CA_REFNO='" . $_GET['refno'] . "'";
        $result = $conn->query($sql);

        if ($row = $result->fetch()) {
            $ResponseXML .= "<C_REFNO><![CDATA[" . $row["CA_REFNO"] . "]]></C_REFNO>";
            $ResponseXML .= "<C_DATE><![CDATA[" . $row["CA_DATE"] . "]]></C_DATE>";
            $ResponseXML .= "<C_CODE><![CDATA[" . $row["CA_CODE"] . "]]></C_CODE>";

            $sql = "select * from vendor where code = '" . $row["CA_CODE"] . "'";
            $result_v = $conn->query($sql);

            if ($row_v = $result_v->fetch()) {
                $ResponseXML .= "<name><![CDATA[" . $row_v["NAME"] . "]]></name>";
            }


            $ResponseXML .= "<currency><![CDATA[" . trim($row["paycur"]) . "]]></currency>";
            $ResponseXML .= "<txt_rate><![CDATA[" . $row["payrate"] . "]]></txt_rate>";
            $ResponseXML .= "<txt_amount><![CDATA[" . $row["CA_AMOUNT"] . "]]></txt_amount>";
            $ResponseXML .= "<txt_amount_lkr><![CDATA[" . $row["curamo"] . "]]></txt_amount_lkr>";

            $ResponseXML .= "<tmp_no><![CDATA[" . $row["tmp_no"] . "]]></tmp_no>";
            $msg = "";
            if ($row['CANCELL'] == "1") {
                $msg = "Cancelled";
            }
            $ResponseXML .= "<msg><![CDATA[" . $msg . "]]></msg>";


            $sql = "select * from s_invcheq where refno ='" . $row["CA_REFNO"] . "'";
           
            $result_c = $conn->query($sql);
            if ($row_c = $result_c->fetch()) {
                $ResponseXML .= "<cheq_no><![CDATA[" . $row_c["cheque_no"] . "]]></cheq_no>";
                $ResponseXML .= "<cheq_date><![CDATA[" . $row_c["che_date"] . "]]></cheq_date>";
                $ResponseXML .= "<currency1><![CDATA[" . trim($row_c["CUR"]) . "]]></currency1>";
                $ResponseXML .= "<txt_rate1><![CDATA[" . trim($row_c["rate"]) . "]]></txt_rate1>";
            }


            $tb = "<table class=\"table\">";
            $tb .= "<tr>";
            $tb .= "<th>Invoice No</th>";
            $tb .= "<th>Paid</th>";
            $tb .= "<th></th>";
            $tb .= "</tr>";

            $sql = "select * from s_sttr where st_refno = '" . $row["CA_REFNO"] . "'";
             
            foreach ($conn->query($sql) as $row1) {
                $tb .= "<tr>";
                $tb .= "<td>" . $row1['ST_INVONO'] . "</td>";
                $tb .= "<td>" . $row1['curamo'] . "</td>";
                $tb .= "<td>" . $row1['ST_PAID'] . "</td>";
                $tb .= "</tr>";
            }
            $tb .= "</table>";
            $ResponseXML .= "<sales_table3><![CDATA[" . $tb . "]]></sales_table3>";

            $sql = "delete from tmp_che_data where  tmp_no='" . $row["tmp_no"] . "'";
            
            $result = $conn->query($sql);


            $sql = "Select L_CODE,C_NAME,curamo from view_ledger where l_refno='" . $row["CA_REFNO"] . "' AND l_flag1='DEB'";
            
            foreach ($conn->query($sql) as $row1) {
                $sql = "Insert into tmp_che_data (code, descript, amount, tmp_no,form_no)values
			('" . $row1['L_CODE'] . "', '" . $row1['C_NAME'] . "', " . $row1['curamo'] . ",'" . $row['tmp_no'] . "','CN1') ";
			   
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
            $sql = "Select * from tmp_che_data where tmp_no='" . $row["tmp_no"] . "' AND form_no='CN1'";
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

            $sql = "Select L_CODE,C_NAME,curamo from view_ledger where l_refno='" . $row["CA_REFNO"] . "' AND l_flag1='cre'";
            foreach ($conn->query($sql) as $row1) {
                $sql = "Insert into tmp_che_data (code, descript, amount, tmp_no,form_no)values
			('" . $row1['L_CODE'] . "', '" . $row1['C_NAME'] . "', " . $row1['curamo'] . ",'" . $row['tmp_no'] . "','CN2') ";
                $result = $conn->query($sql);
            }
            $sql = "Select * from tmp_che_data where tmp_no='" . $row["tmp_no"] . "' AND form_no='CN2'";
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

            $sql = "select * from docs where refno = '" . $row["CA_REFNO"] . "'";
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

                        <th width=\"121\">Amount</th>
                    </tr>";


        $sql = "select CA_REFNO, CA_DATE,CA_CODE,CA_AMOUNT,ID from s_crec  where flag = 'REC'";
        if ($_GET['refno'] != "") {
            $sql .= " and CA_REFNO like '%" . $_GET['refno'] . "%'";
        }
        if ($_GET['cusname'] != "") {
            $sql .= " and CA_CODE like '%" . $_GET['cusname'] . "%'";
        }
        $stname = $_GET['stname'];

        $sql .= " ORDER BY CA_REFNO desc limit 50";

        foreach ($conn->query($sql) as $row) {
            $cuscode = $row["CA_REFNO"];


            $ResponseXML .= "<tr>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['CA_REFNO'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['CA_DATE'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['CA_CODE'] . "</a></td>

                                      <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['CA_AMOUNT'] . "</a></td>
                            </tr>";
        }
        $ResponseXML .= "</table>";
        echo $ResponseXML;
    }
}
?>
