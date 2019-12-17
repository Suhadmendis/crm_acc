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

  require_once './gl_posting.php';
     $ayear = ac_year(date('Y-m-d'));


    include './connection_sql.php';
    $sql = "select INVNO from dep_mas where current_year = '" . $ayear . "'";
    $result = $conn->query($sql);
    $row = $result->fetch();
    $tmpinvno = "00000" . $row["INVNO"];
    $lenth = strlen($tmpinvno);
    return $invno = trim("INV-") . substr($ayear,2) . "/" . substr(($ayear+1),2) . "-" . substr($tmpinvno, $lenth - 5);
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
                             <td>" . $row['stk_no'] . "</td>
							 <td>" . $row['descript'] . "</td>
							 <td>" . number_format($row['rate'], 2, ".", ",") . "</td>
							 <td>" . number_format($row['qty'], 2, ".", ",") . "</td>
							 <td>" . number_format($row['subtot'], 2, ".", ",") . "</td>
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
    $sql = "select vatrate,nbt from invpara";
    $result = $conn->query($sql);
    $row = $result->fetch();

    $nbt = ($mtot * ($row['nbt'] / 100));
    if ($_GET['vat'] != "non") {
        $mtot1 = ($mtot + $nbt) * ($row['vatrate'] / 100);
    }



    $ResponseXML .= "   </table>]]></sales_table>";

    $ResponseXML .= "<item_count><![CDATA[" . $i . "]]></item_count>";
    $ResponseXML .= "<vattot><![CDATA[" . number_format($mtot1, 2, ".", ",") . "]]></vattot>";
    $ResponseXML .= "<nbt><![CDATA[" . number_format($nbt, 2, ".", ",") . "]]></nbt>";
    $ResponseXML .= "<gtot><![CDATA[" . number_format($mtot1 + $mtot + $nbt, 2, ".", ",") . "]]></gtot>";
    $ResponseXML .= "<subtot><![CDATA[" . number_format($mtot, 2, ".", ",") . "]]></subtot>";
    $ResponseXML .= "</salesdetails>";

    echo $ResponseXML;
}

if ($_GET["Command"] == "save_item") {
    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();


        require_once './gl_posting.php';
        $ayear = ac_year($_GET["invdate"]);

        $sql = "select REF_NO,CANCELL,curtotpay from s_salma where tmp_no ='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {


            if ($row['CANCELL'] != "0") {
                echo "Already Enterd";
                exit();
            }
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

            $sql = "delete from s_salma where REF_NO = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from ledger where l_refno = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from c_master where ref_no1 = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from s_invo where ref_no = '" . $invno . "'";
            $conn->exec($sql);
        } else {
            $invno = getno();



            $sql = "update dep_mas set INVNO=INVNO+1  where current_year = '" . $ayear . "'";
            $conn->exec($sql);
        }

        $subtot = str_replace(",", "", $_GET["subtot"]);


        $mtot = 0;
        $sql = "select * from tmp_po_data where tmp_no='" . $_GET["tmpno"] . "'";
        foreach ($conn->query($sql) as $row) {

            $sql = "Insert into s_invo (REF_NO, SDATE, STK_NO, DESCRIPT, QTY , PRICE) values
		('" . trim($invno) . "', '" . $_GET['invdate'] . "','" . $row["stk_no"] . "','" . $row["descript"] . "', " . $row["rate"] . "," . $row["qty"] . ")";
            $result = $conn->query($sql);
            $mtot = $mtot + ($row["rate"] * $row["qty"]);
        }
        $mtot1 = 0;

        $sql = "select vatrate,nbt from invpara";
        $result = $conn->query($sql);
        $row = $result->fetch();

        $nbt = ($mtot * ($row['nbt'] / 100));
        if ($_GET['vat'] != "non") {
            $mtot1 = ($mtot + $nbt) * ($row['vatrate'] / 100);
        }

        $mgrand_tot = ($mtot + $mtot1) * $_GET['txt_rate'];

        $sql = "insert s_salma (REF_NO,SDATE,trn_type,  C_CODE, CUS_NAME,c_add1, vat,curamo,tmp_no,REMARK,btt,cur,rate,grand_tot) values
	('" . $invno . "', '" . $_GET['invdate'] . "','INV' ,'" . $_GET["customercode"] . "', '" . $_GET["customername"] . "','" . $_GET["cont_p"] . "','" . $mtot1 . "','" . ($mtot + $mtot1) . "','" . $_GET["tmpno"] . "','" . $_GET['txt_remarks'] . "','" . $nbt . "','" . $_GET['currency'] . "','" . $_GET['txt_rate'] . "','" . $mgrand_tot . "')";
        $result = $conn->query($sql);

        $sql = "insert into c_master (ref_no,ref_no1,sdate,c_code,amo,cur,rate,type) values
                ('" . $invno . "','" . $invno . "','" . $_GET["invdate"] . "','" . $_GET["customercode"] . "','" . ($mtot + $mtot1) . "','" . trim($_GET["currency"]) . "','" . $_GET["txt_rate"] . "','DEB')";
        $conn->exec($sql);



        $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo,acyear) value
                   ('" . $invno . "','" . $_GET["invdate"] . "','TDL-8000.001','" . $mgrand_tot . "','INV','CRE','Invoice','" . $_GET["currency"] . "','" . $_GET["txt_rate"] . "','" . ($mtot + $mtot1) . "','" . $ayear . "')";

        $conn->exec($sql);

        $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo,acyear) value
                   ('" . $invno . "','" . $_GET["invdate"] . "','TDL-4000.001','" . $mgrand_tot . "','INV','DEB','Invoice','" . $_GET["currency"] . "','" . $_GET["txt_rate"] . "','" . ($mtot + $mtot1) . "','" . $ayear . "')";

        $conn->exec($sql);


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
    $sql = "Select * from s_salma where REF_NO='" . $_GET['refno'] . "'";
    $result = $conn->query($sql);

    if ($row = $result->fetch()) {
        $ResponseXML .= "<C_REFNO><![CDATA[" . $row["REF_NO"] . "]]></C_REFNO>";
        $ResponseXML .= "<C_DATE><![CDATA[" . $row["SDATE"] . "]]></C_DATE>";
        $ResponseXML .= "<C_CODE><![CDATA[" . $row["C_CODE"] . "]]></C_CODE>";
        $ResponseXML .= "<name><![CDATA[" . $row["CUS_NAME"] . "]]></name>";
        $ResponseXML .= "<txt_remarks><![CDATA[" . $row["REMARK"] . "]]></txt_remarks>";
        $ResponseXML .= "<Attn><![CDATA[" . $row['C_ADD1'] . "]]></Attn>";
        $ResponseXML .= "<tmp_no><![CDATA[" . $row["tmp_no"] . "]]></tmp_no>";

        $ResponseXML .= "<currency><![CDATA[" . $row['cur'] . "]]></currency>";
        $ResponseXML .= "<txt_rate><![CDATA[" . $row["rate"] . "]]></txt_rate>";


        $msg = "";
        if ($row['CANCELL'] == "1") {
            $msg = "Cancelled";
        }
        $ResponseXML .= "<msg><![CDATA[" . $msg . "]]></msg>";

        $sql = "delete from tmp_po_data where tmp_no='" . $row["tmp_no"] . "'";
        $result = $conn->query($sql);


        $sql = "Select * from s_invo where REF_NO='" . $row["REF_NO"] . "'";
        foreach ($conn->query($sql) as $row1) {
            $subtotal = $row1['QTY'] * $row1['PRICE'];
            $sql = "Insert into tmp_po_data (stk_no, descript, qty, rate,subtot, tmp_no) values
			('" . $row1['STK_NO'] . "', '" . $row1['DESCRIPT'] . "', " . $row1['QTY'] . ", " . $row1['PRICE'] . ",'" . $subtotal . "','" . $row["tmp_no"] . "') ";
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
                             <td>" . $row1['stk_no'] . "</td>
							 <td>" . $row1['descript'] . "</td>
							 <td>" . number_format($row1['rate'], 2, ".", ",") . "</td>
							 <td>" . number_format($row1['qty'], 2, ".", ",") . "</td>
							 <td>" . number_format($row1['subtot'], 2, ".", ",") . "</td>
							 <td><a class=\"btn btn-danger btn-xs\" onClick=\"del_item('" . $row1['stk_no'] . "')\"> <span class='fa fa-remove'></span></a></td>
							 </tr>";

            $mtot = $mtot + $row1['subtot'];
            $i = $i + 1;
        }

        $mtot1 = 0;


        $ResponseXML .= "   </table>]]></sales_table>";

        $ResponseXML .= "<item_count><![CDATA[" . $i . "]]></item_count>";
        $ResponseXML .= "<vattot><![CDATA[" . number_format($row['VAT'], 2, ".", ",") . "]]></vattot>";
        $ResponseXML .= "<nbt><![CDATA[" . number_format($row['BTT'], 2, ".", ",") . "]]></nbt>";
        $ResponseXML .= "<gtot><![CDATA[" . number_format($row['curamo'], 2, ".", ",") . "]]></gtot>";
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


    $sql = "select REF_NO, SDATE,C_CODE,CUS_NAME,GRAND_TOT from s_salma where trn_type = 'INV'";

    if ($_GET['refno'] != "") {
        $sql .= " and REF_NO like '%" . $_GET['refno'] . "%'";
    }
    if ($_GET['cusname'] != "") {
        $sql .= " and cus_NAME like '%" . $_GET['cusname'] . "%'";
    }
    $stname = $_GET['stname'];

    $sql .= " ORDER BY id desc limit 50";

    foreach ($conn->query($sql) as $row) {
        $cuscode = $row["REF_NO"];


        $ResponseXML .= "<tr>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['REF_NO'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['SDATE'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['C_CODE'] . "</a></td>
                                  <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['CUS_NAME'] . "</a></td>
                                      <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['GRAND_TOT'] . "</a></td>
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

        $sql = "select REF_NO,CANCELL,curtotpay from s_salma where tmp_no ='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {


            if ($row['CANCELL'] != "0") {
                echo "Already Enterd";
                exit();
            }
            if ($row['CANCELL'] != "0") {
                echo "Already Cancelled";
                exit();
            }

            if ($row['curtotpay'] > 0) {
                echo "Already Paid";
                exit();
            }

            $invno = $row['REF_NO'];

            $sql = "delete from ledger where l_refno = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from c_master where ref_no1 = '" . $invno . "'";
            $conn->exec($sql);

            $sql = "update s_salma set CANCELL='1' where REF_NO = '" . $row['REF_NO'] . "'";
            $conn->exec($sql);

            $sql = "update s_invo set CANCELL='1' where ref_no = '" . $row['REF_NO'] . "'";
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
