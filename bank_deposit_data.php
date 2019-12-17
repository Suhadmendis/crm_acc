<?php

session_start();

////////////////////////////////////////////// Database Connector /////////////////////////////////////////////////////////////
require_once ("connection_sql.php");

////////////////////////////////////////////// Write XML ////////////////////////////////////////////////////////////////////
header('Content-Type: text/xml');

date_default_timezone_set('Asia/Colombo');

/////////////////////////////////////// GetValue //////////////////////////////////////////////////////////////////////////
///////////////////////////////////// Registration /////////////////////////////////////////////////////////////////////////


if ($_GET["Command"] == "setcheq") {


    if ($_GET["Command1"] == "add") {

        $sql = "delete from tmp_bankdepo_chq where chqno='" . $_GET["chqno"] . "' and tmp_no='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);

        if ($_GET["narration"] == "") {
            $narration = str_replace("~", "&", $_GET["TXT_HEADING"]);
        } else {
            $narration = str_replace("~", "&", $_GET["narration"]);
        }

        $TXT_DETAILS = str_replace("~", "&", $narration);
        $TXT_DETAILS = str_replace("&nbsp;", " ", $TXT_DETAILS);

        $sql = "insert into tmp_bankdepo_chq(id, entno, chqno, chqdate, narration, bank, amt, tmp_no) values ('" . $_GET["id"] . "', '" . $_GET["txt_entno"] . "', '" . $_GET["chqno"] . "', '" . $_GET["chqdate"] . "', '" . $TXT_DETAILS . "', '" . $_GET["bank"] . "', " . $_GET["chqamt"] . ", '" . $_GET['tmpno'] . "')";
        $result = $conn->query($sql);
    }
    if ($_GET["Command1"] == "del") {

        $sql = "delete from tmp_bankdepo_chq where chqno='" . $_GET["accno1"] . "' and tmp_no='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);
    }


    $totamt = 0;
    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";

    $ResponseXML .= "<chq_table><![CDATA[<table class=\"table\">
                                        <tr>
                        <th style=\"width: 120px;\"></th>
                        <th style=\"width: 120px;\"></th>
                        <th style=\"width: 120px;\"></th>
                        <th style=\"width: 120px;\"></th>
                        <th style=\"width: 10px;\"></th>
                        <th style=\"width: 120px;\"></th>
                        <th style=\"width: 10px;\"></th>
                        <th style=\"width: 10px;\"></th>
                    </tr>";

    $i = 1;
    $totamt = 0;
    $sql = "select * from tmp_bankdepo_chq where tmp_no='" . $_GET['tmpno'] . "' ";
    foreach ($conn->query($sql) as $row) {

        $amt = "amteditchq" . $i;

        $ResponseXML .= "<tr>
					<td>" . $row["chqno"] . "</td>
					<td>" . $row["chqdate"] . "</td>
					<td>" . $row["narration"] . "</td>
					<td>" . $row["bank"] . "</td>
                                            <td></td>
					<td>" . number_format($row["amt"], 2, ".", "") . "</td>
					<td><a class=\"btn btn-danger btn-xs\" onClick=\"del_item2('" . $row['chqno'] . "')\"> <span class='fa fa-remove'></span></a></td>

					<td>" . $row["id"] . "</td></tr>";


        $totamt = $totamt + $row["amt"];
        $i = $i + 1;
    }

    $ResponseXML .= "   </table>]]></chq_table>";
    $ResponseXML .= "<totamt><![CDATA[" . number_format($totamt, 2, ".", "") . "]]></totamt>";

    $ResponseXML .= " </salesdetails>";
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
                         <td><a class=\"btn btn-danger btn-sm\" onClick=\"del_item('" . $row['id'] . "','PCH')\"> <span class='fa fa-remove'></span></a></td>
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


if ($_GET["Command"] == "save_item") {


    require_once './gl_posting.php';
    $ayear = ac_year($_GET["entrydate"]);
    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        $sql = "select REFNO from bankdepmas where tmp_no ='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {
            $invno = $row['REFNO'];
            $sql = "delete from bankdepmas where REFNO = '" . $invno . "'";
            $conn->exec($sql);

            $sql = "delete from bankdeptrn where REFNO = '" . $invno . "'";
            $conn->exec($sql);

            $sql = "delete from bankdepche where refno = '" . $invno . "'";
            $conn->exec($sql);

            $sql = "Update s_invcheq  set ret_refno='0' where ret_refno='" . trim($invno) . "'";
            $conn->exec($sql);

            $sql = "delete from ledger where l_refno = '" . $invno . "'";
            $conn->exec($sql);
        } else {
            $invno = getno($_GET["entrydate"]);
            $sql = "update dep_mas set BANKDEP=BANKDEP+1  where current_year = '" . $ayear . "'";
            $conn->exec($sql);
        }




        $sql = "select * from BANKMASTER where bm_code='" . $_GET['bank'] . "'";
        $result = $conn->query($sql);
        $row = $result->fetch();



        $sql = "Insert into bankdepmas (refno, bdate, heading, code, name, amount, cash, comcode, cancel, type, tmp_no) "
                . "Values ('" . trim($invno) . "', '" . $_GET["entrydate"] . "', '" . trim($_GET['txt_narration']) . "', '" . $_GET['bank'] . "', '" . $row["BM_BANK"] . "', " . $_GET['txt_payments'] . ", 0,'" . $_SESSION['COMCODE'] . "', '0', 'D', '" . $_GET['tmpno'] . "')";

        $conn->exec($sql);




        $sql = "Select * from tmp_che_data where tmp_no='" . $_GET['tmpno'] . "' AND form_no='PCH'";
        foreach ($conn->query($sql) as $row) {
            $amo = $row['amount'] * $_GET["txt_rate"];
            $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo,acyear,ComCode) value
                   ('" . $invno . "','" . $_GET["entrydate"] . "','" . $row['code'] . "','" . $amo . "','BDE','CRE','" . $_GET['txt_narration'] . "','" . $_GET["currency"] . "','" . $_GET["txt_rate"] . "','" . $row['amount'] . "','" . $ayear . "','".$_SESSION['COMCODE']."')";
            $conn->exec($sql);

            $sql = "insert into bankdeptrn (refno, bdate, code, amount, flag,nara,ComCode) value
                   ('" . $invno . "','" . $_GET["entrydate"] . "','" . $row['code'] . "','" . $amo . "','CRE','" . $_GET['txt_narration'] . "','".$_SESSION['COMCODE']."')";
            $conn->exec($sql);
        }




        $sql = "Select * from tmp_bankdepo_chq where tmp_no='" . $_GET['tmpno'] . "'";
        foreach ($conn->query($sql) as $row) {
            $amo = $row['amt'] * $_GET["txt_rate"];
            $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo,acyear,chno,ComCode) value
                   ('" . $invno . "','" . $_GET["entrydate"] . "','" . $_GET['bank'] . "','" . $amo . "','BDE','DEB','" . $_GET['txt_narration'] . "','" . $_GET["currency"] . "','" . $_GET["txt_rate"] . "','" . $row['amt'] . "','" . $ayear . "','" . $row["chqno"] . "','".$_SESSION['COMCODE']."')";
            $conn->exec($sql);


            $sql = "Insert into bankdepche(refno, cheno, bdate, ven_code, ven_name, bank, amount ,comcode, id  ) "
                    . "Values ('" . trim($invno) . "', '" . $row["chqno"] . "', '" . $_GET["entrydate"] . "', '" . $_GET['txt_narration'] . "', '', '" . $row["bank"] . "', " . $amo . ",'C', " . $row["id"] . " )";
            $conn->exec($sql);

            if ($row["id"] != "" and ($row["id"] != "0"))  {
                $sql = "Update s_invcheq  set ret_refno='" . trim($invno) . "' where id=" . $row["id"] . "";
                $result = $conn->exec($sql);
            }
        }

        $conn->commit();
        echo "Saved";
    } catch (Exception $e) {
        $conn->rollBack();
        echo $e ." - Failed";
    }
}

function getno($sdate) {


    require_once './gl_posting.php';
    $ayear = ac_year($sdate);

    include './connection_sql.php';
    $sql = "select BANKDEP from dep_mas  where current_year = '" . $ayear . "'";
    $result = $conn->query($sql);
    $row = $result->fetch();
    $tmpinvno = "0000" . $row["BANKDEP"];
    $lenth = strlen($tmpinvno);
    $invno = trim("DEP/" . substr($ayear, 2, 2) . "/") . substr($tmpinvno, $lenth - 4);

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

    $sql = "delete from tmp_bankdepo_chq where tmp_no='" . $tono . "'";
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

if ($_GET["Command"] == "getno") {

    require_once './gl_posting.php';
    $ayear = ac_year($_GET['sdate']);

    $invno = getno($_GET['sdate']);
    $sdate = $_GET['sdate'];


    $sql = "select REFNO,bdate from bankdepmas where tmp_no ='" . $_GET['tmpno'] . "'";
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


if ($_GET["Command"] == "pass_rec") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";
    $sql = "Select * from bankdepmas where REFNO='" . $_GET['refno'] . "'";
    $result = $conn->query($sql);

    if ($row = $result->fetch()) {
        $ResponseXML .= "<C_REFNO><![CDATA[" . $row["REFNO"] . "]]></C_REFNO>";
        $ResponseXML .= "<C_DATE><![CDATA[" . $row["BDATE"] . "]]></C_DATE>";


        $ResponseXML .= "<BANK><![CDATA[" . $row["CODE"] . "]]></BANK>";






        $ResponseXML .= "<txt_narration><![CDATA[" . $row["NARATION"] . "]]></txt_narration>";


        $ResponseXML .= "<tmp_no><![CDATA[" . $row["tmp_no"] . "]]></tmp_no>";
        $msg = "";
        if ($row['CANCEL'] == "1") {
            $msg = "Cancelled";
        }
        $ResponseXML .= "<msg><![CDATA[" . $msg . "]]></msg>";







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
        $ResponseXML .= "<subtot><![CDATA[" . number_format($mtot, 2, ".", ",") . "]]></subtot>";


        $sql = "delete from tmp_bankdepo_chq where  tmp_no='" . $row["tmp_no"] . "'";
        $result = $conn->query($sql);
        $sql = "Select * from bankdepche where refno='" . $row["REFNO"] . "'";
        foreach ($conn->query($sql) as $row1) {

            $sql = "insert into tmp_bankdepo_chq(id, entno, chqno, chqdate, narration, bank, amt, tmp_no) "
                    . "values ('" . $row1["id"] . "', '" . $row["REFNO"]  . "', '" . $row1["CHENO"] . "', '" . $row1["BDATE"] . "', '', '" . $row1["BANK"] . "', " . $row1["AMOUNT"] . ", '" . $row["tmp_no"] . "')";
               $result = $conn->query($sql);

        }

        $ResponseXML .= "<sales_table1><![CDATA[<table class=\"table\">
                                        <tr>
                        <th style=\"width: 120px;\"></th>
                        <th style=\"width: 120px;\"></th>
                        <th style=\"width: 120px;\"></th>
                        <th style=\"width: 120px;\"></th>
                        <th style=\"width: 10px;\"></th>
                        <th style=\"width: 120px;\"></th>
                        <th style=\"width: 10px;\"></th>
                        <th style=\"width: 10px;\"></th>
                    </tr>";

    $i = 1;
    $totamt = 0;
    $sql = "select * from tmp_bankdepo_chq where tmp_no='" . $row["tmp_no"] . "' ";
    foreach ($conn->query($sql) as $row1) {

        $amt = "amteditchq" . $i;

        $ResponseXML .= "<tr>
					<td>" . $row1["chqno"] . "</td>
					<td>" . $row1["chqdate"] . "</td>
					<td>" . $row1["narration"] . "</td>
					<td>" . $row1["bank"] . "</td>
                                            <td></td>
					<td>" . number_format($row1["amt"], 2, ".", "") . "</td>
					<td><a class=\"btn btn-danger btn-xs\" onClick=\"del_item2('" . $row1['chqno'] . "')\"> <span class='fa fa-remove'></span></a></td>

					<td>" . $row1["id"] . "</td></tr>";


        $totamt = $totamt + $row1["amt"];
        $i = $i + 1;
    }

    $ResponseXML .= "   </table>]]></sales_table1>";
    $ResponseXML .= "<subtot1><![CDATA[" . number_format($totamt, 2, ".", "") . "]]></subtot1>";



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



if ($_GET["Command"] == "update_list") {
    $ResponseXML = "";
    $ResponseXML .= "<table class=\"table\">
	            <tr>
                        <th width=\"121\">Reference No</th>
                        <th width=\"121\">Date</th>

                        <th width=\"200\">Remarks</th>

                    </tr>";


    $sql = "select REFNO, BDATE,name,AMOUNT,ID from bankdepmas where REFNO <> '' and comcode = '" . $_SESSION['COMCODE'] . "'" ;
    if ($_GET['refno'] != "") {
        $sql .= " and REFNO like '%" . $_GET['refno'] . "%'";
    }
    if ($_GET['cusname'] != "") {
        $sql .= " and name like '%" . $_GET['cusname'] . "%'";
    }
    $stname = $_GET['stname'];

    $sql .= " ORDER BY ID desc limit 50";
    //echo $sql;

    foreach ($conn->query($sql) as $row) {
        $cuscode = $row["REFNO"];


        $ResponseXML .= "<tr>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['REFNO'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['BDATE'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['name'] . "</a></td>
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

        $sql = "select REFNO,CANCEL from bankdepmas where tmp_no ='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {
            if ($row['CANCEL'] != "0") {
                echo "Already Cancelled";
                exit();
            }

            $sql="Update bankdepmas set CANCEL = '1' where refno='" .  trim($row['REFNO'])  . "'";
            $conn->exec($sql);
            $sql = "delete from ledger where l_refno = '" . $row['REFNO'] . "'";
            $conn->exec($sql);
            $sql="Update s_invcheq  set ret_refno='0' where ret_refno='" .  trim($row['REFNO'])  . "'";
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



if ($_GET["Command"]=="search_chq"){



		$sql="select cheque_no, che_amount, che_date, bank, refno, CUS_NAME, cus_code, ID, ret_refno  from s_invcheq where (ret_refno='0' or ret_refno='1') ";
		if ($_GET['customername']!= "") {
		    $sql .= " and CUS_NAME like '%" . $_GET['customername'] . "%'";
		}
		if ($_GET['cusno']!= "") {
		     $sql .= " and cheque_no like '%" . $_GET['cusno'] . "%'";
		}

		$tb = "<table class=\"table\">";


			$tb .=  "<tr><th>Cheque No</th>
                              <th>Cheque Date</th>
                              <th>Cheque Amount</th>
                              <th>Bank</th>
                              <th>Customer</th></tr>";

		$sql .= "  ORDER BY che_date desc limit 50";

					 //echo $sql;
							$stname = $_GET["stname"];
							foreach ($conn->query($sql) as $row) {
								$cuscode = $row["ID"];


							$tb .= "<tr>
                              <td onclick=\"chqno('$cuscode', '$stname');\">".$row['cheque_no']."</a></td>
                              <td onclick=\"chqno('$cuscode', '$stname');\">".$row['che_date']."</a></td>
                         	  <td onclick=\"chqno('$cuscode', '$stname');\">".$row['che_amount']."</a></td>
							  <td onclick=\"chqno('$cuscode', '$stname');\">".$row['bank']."</a></td>
							  <td onclick=\"chqno('$cuscode', '$stname');\">".$row['cus_code']." ".$row['CUS_NAME']."</a></td>";

                            $tb .= "</tr>";
							}

	$tb .= "</table>";
	echo $tb;

}

if ($_GET["Command"]=="pass_chqno"){


	header('Content-Type: text/xml');
	echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

	$ResponseXML = "";
	$ResponseXML .= "<salesdetails>";

	$sql="select * from s_invcheq where ID=" . $_GET["id"];

	$result = $conn->query($sql);
	if ($row = $result->fetch()) {
		$ResponseXML .= "<cheque_no><![CDATA[".$row['cheque_no']."]]></cheque_no>";
		$ResponseXML .= "<che_date><![CDATA[".$row['che_date']."]]></che_date>";
		$ResponseXML .= "<che_amount><![CDATA[".$row['che_amount']."]]></che_amount>";
		$ResponseXML .= "<id><![CDATA[".$row['ID']."]]></id>";

	}

	$ResponseXML .= "</salesdetails>";
	echo $ResponseXML;
}
