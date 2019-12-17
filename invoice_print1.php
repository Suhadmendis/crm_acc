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

$sql = "select * from inv_setup ";
foreach ($conn->query($sql) as $row) {
    $html .= "

.cl" . $row["id"] . " {
	font-size: " . $row["font_size"] . "px;
	left:" . $row["left_loc"] . "px;
	top:" . $row["top_loc"] . "px;
	font-family:" . $row["font_name"] . ";
	position:absolute;
}";
    
}



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


    $html .= "<body> 
        

            <table   style='width: 660px;' class='table1'>

            </table>

            <table   style='width: 660px;' class='cl2 table1'>
            <tr>
                <th style='width: 80px;'></th>
                <td style='width: 360px;'></td>
                <th style='width: 80px;'></th>   
                <td style='width: 80px;'>" . $row['REF_NO'] . "</td>   
            </tr>
            <tr>
                <th></th>
                <td></td>
                <th></th>   
                <td>" . $row['SDATE'] . "</td>   
            </tr>     
            <tr>
                <th></th>
                <td></td>
                <th></th>   
                <td></td>   
            </tr>  
            <tr>
                <th></th>
                <td></td>
                <th></th>   
                <td>" . $row['ORD_NO'] . "</td>   
            </tr> 
            <tr>
                <th></th>
                <td></td>
                <th></th>   
                <td></td>   
            </tr>
            <tr>
                <td colspan = '2'>TAX INVOICE</td> 
                <th></th>   
                <td>" . $row['cur'] . "</td>   
            </tr>
             
            <tr>
                <th></th>
                <td>" . $row['CUS_NAME'] . "</td>
                <th></th>   
                <td></td>   
            </tr>
            <tr>
                <th></th>
                <td>" . $row['C_ADD1'] . "</td>
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










    $html .= "<table style='width: 660px;'  class='table1'>
           <tr><th style='width: 80px;'></th>
               <th style='width: 360px;'></th>
               <th style='width: 80px;'></th>
               <th style='width: 80px;'></th>
               </tr>";

    $sql = "select * from s_invo where ref_no = '" . $row['REF_NO'] . "'";
    foreach ($conn->query($sql) as $row1) {
        $html .= "<tr>
        <td >" . number_format($row1['QTY'], 2, ".", ",") . "</td>
        <td>" . $row1['DESCRIPT'] . "</td>
        <td  align='right'>" . number_format($row1['PRICE'], 2, ".", ",") . "</td>  
        <td  align='right'>" . number_format(($row1['PRICE'] * $row1['QTY']), 2, ".", ",") . "</td>      
        </tr>";
    }

    $html .= "</table>";





    $html .= " <table  style='width: 660px;' class='cl1 table1'>
             
            <tr>
                <td style='width: 80px;'>&nbsp;</td>
                <td style='width: 360px;'>&nbsp;</td>
                <td style='width: 80px;'>&nbsp;</td>
                <td style='width: 80px;'>Sub Total</td>
                <td  align='right' style='width: 80px;'>" . number_format($row['AMOUNT'], 2, ".", ",") . "</td>
            </tr>";
    if ($row['BTT'] > 0) {
        $html .= "<tr>
                <td style='width: 80px;'>&nbsp;</td>
                <td style='width: 360px;'>&nbsp;</td>
                <td style='width: 80px;'>&nbsp;</td>
                <td style='width: 80px;'>NBT</td>
                <td  align='right' style='width: 80px;'>" . number_format($row['BTT'], 2, ".", ",") . "</td>
            </tr>";
    }
    if ($row['VAT'] > 0) {
        $html .= "<tr>
                <td style='width: 80px;'>&nbsp;</td>
                <td style='width: 360px;'>&nbsp;</td>
                <td style='width: 80px;'>&nbsp;</td>
                <td style='width: 80px;'>VAT</td>
                <td  align='right' style='width: 80px;'>" . number_format($row['VAT'], 2, ".", ",") . "</td>
            </tr>";
    }
    $html .= "<tr>
                <td style='width: 80px;'>&nbsp;</td>
                <td style='width: 360px;'>&nbsp;</td>
                <td style='width: 80px;'>&nbsp;</td>
                <td style='width: 80px;'>Total Amount</td>
                <td  align='right' style='width: 80px;'>" . number_format($row['GRAND_TOT'], 2, ".", ",") . "</td>
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
        $pdf_name = str_replace("/", "-", $row['C_REFNO']) . strtotime(date('Y-m-d'));



        $file_location = "pdfReports/" . $pdf_name . ".pdf";
        file_put_contents($file_location, $pdf);

        $sql = "insert into docs (loc,file_name,user_nm,folder,las_modifi,loc1,loc2,loc3,refno) values ('" . $file_location . "','" . $pdf_name . ".pdf" . "','','','" . date('Y-m-d') . "','','','','" . $row['C_REFNO'] . "')";
        $result = $conn->query($sql);
    }
    echo $html;
    