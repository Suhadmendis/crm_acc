<?php

session_start();

////////////////////////////////////////////// Database Connector /////////////////////////////////////////////////////////////
require_once ("connection_sql.php");

////////////////////////////////////////////// Write XML ////////////////////////////////////////////////////////////////////
header('Content-Type: text/xml');

date_default_timezone_set('Asia/Colombo');

/////////////////////////////////////// GetValue //////////////////////////////////////////////////////////////////////////
///////////////////////////////////// Registration /////////////////////////////////////////////////////////////////////////

if ($_GET["Command"] == "new_inv") {

    $invno = getno();

    $sql = "Select QTNNO from tmpinvpara_acc";
    $result = $conn->query($sql);
    $row = $result->fetch();

    $tono = $row['QTNNO'];

    $sql = "delete from tmp_po_data where tmp_no='" . $tono . "'";
    $result = $conn->query($sql);

    $sql = "update tmpinvpara_acc set QTNNO=QTNNO+1";
    $result = $conn->query($sql);


    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";
    $ResponseXML .= "<invno><![CDATA[" . $invno . "]]></invno>";
    $ResponseXML .= "<tmpno><![CDATA[" . $tono . "]]></tmpno>";
    $ResponseXML .= "<dt><![CDATA[" . date('Y-m-d') . "]]></dt>";

    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}

function getno() {

    include './connection_sql.php';
    $sql = "select QTNNO from invpara";
    $result = $conn->query($sql);
    $row = $result->fetch();
    $tmpinvno = "000000" . $row["QTNNO"];
    $lenth = strlen($tmpinvno);
    return $invno = trim("O/") . substr($tmpinvno, $lenth - 7);
}

if ($_GET["Command"] == "setitem") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";


    $sql = "delete from tmp_po_data where stk_no='" . $_GET['itemCode'] . "' and tmp_no='" . $_GET['tmpno'] . "' ";
    $result = $conn->query($sql);
    if ($_GET["Command1"] == "add_tmp") {
        $rate = str_replace(",", "", $_GET["itemPrice"]);
        $qty = str_replace(",", "", $_GET["qty"]);

        $discount = 0;
        $subtotal = $rate * $qty;

        $sql = "Insert into tmp_po_data (stk_no, descript, qty, rate,subtot, tmp_no)values 
			('" . $_GET['itemCode'] . "', '" . $_GET['itemDesc'] . "', " . $_GET['itemPrice'] . ", " . $_GET['qty'] . ",'" . $subtotal . "','" . $_GET['tmpno'] . "') ";
        $result = $conn->query($sql);
    }

    $ResponseXML .= "<sales_table><![CDATA[<table class=\"table\">
					<tr>
						<td style=\"width: 90px;\">Item</td>
						<td>Description</td>
						<td style=\"width: 60px;\">Qty</td>
						<td style=\"width: 100px;\">Rate</td>
						<td style=\"width: 100px;\">Sub Total</td>
						<td style=\"width: 10px;\"></td>
					</tr>";

    $i = 1;
    $mtot = 0;
    $sql = "Select * from tmp_po_data where tmp_no='" . $_GET['tmpno'] . "'";
    foreach ($conn->query($sql) as $row) {

        $ResponseXML .= "<tr>                              
                             <td ondblclick=\"setitem('" . $row['stk_no'] . "','" . $row['descript'] . "','" . $row['rate'] . "','" . $row['qty'] . "');\">" . $row['stk_no'] . "</td>
                             <td ondblclick=\"setitem('" . $row['stk_no'] . "','" . $row['descript'] . "','" . $row['rate'] . "','" . $row['qty'] . "');\">" . $row['descript'] . "</td>
                             <td ondblclick=\"setitem('" . $row['stk_no'] . "','" . $row['descript'] . "','" . $row['rate'] . "','" . $row['qty'] . "');\">" . number_format($row['rate'], 2, ".", ",") . "</td>
                             <td ondblclick=\"setitem('" . $row['stk_no'] . "','" . $row['descript'] . "','" . $row['rate'] . "','" . $row['qty'] . "');\">" . number_format($row['qty'], 2, ".", ",") . "</td>
                             <td ondblclick=\"setitem('" . $row['stk_no'] . "','" . $row['descript'] . "','" . $row['rate'] . "','" . $row['qty'] . "');\">" . number_format($row['subtot'], 2, ".", ",") . "</td>
                             <td><a class=\"btn btn-danger btn-xs\" onClick=\"del_item('" . $row['stk_no'] . "')\"> <span class='fa fa-remove'></span></a></td>
                             </tr>";

        $mtot = $mtot + $row['subtot'];
        $i = $i + 1;
    }

    $mtot1 = 0;
    if ($_GET['vat'] != "non") {
        $sql = "select vatrate from invpara";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {
            $mtot1 = $mtot * ($row['vatrate'] / 100);
        }
    }

    $ResponseXML .= "   </table>]]></sales_table>";

    $ResponseXML .= "<item_count><![CDATA[" . $i . "]]></item_count>";
    $ResponseXML .= "<vattot><![CDATA[" . number_format($mtot1, 2, ".", ",") . "]]></vattot>";
    $ResponseXML .= "<gtot><![CDATA[" . number_format($mtot1 + $mtot, 2, ".", ",") . "]]></gtot>";
    $ResponseXML .= "<subtot><![CDATA[" . number_format($mtot, 2, ".", ",") . "]]></subtot>";
    $ResponseXML .= "</salesdetails>";

    echo $ResponseXML;
}

if ($_GET["Command"] == "save_item") {
    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        $sql = "select REFNO,cancel from s_ordmas where tmp_no ='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {


            if ($row['cancel'] != "0") {
                echo "Already Cancelled";
                exit();
            }

            $invno = $row['REFNO'];
            $sql = "delete from s_ordmas where REFNO = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from s_ordtrn where REFNO = '" . $invno . "'";
            $conn->exec($sql);
        } else {
            $invno = getno();
            $sql = "update invpara set QTNNO=QTNNO+1";
            $conn->exec($sql);
        }

        $subtot = str_replace(",", "", $_GET["subtot"]);


        $mtot = 0;
        $sql = "select * from tmp_po_data where tmp_no='" . $_GET["tmpno"] . "'";
        foreach ($conn->query($sql) as $row) {

            $sql = "Insert into s_ordtrn (REFNO, SDATE, STK_NO, DESCRIPT, ORD_QTY , RATE) values 
		('" . trim($invno) . "', '" . $_GET['invdate'] . "','" . $row["stk_no"] . "','" . $row["descript"] . "', " . $row["rate"] . "," . $row["qty"] . ")";
            $result = $conn->query($sql);
            $mtot = $mtot + ($row["rate"] * $row["qty"]);
        }
        $mtot1 = 0;
        if ($_GET['vat'] != "non") {
            $sql = "select vatrate from invpara";
            $result = $conn->query($sql);
            if ($row = $result->fetch()) {
                $mtot1 = $mtot * ($row['vatrate'] / 100);
            }
        }

        $sql = "insert s_ordmas (REFNO, SDATE, SUP_CODE, SUP_NAME, AMOUNT,vat,grand_amt,tmp_no,Attn,REMARK) values 
	('" . $invno . "', '" . $_GET['invdate'] . "', '" . $_GET["customercode"] . "', '" . $_GET["customername"] . "' , " . $mtot . ",'" . $mtot1 . "','" . ($mtot + $mtot1) . "','" . $_GET["tmpno"] . "','" . $_GET['cont_p'] . "','" . $_GET['txt_remarks'] . "')";

        $result = $conn->query($sql);

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
    $sql = "Select * from s_ordmas where REFNO='" . $_GET['refno'] . "'";
    $result = $conn->query($sql);

    if ($row = $result->fetch()) {
        $ResponseXML .= "<C_REFNO><![CDATA[" . $row["REFNO"] . "]]></C_REFNO>";
        $ResponseXML .= "<C_DATE><![CDATA[" . $row["SDATE"] . "]]></C_DATE>";
        $ResponseXML .= "<C_CODE><![CDATA[" . $row["SUP_CODE"] . "]]></C_CODE>";
        $ResponseXML .= "<name><![CDATA[" . $row["SUP_NAME"] . "]]></name>";
        $ResponseXML .= "<txt_remarks><![CDATA[" . $row["REMARK"] . "]]></txt_remarks>";
        $ResponseXML .= "<Attn><![CDATA[" . $row["Attn"] . "]]></Attn>";
        $ResponseXML .= "<tmp_no><![CDATA[" . $row["tmp_no"] . "]]></tmp_no>";
        $msg = "";
        if ($row['cancel'] == "1") {
            $msg = "Cancelled";
        }
        $ResponseXML .= "<msg><![CDATA[" . $msg . "]]></msg>";

        $sql = "delete from tmp_po_data where tmp_no='" . $row["tmp_no"] . "'";
        $result = $conn->query($sql);


        $sql = "Select * from s_ordtrn where REFNO='" . $row["REFNO"] . "'";
        foreach ($conn->query($sql) as $row1) {
            $subtotal = $row1['ORD_QTY'] * $row1['RATE'];
            $sql = "Insert into tmp_po_data (stk_no, descript, qty, rate,subtot, tmp_no) values 
			('" . $row1['STK_NO'] . "', '" . $row1['DESCRIPT'] . "', " . $row1['ORD_QTY'] . ", " . $row1['RATE'] . ",'" . $subtotal . "','" . $row["tmp_no"] . "') ";
            $result_t = $conn->query($sql);
        }


        $ResponseXML .= "<sales_table><![CDATA[<table class=\"table\">
					<tr>
						<td style=\"width: 90px;\">Item</td>
						<td>Description</td>
						<td style=\"width: 60px;\">Qty</td>
						<td style=\"width: 100px;\">Rate</td>
						<td style=\"width: 100px;\">Sub Total</td>
						<td style=\"width: 10px;\"></td>
					</tr>";

        $i = 1;
        $mtot = 0;
        $sql = "Select * from tmp_po_data where tmp_no='" . $row["tmp_no"] . "'";
        foreach ($conn->query($sql) as $row1) {

            $ResponseXML .= "<tr>                              
                             <td ondblclick=\"setitem('" . $row1['stk_no'] . "','" . $row1['descript'] . "','" . $row1['rate'] . "','" . $row1['qty'] . "');\">" . $row1['stk_no'] . "</td>
                             <td ondblclick=\"setitem('" . $row1['stk_no'] . "','" . $row1['descript'] . "','" . $row1['rate'] . "','" . $row1['qty'] . "');\">" . $row1['descript'] . "</td>
                             <td ondblclick=\"setitem('" . $row1['stk_no'] . "','" . $row1['descript'] . "','" . $row1['rate'] . "','" . $row1['qty'] . "');\">" . number_format($row1['rate'], 2, ".", ",") . "</td>
                             <td ondblclick=\"setitem('" . $row1['stk_no'] . "','" . $row1['descript'] . "','" . $row1['rate'] . "','" . $row1['qty'] . "');\">" . number_format($row1['qty'], 2, ".", ",") . "</td>
                             <td ondblclick=\"setitem('" . $row1['stk_no'] . "','" . $row1['descript'] . "','" . $row1['rate'] . "','" . $row1['qty'] . "');\">" . number_format($row1['subtot'], 2, ".", ",") . "</td>
                             <td><a class=\"btn btn-danger btn-xs\" onClick=\"del_item('" . $row1['stk_no'] . "')\"> <span class='fa fa-remove'></span></a></td>
                             </tr>";

            $mtot = $mtot + $row1['subtot'];
            $i = $i + 1;
        }

        $mtot1 = 0;


        $ResponseXML .= "   </table>]]></sales_table>";

        $ResponseXML .= "<item_count><![CDATA[" . $i . "]]></item_count>";
        $ResponseXML .= "<vattot><![CDATA[" . number_format($row['Vat'], 2, ".", ",") . "]]></vattot>";
        $ResponseXML .= "<gtot><![CDATA[" . number_format($row['Vat'] + $mtot, 2, ".", ",") . "]]></gtot>";
        $ResponseXML .= "<subtot><![CDATA[" . number_format($mtot, 2, ".", ",") . "]]></subtot>";
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


    $sql = "select REFNO, SDATE,SUP_CODE,SUP_NAME,AMOUNT from s_ordmas where refno <> ''";

    if ($_GET['refno'] != "") {
        $sql .= " and REFNO like '%" . $_GET['refno'] . "%'";
    }
    if ($_GET['cusname'] != "") {
        $sql .= " and SUP_NAME like '%" . $_GET['cusname'] . "%'";
    }
    $stname = $_GET['stname'];

    $sql .= " ORDER BY REFNO desc limit 50";

    foreach ($conn->query($sql) as $row) {
        $cuscode = $row["REFNO"];


        $ResponseXML .= "<tr>               
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['REFNO'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['SDATE'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['SUP_CODE'] . "</a></td>
                                  <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['SUP_NAME'] . "</a></td>
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

        $sql = "select REFNO,cancel from s_ordmas where tmp_no ='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {
            if ($row['cancel'] != "0") {
                echo "Already Cancelled";
                exit();
            } else {

                $sql = "update s_ordmas set cancel='1' where REFNO = '" . $row['REFNO'] . "'";
                $conn->exec($sql);

                $sql = "update s_ordtrn set cancel='1' where REFNO = '" . $row['REFNO'] . "'";
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






?>