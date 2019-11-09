<?php

$html = "<!DOCTYPE html>
    <head>
        <meta charset='utf-8' />
        <title>Print Order</title>
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
$sql = "Select * from s_ordmas where tmp_no='" . $_GET['tmp_no'] . "'";
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
                <th class='bottom head' rowspan='2'><img src='images/logo.JPG'></th>
                
            </tr>


             <tr>
                <th colspan='3' class='bottom head'><center>PURCHASE ORDER</th>
             </tr>

            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>

            </tr>


            <tr>
                <td style='width: 120px;'>Company</td>
                <td colspan='3'>" . $row1['COMPANY'] . "</td> 
            </tr>

            <tr>
                <td>Delivery Address</td>
                <td colspan='3'>" . $row1['ADD1'] . " " . $row1['ADD2'] . "</td> 
            </tr>


            <tr>
                <td>PO #</td>
                <td>" . $row['REFNO'] . "</td>

            </tr>

            <tr>
                <td>Contact Person</td>
                <td>" . $row['Attn'] . "</td>

            </tr>

            <tr>
                <td>Contact</td>   
                <td>Tel  :" . $row1['TELE'] . "</td>";

if ($row['Vat'] > 0) {

    $html.="<td>VAT #</td>
                    <td>" . $row1['VAT_NO'] . "</td>";
} else {

    $html .="<th></th>
                    <th></th>";
}


$html .= "</tr>
            <tr>
                <td></td>   
                <td>Fax :" . $row1['FAX'] . "</td>";

if ($row['Vat'] > 0) {

    $html .="<td>S-VAT #</td>
                    <td>" . $row1['SVAT_NO'] . "</td>";
} else {

    $html .="<th></th>
                    <th></th>";
}

$html .="</tr>
            <tr>
                <td>Email</td>
                <td>IsuruA@wingslogisitcs.lk</td>

            </tr>

            <tr>
                <td>Supplier Name</td>
                <td colspan='3'>" . $row['SUP_NAME'] . "</td>
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
$sql = "Select * from s_ordtrn where REFNO='" . $row["REFNO"] . "'";
foreach ($conn->query($sql) as $row1) {
    $subtotal = $row1['ORD_QTY'] * $row1['RATE'];
    $mnet = $mnet + $subtotal;

    $html .="<tr>                              
                    <td>" . $i . "</td>
                    <td>" . $row1['DESCRIPT'] . "</td>
                    <td align='right'>" . number_format($row1['ORD_QTY'], 2, ".", ",") . "</td>
                    <td align='right'>" . number_format($row1['RATE'], 2, ".", ",") . "</td>
                    <td align='right'>" . number_format($subtotal, 2, ".", ",") . "</td>

                </tr>";

    $i = $i + 1;
}


$html .="</table>
        <table style='width: 660px;'   class='table1'>
            <tr>";

if ($row['Vat'] > 0) {

    $html .="<th  style='width: 50px;'>SVAT</th>
                    <th  align='left' style='width: 360px;'>" . number_format($row['Vat'], 2, ".", ",") . "</th>";
} else {

    $html .="<th  style='width: 50px;'></th>
                    <th  align='left' style='width: 360px;'></th>";
}

$html .= "<th style='width: 80px;'></th>

                <th style='width: 80px;'>Sub Total</th>                        
                <th style='width: 80px;'>" . number_format($mnet, 2, ".", ",") . "</th>
            </tr>
        </table>

        <br/><br/>
        <table style='width: 660px;' class='table1'>
            <tr>
                <td>MR.ISURU</td>
            </tr>
            <tr>
                <td class='spn'>Authorized Signature</td>
                <td align='right'>Date of Purchase Order</td>
                <td align='center' class='box'>" . $row['SDATE'] . "</td>
            </tr>
        </table>
        <br/><br/>
        
        <p>
            Goods Should be deliverd within 14 working days from the date of purchase order <br/>
            Late delivery of goods will subject to a deduction of 5% from invoice value<br/>
            Office Use Only<br/>
        </p>
        
        <br/><br/>
        
        
        <table style='width: 660px;' class='table1'>
            <tr>
                <td  style='width: 10px;'>1.</td>
                <td  style='width: 200px;'>Goods Received On</td>
                <td class='box'></td>
            </tr>
            <tr>
                <td>2.</td>
                <td>Supplier Invoice No</td>
                <td  class='box'></td>
            </tr>
            <tr>
                <td>3.</td>
                <td>Remarks If Any</td>
                <td class='box'></td>
            </tr>



        </table>
    </body>
</html>";



// include autoloader
require_once 'dompdf/autoload.inc.php';
// reference the Dompdf namespace
use Dompdf\Dompdf;

if ($_GET['action']=="save") {


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
