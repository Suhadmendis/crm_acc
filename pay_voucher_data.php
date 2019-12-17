<?php

session_start();

////////////////////////////////////////////// Database Connector /////////////////////////////////////////////////////////////
require_once ("connection_sql.php");

////////////////////////////////////////////// Write XML ////////////////////////////////////////////////////////////////////
header('Content-Type: text/xml');
date_default_timezone_set('Asia/Colombo');

if ($_GET["Command"] == "getdt") {
    header('Content-Type: text/xml');
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";


    $ResponseXML = "";
    $ResponseXML .= "<new>";


    $sql = "SELECT payment_voucher_code FROM invpara";
    $result = $conn->query($sql);
    $row = $result->fetch();
    $no = $row['payment_voucher_code'];
    $uniq = uniqid();
    $tmpinvno = "000000000" . $row["payment_voucher_code"];
    $lenth = strlen($tmpinvno);
    $no = trim("PV/") . substr($tmpinvno, $lenth - 7);

    $ResponseXML .= "<id3><![CDATA[$uniq]]></id3>";
    $ResponseXML .= "<py_no><![CDATA[$no]]></py_no>";

    $ResponseXML .= "</new>";

    echo $ResponseXML;
}



if ($_GET["Command"] == "save_content") {

    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

         $sql = "delete from payment_voucher where py_no = '" . $_GET['py_no'] . "'";
        $result = $conn->query($sql);
        
        $saveContentSql = "Insert into payment_voucher(py_no,manual_ref,date,payment_cash_book,currency_code,rate,payment_type,cheque_no_ref,chq_date_ref,chq_bank_ref,total_amount_of_payment,allocation_percentage,allocated,to_be_allocated,payee_name,remark,user,uniq,c_ref)values
               ('" . $_GET['py_no'] . "','" . $_GET['manual_ref'] . "','" . $_GET['date'] . "','" . $_GET['payment_cash_book'] . "','" . $_GET['currency_code'] . "','" . $_GET['rate'] . "','" . $_GET['payment_type'] . "','" . $_GET['cheque_no_ref'] . "','" . $_GET['chq_date_ref'] . "','" . $_GET['chq_bank_ref'] . "','" . $_GET['total_amount_of_payment'] . "','" . $_GET['allocation_percentage'] . "','" . $_GET['allocated'] . "','" . $_GET['to_be_allocated'] . "','" . $_GET['payee_name'] . "','" . $_GET['remark'] . "','" . $_GET['user'] . "','" . $_GET['uniq'] . "','" . $_GET['c_ref'] . "') ";
        $result = $conn->query($saveContentSql);




        



        // $saveContentSql = "Insert into s_sttr(ST_REFNO,ST_DATE)values
        //                   ('" . $_GET['py_no'] . "','" . $_GET['date'] . "') ";
        // $result = $conn->query($saveContentSql);


// $sql="insert into ledger(l_refno, l_date, l_code, l_amount, l_flag, l_flag1, l_lmem, l_head, l_flag2, l_flag3, l_yearfl, comcode, chno, recdate, l_year) values ('".$_GET['py_no']."', '".$_GET['date']."', '". $_GET['payment_cash_book'] ."', ". $_GET['total_amount_of_payment'] .", ". $_GET['total_amount_of_payment']*-1 .", 'CAP', 'CRE', '". $_GET['remark'] ."', '', '0', 'R', 0, '".$_SESSION['company']."', '', '0', ".date("Y").")";
    
     // $result = $conn->query($sql);


        $sql = "SELECT payment_voucher_code FROM invpara";
        $result = $conn->query($sql);
        $row = $result->fetch();
        $no = $row['payment_voucher_code'];

        // $sql2 = "select * from payment_voucher_table_temp where uniq = '" . $_GET['uniq'] . "'";
        // foreach ($conn->query($sql2) as $row) {

        //     $sql = "Insert into payment_voucher_table(paymentvoucherid,debitaccount,accountname,percentage_apply,amount,jobno,supplierinno,supplierinvdate)values 
        //      ('" . $row['paymentvoucher_id'] . "','" . $row['debitaccount'] . "','" . $row['accountname'] . "','" . $row['percentage_apply'] . "','" . $row['amount'] . "','" . $row['jobno'] . "','" . $row['supplier_in_no'] . "','" . $row['supplier_inv_date'] . "')";

        //     $result = $conn->query($sql);

        //        $sql="insert into ledger(l_refno, l_date, l_code, l_amount,l_amount1, l_flag, l_flag1, l_lmem, l_head, l_flag2, l_flag3, l_yearfl, comcode, chno, recdate, l_year) values ('".$_GET['py_no']."', '".$_GET['date']."', '". $row['debitaccount'] ."', ". $row['amount'] .", ". $row['amount'] .", 'CAP', 'DEB', '". $row['remark'] ."', '', '0', 'R', 0, '".$_SESSION['company']."', '', '0', ".date("Y").")";
            
        //      $result = $conn->query($sql);



        // }

        // foreach ($conn->query($sql2) as $row) {

        //     $sql = "DELETE FROM payment_voucher_table_temp where uniq= '" . $_GET['uniq'] . "'";

        //     $result = $conn->query($sql);
        // }

        $no2 = $no + 1;
        $sql = "update invpara set payment_voucher_code = $no2 where payment_voucher_code = $no";
        $result = $conn->query($sql);

        $conn->commit();
        echo "Saved";
    } catch (Exception $e) {
        $conn->rollBack();
        echo $e;
    }
}
if ($_GET["Command"] == "setitem") {
    header('Content-Type: text/xml');
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";



    if ($_GET["Command1"] == "add_tmp") {



        $mid = $_GET["py_no"];

   
        // echo $mid;

        $sql = "Insert into payment_voucher_table_temp(paymentvoucher_id,debitaccount,uniq,accountname,percentage_apply,amount,jobno,supplier_in_no,supplier_inv_date)values 
     ('" . $mid . "','" . $_GET['debitaccount'] . "','" . $_GET['uniq'] . "','" . $_GET['accountname'] . "','" . $_GET['percentage_apply'] . "','" . $_GET['amount'] . "','" . $_GET['jobno'] . "','" . $_GET['supplier_in_no'] . "','" . $_GET['supplier_inv_date'] . "')";

        $result = $conn->query($sql);
        //echo $sql;
    }

    $ResponseXML .= "<sales_table><![CDATA[<table id='myTable' class='table table-bordered'>
                            <thead>
                                <tr>
                                <th style='width: 10%;'>Debit Account</th>
                                <th style='width: 15%;'>Account Name</th>
                                <th style='width: 3%;'></th>
                                <th style='width: 10%;'>Percentage Apply</th>
                                <th style='width: 10%;'>Amount</th>
                                <th style='width: 10%;'>Job No</th>
                                <th style='width: 3%;'></th>
                                <th style='width: 10%;'>Supplier In No.</th>
                                <th style='width: 10%;'>Supplier Inv Date</th>
                                <th style='width: 3%;'>Add/Remove</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                    <input type='text' placeholder='Debit Account' id='debitaccount' class='form-control input-sm'>
                                    </td>
                                    <td>
                                    <input type='text' placeholder='Account Name'  id='accountname' class='form-control input-sm'>
                                    </td>
                                    <td> 
                                        <a  onclick=\"NewWindow('search_ledg.php?stname=payment_voucher_table', 'mywin', '800', '700', 'yes', 'center');
                                                return false\" onfocus=\"this.blur()\">
                                            <input type=\"button\"  value=\"...\" class=\"btn btn-default btn-sm\">
                                        </a>
                                    </td>
                                    <td>
                                    <input  type='text' placeholder='Percentage Apply' onkeyup=\"perCal('per',event);\" id='percentage_apply' class='form-control input-sm'>
                                    </td>
                                    <td>
                                    <input  type='text' placeholder='Amount' onkeyup=\"perCal('am',event);\" id='amount' class='form-control input-sm'>
                                    </td>
                                    <td>
                                    <input type='text' placeholder='Job No' id='jobno' class='form-control input-sm'>
                                    </td>
                                    <td>
                                        <a  onclick=\"NewWindow('search_joborder.php?stname=rec_payment', 'mywin', '800', '700', 'yes', 'center');
                                                return false\" onfocus=\"this.blur()\">
                                            <input type=\"button\"  value=\"...\" class=\"btn btn-default btn-sm\">
                                        </a>
                                    </td>
                                    <td>
                                    <input type='text' placeholder='Supplier In No.'  id='supplier_in_no' class='form-control input-sm'>
                                    </td>
                                    <td>
                                    <input  type='text' placeholder='Supplier Inv Date'  id='supplier_inv_date' class='form-control input-sm'>
                                    </td>
                                    <td><a onclick='add_tmp();' class='btn btn-default btn-sm'> <span class='fa fa-plus'></span> &nbsp; </a></td>
                                    </tr>";




    $sql1 = "SELECT * FROM payment_voucher_table_temp where uniq = '" . $_GET['uniq'] . "'";

  $fulltableAmount = 0;
    foreach ($conn->query($sql1) as $row2) {

        $ResponseXML .= "<tr><td>" . $row2['debitaccount'] . "</td><td>" . $row2['accountname'] . "</td><td></td><td>" . $row2['percentage_apply'] . "</td><td>" . $row2['amount'] . "</td><td>" . $row2['jobno'] . "</td><td></td><td>" . $row2['supplier_in_no'] . "</td><td>" . $row2['supplier_inv_date'] . "</td><td><a onclick='remove_tmp(" . $row2['id'] . ");' class='btn btn-default btn-sm'><span class=''></span> &nbsp; REMOVE</a></td></tr>";
        $fulltableAmount = $fulltableAmount + $row2['amount'];
    }

    $ResponseXML .= "</tbody></table>";
    $ResponseXML .= "</table>]]></sales_table>";
    
     $totAmount = $_GET['total_amount_of_payment'];

    //get Total
    $sql = "SELECT sum(amount) FROM payment_voucher_table_temp where uniq = '" . $_GET['uniq'] . "'";
    $result = $conn->query($sql);
    $row = $result->fetch();
    $fulltableAmount = $row[0];


    $ResponseXML .= "<totAmount><![CDATA[" . $totAmount . "]]></totAmount>";
    $ResponseXML .= "<alAmount><![CDATA[" . $fulltableAmount . "]]></alAmount>";
    $tbAmount = $totAmount - $fulltableAmount;
    $ResponseXML .= "<tbAmount><![CDATA[" . $tbAmount . "]]></tbAmount>";

    
    
    
    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}


if ($_GET["Command"] == "removerow") {
    header('Content-Type: text/xml');
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";

    $sql = "delete from payment_voucher_table_temp where id = '" . $_GET['id'] . "'";
    $result = $conn->query($sql);
    $ResponseXML .= "<sales_table><![CDATA[<table id='myTable' class='table table-bordered'>
                            <thead>
                                <tr>
                                    <th style='width: 10%;'>Debit Account</th>
                                     <th style='width: 10%;'>Account Name</th>
                                     <th style='width: 10%;'>Percentage Apply</th>
                                     <th style='width: 10%;'>Amount</th>
                                     <th style='width: 10%;'>Job No</th>
                                     <th style='width: 10%;'>Supplier In No.</th>
                                     <th style='width: 10%;'>Supplier Inv Date</th>
                                     <th style='width: 10%;'>Add/Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                      <td>
                                    <input type='text' placeholder='Debit Account' id='debitaccount' class='form-control input-sm'>
                                    </td>
                                    <td>
                                    <input type='text' placeholder='Account Name'  id='accountname' class='form-control input-sm'>
                                    </td>
                                    <td>
                                    <input  type='text' placeholder='Percentage Apply'  id='percentage_apply' class='form-control input-sm'>
                                    </td>
                                    <td>
                                    <input  type='text' placeholder='Amount'  id='amount' class='form-control input-sm'>
                                    </td>
                                    <td>
                                    <input type='text' placeholder='Job No' id='jobno' class='form-control input-sm'>
                                    </td>
                                    <td>
                                    <input type='text' placeholder='Supplier In No.'  id='supplier_in_no' class='form-control input-sm'>
                                    </td>
                                    <td>
                                    <input  type='text' placeholder='Supplier Inv Date'  id='supplier_inv_date' class='form-control input-sm'>
                                    </td>
                                        
                                        <td><a onclick='add_tmp();' class='btn btn-default btn-sm'> <span class='fa fa-plus'></span> &nbsp; </a></td>


                                </tr>";




    $sql1 = "SELECT * FROM payment_voucher_table_temp where uniq = '" . $_GET['uniq'] . "'";


    foreach ($conn->query($sql1) as $row2) {

        $ResponseXML .= "<tr><td>" . $row2['debitaccount'] . "</td><td>" . $row2['accountname'] . "</td><td>" . $row2['percentage_apply'] . "</td><td>" . $row2['amount'] . "</td><td>" . $row2['jobno'] . "</td><td>" . $row2['supplier_in_no'] . "</td><td>" . $row2['supplier_inv_date'] . "</td><td><a onclick='remove_tmp(" . $row2['id'] . ");' class='btn btn-default btn-sm'><span class=''></span> &nbsp; REMOVE</a></td></tr>";
    }

    $ResponseXML .= "</tbody></table>";
    $ResponseXML .= "</table>]]></sales_table>";
    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
    
}
