<?php

$html = "<!DOCTYPE html>
    <head>
        <meta charset='utf-8' />
        <title>Print Purchase</title>
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
$sql = "Select * from s_purmas where tmp_no='" . $_GET['tmp_no'] . "'";
$result = $conn->query($sql);

if (!$row = $result->fetch()) {
    exit();
}

$sql = "Select * from s_ordmas where refno='" . $row['ORDNO'] . "'";
$result_ord = $conn->query($sql);

if (!$row_ord = $result_ord->fetch()) {
    
}


$sql = "Select * from COMPANY_INFO";
$result1 = $conn->query($sql);

if (!$row1 = $result1->fetch()) {
    exit();
}


$html .="<body> 
        

        <table   style='width: 660px;' class='table1'>



            <tr>
                <th class='bottom head' rowspan='2'><img src='images/logo.JPG'></th>
                
            </tr>


             <tr>
                <th colspan='3' class='bottom head'><center>PURCHASE RECEIVED</th>
             </tr>

            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>

            </tr></table>


           
 
            <table style='width: 660px;' class='table1'>
             <tr>
                <td>PO #</td>
                <td>" . $row['ORDNO'] . "</td>
                <td>PO Date</td> 
                <td>" . $row_ord['SDATE'] . "</td>        
            </tr>
            <tr>
                <td>PR #</td>
                <td>" . $row['REFNO'] . "</td>
                <td>Goods Received</td> 
                <td>". $row['SDATE'] . "</td>
            </tr>
            <tr>
                <td>Invoice #</td>
                <td>" . $row['LCNO'] . "</td>
                <td></td> 
                <td></td>
            </tr>            
            <tr>
                <td>&nbsp;</td>
                <td colspan='2'>&nbsp;</td>
            </tr>            

            <tr>
                <td>Supplier Name</td>
                <td colspan='2'>" . $row['SUP_NAME'] . "</td>
            </tr>
        </table>
        <br/><br/>
        <table border=1 style='width: 660px;' class='table1'>
            <tr>
                <th style='width: 50px;'>No</th>
                <th style='width: 360px;'>Description</th>
                <th style='width: 80px;'>Qty</th>
                <th style='width: 80px;'>Per Unit</th>
                <th style='width: 80px;'>Sub Total</th>
            </tr>";


$i = 1;
$mnet = 0;
$sql = "Select * from s_purtrn where REFNO='" . $row["REFNO"] . "'";
foreach ($conn->query($sql) as $row1) {
    $subtotal = $row1['REC_QTY'] * $row1['COST'];
    $mnet = $mnet + $subtotal;

    $html .="<tr>                              
                    <td>" . $i . "</td>
                    <td>" . $row1['DESCRIPT'] . "</td>
                    <td align='right'>" . number_format($row1['REC_QTY'], 2, ".", ",") . "</td>
                    <td align='right'>" . number_format($row1['COST'], 2, ".", ",") . "</td>
                    <td align='right'>" . number_format($subtotal, 2, ".", ",") . "</td>

                </tr>";

    $i = $i + 1;
}


$html .="</table>
        <table style='width: 660px;'   class='table1'>
            <tr>";


$html .= "<th style='width: 80px;'></th>

                <th style='width: 80px;'>Sub Total</th>                        
                <th style='width: 80px;'>" . number_format($mnet, 2, ".", ",") . "</th>
            </tr>
        </table>

        <br/><br/>
         
        <br/><br/>
        <table style='width: 660px;' class='table1'>
             
            <tr>
                <td class='spn'>Prepared By</td>
                <td>&nbsp;</td>
                <td class='spn'>Authorized By</td>
                <td>&nbsp;</td>
                <td class='spn'>Received By</td>
            </tr>
        </table>
        <br/><br/>
        
        
        
    </body>
</html>";


////
// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

if ($_GET['action'] =="save") {
// instantiate and use the dompdf class
$dompdf = new Dompdf();



$dompdf->loadHtml($html);


// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$pdf = $dompdf->output();
$pdf_name ="drre";

$file_location = "pdfReports/".$pdf_name.".pdf";
file_put_contents($file_location,$pdf);


}

echo $html;

?>