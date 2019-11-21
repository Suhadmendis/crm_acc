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

    include './connection_sql.php';
    $sql = "select ARN from invpara";
    $result = $conn->query($sql);
    $row = $result->fetch();
    $tmpinvno = "00000" . $row["ARN"];
    $lenth = strlen($tmpinvno);
    $invno = trim("ARN/") . substr($tmpinvno, $lenth - 5);

    return $invno;
}

if (isset($_POST["Command"])) {
    if ($_POST["Command"] == "save_item") {

        if ($_POST["count"] > 0) {

            try {

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->beginTransaction();

                $sql_invpara = "SELECT * from invpara";

                $result_invpara = $conn->query($sql_invpara);
                $row_invpara = $result_invpara->fetch();

                $vatrate = $row_invpara["vatrate"];

                $sql = "select * from s_purmas where REFNO='" . $_POST["invno"] . "'";
                $result = $conn->query($sql);
                if ($row = $result->fetch()) {
                    echo "AR Number Already Exists";
                    exit();
                } else {

// $sql1 = "insert into c_bal(REFNO, SDATE, CUSCODE, AMOUNT, BALANCE, Cancell, trn_type, vatrate, totpay) values ('" . $_POST["invno"] . "', '" . $_POST["invdate"] . "', '" . $_POST["cus_code"] . "', '" . $_POST["total_value"] . "', '" . $_POST["total_value"] . "',  '0', 'ARN', '" . $vatrate . "', 0)";
// $conn->exec($sql1);

                    $sql1 = "insert into s_purmas(REFNO, SDATE, ORDNO, SUP_CODE, SUP_NAME, AMOUNT, PUR_DATE, tmp_no,LCNO) values ('" . $_POST["invno"] . "', '" . $_POST["invdate"] . "', '" . $_POST["orderno1"] . "', '" . $_POST["cus_code"] . "', '" . $_POST["c_name"] . "',  '" . $_POST["total_value"] . "', '" . $_POST["invdate"] . "', '" . $_POST["tmpno"] . "','" . $_POST['LCNO'] . "')";
                    $conn->exec($sql1);
                }

                $i = 1;
                while ($i < $_POST["count"]) {

                    $itemcode = "itemcode" . $i;
                    $itemname = "itemname" . $i;
                    $ord_qty = "ord_qty" . $i;
                    $qty = "qty" . $i;
                    $cost = "cost" . $i;
                    $subtotal = "subtotal" . $i;


                    if ($_POST[$qty] > 0) {





                        $sql121 = "insert into s_purtrn(REFNO, SDATE, STK_NO, DESCRIPT, COST, REC_QTY, O_QTY, vatrate, CANCEL) values"
                                . " ('" . $_POST["invno"] . "', '" . $_POST["invdate"] . "', '" . $_POST[$itemcode] . "', '" . $_POST[$itemname] . "', " . $_POST[$cost] . ", " . $_POST[$qty] . "," . $_POST[$ord_qty] . ",'" . $vatrate . "','0')";

                        $conn->exec($sql121);


                        $sql1 = "update s_mas set  QTYINHAND=QTYINHAND+" . $_POST[$qty] . " where  STK_NO='" . $_POST[$itemcode] . "'";
                        $conn->exec($sql1);


                        $sql1 = "update s_ordmas set cancel='1' where REFNO='" . $_POST["orderno1"] . "'";
                        $conn->exec($sql1);

                        $sql1 = "update s_ordtrn set CANCEL='1' where REFNO='" . $_POST["orderno1"] . "'";
                        $conn->exec($sql1);

                        $sql12 = "insert into s_trn(STK_NO, SDATE, QTY, LEDINDI, REFNO) values "
                                . "('" . $_POST[$itemcode] . "', '" . $_POST["invdate"] . "', '" . $_POST[$qty] . "', 'ARN', '" . $_POST["invno"] . "')";
//echo $sql1;
                        $conn->exec($sql12);
                    }

                    $i = $i + 1;
                }

                $sql = "Select * from tmp_che_data where tmp_no='" . $_POST['tmpno'] . "' AND form_no='ARN1'";
                foreach ($conn->query($sql) as $row) {
                    $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo) value
                   ('" . $_POST["invno"] . "','" . $_POST["invdate"] . "','" . $row['code'] . "','" . $row['amount'] . "','ARN','DEB','','','','')";

                    $conn->exec($sql);
                }

                $sql = "Select * from tmp_che_data where tmp_no='" . $_POST['tmpno'] . "' AND form_no='ARN2'";
                foreach ($conn->query($sql) as $row) {
                    $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo) value
                   ('" . $_POST["invno"] . "','" . $_POST["invdate"] . "','" . $row['code'] . "','" . $row['amount'] . "','ARN','CRE','','','','')";

                    $conn->exec($sql);
                }

                $sql1 = "update invpara set ARN=ARN+1";
                $conn->exec($sql1);

                $conn->commit();
                echo "Saved";
            } catch (Exception $e) {
                $conn->rollBack();
                echo $e;
            }
        } else {
            echo "Invalid Entry";
            exit();
        }
    }
} else {

    if ($_GET["Command"] == "new_inv") {


        $invno = getno();

        $sql = "Select ARN from tmpinvpara_acc";
        $result = $conn->query($sql);
        $row = $result->fetch();

        $tono = $row['ARN'];



        $sql = "update tmpinvpara_acc set ARN=ARN+1";
        $result = $conn->query($sql);


        $ResponseXML = "";
        $ResponseXML .= "<salesdetails>";
        $ResponseXML .= "<invno><![CDATA[" . $invno . "]]></invno>";
        $ResponseXML .= "<tmpno><![CDATA[" . $tono . "]]></tmpno>";
        $ResponseXML .= "<dt><![CDATA[" . date('Y-m-d') . "]]></dt>";
        $ResponseXML .= "</salesdetails>";
        echo $ResponseXML;
    }

    if ($_GET["Command"] == "arn") {


        $ResponseXML = "";
        $ResponseXML .= "<salesdetails>";

        $sql = "select * from s_ordmas where REFNO='" . $_GET['refno'] . "' ";
        $result = $conn->query($sql);

        if ($row = $result->fetch()) {
            $ResponseXML .= "<REFNO><![CDATA[" . $row['REFNO'] . "]]></REFNO>";
            $ResponseXML .= "<SUP_CODE><![CDATA[" . $row['SUP_CODE'] . "]]></SUP_CODE>";
            $ResponseXML .= "<SUP_NAME><![CDATA[" . $row['SUP_NAME'] . "]]></SUP_NAME>";
        }


        $ResponseXML .= "<sales_table><![CDATA[<table class=\"table\"><tr>
                              <th style=\"width: 10px;\">Code</th>
                              <th style=\"width: 10px;\">Description</th>
                              <th style=\"width: 10px;\">Order Qty</th>
                              <th style=\"width: 10px;\">Qty</th>
                              <th style=\"width: 10px;\">Cost</th>                               
			      <th style=\"width: 10px;\">Sub Total</th>		
                            </tr>";



        $i = 1;
        $sql = "Select * from s_ordtrn where REFNO='" . $_GET['refno'] . "'";
        foreach ($conn->query($sql) as $row) {

            $itemcode = "itemcode" . $i;
            $itemname = "itemname" . $i;
            $ord_qty = "ord_qty" . $i;
            $qty = "qty" . $i;
            $cost = "cost" . $i;
            $subtotal = "subtotal" . $i;

            $ResponseXML .= "<tr>                              
                            <td><div name=" . $itemcode . " id=" . $itemcode . ">" . $row['STK_NO'] . "</div></td>
                            <td><div name=" . $itemname . " id=" . $itemname . ">" . $row['DESCRIPT'] . "</div></td>
                            <td><div name=" . $ord_qty . " id=" . $ord_qty . ">" . $row['ORD_QTY'] . "</div></td>			
                            <td><input type=\"text\"  name=" . $qty . " id=" . $qty . " onkeyup=\"cal_subtot();\"/></td>
                            <td><input type=\"text\"  name=" . $cost . " id=" . $cost . " onkeyup=\"cal_subtot();\" value='" . $row['RATE'] . "' /></td>                            
                            <td><input type=\"text\"  name=" . $subtotal . " id=" . $subtotal . "   disabled=\"disabled\"/></td>
                        </tr>";
            $i = $i + 1;
        }

        $ResponseXML .= "   </table>]]></sales_table>";
        $ResponseXML .= "<count><![CDATA[" . $i . "]]></count>";
        $ResponseXML .= " </salesdetails>";
        echo $ResponseXML;
    }

    if ($_GET["Command"] == "add_tmp") {

        $ResponseXML = "";
        $ResponseXML .= "<salesdetails>";


        try {
            $sql = "Insert into tmp_che_data (code, descript, amount, tmp_no,form_no)values 
			('" . $_GET['itemCode'] . "', '" . $_GET['itemDesc'] . "', " . $_GET['itemPrice'] . ",'" . $_GET['tmpno'] . "','" . $_GET['form'] . "') ";

            $result = $conn->query($sql);
        } catch (Exception $e) {
            echo $e->getMessage();
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

            $sql = "select REFNO,curamo,curbal,cancell from c_bal where REFNO ='" . $_GET['tmpno'] . "'";
            $result = $conn->query($sql);
            if ($row = $result->fetch()) {
                if ($row['cancell'] != "0") {
                    echo "Already Cancelled";
                    exit();
                }

                if ($row['curamo'] != $row['curbal']) {
                    echo "Already Paid";
                    exit();
                } else {
                    $sql = "delete from ledger where l_refno = '" . $row['REFNO'] . "'";
                    $conn->exec($sql);
                    $sql = "update cred set cancell='1' where c_refno = '" . $row['REFNO'] . "'";
                    $conn->exec($sql);

                    $sql = "delete from c_bal where REFNO = '" . $row['REFNO'] . "'";
                    $conn->exec($sql);

                    $conn->commit();
                    echo "ok";
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



    if ($_GET["Command"] == "pass_rec") {

        $ResponseXML = "";
        $ResponseXML .= "<salesdetails>";
        $sql = "Select * from s_purmas where REFNO='" . $_GET['refno'] . "'";
        $result = $conn->query($sql);

        if ($row = $result->fetch()) {
            $ResponseXML .= "<REFNO><![CDATA[" . $row["REFNO"] . "]]></REFNO>";
            $ResponseXML .= "<SDATE><![CDATA[" . $row["SDATE"] . "]]></SDATE>";
            $ResponseXML .= "<ORDNO><![CDATA[" . $row["ORDNO"] . "]]></ORDNO>";
            $ResponseXML .= "<SUP_CODE><![CDATA[" . $row["SUP_CODE"] . "]]></SUP_CODE>";
            $ResponseXML .= "<SUP_NAME><![CDATA[" . $row["SUP_NAME"] . "]]></SUP_NAME>";
            $ResponseXML .= "<AMOUNT><![CDATA[" . $row["AMOUNT"] . "]]></AMOUNT>";
            $ResponseXML .= "<LCNO><![CDATA[" . $row["LCNO"] . "]]></LCNO>";
            
            $ResponseXML .= "<tmp_no><![CDATA[" . $row["tmp_no"] . "]]></tmp_no>";
            $msg = "";
            if ($row['CANCEL'] == "1") {
                $msg = "Cancelled";
            }
            $ResponseXML .= "<msg><![CDATA[" . $msg . "]]></msg>";

            $ResponseXML .= "<sales_table0><![CDATA[ <table class=\"table\"><tr>
                              <th style=\"width: 10px;\">Code</th>
                              <th style=\"width: 10px;\">Description</th>
                              <th style=\"width: 10px;\">Order Qty</th>
                              <th style=\"width: 10px;\">Qty</th>
                              <th style=\"width: 10px;\">Cost</th>
			      <th style=\"width: 10px;\">Sub Total</th>
							
                            </tr>";

            $i = 1;
            $sql = "Select * from s_purtrn where REFNO='" . $row["REFNO"] . "'";
            foreach ($conn->query($sql) as $row1) {

                $ResponseXML .= "<tr>                              
							 <td>" . $row1['STK_NO'] . "</td>
							 <td>" . $row1['DESCRIPT'] . "</td>
							 <td>" . number_format($row1['O_QTY'], 2, ".", ",") . "</td>
							 <td>" . number_format($row1['REC_QTY'], 2, ".", ",") . "</td>
							 <td>" . number_format($row1['acc_cost'], 2, ".", ",") . "</td>
							 <td>" . number_format($row1['REC_QTY'] * $row1['acc_cost'], 2, ".", ",") . "</td>
							 </tr>";
            }
            $ResponseXML .= "   </table>]]></sales_table0>";

////////////////

            $sql = "delete from tmp_che_data where  tmp_no='" . $row["tmp_no"] . "'";
            $result = $conn->query($sql);


            $sql = "Select L_CODE,C_NAME,L_AMOUNT from view_ledger where l_refno='" . $row["REFNO"] . "' AND l_flag1='DEB'";
            foreach ($conn->query($sql) as $row1) {
                $sql = "Insert into tmp_che_data (code, descript, amount, tmp_no,form_no)values 
			('" . $row1['L_CODE'] . "', '" . $row1['C_NAME'] . "', " . $row1['L_AMOUNT'] . ",'" . $row['tmp_no'] . "','CN1') ";
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
                         <td></td>
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
            $sql = "Select L_CODE,C_NAME,L_AMOUNT,ID from view_ledger where l_refno='" . $row["REFNO"] . "' AND l_flag1='CRE'";

//        $sql = "Select L_CODE,C_NAME,L_AMOUNT from view_ledger where l_refno='" . $row["REFNO"] . "' AND l_flag1='cre'";
            foreach ($conn->query($sql) as $row1) {
                $sql = "Insert into tmp_che_data (code, descript, amount, tmp_no,form_no)values 
			('" . $row1['L_CODE'] . "', '" . $row1['C_NAME'] . "', " . $row1['L_AMOUNT'] . ",'" . $row['tmp_no'] . "','CN2') ";
                $result = $conn->query($sql);
            }
            $sql = "Select * from tmp_che_data where tmp_no='" . $row["tmp_no"] . "' AND form_no='CN2'";
            foreach ($conn->query($sql) as $row1) {
                $ResponseXML .= "<tr>                              
                         <td>" . $row1['code'] . "</td>
                         <td>" . $row1['descript'] . "</td>
                         <td></td>
                         <td>" . number_format($row1['amount'], 2, ".", ",") . "</td>
                         <td></td>
                         </tr>";
                $mtot = $mtot + $row1['amount'];
                $i = $i + 1;
            }
            $ResponseXML .= "</table>]]></sales_table1>";
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


        $sql = "select CA_REFNO, CA_DATE,CA_CODE,CA_AMOUNT,ID from s_crec  where CA_REFNO <> ''";
        if ($_GET['refno'] != "") {
            $sql .= " and CA_REFNO like '%" . $_GET['refno'] . "%'";
        }
        if ($_GET['cusname'] != "") {
            $sql .= " and CA_CODE like '%" . $_GET['cusname'] . "%'";
        }
        $stname = $_GET['stname'];

        $sql .= " ORDER BY ID desc limit 50";

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