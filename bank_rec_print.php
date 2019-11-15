<?php

$html = "<!DOCTYPE html>
    <head>
        <meta charset='utf-8' />
        <title>Bank Reconciliation</title>
        <style>
            .spn {
                border-top: 1px solid !important; 

            }
            .box {
                border : 1px solid !important;
            }
        </style>
        <style>


            .bottom  {
                border-bottom: 1px solid #000000;
            }
            .table1 {
                border-collapse: collapse;
            }
            .table1, td, th {

                font-family: Arial, Helvetica, sans-serif;
                padding: 5px;
            }
            .table1 th {
                font-weight: bold;
                font-size: 12px;
            }
            .table1 td {
                font-size: 12px;
                border-bottom: none;
                border-top: none;
            }

            .head {
                font-size: 15px !important;
            }

            p {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
            }    
        </style>
    </head>";
require_once ("connection_sql.php");
date_default_timezone_set('Asia/Colombo');

$txtBankCode = $_GET['bank'];
$dtpDate = $_GET['dtto'];
$txtBankClosBal = $_GET['bank_bal'];



$sql = "Select * from COMPANY_INFO";
$result1 = $conn->query($sql);

if (!$row1 = $result1->fetch()) {
    exit();
}

$sql = "Select * from lcodes where c_code ='" . $txtBankCode . "'";
$result2 = $conn->query($sql);

if (!$row2 = $result2->fetch()) {
    exit();
} else {
    
    $OpCrAmu=0;
    $OpDbAmu=0;
    $bF =0;
    if ($_GET['cur'] != "LKR" ) {
         $sql_opCR = "select sum(curamo) as ctot from ledger where  l_flag1='CRE' and l_code='" . $txtBankCode . "' and Currency='" . $_GET['cur'] . "' and l_yearfl='0' and (l_date<='" . $dtpDate . "'  )"; 
    } else {
      $sql_opCR = "select sum(l_amount) as ctot from ledger where  l_flag1='CRE' and l_code='" . $txtBankCode . "' and (l_date<='" . $dtpDate . "'  ) and l_yearfl='0'";
    }
    $result = $conn->query($sql_opCR);
    if ($row_opCR = $result->fetch()) {
        $OpCrAmu = $OpCrAmu + $row_opCR["ctot"];
    }
    if ($_GET['cur'] != "LKR" ) {
    $sql_opDb = "select sum(curamo) as dtot from ledger where  l_flag1='DEB' and l_code='" . $txtBankCode . "' and Currency='" . $_GET['cur'] . "'  and (l_date<='" . $dtpDate . "' ) and l_yearfl='0' ";
    } else {
        $sql_opDb = "select sum(l_amount) as dtot from ledger where  l_flag1='DEB' and l_code='" . $txtBankCode . "'  and (l_date<='" . $dtpDate . "' ) and l_yearfl='0' "; 
    }    
    $result = $conn->query($sql_opDb);
    if ($row_opDb = $result->fetch()) {
        $OpDbAmu = $OpDbAmu + $row_opDb["dtot"];
    }
    $bF = $OpDbAmu - $OpCrAmu;
}



$html .= "<body> 
        

        <table   style='width: 660px;' class='table1'>



            <tr>
                 <th class='head' colspan='4'>" . $row1['COMPANY'] . "</th> 
            </tr>
            
            <tr>
                <th align='left'>Bank</th>
                <td colspan='3'>" . $txtBankCode . "   &nbsp;&nbsp;&nbsp; " . $row2['C_NAME'] . "</td>
                  
            </tr>
            <tr>
                <th align='left'>Bank Reconciliation As At</th>
                <td colspan='3'>" . $dtpDate . "</td>
                 
            </tr>
             <tr>
                <th align='left'>Balance As Per Bank Statment</th>
                <td colspan='3'>" . number_format($_GET['bank_bal'], "2", ".", ",") . "</td>
                 
            </tr>

        </table>";






$strsql = "select * from ledger where l_code='" . $txtBankCode . "' and Currency='" . $_GET['cur'] . "' and l_flag <> 'OPB' and  (l_flag2='0' or  tmprecdate >'" . $dtpDate . "') and (l_date<='" . $dtpDate . "' )  order by L_FLAG1 desc,l_date";

$mflag = "";
$mtot = 0;
$html .= "<table  style='width: 660px;' class='table1' border=1>";
foreach ($conn->query($strsql) as $row) {
    if ($mflag != $row['L_FLAG1']) {

        IF ($mflag != "") {

            $html .= "<tr><th colspan='4'></th><th  align='right'>" . number_format($mtot, "2", ".", ",") . "</th></tr>";
            $mtot = 0;
        }
        if ($row['L_FLAG1'] == "DEB") {
            $html .= "<tr><th  align='left' colspan='5'>Add: Un-Realized Deposits</th></tr>";

            $html .= "<tr><th>Ref. No </th>"
                    . "<th>Date</th>"
                    . "<th>Cheque No</th>"
                    . "<th>Description</th>"
                    . "<th>Amount</th></tr>";
        } else {
            $html .= "<tr><th   align='left' colspan='5'>Less: Un-Presented Cheques</th></tr>";
        }
    }
    $mflag = $row['L_FLAG1'];
    $html .= "<tr><td>" . $row['L_REFNO'] . "</td>
                  <td>" . $row['L_DATE'] . "</td>
                  <td>" . $row['chno'] . "</td>
                  <td>" . $row['L_LMEM'] . "</td>    
                  <td align='right'>" . number_format($row['curamo'], "2", ".", ",") . "</td></tr>";
    $mtot = $mtot + $row['curamo'];
}
$html .= "<tr><th colspan='4'></th><th  align='right'>" . number_format($mtot, "2", ".", ",") . "</th></tr>";
$html .= "</table>";


$less = "select sum(curamo) as tot from ledger where l_code='" . $txtBankCode . "'  and Currency='" . $_GET['cur'] . "'  and l_flag <> 'OPB' and   l_flag1='CRE'  and (l_flag2='0' or tmprecdate>'" . $dtpDate . "') and (l_date<='" . $dtpDate . "') ";


$add = "select sum(curamo) as tot from ledger where l_code='" . $txtBankCode . "' and Currency='" . $_GET['cur'] . "' and l_flag <> 'OPB' and  l_flag1='DEB' and (l_flag2='0'   or tmprecdate>'" . $dtpDate . "') and (l_date<='" . $dtpDate . "' )";


$result_less = $conn->query($less);
$row_less = $result_less->fetch();

if (!is_null($row_less['tot'])) {
    $less = $row_less['tot'];
}


$result_add = $conn->query($add);
$row_add = $result_add->fetch();

if (!is_null($row_add['tot'])) {
    $add = $row_add['tot'];
}


 

$nwbal = $txtBankClosBal + ($add - $less);

$html .= "<table   style='width: 660px;' class='table1'>



            <tr>
                <th align='left'>Amended balance as per bank Statement As at " . $dtpDate . "</th>
                <th colspan='3'>" . number_format($nwbal, "2", ".", ",") . "</th>
                  
            </tr>
            <tr>
                <th align='left'>Balance as per Cash As at " . $dtpDate . "</th>
                <th colspan='3'>" . number_format($bF, "2", ".", ",") . "</th>
                  
            </tr>
            <tr>
                <th align='left'>Diffrence as As " . $dtpDate . "</th>
                <th colspan='3'>" . number_format(($nwbal-$bF), "2", ".", ",")  . "</th>
                  
            </tr>
";





//m_Report.txtBankBal.SetText Format(txtBankClosBal, "###,###.00")
//$html .= 
//m_Report.txtbot.SetText "Balance as per Cash Book as at " & lastDayOfMonth(dtpDate.Month, dtpDate.year)

echo $html;


// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

if ($_GET['action']=="save") {
////

$dompdf->loadHtml($html);


// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$pdf = $dompdf->output();
$pdf_name = str_replace("/", "-", $txtBankCode) . strtotime(date('Y-m-d'));



$file_location = "pdfReports/" . $pdf_name . ".pdf";
file_put_contents($file_location, $pdf);

$sql = "insert into docs (loc,file_name,user_nm,folder,las_modifi,loc1,loc2,loc3,refno) values ('" . $file_location . "','" . $pdf_name . ".pdf" . "','','','" . date('Y-m-d') . "','','','','" . $txtBankCode . "')";  
$result = $conn->query($sql);
}
 