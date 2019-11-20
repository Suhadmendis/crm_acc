<?php

$html = "<!DOCTYPE html>
    <head>
        <meta charset='utf-8' />
        <title>Journal Entry</title>
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
$sql = "Select * from ledmas where tmp_no='" . $_GET['tmp_no'] . "'";
$result = $conn->query($sql);

if (!$row = $result->fetch()) {
    exit();
}

$sql = "Select * from COMPANY_INFO";
$result1 = $conn->query($sql);

if (!$row1 = $result1->fetch()) {
    exit();
}


$html .= "<body> 
        

        <table   style='width: 660px;' class='table1'>



              <tr>
                <th class='bottom head' align='left'><img src='images/ldogo.JPG'>&nbsp;</th>
                <th class='bottom head' align='left' colspan='3'>&nbsp;" . $row1['COMPANY'] . "</th> 
            </tr>
    
    
            <tr>               
               <th align='center' colspan='4'>" . $row1['ADD1'] . " " . $row1['ADD2'] . "</th> 
            </tr>


            <tr>
                  
                 <th class='bottom head' align='center' colspan='4'>Tel  :" . $row1['TELE'] . " Fax :" . $row1['FAX'] . "</th>
            </tr>

            <tr>
              <th colspan='4' ><center>Journal Entry</th>
               
            </tr>
            </table>
            <table   style='width: 660px;' class='table1'>

            <tr>
                <th align='left'>REF #</th>
                <th>:</th>
                <td>" . $row['REFNO'] . "</td>
                <th align='left'>CURRENCY</th>  
                <th>:</th>
                <td>" . $row['Currency'] . "/" . $row['rate'] . "</td>   
            </tr>

            <tr>
                <th align='left'>DATE</th>
                <th>:</th>
                <td>" . $row['BDATE'] . "</td>
                <th></th>   
                <td></td>   
            </tr>";



$html .= "<tr>
                <th align='left'>NARATION</th>
                <th>:</th>
                <td>" . $row['DETAILS'] . "</td>
            </tr>
            </table>
            <br>";










$html .= "<table style='width: 660px;'  border=1 class='table1'>
           <tr><th style='width: 80px;'>CODE</th>
               <th style='width: 180px;'>ACCOUNT</th>
               <th style='width: 80px;'>AMOUNT</th>
           </tr>";


$sql = "Select C_CODE,C_NAME,curamo,FLAG from view_jou where refno='" . $row["REFNO"] . "' order by FLAG";
$mflag = "";
$mtot =0;
foreach ($conn->query($sql) as $row1) {

if ($mflag != $row1['FLAG']) {    
if ($mflag != "") {
    $html .= "<tr><th colspan='2'></th><th align='right'>" . number_format($mtot, 2, ".", ",") . "</th></tr>";   
    $mtot =0;
}    
if ($row1['FLAG']=="DEB") {
$html .= "<tr><th colspan ='3'>DEBIT</th></tr>";
} else {
$html .= "<tr><th colspan ='3'>CREDIT</th></tr>";    
}    

}


$mflag = $row1['FLAG'];


$html .= "<tr>
        <td>" . $row1['C_CODE'] . "</td>
        <td>" . $row1['C_NAME'] . "</td>
        <td align='right'>" . number_format($row1['curamo'], 2, ".", ",") . "</td>    
        </tr>";
        $mtot = $mtot + $row1['curamo'];

}
$html .= "<tr><th colspan='2'></th><th align='right'>" . number_format($mtot, 2, ".", ",") . "</th></tr>";  
        

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




// include autoloader
require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

if ($_GET['action'] == "save") {
////

    $dompdf->loadHtml($html);


// Render the HTML as PDF
    $dompdf->render();

// Output the generated PDF to Browser
    $pdf = $dompdf->output();
    $pdf_name = str_replace("/", "-", $row['REFNO']) . strtotime(date('Y-m-d'));



    $file_location ="pdfReports/" . $pdf_name . ".pdf";
    file_put_contents($file_location, $pdf);

    $sql = "insert into docs (loc,file_name,user_nm,folder,las_modifi,loc1,loc2,loc3,refno) values ('" . $file_location . "','" . $pdf_name . ".pdf" . "','','','" . date('Y-m-d') . "','','','','" . $row['REFNO'] . "')";
    $result = $conn->query($sql);
}
echo $html;
