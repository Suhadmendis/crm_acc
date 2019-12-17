<?php

date_default_timezone_set('Asia/Colombo');

$html = "<!DOCTYPE html>
    <head>
        <meta charset='utf-8' />
        <title>Payment Entry</title>
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
        #footer { position: absolute; bottom: 0; }
        </style>
    </head>";


include './connection_sql.php';
$sql = "Select * from paymas where tmp_no='" . $_GET['tmp_no'] . "'";
$result = $conn->query($sql);

if (!$row = $result->fetch()) {
    exit();
}

$sql = "Select * from COMPANY_INFO";
$result1 = $conn->query($sql);

if (!$row1 = $result1->fetch()) {
    exit();
}

$add = $row1['ADD1'] . " " . $row1['ADD2'] . " | T  :" . $row1['TELE'] . " | F :" . $row1['FAX'];


$sql = "Select * from view_ledger where l_refno='" . $row['REFNO'] . "' and l_flag1='CRE'";
$resultb = $conn->query($sql);

if ($rowb = $resultb->fetch()) {
    //exit();
}

$sql = "Select * from vendor where code='" . $row['VEN_CODE'] . "' ";
$sql1v = $conn->query($sql);
($rowV = $sql1v->fetch());


$html .= "<body> 
        

        <table   style='width: 660px;' class='table1'>



            <tr>
                <th class='bottom head' align='left'><img src='images/logo.JPG'>&nbsp;</th>
                <th class='bottom head' align='left' colspan='3'>&nbsp;" . $row1['COMPANY'] . "</th> 
            </tr>
    
            


            <tr>
                  
                 <th class='bottom head' align='center' colspan='4'>PAYMENT VOUCHER</th>
            </tr>

            <tr>
              <th colspan='4' ><center></th>
            </tr>
            </table>
            <table   style='width: 660px;' class='table1'>

            <tr>
                <th class='left'>TO</th>
                <th class='left'>:</th>
                <th class='left'>" . wordwrap($row['Barer'],40,"<br />\n") . "</th>
                <th class='left'>Voucher No</th> 
                <th class='left'>:</th>
                <td>" . $row['REFNO'] . "</td>   
            </tr>

            <tr>
                <td></td>
                <th class='left'></th>
                <td>" . $rowV['ADD1'] . "</td>
                <th class='left'>Date</th>   
                <th class='left'>:</th>
                <td>" . $row['BDATE'] . "</td>   
            </tr>
            <tr>
                <th class='left'>Bank</th>
                <th class='left'>:</th>
                <td>" . $rowb['C_NAME'] . "</td>
                <th class='left'>Cheque No</th>  
                <th class='left'>:</th>
                <td>" . $row['CHENO'] . "</td>   
            </tr>";



$html .= "<tr>
                <td></td>
                <td></td>
            </tr></table><br>";










$html .= "<table style='width: 660px;'  border=1 class='table1'>
            
           <tr><th  style='width: 100px;'>Code</th><th style='width: 360px;'>Particulars</th>
               <th style='width: 80px;'>Payment</th><th></th>
               <th style='width: 80px;'>Local Equiv</th><th></th></tr>";


$mtot = 0;
$mtot1 = 0;
$sql = "select * from ledger where l_refno = '" . $row['REFNO'] . "' and l_flag1 = 'DEB'";
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








$html .= "</table><br><br><br>";


$html .= " <table style='width: 660px;' class='table1'>
             
            <tr>
                <td style='width: 120px;' >Prepared By</td>
                <td  style='width: 120px;'>&nbsp;</td>
                <td>&nbsp;</td>
                <td  style='width: 150px;'>Authorization Required</td>
                <td  style='width: 150px;'>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td class='spn'>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class='spn'>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
           
            
            <tr>
                <td style='width: 120px;' >First Authorization</td>
                <td  style='width: 120px;'>&nbsp;</td>
                <td>&nbsp;</td>
                <td  style='width: 150px;'>Received By</td>
                <td  style='width: 120px;'>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td class='spn'>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class='spn'>&nbsp;</td>
            </tr>
             
        </table>  <footer id='footer'>
            <table style='width: 660px;'  class='table1'>
            <tr>
                <td colspan='4' class='center'>" . $add . "</td>
            </tr>
            </table>
            </footer>";



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


?>


<script>
window.print();
</script>
