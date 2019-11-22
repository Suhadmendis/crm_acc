<?php

session_start();

include_once './connection_sql.php';

if ($_GET["Command"] == "pass_quot") {
    $_SESSION["custno"] = $_GET['custno'];

    header('Content-Type: text/xml');
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";

    $cuscode = $_GET["custno"];


    $sql = "Select * from payment_voucher where py_no ='" . $cuscode . "'";


    $sql = $conn->query($sql);
    if ($row = $sql->fetch()) {


        $ResponseXML .= "<id><![CDATA[" . $row['py_no'] . "]]></id>";
        $ResponseXML .= "<str_customername1><![CDATA[" . $row['manual_ref'] . "]]></str_customername1>";
        $ResponseXML .= "<str_customername2><![CDATA[" . $row['date'] . "]]></str_customername2>";
        $ResponseXML .= "<str_customername3><![CDATA[" . $row['payment_cash_book'] . "]]></str_customername3>";
        $ResponseXML .= "<str_customername4><![CDATA[" . $row['currency_code'] . "]]></str_customername4>";
        $ResponseXML .= "<str_customername5><![CDATA[" . $row['rate'] . "]]></str_customername5>";
        $ResponseXML .= "<str_customername6><![CDATA[" . $row['payment_type'] . "]]></str_customername6>";
        $ResponseXML .= "<str_customername7><![CDATA[" . $row['cheque_no_ref'] . "]]></str_customername7>";
        $ResponseXML .= "<str_customername8><![CDATA[" . $row['chq_date_ref'] . "]]></str_customername8>";
        $ResponseXML .= "<str_customername9><![CDATA[" . $row['chq_bank_ref'] . "]]></str_customername9>";
        $ResponseXML .= "<str_customername10><![CDATA[" . $row['total_amount_of_payment'] . "]]></str_customername10>";
        $ResponseXML .= "<str_customername11><![CDATA[" . $row['allocation_percentage'] . "]]></str_customername11>";
        $ResponseXML .= "<str_customername12><![CDATA[" . $row['allocated'] . "]]></str_customername12>";
        $ResponseXML .= "<str_customername13><![CDATA[" . $row['to_be_allocated'] . "]]></str_customername13>";
        $ResponseXML .= "<str_customername14><![CDATA[" . $row['payee_name'] . "]]></str_customername14>";
        $ResponseXML .= "<str_customername15><![CDATA[" . $row['remark'] . "]]></str_customername15>";
        $ResponseXML .= "<str_customername16><![CDATA[" . $row['c_ref'] . "]]></str_customername16>";
    }

    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}



if ($_GET["Command"] == "updateTable") {

    header('Content-Type: text/xml');
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    $ResponseXML = "";
    $ResponseXML .= "<new>";
    $rows = "";



    $sql = "SELECT * FROM payment_voucher_table WHERE paymentvoucherid = '" . $_GET['reference_no'] . "'";
    $rows .= "<br><table id='myTable' class='table table-bordered'>
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
     
    
    $sql1 = "SELECT * FROM payment_voucher_table WHERE paymentvoucherid = '" . $_GET['reference_no'] . "'";
    foreach ($conn->query($sql1) as $row2) {
  
        $rows .= "<tr><td>" . $row2['debitaccount'] . "</td><td>" . $row2['accountname'] . "</td><td>" . $row2['percentage_apply'] . "</td><td>" . $row2['amount'] . "</td><td>" . $row2['jobno'] . "</td><td>" . $row2['supplierinno'] . "</td><td>" . $row2['supplierinvdate'] . "</td><td><a onclick='remove_tmp(" . $row2['id'] . ");' class='btn btn-default btn-sm'><span class=''></span> &nbsp; REMOVE</a></td></tr>";    }

    $rows .= "   </table>";
    $ResponseXML .= "<rows><![CDATA[" . $rows . "]]></rows>";
  $ResponseXML .= "</new>";
    echo $ResponseXML;
}





if ($_GET["Command"] == "search_custom") {


    $ResponseXML = "";

    $ResponseXML .= "<table class=\"table table-bordered\">
                <tr>
                  <th>Pay No.</th>
                    <th>Manual Ref.</th>
                    <th>Date</th>
                </tr>";


    $sql = "Select * from payment_voucher where py_no<> ''";

    if ($_GET['cusno'] != "") {
        $sql .= " and py_no like '%" . $_GET['cusno'] . "%'";
    }
    if ($_GET['customername1'] != "") {
        $sql .= " and manual_ref like '%" . $_GET['customername1'] . "%'";
    }
    if ($_GET['customername2'] != "") {
        $sql .= " and date like '%" . $_GET['customername2'] . "%'";
    }

    $sql .= "  ORDER BY py_no limit 50 ";



    foreach ($conn->query($sql) as $row) {
        $cuscode = $row['py_no'];

        $stname = $_GET["stname"];

        $ResponseXML .= "<tr> 
                              <td onclick=\"custno('$cuscode', '$stname');\">" . $row['py_no'] . "</a></td>
                              <td onclick=\"custno('$cuscode', '$stname');\">" . $row['manual_ref'] . "</a></td>
                              <td onclick=\"custno('$cuscode', '$stname');\">" . $row['date'] . "</a></td>
                         </tr>";
    }

    $ResponseXML .= "   </table>";


    echo $ResponseXML;
}
?>
