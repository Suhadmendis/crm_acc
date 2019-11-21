<?php

include './connection_sql.php';

$html = "<!DOCTYPE html>
    <head>
        <meta charset='utf-8' />
        <title>Invoice</title>
        <style>
            .spn {
                border-top: 1px solid !important; 

            }
            .box {
                border : 1px solid !important;
            }
        </style><style>";




$html .= ".bottom  {
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
            .left {
                text-align:left;
            }
             .center {
            text-align: center;
        }
        #footer { position: absolute; bottom: 0; }
        </style>
    </head>";



$sql = "Select * from s_salma where tmp_no='" . $_GET['tmp_no'] . "'";
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

$html .= "<body> 
        

            <table   style='width: 660px;' class='table1'>
            <tr>
                <th  colspan='3'></th>
                <th align='right' class='head'><img src='images/logo.JPG'></th> 
            </tr>
             

            <tr>
              <th colspan='4' class='left' ><h2>INVOICE</h2></th>
               
            </tr></table>




            <table   style='width: 660px;' class='table1'>
             <tr>
                <th  class='left' >Customer</th>
                <th>:</th>
                <td>" . $row['C_CODE'] . "</td>
                <th>Date</th>
                <th>:</th>
                <td>" . $row['SDATE'] . "</td>   
            </tr>

            <tr>
                <td></td>
                <th></th>
                <td>" . $row['CUS_NAME'] . "</td>
                <th>Inv No</th>
                <th>:</th>
                <td>" . $row['REF_NO'] . "</td>   
            </tr> 
            
            <tr>
                <th></th>
                <th></th>
                <td>" . $row['C_ADD1'] . "</td>
                <th></th>  
                <th></th>
                <td></td>   
            </tr>";

$sql = "Select * from masterclient where Clid='" . $row['C_CODE'] . "' ";
$sql1 = $conn->query($sql);
($row1 = $sql1->fetch());


$html .= "<tr>
                <td></td>
                <td>" . $row1['ClAddress'] . "</td>
            </tr></table><br>";










$html .= "<table style='width: 660px;' border='1' class='table1'>
           <tr>
               <th style='width: 360px;'>Description</th>
               <th style='width: 80px;'>Qty</th>
               <th style='width: 80px;'>Unit Price</th>
               <th style='width: 80px;'>Amount</th>
               </tr>";

$sql = "select * from s_invo where ref_no = '" . $row['REF_NO'] . "'";
foreach ($conn->query($sql) as $row1) {
    $html .= "<tr>
        
        <td>" . $row1['DESCRIPT'] . "</td>
        <td  align='right' >" . number_format($row1['QTY'], 2, ".", ",") . "</td>
        <td  align='right'>" . number_format($row1['PRICE'], 2, ".", ",") . "</td>  
        <td  align='right'>" . number_format(($row1['PRICE'] * $row1['QTY']), 2, ".", ",") . "</td>      
        </tr>";
}

$html .= "</table>";





$html .= " <table  style='width: 660px;' class='cl1 table1'>
             
            <tr>
                <td style='width: 80px;'>&nbsp;</td>
                <td style='width: 360px;'>&nbsp;</td>
                <th style='width: 90px;'>Total Amount</th>
                <th style='width: 80px;'>" . $row['cur'] . "</th>
                <th  align='right' style='width: 80px;'>" . number_format($row['curamo'], 2, ".", ",") . "</th>
            </tr>
        </table><br><br><br>";



$html .= " <table style='width: 660px;' class='table1'>
             
            <tr>
                <td style='width: 180px;'>Name of Beneficiary </td>
                <td>TDL HOLDINGS (PVT) LIMITED</td>
                <td></td>
                <td>&nbsp;</td>
                <td></td>
            </tr>
            <tr>
                <td style='width: 180px;'>Address</td>
                <td>No 04, Adam's Avenue, Colombo 04</td>
                <td></td>
                <td>&nbsp;</td>
                <td></td>
            </tr>
            <tr>
                <td style='width: 180px;'>Beneficiary Bank</td>
                <td>SAMPATH BANK PLC</td>
                <td></td>
                <td>&nbsp;</td>
                <td></td>
            </tr>
            <tr>
                <td style='width: 180px;'></td>
                <td>NO.110, SIR JAMES PEIRIS MAWATHA, COLOMBO 02, SRI LANKA</td>
                <td></td>
                <td>&nbsp;</td>
                <td></td>
            </tr>            
            <tr>
                <td style='width: 180px;'>Bank account number</td>
                <td>5029 3000 8708</td>
                <td></td>
                <td>&nbsp;</td>
                <td></td>
            </tr>   
            <tr>
                <td style='width: 180px;'>Swift code</td>
                <td>BSAMLKLX</td>
                <td></td>
                <td>&nbsp;</td>
                <td></td>
            </tr>   

        </table><br><br><br><br>";

$html .=   "<table style='width: 660px;' class='table1'> 
            <tr>
                <td  style='width: 180px;' class='spn'>Authorized Signatory</td>
                <td>&nbsp;</td>
                <td></td>
                <td>&nbsp;</td>
                <td></td>
            </tr>
            
            <tr>
                <td></td>
                <td>&nbsp;</td>
                <td></td>
                <td>&nbsp;</td>
                <td></td>
            </tr>
            </table>
            
            <footer id='footer'>
            <table style='width: 660px;'  class='table1'>
            <tr>
                <td colspan='4' class='center'>" . $add . "</td>
            </tr>            
            </table>
            </footer>";

// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

if ($_GET['action'] == "save") {

    $dompdf->loadHtml($html);

// Render the HTML as PDF
    $dompdf->render();

// Output the generated PDF to Browser
    $pdf = $dompdf->output();
    $pdf_name = str_replace("/", "-", $row['REF_NO']) . strtotime(date('Y-m-d'));



    $file_location = "pdfReports/" . $pdf_name . ".pdf";
    file_put_contents($file_location, $pdf);

    $sql = "insert into docs (loc,file_name,user_nm,folder,las_modifi,loc1,loc2,loc3,refno) values ('" . $file_location . "','" . $pdf_name . ".pdf" . "','','','" . date('Y-m-d') . "','','','','" . $row['REF_NO'] . "')";
    $result = $conn->query($sql);
    
}

echo $html;



?>


<script>
window.print();
</script>