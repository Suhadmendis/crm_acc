<?php

date_default_timezone_set('Asia/Colombo');

$html = "<!DOCTYPE html>
    <head>
        <meta charset='utf-8' />
        <title>Receipt Entry</title>
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
             .right {
            text-align: right;
        }  
        .left {
            text-align: left;
        }
        .center {
            text-align: center;
        }
        </style>
    </head>";


include './connection_sql.php';
$sql = "Select * from s_crec where tmp_no='" . $_GET['tmp_no'] . "'";
$result = $conn->query($sql);

if (!$row = $result->fetch()) {
    exit();
}

$sql = "Select * from COMPANY_INFO";
$result1 = $conn->query($sql);

if (!$row1 = $result1->fetch()) {
    exit();
}
$sql = "Select * from view_ledger where l_refno='" . $row['CA_REFNO'] . "' and l_flag1='DEB'";
$resultb = $conn->query($sql);

if ($rowb = $resultb->fetch()) {
    //exit();
}


$html .= "<body> 
        

        <table   style='width: 660px;' class='table1'>



               <tr>
                <th class='bottom head' align='left'><img src='images/logo.JPG'>&nbsp;</th>
                <th class='bottom head' align='left' colspan='3'>&nbsp;" . $row1['COMPANY'] . "</th> 
            </tr>
    
    
            <tr>               
               <th align='center' colspan='4'>" . $row1['ADD1'] . " " . $row1['ADD2'] . "</th> 
            </tr>


            <tr>
                  
                 <th class='bottom head' align='center' colspan='4'>Tel  :" . $row1['TELE'] . " Fax :" . $row1['FAX'] . "</th>
            </tr>

            <tr>
              <th colspan='4' ><center>RECEIPT</th>
               
            </tr>
            </table>
            <table   style='width: 660px;' class='table1'>";

$sql = "Select * from vendor where code='" . $row['CA_CODE'] . "' ";
$sql1 = $conn->query($sql);
($row1 = $sql1->fetch());

            $html .= "<tr>
                <th>From</th>
                <th>:</th>
                <td>" . $row1['NAME'] . "</td>
                <th>Receipt No</th>
                <th>:</th>
                <td>" . $row['CA_REFNO'] . "</td>   
            </tr>";

            
$html .= "<tr>
                <td></td>
                <th></th>
              
                 
                <td
                >" . $row1['ADD1'] . "</td>
                 <th></th>
                  <th></th>
                   <th></th>
            </tr>";
            
            $html .= "<tr>
                <th>Bank</th>
                <td>:</td>
                <td>" . $rowb['C_NAME'] . "</td> 
                <th>Date</th>
                <th>:</th>
                <td>" . $row['CA_DATE'] . "</td>   
            </tr>
            <tr>
                <th></th>
                <th></th>
                <td></td>
                <th></th>
                <th></th> 
                <td></td>   
            </tr></table><br>";













$html .= "<table style='width: 660px;'  border=1 class='table1'>
            
           <tr><th  style='width: 100px;'>Code</th><th style='width: 360px;'>Particulars</th>
               <th style='width: 80px;'>Payment</th><th></th>
               <th style='width: 80px;'>Local Equiv</th><th></th></tr>";


$mtot = 0;
$mtot1 = 0;
$sql = "select * from ledger where l_refno = '" . $row['CA_REFNO'] . "' and l_flag1 = 'CRE'";
foreach ($conn->query($sql) as $row1) {
    $html .= "<tr>
        <td>" . $row1['L_CODE'] . "</td>
        <td>" . $row1['L_LMEM'] . "</td>
        <td class='right'>" . number_format($row1['curamo'], 2, ".", ",") . "</td>
        <td class='right'>" . $row1['Currency'] . "</td>
        <td class='right'>" . number_format($row1['L_AMOUNT'], 2, ".", ",") . "</td>
        <td class='right'>LKR</td>
        </tr>";
    $mtot = $mtot + $row1['curamo'];
    $mtot1 = $mtot1 + $row1['L_AMOUNT'];
    
}


$html .= "<tr><th  colspan='2'></th><th  class='right'>" . number_format($mtot, 2, ".", ",") . "</th><th></th><th  class='right'>" . number_format($mtot, 2, ".", ",") . "</th><th></th>";








$html .= "</table><br><br><br><br><br><br><br>";


$html .= " <table style='width: 660px;' class='table1'>
             
            <tr>
                <td class='spn'>Prepared By</td>
                <td>&nbsp;</td>
                <td class='spn'>Authorized By</td>
                <td>&nbsp;</td>
                <td class='spn'>Received By</td>
            </tr>
        </table>";


require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

echo $html;


if ($_GET['action'] == "save") {
////
// include autoloader

    $dompdf->loadHtml($html);



// Render the HTML as PDF
    $dompdf->render();

// Output the generated PDF to Browser
    $pdf = $dompdf->output();
    $pdf_name = str_replace("/", "-", intval(substr($row['REFNO'], 5, 8))) . "_" . (date('Ymd g i'));


    $file_location = "pdfReports/" . $pdf_name . ".pdf";
    file_put_contents($file_location, $pdf);

    $sql = "insert into docs (loc,file_name,user_nm,folder,las_modifi,loc1,loc2,loc3,refno) values ('" . $file_location . "','" . $pdf_name . ".pdf" . "','','','" . date('Y-m-d') . "','','','','" . $row['REFNO'] . "')";
    $result = $conn->query($sql);
}
