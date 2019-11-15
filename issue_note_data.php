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

    $sql = "Select adj from tmpinvpara_acc";
    $result = $conn->query($sql);
    $row = $result->fetch();

    $tono = $row['adj'];

    $sql = "delete from tmp_stock_adjust_data where str_invno ='" . $tono . "'";
    $result = $conn->query($sql);

    $sql = "update tmpinvpara_acc set adj=adj+1";
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
    $sql = "select adj from invpara";
    $result = $conn->query($sql);
    $row = $result->fetch();
    $tmpinvno = "000000" . $row["adj"];
    $lenth = strlen($tmpinvno);
    return $invno = trim("ADJ/") . substr($tmpinvno, $lenth - 7);
}

if ($_GET["Command"] == "setitem") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";


    $sql = "delete from tmp_stock_adjust_data where str_code='" . $_GET['itemCode'] . "' and str_invno='" . $_GET['tmpno'] . "' ";
    $result = $conn->query($sql);
    if ($_GET["Command1"] == "add_tmp") {
          
        $sql = "Insert into tmp_stock_adjust_data (str_code, str_description, cur_qty, str_invno)values 
			('" . $_GET['itemCode'] . "', '" . $_GET['itemDesc'] . "', " . $_GET['qty'] . ",'" . $_GET['tmpno'] . "') ";
        $result = $conn->query($sql);
    }

    $ResponseXML .= "<sales_table><![CDATA[<table class=\"table\">
					<tr>
						<td style=\"width: 90px;\">Item</td>
						<td>Description</td>
						<td style=\"width: 60px;\">Qty</td>
						<td style=\"width: 10px;\"></td>
					</tr>";

    $i = 1;
    $mtot = 0;
    $sql = "Select * from tmp_stock_adjust_data where str_invno='" . $_GET['tmpno'] . "'";
    foreach ($conn->query($sql) as $row) {

        $ResponseXML .= "<tr>                              
                             <td ondblclick=\"setitem('" . $row['str_code'] . "','" . $row['str_description'] . "','" . $row['cur_qty'] . "');\">" . $row['str_code'] . "</td>
                             <td ondblclick=\"setitem('" . $row['str_code'] . "','" . $row['str_description'] . "','" . $row['cur_qty'] . "');\">" . $row['str_description'] . "</td>
                             <td ondblclick=\"setitem('" . $row['str_code'] . "','" . $row['str_description'] . "','" . $row['cur_qty'] . "');\">" . number_format($row['cur_qty'], 2, ".", ",") . "</td>
                             <td><a class=\"btn btn-danger btn-xs\" onClick=\"del_item('" . $row['str_code'] . "')\"> <span class='fa fa-remove'></span></a></td>
                             </tr>";
        $i = $i + 1;
    }


    $ResponseXML .= "   </table>]]></sales_table>";

    $ResponseXML .= "<item_count><![CDATA[" . $i . "]]></item_count>";
    $ResponseXML .= "</salesdetails>";

    echo $ResponseXML;
}

if ($_GET["Command"] == "save_item") {
    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        $sql = "select refno,CANCELL from inreq_mas where tmp_no ='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {


            if ($row['CANCELL'] != "0") {
                echo "Already Cancelled";
                exit();
            }

            $invno = $row['refno'];
            $sql = "delete from inreq_mas where refno = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from inreq_trn where refno = '" . $invno . "'";
            $conn->exec($sql);
        } else {
            $invno = getno();
            $sql = "update invpara set adj=adj+1";
            $conn->exec($sql);
        }



        $sql = "select * from tmp_stock_adjust_data where str_invno='" . $_GET["tmpno"] . "'";
        foreach ($conn->query($sql) as $row) {
            $cur_qty = str_replace(",", "", $row["cur_qty"]);
            $sql = "Insert into inreq_trn (refno, sdate, stk_no, trn_type, qty , CANCELL, descript) values 
		('" . trim($invno) . "', '" . $_GET['invdate'] . "','" . $row["str_code"] . "','" . $_GET["ttype"] . "', " . $cur_qty . ",'0','" . $row["str_description"] . "')";
            $result = $conn->exec($sql);
            if ($_GET["ttype"] == "IIN") {
                    $sql1 = "update s_mas set QTYINHAND=QTYINHAND+" . $cur_qty . " where STK_NO='" . $row["str_code"] . "' ";
                    //echo $sql1;
                    $result = $conn->exec($sql1);
            } else if ($_GET["ttype"] == "IOU") {
                    $sql1 = "update s_mas set QTYINHAND=QTYINHAND-" . $cur_qty . " where STK_NO='" . $row["str_code"] . "' ";
                    //echo sql1;
                    $result = $conn->exec($sql1);
            }
            $sql5 = "Insert into s_trn (STK_NO, SDATE, REFNO, QTY, LEDINDI) values('" . $row["str_code"] . "', '" . $_GET["invdate"] . "', '" . trim($invno) . "', " . $cur_qty . ", '" . $_GET["ttype"] . "')";
            $result = $conn->exec($sql5);
        }
        $sql1 = "insert into inreq_mas(refno, sdate, trn_type, remark, CANCELL, tmp_no) values 
                ('" . $invno . "', '" . $_GET["invdate"] . "', '" . $_GET["ttype"] . "', '" . $_GET['txt_remarks'] . "', '0', '" . $_GET["tmpno"] . "')";

        $result = $conn->exec($sql1);

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
    $sql = "Select * from inreq_mas where REFNO='" . $_GET['refno'] . "'";
    $result = $conn->query($sql);

    if ($row = $result->fetch()) {
        $ResponseXML .= "<C_REFNO><![CDATA[" . $row["refno"] . "]]></C_REFNO>";
        $ResponseXML .= "<C_DATE><![CDATA[" . $row["sdate"] . "]]></C_DATE>";
        $ResponseXML .= "<txt_remarks><![CDATA[" . $row["remark"] . "]]></txt_remarks>";
        $ResponseXML .= "<tmp_no><![CDATA[" . $row["tmp_no"] . "]]></tmp_no>";
        $ResponseXML .= "<trn_type><![CDATA[" . $row["trn_type"] . "]]></trn_type>";
        $msg = "";
        if ($row['CANCELL'] == "1") {
            $msg = "Cancelled";
        }
        $ResponseXML .= "<msg><![CDATA[" . $msg . "]]></msg>";

        $sql = "delete from tmp_stock_adjust_data where str_invno='" . $row["tmp_no"] . "'";
        $result = $conn->exec($sql);


        $sqlTrn = "Select * from inreq_trn where refno='" . $row["refno"] . "'";


        foreach ($conn->query($sqlTrn) as $row1) {
            $sql = "Insert into tmp_stock_adjust_data (str_code, str_description, cur_qty, str_invno) values
                    ('" . $row1['stk_no'] . "', '" . $row1['descript'] . "', " . $row1['qty'] . ", '" . $row['tmp_no'] . "') ";
//                    $ResponseXML .= "<test><![CDATA[" . $sql . "]]></test>";
            $result = $conn->exec($sql);
        }

        $ResponseXML .= "<sales_table><![CDATA[<table class=\"table\">
					<tr>
						<td style=\"width: 90px;\">Item</td>
						<td>Description</td>
						<td style=\"width: 60px;\">Qty</td>
						<td style=\"width: 10px;\"></td>
					</tr>";

        $i = 1;
        $sql = "Select * from tmp_stock_adjust_data where str_invno='" . $row["tmp_no"] . "'";
        foreach ($conn->query($sql) as $row1) {

            $ResponseXML .= "<tr>                              
                            <td ondblclick=\"setitem('" . $row1['str_code'] . "','" . $row1['str_description'] . "','" . $row1['cur_qty'] . "');\">" . $row1['str_code'] . "</td>
                            <td ondblclick=\"setitem('" . $row1['str_code'] . "','" . $row1['str_description'] . "','" . $row1['cur_qty'] . "');\">" . $row1['str_description'] . "</td>
                            <td ondblclick=\"setitem('" . $row1['str_code'] . "','" . $row1['str_description'] . "','" . $row1['cur_qty'] . "');\">" . number_format($row1['cur_qty'], 2, ".", ",") . "</td>
                            <td></td>
                            </tr>";

            $i = $i + 1;
        }

        $mtot1 = 0;

        $ResponseXML .= "   </table>]]></sales_table>";
        $ResponseXML .= "<item_count><![CDATA[" . $i . "]]></item_count>";
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
                        <th width=\"100\">Type</th> 
                    </tr>";


    $sql = "select * from inreq_mas where refno <> ''";

    if ($_GET['refno'] != "") {
        $sql .= " and refno like '%" . $_GET['refno'] . "%'";
    }
    if ($_GET['cusname'] != "") {
        $sql .= " and trn_type like '%" . $_GET['cusname'] . "%'";
    }
    $stname = $_GET['stname'];
    
    $sql .= " ORDER BY refno desc limit 50";

    foreach ($conn->query($sql) as $row) {
        $cuscode = $row["refno"];


        $ResponseXML .= "<tr>               
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['refno'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['sdate'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['trn_type'] . "</a></td>
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

        $sql = "select refno,CANCELL from inreq_mas where tmp_no ='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {
            if ($row['CANCELL'] != "0") {
                echo "Already Cancelled";
                exit();
            } else {
                $sql = "select * from s_trn where REFNO='" . $row['refno'] . "'";
                foreach ($conn->query($sql) as $row) {

                        if ($row["LEDINDI"] == "IIN") {
                                $sql1 = "update s_mas set QTYINHAND=QTYINHAND-" . $row['QTY'] . " where STK_NO='" . $row["STK_NO"] . "' ";
                                $conn->exec($sql1);
                        } else if ($row["LEDINDI"] == "IOU") {
                                $sql1 = "update s_mas set QTYINHAND=QTYINHAND+" . $row['QTY'] . " where STK_NO='" . $row["STK_NO"] . "' ";
                                $conn->exec($sql1);
                        }
                        
                }

                $sql = "update inreq_mas set CANCELL='1' where refno = '" . $row['refno'] . "'";
                $conn->exec($sql);

                $sql = "update inreq_trn set CANCELL='1' where refno = '" . $row['refno'] . "'";
                $conn->exec($sql);
                
                $sql = "delete from s_trn where REFNO='" . $row['refno'] . "'";
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