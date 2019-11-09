<?php

session_start();

////////////////////////////////////////////// Database Connector /////////////////////////////////////////////////////////////
require_once ("connection_sql.php");

////////////////////////////////////////////// Write XML ////////////////////////////////////////////////////////////////////
header('Content-Type: text/xml');

date_default_timezone_set('Asia/Colombo');

/////////////////////////////////////// GetValue //////////////////////////////////////////////////////////////////////////
///////////////////////////////////// Registration /////////////////////////////////////////////////////////////////////////
function getno() {


 require_once './gl_posting.php';
    $ayear = ac_year(date('Y-m-d'));


    include './connection_sql.php';
    $sql = "select CONTRA from dep_mas where current_year = '" . $ayear . "'";
    $result = $conn->query($sql);
    $row = $result->fetch();
    $tmpinvno = "00000" . $row["CONTRA"];
    $lenth = strlen($tmpinvno);
    $invno = trim("CON-") . substr($ayear,2) . "/" . substr(($ayear+1),2) . "-" . substr($tmpinvno, $lenth - 5);

    return $invno;
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
                $invno = getno();
                $sql = "update dep_mas set CONTRA=CONTRA+1  where current_year = '" . $ayear . "'";
                $conn->exec($sql);
            }




            $i = 1;
            $mpaid = 0;
            $count = $_POST['count'];
            while ($_POST["count"] > $i) {
                $refno = "refno" . $i;
                $pay = "pay" . $i;
                if (is_numeric($_POST[$pay])) {


                    $bb = 0;

                    $ss = $_POST[$pay] / $_POST[$pay];
                    $bb = $_POST[$pay] * $ss;
                    $bb = $bb * $_POST["Rate"];




                    $sql = "insert into s_sttr(ST_REFNO, ST_DATE, ST_INVONO, ST_PAID,  ST_FLAG,  cus_code,curamo,cur,rate) values
	  ('" . $invno . "', '" . $_POST["crndate"] . "', '" . $_POST[$refno] . "', " . $bb . ", 'CON','" . $_POST["cus_code"] . "','" . $_POST[$pay] . "','" . $_POST["cur"] . "','" . $_POST["Rate"] . "')";

                    $conn->exec($sql);


                    $sql = "insert into c_master (ref_no, ref_no1, sdate, c_code, amo,cur,rate,type) values
	   ('" . $_POST[$refno] . "', '" . $invno . "', '" . $_POST["crndate"] . "','" . trim($_POST['cus_code']) . "', '" . ($_POST[$pay] * -1) . "','" . $_POST["cur"] . "','" . $_POST["Rate"] . "','DEB')";
                    $conn->exec($sql);

                    $sql = "update s_salma set totpay = totpay + '" . $bb . "',curtotpay=curtotpay + '" . $_POST[$pay] . "' where ref_no = '" . $_POST[$refno] . "'";
                    $conn->exec($sql);
                    $mpaid = $mpaid + $_POST[$pay];
                }
                $i = $i + 1;
            }

            $i = 1;
            $mpaid = 0;
            $count = $_POST['count1'];
            while ($_POST["count1"] > $i) {
                $refno = "crefno" . $i;
                $pay = "cpay" . $i;
                if (is_numeric($_POST[$pay])) {


                    $bb = 0;

                    $ss = $_POST[$pay] / $_POST[$pay];
                    $bb = $_POST[$pay] * $ss;
                    $bb = $bb * $_POST["Rate"];




                    $sql = "insert into cbal_sttr(ST_REFNO, ST_DATE, ST_INVONO, ST_PAID,  ST_FLAG,  cus_code,Currencypaid,cur,rate) values
	  ('" . $invno . "', '" . $_POST["crndate"] . "', '" . $_POST[$refno] . "', " . $bb . ", 'CON','" . $_POST["cus_code"] . "','" . $_POST[$pay] . "','" . $_POST["cur"] . "','" . $_POST["Rate"] . "')";

                    $conn->exec($sql);


                    $sql = "insert into c_master (ref_no, ref_no1, sdate, c_code, amo,cur,rate,type) values
	   ('" . $_POST[$refno] . "', '" . $invno . "', '" . $_POST["crndate"] . "','" . trim($_POST['cus_code']) . "', '" . ($_POST[$pay]) . "','" . $_POST["cur"] . "','" . $_POST["Rate"] . "','CRE')";
                    $conn->exec($sql);

                    $sql = "update c_bal set balance = balance - '" . $bb . "',curbal=curbal - '" . $_POST[$pay] . "' where refno = '" . $_POST[$refno] . "'";
                    $conn->exec($sql);
                    $mpaid = $mpaid + $_POST[$pay];
                }
                $i = $i + 1;
            }


            $sql = "insert into s_crec(CA_REFNO, CA_DATE, CA_CODE, CA_AMOUNT, overpay, FLAG, pay_type,  CANCELL, tmp_no,curamo,cur,rate,paycur,payrate) values
	  ('" . $invno . "', '" . $_POST["crndate"] . "', '" . $_POST["cus_code"] . "', " . $mpaid . ",'0', 'CON', '', '0', '" . $_POST['tmpno'] . "', '" . $mpaid . "','" . $_POST["cur"] . "','" . $_POST["Rate"] . "','" . $_POST["cur"] . "','" . $_POST["Rate"] . "')";
            $conn->exec($sql);







            $conn->commit();
            echo "Saved";
        } catch (Exception $e) {
            $conn->rollBack();
            echo $e;
        }
    }
} else {

    if ($_GET["Command"] == "new_inv") {


        $invno = getno();

        $sql = "Select CRENO from tmpinvpara_acc";
        $result = $conn->query($sql);
        $row = $result->fetch();

        $tono = $row['CRENO'];



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

                $sql = "Select * from cbal_sttr where st_refno='" . $row['CA_REFNO'] . "'";
                foreach ($conn->query($sql) as $row1) {
                    $sql = "update c_bal set balance = balance + '" . $row1['ST_PAID'] . "',curbal=curbal + '" . $row1["Currencypaid"] . "' where refno = '" . $row1["ST_INVONO"] . "'";
                    $conn->exec($sql);
                }

                $sql = "delete from c_master where ref_no1 = '" . $row['CA_REFNO'] . "'";
                $conn->exec($sql);




                $sql = "update s_crec set CANCELL ='1'  where CA_REFNO = '" . $row['CA_REFNO'] . "'";
                $conn->exec($sql);

                $sql = "update s_sttr set CANCELL ='1'  where ST_REFNO = '" . $row['CA_REFNO'] . "'";
                $conn->exec($sql);
                $sql = "delete from cbal_sttr   where ST_REFNO = '" . $row['CA_REFNO'] . "'";
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


            $tb = "<table class=\"table\">";
            $tb .= "<tr>";
            $tb .= "<th>Invoice No</th>";
            $tb .= "<th>Paid</th>";
            $tb .= "<th></th>";
            $tb .= "</tr>";

            $sql = "select * from cbal_sttr where st_refno = '" . $row["CA_REFNO"] . "'";
            foreach ($conn->query($sql) as $row1) {
                $tb .= "<tr>";
                $tb .= "<td>" . $row1['ST_INVONO'] . "</td>";
                $tb .= "<td>" . $row1['Currencypaid'] . "</td>";
                $tb .= "<td>" . $row1['ST_PAID'] . "</td>";
                $tb .= "</tr>";
            }
            $tb .= "</table>";
            $ResponseXML .= "<sales_table4><![CDATA[" . $tb . "]]></sales_table4>";

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


        $sql = "select CA_REFNO, CA_DATE,CA_CODE,CA_AMOUNT,ID from s_crec  where flag = 'CON'";
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
