<?php 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$table = "";

   $table .= "<!DOCTYPE html>
    <head>
    <meta charset='UTF-8'>";
    $table .= "<title>Ledger Detail Report</title>";
    




   $table .= "<style>
        @media print {
            a[href]:after {
                content: none !important;
            }
        }
        a:link, a:visited {

            text-decoration: none;
            color:#000000;
        }
        
        .table1 {
  border-collapse: collapse;
}
.table1, td, th {

 
  padding: 4px;
 
}
        
        
        
        
.table1 td {
  
  border-bottom: 1px solid;
  border-top: 1px solid;
  border-left: 1px solid;
  border-right: 1px solid;
}

.table1 th {
  font-weight: bold;
   
    
   border-bottom: 1px solid;
  border-top: 1px solid;
  border-left: 1px solid;
  border-right: 1px solid;
}

        a:hover {
            text-decoration: underline;
        }
        body {
            color: #333;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.42857;
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
</head>


    <body>";
       
        include('connection_sql.php');
        require_once './gl_posting.php';
        date_default_timezone_set('Asia/Colombo');

require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();



        $sql_rspara = "select * from COMPANY_INFO";
        $result = $conn->query($sql_rspara);
        $row_rspara = $result->fetch();
        
        
        if ($_GET['optionsRadios'] =="op_glreport" ) {

        $sql = "delete from ledprint ";
        $result = $conn->query($sql);
        $txtAccCode = $_GET["txt_gl_code"];

        $sql = "select * from lcodes where c_code = '" . $txtAccCode . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {
            $ac_type = $row['C_TYPE'];
        }


        $ayear = ac_year($_GET["dtfrom"]);
        $dtb = $ayear . "-04-01";


        $OpDbAmu = 0;
        $OpCrAmu = 0;
        $OpBalAm = 0;
        $OpDbAmu = 0;
        $OpLnkAmt = 0;
        if ($_GET['currency'] != "LKR") {
        $sql_opCR = "select sum(curamo)as ctot from ledger where  l_flag1='CRE' and l_code='" . $txtAccCode . "' and (l_date<'" . $_GET["dtfrom"] . "'  )";
        } else {
            $sql_opCR = "select sum(l_amount)as ctot from ledger where  l_flag1='CRE' and l_code='" . $txtAccCode . "' and (l_date<'" . $_GET["dtfrom"] . "'  )";
        }
        if ($ac_type != "B") {
            $sql_opCR .= " and acyear ='" . $ayear . "'";
        }
        if (($_GET['c_code'])!="") {
        $sql_opCR .= " and c_remarks ='" . $_GET['c_code'] . "'";
        }
          if (($_GET['currency'])!="LKR") {
        $sql_opCR .= " and currency ='" . $_GET['currency'] . "'";
        }



        $result = $conn->query($sql_opCR);
        if ($row_opCR = $result->fetch()) {
            $OpCrAmu = $OpCrAmu + $row_opCR["ctot"];
        }
        if ($_GET['currency'] != "LKR") {
        $sql_opDb = "select sum(curamo)as dtot from ledger where  l_flag1='DEB'  and l_code='" . $txtAccCode . "'  and (l_date<'" . $_GET["dtfrom"] . "' ) ";
        } else {
         $sql_opDb = "select sum(l_amount)as dtot from ledger where  l_flag1='DEB'  and l_code='" . $txtAccCode . "'  and (l_date<'" . $_GET["dtfrom"] . "' ) ";    
        }
        
        if ($ac_type != "B") {
            $sql_opDb .= " and acyear ='" . $ayear . "'";
        }
        if (($_GET['c_code'])!="") {
        $sql_opDb .= " and c_remarks ='" . $_GET['c_code'] . "'";
        }
         if (($_GET['currency'])!="LKR") {
        $sql_opDb .= " and currency ='" . $_GET['currency'] . "'";
        }

        $result = $conn->query($sql_opDb);
        if ($row_opDb = $result->fetch()) {
            $OpDbAmu = $OpDbAmu + $row_opDb["dtot"];
        }


        $bF = $OpBalAm + $OpDbAmu - $OpCrAmu + $OpLnkAmt;


        $txtdes = " Account :  " . $_GET["txt_gl_code"] . " - " . $_GET["txt_gl_name"] . "   " . "<br><b>Date From</b> - " . $_GET["dtfrom"] . " <b>To</b> " . $_GET["dtto"];
        ;
        
        $table .= "<h3>" . $row_rspara['COMPANY'] . "</h3>";
        $table .= "<h4>" . $txtdes . "</h4>";
if (($_GET['c_code'])!="") {
        $table .= "<h4>Customer " . $_GET['c_code']  . " " .  $_GET['c_name'] . "</h4>";
        }





       


        $DEB_amt = 0;
        $CRE_amt = 0;



        $table .= "<table  class='table table1'>
		
      	<tr>
        <th width=\"70\">Date</th>
        <th width=\"200\">Refno</th>
        <th width=\"200\">Cheque No</th>
        <th width=\"200\">Payee</th>
        <th width=\"400\">Naration</th>
        <th width=\"100\">Debit</th>
	<th width=\"100\">Credit</th>
	<th width=\"100\">Balance</th>
        </tr>";
        $debamt = 0;
        $creamt = 0;
        $baltot = 0;

        $totdebamt = 0;
        $totcreamt = 0;
        $refno = "";

        $mst = 0;

        $table .= "<tr><td>" . $_GET["dtfrom"] . "</td>
				<td>B/F</td>
        <td></td><td></td>
				<td>Opening Balance</td>";

        if ($bF > 0) {
            $table .= "<td align=right>" . number_format($bF, 2, ".", ",") . "</td>";
            $table .= "<td></td>";
            $totdebamt = $totdebamt + $bF;
        } else {
            $table .= "<td></td>";
            $table .= "<td align=right>" . number_format($bF, 2, ".", ",") . "</td>";
            $totcreamt = $totcreamt + $bF;
        }

        if ($bF > 0) {
            $table .= "<td align=right>" . number_format($bF, 2, ".", ",") . "</td>";
        } else {
            $table .= "<td align=right>(" . number_format((-1 * $bF), 2, ".", ",") . ")</td>";
        }


        $baltot = $baltot + $bF;

        $table .= "</tr>";


        $sql_rsPrInv = "select * from ledger where l_flag != 'OPB'  and acyear = '" . $ayear . "' and l_code = '" . $txtAccCode . "' and ( l_date >='" . $_GET["dtfrom"] . "') and  ( l_date <= '" . $_GET["dtto"] . "' ) ";

        //$sql_rsPrInv = "select *  from ledprint order by sdate, refno";
        
        if (($_GET['c_code'])!="") {
        $sql_rsPrInv .= " and c_remarks ='" . $_GET['c_code'] . "'";
        }
        if (($_GET['currency'])!="LKR") {
        $sql_rsPrInv .= " and currency ='" . $_GET['currency'] . "'";
        }
        
        $sql_rsPrInv .= " order by L_DATE,l_refno";
        
        foreach ($conn->query($sql_rsPrInv) as $row_rsPrInv) {
            
             if (($_GET['currency'])!="LKR") {
            if ($row_rsPrInv["L_FLAG1"] == "DEB") {
                $debamt = $row_rsPrInv["curamo"];
            } else {
                $debamt = 0;
            }

            if ($row_rsPrInv["L_FLAG1"] == "CRE") {
                $creamt = $row_rsPrInv["curamo"];
            } else {
                $creamt = 0;
            }
             } else {
                if ($row_rsPrInv["L_FLAG1"] == "DEB") {
                $debamt = $row_rsPrInv["L_AMOUNT"];
            } else {
                $debamt = 0;
            }

            if ($row_rsPrInv["L_FLAG1"] == "CRE") {
                $creamt = $row_rsPrInv["L_AMOUNT"];
            } else {
                $creamt = 0;
            }   
                 
                 
             }


            $bal = $debamt - $creamt;
            $baltot = $baltot + $bal;



            $table .= "<tr ><td>" . $row_rsPrInv["L_DATE"] . "</td>
				<td>" . $row_rsPrInv["L_REFNO"] . "</td>
        <td>" . $row_rsPrInv["chno"] . "</td>
        <td>" . 'Payee' . "</td>
				<td>" . $row_rsPrInv["L_LMEM"] . "</td>";

            $table .= "<td align=right>" . number_format($debamt, 2, ".", ",") . "</td>";

            $table .= "<td align=right>" . number_format($creamt, 2, ".", ",") . "</td>";

            if ($baltot > 0) {
                $table .= "<td align=right>" . number_format($baltot, 2, ".", ",") . "</td>";
            } else {
                $table .= "<td align=right>(" . number_format((-1 * $baltot), 2, ".", ",") . ")</td>";
            }
            $table .= "</tr>";


            $totdebamt = $totdebamt + $debamt;
            $totcreamt = $totcreamt + $creamt;
        }
        $table .= "<tr><td colspan=5>&nbsp;</td><td align=right><b>" . number_format($totdebamt, 2, ".", ",") . "</b></td><td align=right><b>" . number_format($totcreamt, 2, ".", ",") . "</b></td>";
        if ($baltot > 0) {
            $table .= "<td align=right><b>" . number_format($baltot, 2, ".", ",") . "</b></td></tr>";
        } else {
            $table .= "<td align=right><b>(" . number_format((-1 * $baltot), 2, ".", ",") . ")</b></td></tr>";
        }
        
        
         $table .= "</table>
            


</body>
</html>";
        
        
              if ($_GET['view1']=="view") {
            echo $table;
            } 
                
                if ($_GET['view1']=="pdf")  {
                    
                    
                        $dompdf->loadHtml($table);



// Render the HTML as PDF
    $dompdf->render();

// Output the generated PDF to Browser
    $pdf = $dompdf->output();
    $pdf_name = "GLREP" . "_" . (date('Ymd g i'));


    $file_location = "pdfReports/" . $pdf_name . ".pdf";
    file_put_contents($file_location, $pdf);
    
    
     $data = file_get_contents($file_location);
  header("Content-type: application/octet-stream");
  header("Content-disposition: attachment;filename=YOURFILE.pdf");

  echo $data;

    
    
    
                    
                }
                
                 if ($_GET['view1']=="excel")  {
                    
                    
                     
 
    
    	
				 $file = "rpt_glrep.xls";

        $f = fopen($file, "w+");

        fwrite($f, $table);

        
             $data = file_get_contents($file);
  header("Content-type: application/octet-stream");
  header("Content-disposition: attachment;filename=PNLS.xls");

		 echo $data;
    
                    
                }
        
        }
        
        
        
        
        if ($_GET['optionsRadios'] =="op_stamp" ) {
             $txtdes = " Stamp Duty Payable <br><b>Date From</b> - " . $_GET["dtfrom"] . " <b>To</b> " . $_GET["dtto"];
        ;
       
        $table .= "<h3>" . $row_rspara['COMPANY'] . "</h3>";
        $table .= "<h4>" . $txtdes . "</h4>";
if (($_GET['c_code'])!="") {
        $table .= "<h4>Customer " . $_GET['c_code']  . " " .  $_GET['c_name'] . "</h4>";
        }
        
        
        $sql = "select * from stampduty where l_date >='" . $_GET["dtfrom"] . "' and l_date<='" . $_GET["dtto"] ."' order by L_REFNO,l_date";
        $i = 1;
        $mstamp=0;
        
          $table .= "<table  class='table table1'>";
		
             $table .= "<th>No</th>";
             $table .= "<th>Bank</th>";
             $table .= "<th>Trans Date</th>";
             $table .= "<th>Trans Type</th>";
             $table .= "<th>Trans No</th>";
             $table .= "<th>Cheque Drawer</th>";
             $table .= "<th>Local Debit</th>";
             $table .= "<th>Stmp</th>";
             $table .= "</tr>";
        
        
        
         foreach ($conn->query($sql) as $row) {
             
             
             $table .= "<tr>";
             $table .= "<td>" .$i . "</td>";
             $table .= "<td>" . $row['C_NAME'] . "</td>";
             $table .= "<td>" .$row['L_DATE'] . "</td>";
             $table .= "<td>" . substr($row['L_REFNO'],0,3) . "</td>";
             $table .= "<td>" .$row['L_REFNO'] . "</td>";
              $cusname="";
             $sql = "select * from s_crec where ca_refno = '" . $row['L_REFNO'] . "'"; 
             $resultn = $conn->query($sql);
        if ($rown = $resultn->fetch()) {
            $cusname = $rown['CA_CODE'];
        }
        if ($cusname=="") {
             $sql = "select * from recmas where refno = '" . $row['L_REFNO'] . "'"; 
             $resultn = $conn->query($sql);
        if ($rown = $resultn->fetch()) {
            $cusname = $rown['BARER'];
        }
        }
             
             
             $table .= "<td>" . $cusname ."</td>";
             $table .= "<td class='right'>" . number_format($row['L_AMOUNT'], 2, ".", ",") . "</td>";
             $stamp=0;
             
             if ($row['L_AMOUNT']>=25000) {
             $stamp = 25;
             }
             $mstamp = $mstamp + $stamp;
             $table .= "<td class='right'>" . number_format($stamp, 2, ".", ",") . "</td>";
             $table .= "</tr>";
             $i = $i+1;
         } 
         $table .= "<tr>";
             $table .= "<td></td>";
             $table .= "<td></td>";
             $table .= "<td></td>";
             $table .= "<td></td>";
             $table .= "<td></td>";
             $table .= "<td></td>";
             $table .= "<td class='right'></td>";
              
             $table .= "<td class='right'>" . number_format($mstamp, 2, ".", ",") ."</td>";
             $table .= "</tr>";
        
        echo $table;
        }
        
        
        ?>  



     