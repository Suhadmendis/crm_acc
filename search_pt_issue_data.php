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

 $sql = "select C_CODE from lcodes where C_CODE = '" . $_GET['itemCode'] . "'";
    $num = $conn->query($sql);
    if ($num->rowCount() == 0) {
        exit();
    }


    $sql = "Insert into tmp_che_data (code, descript, amount, tmp_no,form_no,descript1)values
			('" . $_GET['itemCode'] . "', '" . $_GET['itemDesc'] . "', " . $_GET['itemPrice'] . ",'" . $_GET['tmpno'] . "','" . $_GET['form'] . "','" . $_GET['txt_gl_name1'] . "') ";

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
    $sql = "Select * from tmp_che_data where tmp_no='" . $_GET['tmpno'] . "' AND form_no='" . $_GET['form'] . "'";
    foreach ($conn->query($sql) as $row) {
        $ResponseXML .= "<tr>
                         <td>" . $row['code'] . "</td>
                         <td>" . $row['descript'] . "</td>
                         <td>" . $row['descript1'] . "</td>
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

        $sql = "select REFNO,Cancel from ledmas where REFNO ='" . $_GET['crnno'] . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {
            if ($row['Cancel'] != "0") {
                echo "Already Cancelled";
                exit();
            }


            $sql = "delete from ledger where l_refno = '" . $row['REFNO'] . "'";
            $conn->exec($sql);
            $sql = "update ledmas set Cancel='1' where REFNO = '" . $row['REFNO'] . "'";
            $conn->exec($sql);

            $sql = "update ledtrans set Cancel='1' where REFNO = '" . $row['REFNO'] . "'";
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

if ($_GET["Command"] == "del_item") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";

    $sql = "delete from tmp_che_data where id='" . $_GET['code'] . "' and tmp_no='" . $_GET['invno'] . "' AND form_no='" . $_GET['form'] . "'";
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
    $sql = "Select * from tmp_che_data where tmp_no='" . $_GET['invno'] . "' AND form_no='" . $_GET['form'] . "'";
    foreach ($conn->query($sql) as $row) {
        $ResponseXML .= "<tr>
                         <td>" . $row['code'] . "</td>
                         <td>" . $row['descript'] . "</td>
                         <td>" . $row['descript1'] . "</td>
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
if ($_GET["Command"] == "getno") {

    require_once './gl_posting.php';
    $ayear = ac_year($_GET['sdate']);

    $invno = getno($_GET['sdate']);
    $sdate = $_GET['sdate'];



    $sql = "select REFNO,BDATE from ledmas where tmp_no ='" . $_GET['tmpno'] . "'";
    $result = $conn->query($sql);
    if ($row = $result->fetch()) {
        $invno = $row['REFNO'];

        if ($ayear != ac_year($row['BDATE'])) {
            $sdate = $row['BDATE'];
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
    $ayear = ac_year($_GET["crndate"]);

    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        $sql = "select REFNO,Cancel from ledmas where tmp_no ='" . $_GET['tmpno'] . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {
            if ($row['Cancel'] != 0) {
                echo "Already Cancel";
                exit();
            } else {
                $invno = $row['REFNO'];
            }
            $sql = "delete from ledmas where REFNO = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from ledtrans where REFNO = '" . $invno . "'";
            $conn->exec($sql);
            $sql = "delete from ledger where l_refno = '" . $invno . "'";
            $conn->exec($sql);
        } else {
            $invno = getno($_GET["crndate"]);
            $sql = "update dep_mas set LEDGER=LEDGER+1  where current_year = '" . $ayear . "' ";
            $conn->exec($sql);
        }
        $remarks = $_GET['remark'];
        $sql = "Insert into ledmas (REFNO, BDATE, type, DETAILS, Currency,rate,tmp_no) values
		('" . $invno . "', '" . $_GET["crndate"] . "','JOU','" . $remarks . "','" . $_GET["cur"] . "', '" . $_GET["Rate"] . "', '" . $_GET['tmpno'] . "') ";
        $conn->exec($sql);


        $sql = "Select * from tmp_che_data where tmp_no='" . $_GET['tmpno'] . "' AND form_no='CN1'";
        foreach ($conn->query($sql) as $row) {
            $amo = $row['amount'] * $_GET["Rate"];
            if ($row['descript1'] !="") {
            $remarks = $row['descript1'];
            } else {
            $remarks = $remarks;
            }

            $sql = "insert into ledtrans (REFNO, BDATE, CODE, AMOUNT, FLAG, NARA,Currency,rate,curamo) value
                   ('" . $invno . "','" . $_GET["crndate"] . "','" . $row['code'] . "','" . $amo . "','DEB','" . $remarks . "','" . $_GET["cur"] . "','" . $_GET["Rate"] . "','" . $row['amount'] . "')";
            $conn->exec($sql);


            $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo,acyear) value
                   ('" . $invno . "','" . $_GET["crndate"] . "','" . $row['code'] . "','" . $amo . "','CRN','DEB','" . $remarks . "','" . $_GET["cur"] . "','" . $_GET["Rate"] . "','" . $row['amount'] . "','" . $ayear . "')";
            $conn->exec($sql);
        }

        $sql = "Select * from tmp_che_data where tmp_no='" . $_GET['tmpno'] . "' AND form_no='CN2'";
        foreach ($conn->query($sql) as $row) {
            $amo = $row['amount'] * $_GET["Rate"];
            if ($row['descript1'] !="") {
            $remarks = $row['descript1'];
            } else {
            $remarks = $remarks;
            }
            $sql = "insert into ledtrans (REFNO, BDATE, CODE, AMOUNT, FLAG, NARA,Currency,rate,curamo) value
                   ('" . $invno . "','" . $_GET["crndate"] . "','" . $row['code'] . "','" . $amo . "','CRE','" . $remarks . "','" . $_GET["cur"] . "','" . $_GET["Rate"] . "','" . $row['amount'] . "')";
            $conn->exec($sql);

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
    $sql = "select LEDGER from dep_mas  where current_year = '" . $ayear . "' ";
    // echo $sql;
    $result = $conn->query($sql);
    $row = $result->fetch();
    $tmpinvno = "00000" . $row["LEDGER"];
    $lenth = strlen($tmpinvno);
    $invno = trim("J-") . substr($ayear,2) . "/" . substr(($ayear+1),2) . "-" . substr($tmpinvno, $lenth - 5);

    return $invno;
}

if ($_GET["Command"] == "pass_rec") {

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";
    $sql = "Select * from ledmas where REFNO='" . $_GET['refno'] . "'";
    $result = $conn->query($sql);

    if ($row = $result->fetch()) {
        $ResponseXML .= "<C_REFNO><![CDATA[" . $row["refno"] . "]]></C_REFNO>";
        $ResponseXML .= "<C_DATE><![CDATA[" . $row["bdate"] . "]]></C_DATE>";

        $ResponseXML .= "<txt_remarks><![CDATA[" . $row["details"] . "]]></txt_remarks>";
        $ResponseXML .= "<currency><![CDATA[" . $row["Currency"] . "]]></currency>";
        $ResponseXML .= "<txt_rate><![CDATA[" . $row["rate"] . "]]></txt_rate>";
        $ResponseXML .= "<tmp_no><![CDATA[" . $row["tmp_no"] . "]]></tmp_no>";


        $msg = "";
        if ($row['Cancel'] == "1") {
            $msg = "Cancelled";
        }
        $ResponseXML .= "<msg><![CDATA[" . $msg . "]]></msg>";

        $sql = "delete from tmp_che_data where  tmp_no='" . $row["tmp_no"] . "'";
         
        $result = $conn->query($sql);


        $sql = "Select C_CODE,C_NAME,curamo,NARA from view_jou where refno='" . $row["refno"] . "' AND flag='DEB'";
        
        foreach ($conn->query($sql) as $row1) {
            $sql = "Insert into tmp_che_data (code, descript, amount, tmp_no,form_no,descript1)values
			('" . $row1['C_CODE'] . "', '" . $row1['C_NAME'] . "', " . $row1['curamo'] . ",'" . $row['tmp_no'] . "','CN1','" . $row1['NARA'] . "') ";
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
        $sql = "Select * from tmp_che_data where tmp_no='" . $row["tmp_no"] . "' AND form_no='CN1'";
        foreach ($conn->query($sql) as $row1) {
            $ResponseXML .= "<tr>
                         <td>" . $row1['code'] . "</td>
                         <td>" . $row1['descript'] . "</td>
                         <td>" . $row1['descript1'] . "</td>
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
                        <th style=\"width: 420px;\"></th>
                        <th></th>
                        <th style=\"width: 10px;\"></th>
                        <th style=\"width: 120px;\"></th>
                        <th style=\"width: 10px;\"></th>
                    </tr>";
        $i = 1;
        $mtot = 0;

        $sql = "Select C_CODE,C_NAME,curamo,nara from view_jou where refno='" . $row["refno"] . "' AND flag='cre'";
        // echo $sql;
        foreach ($conn->query($sql) as $row1) {
            $sql = "Insert into tmp_che_data (code, descript, amount, tmp_no,form_no,descript1)values
			('" . $row1['C_CODE'] . "', '" . $row1['C_NAME'] . "', " . $row1['curamo'] . ",'" . $row['tmp_no'] . "','CN2','" . $row1['nara'] . "')";
            $result = $conn->query($sql);
        }
        $sql = "Select * from tmp_che_data where tmp_no='" . $row["tmp_no"] . "' AND form_no='CN2'";
        foreach ($conn->query($sql) as $row1) {
            $ResponseXML .= "<tr>
                         <td>" . $row1['code'] . "</td>
                         <td>" . $row1['descript'] . "</td>
                         <td>" . $row1['descript1'] . "</td>
                         <td></td>
                         <td>" . number_format($row1['amount'], 2, ".", ",") . "</td>
                         <td><a class=\"btn btn-danger btn-xs\" onClick=\"del_item('" . $row1['id'] . "','CN2')\"> <span class='fa fa-remove'></span></a></td>
                         </tr>";
            $mtot = $mtot + $row1['amount'];
            $i = $i + 1;
        }
        $ResponseXML .= "</table>]]></sales_table1>";
        $ResponseXML .= "<subtot1><![CDATA[" . number_format($mtot, 2, ".", ",") . "]]></subtot1>";



        $prev = "";

        $sql = "select * from docs where refno = '" . $row["refno"] . "'";
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

                        <th width=\"200\">Remarks</th>

                    </tr>";


    $sql = "select REFNO, BDATE,DETAILS,ID  from ledmas where REFNO <> ''";
    if ($_GET['refno'] != "") {
        $sql .= " and REFNO like '%" . $_GET['refno'] . "%'";
    }
    if ($_GET['cusname'] != "") {
        $sql .= " and DETAILS like '%" . $_GET['cusname'] . "%'";
    }
    $stname = $_GET['stname'];

    $sql .= " ORDER BY ID desc limit 50";

    foreach ($conn->query($sql) as $row) {
        $cuscode = $row["REFNO"];


        $ResponseXML .= "<tr>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['REFNO'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['BDATE'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['DETAILS'] . "</a></td>
                            </tr>";
    }
    $ResponseXML .= "</table>";
    echo $ResponseXML;
}
?>
