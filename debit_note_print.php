<?php

$html = "<!DOCTYPE html>
    <head>
        <meta charset='utf-8' />
        <title>Debit Note</title>
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


include './connection_sql.php';
$sql = "Select * from deb where tmp_no='" . $_GET['tmp_no'] . "'";
$result = $conn->query($sql);

if (!$row = $result->fetch()) {
    exit();
}

$sql = "Select * from COMPANY_INFO";
$result1 = $conn->query($sql);

if (!$row1 = $result1->fetch()) {
    exit();
}


$html .="<body> 
        

        <table   style='width: 660px;' class='table1'>



            <tr>
                <th class='bottom head'><img src='images/logo.JPG'></th>
                 <th class='bottom head' colspan='3'>" . $row1['COMPANY'] . "</th> 
            </tr>
    
            <tr>               
               <th align='center' colspan='4'>" . $row1['ADD1'] . " " . $row1['ADD2'] . "</th> 
            </tr>


            <tr>
                  
                 <th class='bottom head' align='center' colspan='4'>Tel  :" . $row1['TELE'] . " Fax :" . $row1['FAX'] . "</th>
            </tr>

            <tr>
              <th colspan='4' ><center>DEBIT NOTE</th>
               
            </tr>
            </table>
            <table   style='width: 660px;' class='table1'>

            <tr>
                <th>TO</th>
                <td>" . $row['C_CODE'] . "</td>
                <th>DBN No</th>   
                <td>" . $row['C_REFNO'] . "</td>   
            </tr>

            <tr>
                <td></td>
                <td>" . $row['c_name'] . "</td>
                <th>Date</th>   
                <td>" . $row['C_DATE'] . "</td>   
            </tr>";

$sql = "Select * from vendor where code='" . $row['C_CODE'] . "' ";
$sql1 = $conn->query($sql);
($row1 = $sql1->fetch());


$html .="<tr>
                <td></td>
                <td>" . $row1['ADD1'] . "</td>
            </tr></table><br>";










$html .="<table style='width: 660px;'  border=1 class='table1'>
           <tr><th style='width: 360px;'>Particulars</th>
               <th style='width: 80px;'>Ex. Rate</th>
               <th style='width: 80px;'>LKR</th></tr>";
$html .="<tr>
        <td >" . $row['C_REMARK'] . "</td>
        <td align='right'>" . $row['rate'] . "</td>
        <td  align='right'>" . number_format($row['C_PAYMENT'], 2, ".", ",") . "</td>    
        </tr>
        <td></td>
        <td></td>
        <td></td>    
        </tr>
        <tr>
        <td></td>
        <td></td>
        <td></td>    
        </tr>
        <tr>
        <td></td>
        <td></td>
        <td></td>    
        </tr>
        <tr>
        <td></td>
        <td></td>
        <td></td>    
        </tr>

</table><br><br><br><br><br><br><br>";


$html .= " <table style='width: 660px;' class='table1'>
             
            <tr>
                <td class='spn'>Prepared By</td>
                <td>&nbsp;</td>
                <td class='spn'>Authorized By</td>
                <td>&nbsp;</td>
                <td class='spn'>Received By</td>
            </tr>
        </table>";




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
$pdf_name = str_replace("/", "-", $row['C_REFNO']) . strtotime(date('Y-m-d'));



$file_location = "pdfReports/" . $pdf_name . ".pdf";
file_put_contents($file_location, $pdf);

$sql = "insert into docs (loc,file_name,user_nm,folder,las_modifi,loc1,loc2,loc3,refno) values ('" . $file_location . "','" . $pdf_name . ".pdf" . "','','','" . date('Y-m-d') . "','','','','" . $row['C_REFNO'] . "')";  
$result = $conn->query($sql);
}
echo $html;
