 <input class="button button2" type="button" onclick="tableToExcel('testTable', 'W3C Example Table')" value="Export to Excel">
 <div class="form-group"><br></div>


<?php session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$table = "";

$table .= "<!DOCTYPE html>
<head>
    <meta charset='UTF-8'>";

    if ($_GET['optionsRadios'] == "op_bs") {
        $table .= "<title>BS</title>";
    } else {
        $table .= "<title>PNL</title>";
    }




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

<body> ";

    require_once ("./connection_sql.php");
    require_once './gl_posting.php';


require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

    $sql_rspara = "select * from COMPANY_INFO";
    $result = $conn->query($sql_rspara);
    $row_rspara = $result->fetch();


    if ($_GET['view'] == "view") {

        if ($_GET['optionsRadios'] == "op_pnl") {

            $txtdes = "Profit & Loss Statmenet For the Period : " . date("Y-m-d", strtotime($_GET["dtfrom"])) . "    To: " . date("Y-m-d", strtotime($_GET["dtto"]));



            // $table .= "<h3>" . $row_rspara['COMPANY'] . "</h3>";
            // $table .= "<h4>" . $txtdes . "</h4>";
            
            $heading3 = $row_rspara['COMPANY'] ;
            $heading4 = $txtdes;


            
            $table .= "<table id='testTable' class='table table-bordered table1' style='width: 800px;'>
            <tr >
            <th colspan='3' class='center'  style='width: 90px;'>$heading3</th>
        
            </tr>
            <tr>
            <th colspan='3' class='center'  style='width: 90px;'>$heading4</th>
        
            </tr>







      	<tr>
        <th class='center'  style='width: 90px;'>Ledger</th>
        <th class='center'  style='width: 400px;'>Account Description</th>
	<th class='center' style='width: 90px;'>Closing Balance</th>
        </tr>";


            $bal = 0;
            $mcl_bal = 0;

            $ayear = ac_year($_GET["dtfrom"]);
            $dtb = $ayear . "-04-01";


            $sql = "update ledger set l_amount1 = l_amount where l_flag1 = 'DEB'";
            $result = $conn->query($sql);

            $sql = "update ledger set l_amount1 = l_amount *-1 where l_flag1 = 'CRE'";
            $result = $conn->query($sql);





            $sql_rsPrInv = "select C_TYPE,l_code,C_NAME,sum(L_AMOUNT1)as amo, C_SUBGRO1,C_SUBGRO2   from view_ledger  where c_type ='P' and (l_date>='" . $_GET["dtfrom"] . "' and l_date<='" . $_GET['dtto'] . "')  group by C_TYPE,l_code,C_NAME,C_SUBGRO1,C_SUBGRO2 order by  C_SUBGRO1 desc,C_SUBGRO2,l_code ";
            $bF = 0;
            $cgroup = "";
            $cgroup1 = "";
            $camo = 0;
            $camo1 = 0;
            foreach ($conn->query($sql_rsPrInv) as $row_rsPrInv) {

                //if (trim($row_rsPrInv['C_SUBGRO1']) == "Revenue") {
                $pen = ($row_rsPrInv['amo']);
                //}
                if (trim($row_rsPrInv['C_SUBGRO1']) == "Expenses") {
                    // $pen = abs($row_rsPrInv['amo']);
                    // $pen = $pen * -1;
                }

                if ($pen < 0) {
                    $pen = abs($row_rsPrInv['amo']);
                } else {
                    $pen = ($row_rsPrInv['amo'] * -1);
                }


                if ($cgroup != $row_rsPrInv['C_SUBGRO1']) {


                    if ($cgroup1 != "") {
                        if ($camo1 >= 0) {
                            $table .= "<tr><th colspan='2'></th><th class='right'>" . number_format($camo1, 2, ".", ",") . "</th></tr>";
                        } else {
                            $table .= "<tr><th colspan='2'></th><th class='right'>(" . number_format(($camo1 * -1), 2, ".", ",") . ")</th></tr>";
                        }
                    }
                    if ($cgroup != "") {
                        if ($camo >= 0) {
                            $table .= "<tr><th colspan='2'>Total " . $cgroup . "</th><th class='right'>" . number_format($camo, 2, ".", ",") . "</th></tr>";
                        } else {
                            $table .= "<tr><th colspan='2'>Total " . $cgroup . "</th><th class='right'>(" . number_format(($camo * -1), 2, ".", ",") . ")</th></tr>";
                        }
                    }
                    $table .= "<tr><th class='left' colspan='3'>" . $row_rsPrInv['C_SUBGRO1'] . "</th></tr>";
                    $camo = 0;
                    $camo1 = 0;
                    $cgroup1 = "";
                }
                if ($cgroup1 != $row_rsPrInv['C_SUBGRO2']) {
                    if ($cgroup1 != "") {
                        if ($camo1 >= 0) {
                            $table .= "<tr><th colspan='2'></th><th class='right'>" . number_format($camo1, 2, ".", ",") . "</th></tr>";
                        } else {
                            $table .= "<tr><th colspan='2'></th><th class='right'>(" . number_format(($camo1 * -1), 2, ".", ",") . ")</th></tr>";
                        }
                    }
                    $table .= "<tr><th></th><th class='left' colspan='2'>" . $row_rsPrInv['C_SUBGRO2'] . "</th></tr>";
                    $camo1 = 0;
                }




                $cgroup = $row_rsPrInv['C_SUBGRO1'];
                $cgroup1 = $row_rsPrInv['C_SUBGRO2'];

                $ac_type = $row_rsPrInv['C_TYPE'];



                $camo = $camo + $pen;
                $camo1 = $camo1 + $pen;



                $url = "report_ledger_link.php?txtAccCode=" . $row_rsPrInv["L_CODE"] . "&txtAccName=" . $row_rsPrInv["C_NAME"] . "&repdatefrom=" . $_GET["dtfrom"] . "&repdateto=" . $_GET["dtto"];


            if ($_GET['view1']=="view") {
                $table .= "<tr><td><a target='_blank' href='" . $url . "'>" . $row_rsPrInv["L_CODE"] . "</a></td>
		       <td><a target='_blank' href='" . $url . "'>" . $row_rsPrInv["C_NAME"] . "</a></td>";
} else {
     $table .= "<tr><td>" . $row_rsPrInv["L_CODE"] . "</td>
		       <td>" . $row_rsPrInv["C_NAME"] . "</td>";
}






                $cl_bal = $bF + $pen;

                if ($cl_bal >= 0) {
                    $table .= "<td class='right'>" . number_format($cl_bal, 2, ".", ",") . "</td>";
                } else {
                    $table .= "<td class='right'>(" . number_format(-1 * $cl_bal, 2, ".", ",") . ")</td>";
                }

                $mcl_bal = $mcl_bal + $cl_bal;

                $table .= "</tr>";
            }


            if ($camo1 >= 0) {
                $table .= "<tr><th colspan='2'></th><th class='right'>" . number_format($camo1, 2, ".", ",") . "</th></tr>";
            } else {
                $table .= "<tr><th colspan='2'></th><th class='right'>(" . number_format(($camo1 * -1), 2, ".", ",") . ")</th></tr>";
            }


            if ($camo >= 0) {
                $table .= "<tr><th colspan='2'>Total " . $cgroup . "</th><th class='right'>" . number_format($camo, 2, ".", ",") . "</th></tr>";
            } else {
                $table .= "<tr><th colspan='2'>Total " . $cgroup . "</th><th class='right'>(" . number_format(($camo * -1), 2, ".", ",") . ")</th></tr>";
            }


            $table .= "<tr><th  class='left' colspan=2>Current Year Income/Loss</th>


		<th  class='right'>" . number_format($mcl_bal, 2, ".", ",") . "</th></tr>";
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
    $pdf_name = "PNLBS" . "_" . (date('Ymd g i'));


    $file_location = "pdfReports/" . $pdf_name . ".pdf";
    file_put_contents($file_location, $pdf);


     $data = file_get_contents($file_location);
  header("Content-type: application/octet-stream");
  header("Content-disposition: attachment;filename=YOURFILE.pdf");

  echo $data;





                }

                 if ($_GET['view1']=="excel")  {






				 $file = "rpt_pnlbs.xls";

        $f = fopen($file, "w+");

        fwrite($f, $table);


             $data = file_get_contents($file);
  header("Content-type: application/octet-stream");
  header("Content-disposition: attachment;filename=PNLS.xls");

		 echo $data;


                }






        } else {

            $txtdes = "Balance Sheet As at : " . date("Y-m-d", strtotime($_GET["dtto"]));



            // $table .= "<h3>" . $row_rspara['COMPANY'] . "</h3>";
            // $table .= "<h4>" . $txtdes . "</h4>";


            $heading3 = $row_rspara['COMPANY'] ;
            $heading4 = $txtdes;


            $table .= "<table id='testTable' class='table table1' >
            <tr >
            <th colspan='3' class='center'  style='width: 90px;'>$heading3</th>
        
            </tr>
            <tr>
            <th colspan='3' class='center'  style='width: 90px;'>$heading4</th>
        
            </tr>

      	<tr>
        <th class='center'  style='width: 120px;'>Ledger</th>
        <th class='center'  style='width: 420px;'>Account Description</th>
	    <th class='center'  style='width: 90px;'>Closing Balance</th>
        </tr>";


            $bal = 0;
            $mcl_bal = 0;

            $ayear = ac_year($_GET["dtfrom"]);
            $dtb = $ayear . "-04-01";


            $sql = "update ledger set l_amount1 = l_amount where l_flag1 = 'DEB'";
            $result = $conn->query($sql);

            $sql = "update ledger set l_amount1 = l_amount *-1 where l_flag1 = 'CRE'";
            $result = $conn->query($sql);


            $mearn = 0;
            $sql = "select  sum(L_AMOUNT1) as amo ,C_SUBGRO1 from view_ledger where C_SUBGRO1 <> '' ";
            $sql .= " and acyear <'" . $ayear . "' and l_yearfl='0' group by C_SUBGRO1";

            foreach ($conn->query($sql) as $row_rsPrInv) {
                if (trim($row_rsPrInv['C_SUBGRO1']) == "Revenue") {
                    $mearn = $mearn - $row_rsPrInv['amo'] * -1;
                }
                if (trim($row_rsPrInv['C_SUBGRO1']) == "Expenses") {
                    $mearn = $mearn + $row_rsPrInv['amo'];
                }
            }
            $mearn1 = 0;
            $sql = "select  sum(L_AMOUNT1) as amo ,C_SUBGRO1 from view_ledger where C_SUBGRO1 <> '' ";
            $sql .= " and acyear ='" . $ayear . "' and (l_date>='" . $_GET["dtfrom"] . "' and l_date<='" . $_GET['dtto'] . "') and l_yearfl='0' group by C_SUBGRO1";

            foreach ($conn->query($sql) as $row_rsPrInv) {
                if (trim($row_rsPrInv['C_SUBGRO1']) == "Revenue") {
                    $mearn1 = $mearn1 + $row_rsPrInv['amo'] * -1;
                }
                if (trim($row_rsPrInv['C_SUBGRO1']) == "Expenses") {
                    $mearn1 = $mearn1 - $row_rsPrInv['amo'];
                }
            }


            $sql_rsPrInv = "select C_TYPE,l_code,C_NAME,sum(L_AMOUNT1)as amo, C_SUBGRO1,C_SUBGRO2   from view_ledger  where c_type ='B' and (l_date<='" . $_GET['dtto'] . "') and l_yearfl='0'  group by C_TYPE,l_code,C_NAME,C_SUBGRO1,C_SUBGRO2,pos order by  pos ,C_SUBGRO2,l_code ";

            $bF = 0;
            $cgroup = "";
            $cgroup1 = "";
            $camo = 0;
            $camo1 = 0;
            foreach ($conn->query($sql_rsPrInv) as $row_rsPrInv) {
                $pen = ($row_rsPrInv['amo']);

                if ($row_rsPrInv['L_CODE'] == "TDL-7000.002") {
                    $pen = $pen + $mearn;
                }

                if ($row_rsPrInv['C_SUBGRO1'] == "Equity") {
                    $pen = abs($pen);
                }

                if ($cgroup != $row_rsPrInv['C_SUBGRO1']) {

                    if ($cgroup1 != "") {
                        if ($camo1 >= 0) {
                            $table .= "<tr><th colspan='2'></th><th class='right'>" . number_format($camo1, 2, ".", ",") . "</th></tr>";
                        } else {
                            $table .= "<tr><th colspan='2'></th><th class='right'>(" . number_format(($camo1 * -1), 2, ".", ",") . ")</th></tr>";
                        }
                    }
                    if ($cgroup != "") {
                        if ($camo >= 0) {
                            $table .= "<tr><th colspan='2'>Total " . $cgroup . "</th><th class='right'>" . number_format($camo, 2, ".", ",") . "</th></tr>";
                        } else {
                            $table .= "<tr><th colspan='2'>Total " . $cgroup . "</th><th class='right'>(" . number_format(($camo * -1), 2, ".", ",") . ")</th></tr>";
                        }
                    }


                    if ($cgroup == "Liabilities") {

                        if ($mcl_bal >= 0) {
                            $table .= "<tr><th colspan='2'>Net Assets & Liabilities</th><th class='right'>" . number_format($mcl_bal, 2, ".", ",") . "</th></tr>";
                        } else {
                            $table .= "<tr><th colspan='2'></th><th class='right'>(" . number_format(($mcl_bal * -1), 2, ".", ",") . ")</th></tr>";
                        }
                    }


                    $table .= "<tr><th class='left' colspan='3'>" . $row_rsPrInv['C_SUBGRO1'] . "</th></tr>";
                    $camo = 0;
                    $camo1 = 0;
                    $cgroup1 = "";
                }
                if ($cgroup1 != $row_rsPrInv['C_SUBGRO2']) {
                    if ($cgroup1 != "") {
                        if ($camo1 >= 0) {
                            $table .= "<tr><th colspan='2'></th><th class='right'>" . number_format($camo1, 2, ".", ",") . "</th></tr>";
                        } else {
                            $table .= "<tr><th colspan='2'></th><th class='right'>(" . number_format(($camo1 * -1), 2, ".", ",") . ")</th></tr>";
                        }
                    }
                    $table .= "<tr><th></th><th class='left' colspan='2'>" . $row_rsPrInv['C_SUBGRO2'] . "</th></tr>";
                    $camo1 = 0;
                }




                $cgroup = $row_rsPrInv['C_SUBGRO1'];
                $cgroup1 = $row_rsPrInv['C_SUBGRO2'];

                $ac_type = $row_rsPrInv['C_TYPE'];



                $camo = $camo + $pen;
                $camo1 = $camo1 + $pen;



                $url = "report_ledger_link.php?txtAccCode=" . $row_rsPrInv["L_CODE"] . "&txtAccName=" . $row_rsPrInv["C_NAME"] . "&repdatefrom=" . $_GET["dtfrom"] . "&repdateto=" . $_GET["dtto"];

                

                $cl_bal = $bF + $pen;
                
                
                
                
                
                
                
                if($cl_bal==0){
                
                
            } else {
                $table .= "<tr><td><a target='_blank' href='" . $url . "'>" . $row_rsPrInv["L_CODE"] . "</a></td>
		       <td><a target='_blank' href='" . $url . "'>" . $row_rsPrInv["C_NAME"] . "</a></td>";
                
               if ($cl_bal >= 0) {
                    $table .= "<td class='right'>" . number_format($cl_bal, 2, ".", ",") . "</td>";
                } else {
                    $table .= "<td class='right'>(" . number_format(-1 * $cl_bal, 2, ".", ",") . ")</td>";
                }

                $mcl_bal = $mcl_bal + $cl_bal;

                $table .= "</tr>";

            }
            
            
            
            
                
                
                

                
            }

            $table .= "<tr><td></td><td  class='left'>Current Year Income/Loss</td>
		   <td  class='right'>" . number_format($mearn1, 2, ".", ",") . "</td></tr>";
            $camo1 = $camo1 + $mearn1;
            $camo = $camo + $mearn1;


            if ($camo1 >= 0) {
                $table .= "<tr><th colspan='2'></th><th class='right'>" . number_format($camo1, 2, ".", ",") . "</th></tr>";
            } else {
                $table .= "<tr><th colspan='2'></th><th class='right'>(" . number_format(($camo1 * -1), 2, ".", ",") . ")</th></tr>";
            }
            if ($camo >= 0) {
                $table .= "<tr><th colspan='2'>Total " . $cgroup . "</th><th class='right'>" . number_format($camo, 2, ".", ",") . "</th></tr>";
            } else {
                $table .= "<tr><th colspan='2'>Total " . $cgroup . "</th><th class='right'>(" . number_format(($camo * -1), 2, ".", ",") . ")</th></tr>";
            }

            $table .= "</table>



</body>
</html>";

             if ($_GET['view1']=="view") {
                echo $sql_rsPrInv;
            echo $table;
            }

                if ($_GET['view1']=="pdf")  {


                        $dompdf->loadHtml($table);



// Render the HTML as PDF
    $dompdf->render();

// Output the generated PDF to Browser
    $pdf = $dompdf->output();
    $pdf_name = "PNLBS" . "_" . (date('Ymd g i'));


    $file_location = "pdfReports/" . $pdf_name . ".pdf";
    file_put_contents($file_location, $pdf);


     $data = file_get_contents($file_location);
  header("Content-type: application/octet-stream");
  header("Content-disposition: attachment;filename=YOURFILE.pdf");

  echo $data;





                }

                 if ($_GET['view1']=="excel")  {






				 $file = "rpt_pnlbs.xls";

        $f = fopen($file, "w+");

        fwrite($f, $table);


             $data = file_get_contents($file);
  header("Content-type: application/octet-stream");
  header("Content-disposition: attachment;filename=PNLS.xls");

		 echo $data;


                }
        }
    }

    if ($_GET['view'] == "monthly") {


        if ($_GET['optionsRadios'] == "op_pnl") {



            $ayear = ac_year($_GET["dtfrom"]);
            $dtb = $ayear . "-04-01";


            $sql = "update ledger set l_amount1 = l_amount where l_flag1 = 'DEB'";
            $result = $conn->query($sql);

            $sql = "update ledger set l_amount1 = l_amount *-1 where l_flag1 = 'CRE'";
            $result = $conn->query($sql);

            $sql_rsPrInv = "select month(l_date) as l_month,year(l_date) as l_year,l_code,C_NAME,sum(L_AMOUNT1)as amo, C_SUBGRO1,C_SUBGRO2   from view_ledger  where c_type ='P' and (l_date>='" . $_GET["dtfrom"] . "' and l_date<='" . $_GET['dtto'] . "') and l_yearfl='0'  group by l_code,C_NAME,C_SUBGRO1,C_SUBGRO2,month(l_date),year(l_date) order by  C_SUBGRO1 desc,C_SUBGRO2,l_code ";
            foreach ($conn->query($sql_rsPrInv) as $row_rsPrInv) {
                $usr[] = "('" . $row_rsPrInv['l_month'] . "','" . $row_rsPrInv['l_year'] . "','" . $row_rsPrInv['L_CODE'] . "','" . $row_rsPrInv['C_NAME'] . "','" . (trim($row_rsPrInv['C_SUBGRO1'])) . "','" . (trim($row_rsPrInv['C_SUBGRO2'])) . "','" . $row_rsPrInv['amo'] . "')";
            }

            $sql = "delete from tmp_pnl_mont";
            $conn->query($sql);

            $sql = "insert into tmp_pnl_mont (l_month,l_year,l_code,l_name,c_group,c_group1,l_amount) values " . implode($usr, ",");
            $resu = $conn->query($sql);


            $txtdes = "Profit & Loss Statement For the Period : " . date("Y-m-d", strtotime($_GET["dtfrom"])) . "    To: " . date("Y-m-d", strtotime($_GET["dtto"]));



            // $table .= "<h3>" . $row_rspara['COMPANY'] . "</h3>";
            // $table .= "<h4>" . $txtdes . "</h4>";


          
             $heading3 = $row_rspara['COMPANY'] ;
                $heading4 = $txtdes;


            $table .= "<table id='testTable' class='table table1' >
              <tr >
                <th colspan='3' class='center'  style='width: 90px;'>$heading3</th>
         
             </tr>
                <tr>
             <th colspan='3' class='center'  style='width: 90px;'>$heading4</th>
        
                </tr>

      	<tr>
        <th class='center'  style='width: 10px;'></th>
        <th class='center'  style='width: 300px;'>Account Description</th>";
            $sql = "select l_month,l_year from tmp_pnl_mont group by l_month,l_year order by l_year,l_month";
            $mon = 1;
            foreach ($conn->query($sql) as $row_rsPrInv) {

                $mon_txt = $row_rsPrInv['l_year'] ."-" . $row_rsPrInv['l_month'] . "-01";
                $rtxtm1 = date("M", strtotime($mon_txt)) . " " . date("Y", strtotime($mon_txt));

                $table .= "<th class='center'  style='width: 70px;'>" . $rtxtm1 . "</th>";

                $camoA[$mon] = 0;
                $camo1A[$mon] = 0;
                $mcl_balA[$mon] = 0;
                $mearn1A[$mon] = 0;
                $mon = $mon + 1;
            }




            $table .= "</tr>";


            $bF = 0;
            $cgroup = "";
            $cgroup1 = "";
            $cl_bal = 0;
            $mcl_bal = 0;
            $camo = 0;
            $camo1 = 0;
            $camoA[] = 0;
            $camo1A[] = 0;
            $mcl_balA[] = 0;
            $sql_rsPrInv = "select L_CODE,l_name as C_NAME,c_group AS C_SUBGRO1,c_group1 AS C_SUBGRO2 from tmp_pnl_mont  group by l_code,l_name,c_group,c_group1 order by   C_SUBGRO1 desc,C_SUBGRO2,l_code  ";
            $mon = $mon - 1;
            foreach ($conn->query($sql_rsPrInv) as $row_rsPrInv) {

                if ($cgroup != $row_rsPrInv['C_SUBGRO1']) {
                    $t = 1;
                    if ($cgroup1 != "") {
                        $table .= "<tr><th colspan='2'></th>";
                        while ($t <= $mon) {

                            if ($camo1A[$t] >= 0) {
                                $table .= "<th class='right'>" . number_format($camo1A[$t], 2, ".", ",") . "</th>";
                            } else {
                                $table .= "<th class='right'>(" . number_format(($camo1A[$t] * -1), 2, ".", ",") . ")</th>";
                            }
                            $camo1A[$t] = 0;
                            $t = $t + 1;
                        }
                        $table .= "</tr>";
                    }
                    $t = 1;
                    if ($cgroup != "") {
                        $table .= "<tr><th colspan='2'>Total " . $cgroup . "</th>";
                        while ($t <= $mon) {
                            if ($camoA[$t] >= 0) {
                                $table .= "<th class='right'>" . number_format($camoA[$t], 2, ".", ",") . "</th>";
                            } else {
                                $table .= "<th class='right'>(" . number_format(($camoA[$t] * -1), 2, ".", ",") . ")</th>";
                            }
                            $camoA[$t] = 0;
                            $t = $t + 1;
                        }
                        $table .= "</tr>";
                    }





                    $table .= "<tr><th class='left' colspan='" . ($mon+3) . "'>" . $row_rsPrInv['C_SUBGRO1'] . "</th></tr>";
                    $camo = 0;
                    $camo1 = 0;
                    $cgroup1 = "";
                }

                if ($cgroup1 != $row_rsPrInv['C_SUBGRO2']) {
                    if ($cgroup1 != "") {
                        $t = 1;
                        $table .= "<tr><th colspan='2'></th>";
                        while ($t <= $mon) {
                            if ($camo1A[$t] >= 0) {
                                $table .= "<th class='right'>" . number_format($camo1A[$t], 2, ".", ",") . "</th>";
                            } else {
                                $table .= "<th class='right'>(" . number_format(($camo1A[$t] * -1), 2, ".", ",") . ")</th>";
                            }
                            $camo1A[$t] = 0;
                            $t = $t + 1;
                        }
                        $table .= "</tr>";
                    }
                    $table .= "<tr><th></th><th class='left' colspan='" . ($mon+3) . "'>" . $row_rsPrInv['C_SUBGRO2'] . "</th></tr>";
                    $camo1 = 0;
                }

                $url = "report_ledger_link.php?txtAccCode=" . $row_rsPrInv["L_CODE"] . "&txtAccName=" . $row_rsPrInv["C_NAME"] . "&repdatefrom=" . $_GET["dtfrom"] . "&repdateto=" . $_GET["dtto"];

                $table .= "<tr><td><a target='_blank' href='" . $url . "'></a></td>
		       <td><a target='_blank' href='" . $url . "'>" . $row_rsPrInv["C_NAME"] . "</a></td>";

                $i = 1;
                $sql = "select l_month,l_year from tmp_pnl_mont group by l_month,l_year  order by l_year,l_month";
                foreach ($conn->query($sql) as $row) {
                    $sql = "select sum(l_amount) as amo from tmp_pnl_mont where   l_code = '" . $row_rsPrInv['L_CODE'] . "' and l_month = '" . $row['l_month'] . "' and l_year = '" . $row['l_year'] . "'";
                    $result = $conn->query($sql);
                    $row_1 = $result->fetch();
                    $pen = ($row_1['amo']);
                    if ($pen < 0) {
                        $pen = abs($row_1['amo']);
                    } else {
                        $pen = ($row_1['amo'] * -1);
                    }

                    $camo = $camo + $pen;
                    $camo1 = $camo1 + $pen;


                    $camoA[$i] = $camoA[$i] + $pen;
                    $camo1A[$i] = $camo1A[$i] + $pen;

                    $cl_bal = $bF + $pen;
                    $mcl_bal = $mcl_bal + $cl_bal;
                    $mcl_balA[$i] = $mcl_balA[$i] + $cl_bal;
                    $i = $i + 1;
                    if ($cl_bal >= 0) {
                        $table .= "<td class='right'>" . number_format($cl_bal, 2, ".", ",") . "</td>";
                    } else {
                        $table .= "<td class='right'>(" . number_format(-1 * $cl_bal, 2, ".", ",") . ")</td>";
                    }
                }
                $cgroup = $row_rsPrInv['C_SUBGRO1'];
                $cgroup1 = $row_rsPrInv['C_SUBGRO2'];
                $table .= "</tr>";
            }



            $table .= "<tr><th colspan='2'></th>";
            $t = 1;
            while ($t <= $mon) {
                if ($camo1A[$t] >= 0) {
                    $table .= "<th class='right'>" . number_format($camo1A[$t], 2, ".", ",") . "</th>";
                } else {
                    $table .= "<th class='right'>(" . number_format(($camo1A[$t] * -1), 2, ".", ",") . ")</th>";
                }
                $t = $t + 1;
            }
            $table .= "</tr>";

            $table .= "<tr><th colspan='2'>Total " . $cgroup . "</th>";
            $t = 1;
            while ($t <= $mon) {
                if ($camoA[$t] >= 0) {
                    $table .= "<th class='right'>" . number_format($camoA[$t], 2, ".", ",") . "</th>";
                } else {
                    $table .= "<th class='right'>(" . number_format(($camoA[$t] * -1), 2, ".", ",") . ")</th>";
                }
                $t = $t + 1;
            }
            $table .= "</tr>";

            $table .= "<tr><th  class='left' colspan=2>Current Year Income/Loss</th>";
            $t = 1;
            while ($t <= $mon) {
                if ($mcl_balA[$t] >= 0) {
                    $table .= "<th class='right'>" . number_format($mcl_balA[$t], 2, ".", ",") . "</th>";
                } else {
                    $table .= "<th class='right'>(" . number_format(($mcl_balA[$t] * -1), 2, ".", ",") . ")</th>";
                }
                $t = $t + 1;
            }
            $table .= "</tr>";



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
    $pdf_name = "PNLBS" . "_" . (date('Ymd g i'));


    $file_location = "pdfReports/" . $pdf_name . ".pdf";
    file_put_contents($file_location, $pdf);


     $data = file_get_contents($file_location);
  header("Content-type: application/octet-stream");
  header("Content-disposition: attachment;filename=YOURFILE.pdf");

  echo $data;





                }

                 if ($_GET['view1']=="excel")  {






				 $file = "rpt_pnlbs.xls";

        $f = fopen($file, "w+");

        fwrite($f, $table);


             $data = file_get_contents($file);
  header("Content-type: application/octet-stream");
  header("Content-disposition: attachment;filename=PNLS.xls");

		 echo $data;


                }
        } else {




            $ayear = ac_year($_GET["dtfrom"]);
            $dtb = $ayear . "-04-01";


            $sql = "update ledger set l_amount1 = l_amount where l_flag1 = 'DEB'";
            $result = $conn->query($sql);

            $sql = "update ledger set l_amount1 = l_amount *-1 where l_flag1 = 'CRE'";
            $result = $conn->query($sql);

            $sql_rsPrInv = "select pos,month(l_date) as l_month,year(l_date) as l_year,l_code,C_NAME,sum(L_AMOUNT1)as amo, C_SUBGRO1,C_SUBGRO2   from view_ledger  where c_type ='B' and (l_date>='" . $_GET["dtfrom"] . "' and l_date<='" . $_GET['dtto'] . "')  and l_yearfl='0' group by pos,l_code,C_NAME,C_SUBGRO1,C_SUBGRO2,month(l_date),year(l_date) order by  C_SUBGRO1 desc,C_SUBGRO2,l_code ";
            foreach ($conn->query($sql_rsPrInv) as $row_rsPrInv) {
                $usr[] = "('" . $row_rsPrInv['pos'] . "','" . $row_rsPrInv['l_month'] . "','" . $row_rsPrInv['l_year'] . "','" . $row_rsPrInv['L_CODE'] . "','" . $row_rsPrInv['C_NAME'] . "','" . (trim($row_rsPrInv['C_SUBGRO1'])) . "','" . (trim($row_rsPrInv['C_SUBGRO2'])) . "','" . $row_rsPrInv['amo'] . "')";
            }

            $sql = "delete from tmp_pnl_mont";
            $conn->query($sql);

            $sql = "insert into tmp_pnl_mont (pos,l_month,l_year,l_code,l_name,c_group,c_group1,l_amount) values " . implode($usr, ",");
            $conn->query($sql);




            $txtdes = "Monthly Balance Sheet As at : " . date("Y-m-d", strtotime($_GET["dtto"]));



            // $table .= "<h3>" . $row_rspara['COMPANY'] . "</h3>";
            // $table .= "<h4>" . $txtdes . "</h4>";
            
              
                 $heading3 = $row_rspara['COMPANY'] ;
                $heading4 = $txtdes;


                $table .= "<table id='testTable' class='table table1' >
            <tr >
              <th colspan='3' class='center'  style='width: 90px;'>$heading3</th>
             
            </tr>
             <tr>
            <th colspan='3' class='center'  style='width: 90px;'>$heading4</th>
        
            </tr>

      	<tr>
        <th class='center'  style='width: 10px;'>Ledger</th>
        <th class='center'  style='width: 300px;'>Account Description</th>";
            $sql = "select l_month,l_year from tmp_pnl_mont group by l_month,l_year order by l_year,l_month";
            $mon = 1;
            foreach ($conn->query($sql) as $row_rsPrInv) {
              //  $table .= "<th class='center'  style='width: 70px;'>" . $row_rsPrInv['l_month'] . " " . $row_rsPrInv['l_year'] . "</th>";


             $mon_txt = $row_rsPrInv['l_year'] ."-" . $row_rsPrInv['l_month'] . "-01";
                $rtxtm1 = date("M", strtotime($mon_txt)) . " " . date("Y", strtotime($mon_txt));

                $table .= "<th class='center'  style='width: 70px;'>" . $rtxtm1 . "</th>";

                $camoA[$mon] = 0;
                $camo1A[$mon] = 0;
                $mcl_balA[$mon] = 0;
                $mearn1A[$mon] = 0;
                $mon = $mon + 1;
            }




            $table .= "</tr>";


            $bal = 0;
            $mcl_bal = 0;




            $mearn = 0;



            $bF = 0;
            $cgroup = "";
            $cgroup1 = "";
            $camo = 0;
            $camo1 = 0;
            $camoA[] = 0;
            $camo1A[] = 0;
            $mcl_balA[] = 0;
            $sql_rsPrInv = "select L_CODE,l_name as C_NAME,c_group AS C_SUBGRO1,c_group1 AS C_SUBGRO2 from tmp_pnl_mont  group by l_code,l_name,c_group,c_group1,pos order by  pos ,c_group1,l_code ";
            $mon = $mon - 1;
            foreach ($conn->query($sql_rsPrInv) as $row_rsPrInv) {


                if ($cgroup != $row_rsPrInv['C_SUBGRO1']) {
                    $t = 1;
                    if ($cgroup1 != "") {
                        $table .= "<tr><th colspan='2'></th>";
                        while ($t <= $mon) {

                            if ($camo1A[$t] >= 0) {
                                $table .= "<th class='right'>" . number_format($camo1A[$t], 2, ".", ",") . "</th>";
                            } else {
                                $table .= "<th class='right'>(" . number_format(($camo1A[$t] * -1), 2, ".", ",") . ")</th>";
                            }
                            $camo1A[$t] = 0;
                            $t = $t + 1;
                        }
                        $table .= "</tr>";
                    }
                    $t = 1;
                    if ($cgroup != "") {
                        $table .= "<tr><th colspan='2'>Total " . $cgroup . "</th>";
                        while ($t <= $mon) {
                            if ($camoA[$t] >= 0) {
                                $table .= "<th class='right'>" . number_format($camoA[$t], 2, ".", ",") . "</th>";
                            } else {
                                $table .= "<th class='right'>(" . number_format(($camoA[$t] * -1), 2, ".", ",") . ")</th>";
                            }
                            $camoA[$t] = 0;
                            $t = $t + 1;
                        }
                        $table .= "</tr>";
                    }


                    if ($cgroup == "Liabilities") {
                        $t = 1;
                        $table .= "<tr><th colspan='2'>Net Assets & Liabilities</th>";
                        while ($t <= $mon) {
                            if ($mcl_balA[$t] >= 0) {
                                $table .= "<th class='right'>" . number_format($mcl_balA[$t], 2, ".", ",") . "</th>";
                            } else {
                                $table .= "<th class='right'>(" . number_format(($mcl_balA[$t] * -1), 2, ".", ",") . ")</th>";
                            }
                            $mcl_balA[$t] = 0;
                            $t = $t + 1;
                        }
                        $table .= "</tr>";
                    }


                    $table .= "<tr><th class='left' colspan='" . ($mon+3)   . "'>" . $row_rsPrInv['C_SUBGRO1'] . "</th></tr>";
                    $camo = 0;
                    $camo1 = 0;
                    $cgroup1 = "";
                }

                if ($cgroup1 != $row_rsPrInv['C_SUBGRO2']) {
                    if ($cgroup1 != "") {
                        $t = 1;
                        $table .= "<tr><th colspan='2'></th>";
                        while ($t <= $mon) {
                            if ($camo1A[$t] >= 0) {
                                $table .= "<th class='right'>" . number_format($camo1A[$t], 2, ".", ",") . "</th>";
                            } else {
                                $table .= "<th class='right'>(" . number_format(($camo1A[$t] * -1), 2, ".", ",") . ")</th>";
                            }
                            $camo1A[$t] = 0;
                            $t = $t + 1;
                        }
                        $table .= "</tr>";
                    }
                    $table .= "<tr><th></th><th class='left' colspan='" . ($mon+3)  ."'>" . $row_rsPrInv['C_SUBGRO2'] . "</th></tr>";
                    $camo1 = 0;
                }





                $url = "report_ledger_link.php?txtAccCode=" . $row_rsPrInv["L_CODE"] . "&txtAccName=" . $row_rsPrInv["C_NAME"] . "&repdatefrom=" . $_GET["dtfrom"] . "&repdateto=" . $_GET["dtto"];

                $table .= "<tr><td><a target='_blank' href='" . $url . "'></a></td>
		               <td><a target='_blank' href='" . $url . "'>" . $row_rsPrInv["C_NAME"] . "</a></td>";


                $i = 1;
                $sql = "select l_month,l_year from tmp_pnl_mont group by l_month,l_year  order by l_year,l_month";
                foreach ($conn->query($sql) as $row) {


                    $mearn1A[$i] = 0;
                    $sql = "select  sum(L_AMOUNT1) as amo ,C_SUBGRO1 from view_ledger where C_SUBGRO1 <> '' ";
                    $sql .= " and acyear ='" . $ayear . "'  and month(l_date) ='" . $row['l_month'] . "' and year(l_date) ='" . $row['l_year'] . "' group by C_SUBGRO1";

                    foreach ($conn->query($sql) as $row_1) {
                        if (trim($row_1['C_SUBGRO1']) == "Revenue") {
                            $mearn1A[$i] = $mearn1A[$i] + $row_1['amo'] * -1;
                        }
                        if (trim($row_1['C_SUBGRO1']) == "Expenses") {
                            $mearn1A[$i] = $mearn1A[$i] - $row_1['amo'];
                        }
                    }




                    $sql = "select sum(l_amount) as amo from tmp_pnl_mont where   l_code = '" . $row_rsPrInv['L_CODE'] . "' and l_month = '" . $row['l_month'] . "' and l_year = '" . $row['l_year'] . "'";
                    $result = $conn->query($sql);
                    $row_1 = $result->fetch();


                    $pen = ($row_1['amo']);



                    if ($row_rsPrInv['L_CODE'] == "TDL-7000.002") {
                        $pen = $pen + $mearn;
                    }

                    if ($row_rsPrInv['C_SUBGRO1'] == "Equity") {
                        $pen = abs($pen);
                    }

                    $cl_bal = $bF + $pen;

                    if ($cl_bal >= 0) {
                        $table .= "<td class='right'>" . number_format($cl_bal, 2, ".", ",") . "</td>";
                    } else {
                        $table .= "<td class='right'>(" . number_format(-1 * $cl_bal, 2, ".", ",") . ")</td>";
                    }


                    $mcl_bal = $mcl_bal + $cl_bal;
                    $mcl_balA[$i] = $mcl_balA[$i] + $cl_bal;


                    $camo = $camo + $pen;
                    $camo1 = $camo1 + $pen;


                    $camoA[$i] = $camoA[$i] + $pen;
                    $camo1A[$i] = $camo1A[$i] + $pen;

                    $i = $i + 1;
                }

                $cgroup = $row_rsPrInv['C_SUBGRO1'];
                $cgroup1 = $row_rsPrInv['C_SUBGRO2'];

                $table .= "</tr>";
            }


            $table .= "<tr><td></td><td  class='left'>Current Year Income/Loss</td>";
            $t = 1;
            while ($t <= $mon) {
                $table .= "<td  class='right'>" . number_format($mearn1A[$t], 2, ".", ",") . "</td>";
                $camo1A[$t] = $camo1A[$t] + $mearn1A[$t];
                $camoA[$t] = $camoA[$t] + $mearn1A[$t];
                $t = $t + 1;
            }
            $table .= "</tr>";


            $table .= "<tr><th colspan='2'></th>";
            $t = 1;
            while ($t <= $mon) {
                if ($camo1A[$t] >= 0) {
                    $table .= "<th class='right'>" . number_format($camo1A[$t], 2, ".", ",") . "</th>";
                } else {
                    $table .= "<th class='right'>(" . number_format(($camo1A[$t] * -1), 2, ".", ",") . ")</th>";
                }
                $t = $t + 1;
            }
            $table .= "</tr>";

            $table .= "<tr><th colspan='2'>Total " . $cgroup . "</th>";
            $t = 1;
            while ($t <= $mon) {
                if ($camoA[$t] >= 0) {
                    $table .= "<th class='right'>" . number_format($camoA[$t], 2, ".", ",") . "</th>";
                } else {
                    $table .= "<th class='right'>(" . number_format(($camoA[$t] * -1), 2, ".", ",") . ")</th>";
                }
                $t = $t + 1;
            }
            $table .= "</tr></table>



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
    $pdf_name = "PNLBS" . "_" . (date('Ymd g i'));


    $file_location = "pdfReports/" . $pdf_name . ".pdf";
    file_put_contents($file_location, $pdf);


     $data = file_get_contents($file_location);
  header("Content-type: application/octet-stream");
  header("Content-disposition: attachment;filename=YOURFILE.pdf");

  echo $data;





                }

                 if ($_GET['view1']=="excel")  {






				 $file = "rpt_pnlbs.xls";

        $f = fopen($file, "w+");

        fwrite($f, $table);


             $data = file_get_contents($file);
  header("Content-type: application/octet-stream");
  header("Content-disposition: attachment;filename=PNLS.xls");

		 echo $data;


                }
        }
    }



    if ($_GET['view'] == "yearly") {


        if ($_GET['optionsRadios'] == "op_pnl") {

            $ayear = ac_year($_GET["dtfrom"]);
            $dtb = $ayear . "-04-01";


            $sql = "update ledger set l_amount1 = l_amount where l_flag1 = 'DEB'";
            $result = $conn->query($sql);

            $sql = "update ledger set l_amount1 = l_amount *-1 where l_flag1 = 'CRE'";
            $result = $conn->query($sql);

            $sql_rsPrInv = "select year(l_date) as l_year,l_code,C_NAME,sum(L_AMOUNT1)as amo, C_SUBGRO1,C_SUBGRO2   from view_ledger  where c_type ='P' and (l_date>='" . $_GET["dtfrom"] . "' and l_date<='" . $_GET['dtto'] . "') and l_yearfl='0'  group by l_code,C_NAME,C_SUBGRO1,C_SUBGRO2,year(l_date) order by  C_SUBGRO1 desc,C_SUBGRO2,l_code ";
            foreach ($conn->query($sql_rsPrInv) as $row_rsPrInv) {
                $usr[] = "('" . $row_rsPrInv['l_year'] . "','" . $row_rsPrInv['L_CODE'] . "','" . $row_rsPrInv['C_NAME'] . "','" . (trim($row_rsPrInv['C_SUBGRO1'])) . "','" . (trim($row_rsPrInv['C_SUBGRO2'])) . "','" . $row_rsPrInv['amo'] . "')";
            }

            $sql = "delete from tmp_pnl_mont";
            $conn->query($sql);

            $sql = "insert into tmp_pnl_mont (l_year,l_code,l_name,c_group,c_group1,l_amount) values " . implode($usr, ",");
            $conn->query($sql);


            $txtdes = "Profit & Loss Statement For the Period : " . date("Y-m-d", strtotime($_GET["dtfrom"])) . "    To: " . date("Y-m-d", strtotime($_GET["dtto"]));



            // $table .= "<h3>" . $row_rspara['COMPANY'] . "</h3>";
            // $table .= "<h4>" . $txtdes . "</h4>";
    
              
                        $heading3 = $row_rspara['COMPANY'] ;
                $heading4 = $txtdes;
                
                
                 $table .= "<table id='testTable' class='table table1' >
                <tr >
                        <th colspan='3' class='center'  style='width: 90px;'>$heading3</th>
                        
                        </tr>
                        <tr>
                        <th colspan='3' class='center'  style='width: 90px;'>$heading4</th>
                        
                        </tr>





           

      	<tr>
        <th class='center'  style='width: 10px;'></th>
        <th class='center'  style='width: 300px;'>Account Description</th>";
            $sql = "select l_year from tmp_pnl_mont group by l_year order by l_year";
            $mon = 1;
            foreach ($conn->query($sql) as $row_rsPrInv) {
                $table .= "<th class='center'  style='width: 70px;'>" . $row_rsPrInv['l_year'] . "</th>";

                $camoA[$mon] = 0;
                $camo1A[$mon] = 0;
                $mcl_balA[$mon] = 0;
                $mearn1A[$mon] = 0;
                $mon = $mon + 1;
            }




            $table .= "</tr>";


            $bF = 0;
            $cgroup = "";
            $cgroup1 = "";
            $cl_bal = 0;
            $mcl_bal = 0;
            $camo = 0;
            $camo1 = 0;
            $camoA[] = 0;
            $camo1A[] = 0;
            $mcl_balA[] = 0;
            $sql_rsPrInv = "select L_CODE,l_name as C_NAME,c_group AS C_SUBGRO1,c_group1 AS C_SUBGRO2 from tmp_pnl_mont  group by l_code,l_name,c_group,c_group1 order by   C_SUBGRO1 desc,C_SUBGRO2,l_code  ";
            $mon = $mon - 1;
            foreach ($conn->query($sql_rsPrInv) as $row_rsPrInv) {

                if ($cgroup != $row_rsPrInv['C_SUBGRO1']) {
                    $t = 1;
                    if ($cgroup1 != "") {
                        $table .= "<tr><th colspan='2'></th>";
                        while ($t <= $mon) {

                            if ($camo1A[$t] >= 0) {
                                $table .= "<th class='right'>" . number_format($camo1A[$t], 2, ".", ",") . "</th>";
                            } else {
                                $table .= "<th class='right'>(" . number_format(($camo1A[$t] * -1), 2, ".", ",") . ")</th>";
                            }
                            $camo1A[$t] = 0;
                            $t = $t + 1;
                        }
                        $table .= "</tr>";
                    }
                    $t = 1;
                    if ($cgroup != "") {
                        $table .= "<tr><th colspan='2'>Total " . $cgroup . "</th>";
                        while ($t <= $mon) {
                            if ($camoA[$t] >= 0) {
                                $table .= "<th class='right'>" . number_format($camoA[$t], 2, ".", ",") . "</th>";
                            } else {
                                $table .= "<th class='right'>(" . number_format(($camoA[$t] * -1), 2, ".", ",") . ")</th>";
                            }
                            $camoA[$t] = 0;
                            $t = $t + 1;
                        }
                        $table .= "</tr>";
                    }





                    $table .= "<tr><th class='left' colspan='" . $mon . "'>" . $row_rsPrInv['C_SUBGRO1'] . "</th></tr>";
                    $camo = 0;
                    $camo1 = 0;
                    $cgroup1 = "";
                }

                if ($cgroup1 != $row_rsPrInv['C_SUBGRO2']) {
                    if ($cgroup1 != "") {
                        $t = 1;
                        $table .= "<tr><th colspan='2'></th>";
                        while ($t <= $mon) {
                            if ($camo1A[$t] >= 0) {
                                $table .= "<th class='right'>" . number_format($camo1A[$t], 2, ".", ",") . "</th>";
                            } else {
                                $table .= "<th class='right'>(" . number_format(($camo1A[$t] * -1), 2, ".", ",") . ")</th>";
                            }
                            $camo1A[$t] = 0;
                            $t = $t + 1;
                        }
                        $table .= "</tr>";
                    }
                    $table .= "<tr><th></th><th class='left' colspan='" . $mon . "'>" . $row_rsPrInv['C_SUBGRO2'] . "</th></tr>";
                    $camo1 = 0;
                }

                $url = "report_ledger_link.php?txtAccCode=" . $row_rsPrInv["L_CODE"] . "&txtAccName=" . $row_rsPrInv["C_NAME"] . "&repdatefrom=" . $_GET["dtfrom"] . "&repdateto=" . $_GET["dtto"];

                $table .= "<tr><td><a target='_blank' href='" . $url . "'></a></td>
		       <td><a target='_blank' href='" . $url . "'>" . $row_rsPrInv["C_NAME"] . "</a></td>";

                $i = 1;
                $sql = "select l_year from tmp_pnl_mont group by l_year  order by l_year";
                foreach ($conn->query($sql) as $row) {
                    $sql = "select sum(l_amount) as amo from tmp_pnl_mont where   l_code = '" . $row_rsPrInv['L_CODE'] . "' and l_year = '" . $row['l_year'] . "'";
                    $result = $conn->query($sql);
                    $row_1 = $result->fetch();
                    $pen = ($row_1['amo']);
                    if ($pen < 0) {
                        $pen = abs($row_1['amo']);
                    } else {
                        $pen = ($row_1['amo'] * -1);
                    }

                    $camo = $camo + $pen;
                    $camo1 = $camo1 + $pen;


                    $camoA[$i] = $camoA[$i] + $pen;
                    $camo1A[$i] = $camo1A[$i] + $pen;

                    $cl_bal = $bF + $pen;
                    $mcl_bal = $mcl_bal + $cl_bal;
                    $mcl_balA[$i] = $mcl_balA[$i] + $cl_bal;
                    $i = $i + 1;
                    if ($cl_bal >= 0) {
                        $table .= "<td class='right'>" . number_format($cl_bal, 2, ".", ",") . "</td>";
                    } else {
                        $table .= "<td class='right'>(" . number_format(-1 * $cl_bal, 2, ".", ",") . ")</td>";
                    }
                }
                $cgroup = $row_rsPrInv['C_SUBGRO1'];
                $cgroup1 = $row_rsPrInv['C_SUBGRO2'];
                $table .= "</tr>";
            }



            $table .= "<tr><th colspan='2'></th>";
            $t = 1;
            while ($t <= $mon) {
                if ($camo1A[$t] >= 0) {
                    $table .= "<th class='right'>" . number_format($camo1A[$t], 2, ".", ",") . "</th>";
                } else {
                    $table .= "<th class='right'>(" . number_format(($camo1A[$t] * -1), 2, ".", ",") . ")</th>";
                }
                $t = $t + 1;
            }
            $table .= "</tr>";

            $table .= "<tr><th colspan='2'>Total " . $cgroup . "</th>";
            $t = 1;
            while ($t <= $mon) {
                if ($camoA[$t] >= 0) {
                    $table .= "<th class='right'>" . number_format($camoA[$t], 2, ".", ",") . "</th>";
                } else {
                    $table .= "<th class='right'>(" . number_format(($camoA[$t] * -1), 2, ".", ",") . ")</th>";
                }
                $t = $t + 1;
            }
            $table .= "</tr>";

            $table .= "<tr><th  class='left' colspan=2>Current Year Income/Loss</th>";
            $t = 1;
            while ($t <= $mon) {
                if ($mcl_balA[$t] >= 0) {
                    $table .= "<th class='right'>" . number_format($mcl_balA[$t], 2, ".", ",") . "</th>";
                } else {
                    $table .= "<th class='right'>(" . number_format(($mcl_balA[$t] * -1), 2, ".", ",") . ")</th>";
                }
                $t = $t + 1;
            }
            $table .= "</tr>";



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
    $pdf_name = "PNLBS" . "_" . (date('Ymd g i'));


    $file_location = "pdfReports/" . $pdf_name . ".pdf";
    file_put_contents($file_location, $pdf);


     $data = file_get_contents($file_location);
  header("Content-type: application/octet-stream");
  header("Content-disposition: attachment;filename=YOURFILE.pdf");

  echo $data;





                }

                 if ($_GET['view1']=="excel")  {






				 $file = "rpt_pnlbs.xls";

        $f = fopen($file, "w+");

        fwrite($f, $table);


             $data = file_get_contents($file);
  header("Content-type: application/octet-stream");
  header("Content-disposition: attachment;filename=PNLS.xls");

		 echo $data;


                }
        } else {




            $ayear = ac_year($_GET["dtfrom"]);
            $dtb = $ayear . "-04-01";


            $sql = "update ledger set l_amount1 = l_amount where l_flag1 = 'DEB'";
            $result = $conn->query($sql);

            $sql = "update ledger set l_amount1 = l_amount *-1 where l_flag1 = 'CRE'";
            $result = $conn->query($sql);

            $sql = "select acyear from ledger where (l_date>='" . $_GET["dtfrom"] . "' and l_date<='" . $_GET['dtto'] . "') group by acyear  order by acyear";
            foreach ($conn->query($sql) as $row) {

                $sql_rsPrInv = "select pos,month(l_date) as l_month,year(l_date) as l_year,l_code,C_NAME,sum(L_AMOUNT1)as amo, C_SUBGRO1,C_SUBGRO2   from view_ledger  where c_type ='B' and acyear = '" . $row['acyear'] . "' and l_yearfl='0'  group by pos,l_code,C_NAME,C_SUBGRO1,C_SUBGRO2,month(l_date),year(l_date) order by  C_SUBGRO1 desc,C_SUBGRO2,l_code ";
                foreach ($conn->query($sql_rsPrInv) as $row_rsPrInv) {
                    //$mmonth = $row_rsPrInv['l_year'] . "-" . $row_rsPrInv['l_month'] . "-01";
                    $ayear = $row['acyear'];

                    $usr[] = "('" . $row_rsPrInv['pos'] . "','" . $ayear . "','" . $row_rsPrInv['L_CODE'] . "','" . $row_rsPrInv['C_NAME'] . "','" . (trim($row_rsPrInv['C_SUBGRO1'])) . "','" . (trim($row_rsPrInv['C_SUBGRO2'])) . "','" . $row_rsPrInv['amo'] . "')";
                }
            }

            $sql = "delete from tmp_pnl_mont";
            $conn->query($sql);

            $sql = "insert into tmp_pnl_mont (pos,l_year,l_code,l_name,c_group,c_group1,l_amount) values " . implode($usr, ",");
            $conn->query($sql);




            $txtdes = "Monthly Balance Sheet As at : " . date("Y-m-d", strtotime($_GET["dtto"]));



            // $table .= "<h3>" . $row_rspara['COMPANY'] . "</h3>";
            // $table .= "<h4>" . $txtdes . "</h4>";

  
             $heading3 = $row_rspara['COMPANY'] ;
            $heading4 = $txtdes;
            
            
            $table .= "<table id='testTable' class='table' >
            <tr >
                    <th colspan='3' class='center'  style='width: 90px;'>$heading3</th>
                    
                    </tr>
                    <tr>
                    <th colspan='3' class='center'  style='width: 90px;'>$heading4</th>
                    
                    </tr>





           

      	<tr>
        <th class='center'  style='width: 10px;'>Ledger</th>
        <th class='center'  style='width: 300px;'>Account Description</th>";
            $sql = "select l_year from tmp_pnl_mont group by l_year order by l_year";
            $mon = 1;
            foreach ($conn->query($sql) as $row_rsPrInv) {
                $table .= "<th class='center'  style='width: 70px;'>" . $row_rsPrInv['l_year'] . "</th>";

                $camoA[$mon] = 0;
                $camo1A[$mon] = 0;
                $mcl_balA[$mon] = 0;
                $mearn1A[$mon] = 0;
                $mon = $mon + 1;
            }




            $table .= "</tr>";


            $bal = 0;
            $mcl_bal = 0;




            $mearn = 0;



            $bF = 0;
            $cgroup = "";
            $cgroup1 = "";
            $camo = 0;
            $camo1 = 0;
            $camoA[] = 0;
            $camo1A[] = 0;
            $mcl_balA[] = 0;
            $sql_rsPrInv = "select L_CODE,l_name as C_NAME,c_group AS C_SUBGRO1,c_group1 AS C_SUBGRO2 from tmp_pnl_mont  group by l_code,l_name,c_group,c_group1,pos order by  pos ,c_group1,l_code ";
            $mon = $mon - 1;
            foreach ($conn->query($sql_rsPrInv) as $row_rsPrInv) {


                if ($cgroup != $row_rsPrInv['C_SUBGRO1']) {
                    $t = 1;
                    if ($cgroup1 != "") {
                        $table .= "<tr><th colspan='2'></th>";
                        while ($t <= $mon) {

                            if ($camo1A[$t] >= 0) {
                                $table .= "<th class='right'>" . number_format($camo1A[$t], 2, ".", ",") . "</th>";
                            } else {
                                $table .= "<th class='right'>(" . number_format(($camo1A[$t] * -1), 2, ".", ",") . ")</th>";
                            }
                            $camo1A[$t] = 0;
                            $t = $t + 1;
                        }
                        $table .= "</tr>";
                    }
                    $t = 1;
                    if ($cgroup != "") {
                        $table .= "<tr><th colspan='2'>Total " . $cgroup . "</th>";
                        while ($t <= $mon) {
                            if ($camoA[$t] >= 0) {
                                $table .= "<th class='right'>" . number_format($camoA[$t], 2, ".", ",") . "</th>";
                            } else {
                                $table .= "<th class='right'>(" . number_format(($camoA[$t] * -1), 2, ".", ",") . ")</th>";
                            }
                            $camoA[$t] = 0;
                            $t = $t + 1;
                        }
                        $table .= "</tr>";
                    }


                    if ($cgroup == "Liabilities") {
                        $t = 1;
                        $table .= "<tr><th colspan='2'>Net Assets & Liabilities</th>";
                        while ($t <= $mon) {
                            if ($mcl_balA[$t] >= 0) {
                                $table .= "<th class='right'>" . number_format($mcl_balA[$t], 2, ".", ",") . "</th>";
                            } else {
                                $table .= "<th class='right'>(" . number_format(($mcl_balA[$t] * -1), 2, ".", ",") . ")</th>";
                            }
                            $mcl_balA[$t] = 0;
                            $t = $t + 1;
                        }
                        $table .= "</tr>";
                    }


                    $table .= "<tr><th class='left' colspan='3'>" . $row_rsPrInv['C_SUBGRO1'] . "</th></tr>";
                    $camo = 0;
                    $camo1 = 0;
                    $cgroup1 = "";
                }

                if ($cgroup1 != $row_rsPrInv['C_SUBGRO2']) {
                    if ($cgroup1 != "") {
                        $t = 1;
                        $table .= "<tr><th colspan='2'></th>";
                        while ($t <= $mon) {
                            if ($camo1A[$t] >= 0) {
                                $table .= "<th class='right'>" . number_format($camo1A[$t], 2, ".", ",") . "</th>";
                            } else {
                                $table .= "<th class='right'>(" . number_format(($camo1A[$t] * -1), 2, ".", ",") . ")</th>";
                            }
                            $camo1A[$t] = 0;
                            $t = $t + 1;
                        }
                        $table .= "</tr>";
                    }
                    $table .= "<tr><th></th><th class='left' colspan='2'>" . $row_rsPrInv['C_SUBGRO2'] . "</th></tr>";
                    $camo1 = 0;
                }





                $url = "report_ledger_link.php?txtAccCode=" . $row_rsPrInv["L_CODE"] . "&txtAccName=" . $row_rsPrInv["C_NAME"] . "&repdatefrom=" . $_GET["dtfrom"] . "&repdateto=" . $_GET["dtto"];

                $table .= "<tr><td><a target='_blank' href='" . $url . "'></a></td>
		               <td><a target='_blank' href='" . $url . "'>" . $row_rsPrInv["C_NAME"] . "</a></td>";


                $i = 1;
                $sql = "select l_year from tmp_pnl_mont group by l_year  order by l_year";
                foreach ($conn->query($sql) as $row) {


                    $ayear = $row['l_year'];

                    $mearn1A[$i] = 0;
                    $sql = "select  sum(L_AMOUNT1) as amo ,C_SUBGRO1 from view_ledger where C_SUBGRO1 <> '' ";
                    $sql .= " and acyear ='" . $ayear . "' and l_yearfl='0' group by C_SUBGRO1";

                    foreach ($conn->query($sql) as $row_1) {
                        if (trim($row_1['C_SUBGRO1']) == "Revenue") {
                            $mearn1A[$i] = $mearn1A[$i] + $row_1['amo'] * -1;
                        }
                        if (trim($row_1['C_SUBGRO1']) == "Expenses") {
                            $mearn1A[$i] = $mearn1A[$i] - $row_1['amo'];
                        }
                    }

                    $mearn =0;
                    if ($ayear < 2016) {
                    $sql = "select  sum(L_AMOUNT1) as amo ,C_SUBGRO1 from view_ledger where C_SUBGRO1 <> '' ";
                    $sql .= " and acyear <'" . $ayear . "' and l_yearfl='0' group by C_SUBGRO1";

                    foreach ($conn->query($sql) as $row_1) {
                        if (trim($row_1['C_SUBGRO1']) == "Revenue") {
                            $mearn = $mearn1A[$i] + $row_1['amo'] * -1;
                        }
                        if (trim($row_1['C_SUBGRO1']) == "Expenses") {
                            $mearn = $mearn1A[$i] - $row_1['amo'];
                        }
                    }
                    }


                    $sql = "select sum(l_amount) as amo from tmp_pnl_mont where   l_code = '" . $row_rsPrInv['L_CODE'] . "' and l_year = '" . $row['l_year'] . "'";
                    $result = $conn->query($sql);
                    $row_1 = $result->fetch();


                    $pen = ($row_1['amo']);



                    if ($row_rsPrInv['L_CODE'] == "TDL-7000.002") {
                        $pen = $pen +  $mearn;

                    }

                    if ($row_rsPrInv['C_SUBGRO1'] == "Equity") {
                        $pen = abs($pen);
                    }

                    $cl_bal = $bF + $pen;

                    if ($cl_bal >= 0) {
                        $table .= "<td class='right'>" . number_format($cl_bal, 2, ".", ",") . "</td>";
                    } else {
                        $table .= "<td class='right'>(" . number_format(-1 * $cl_bal, 2, ".", ",") . ")</td>";
                    }


                    $mcl_bal = $mcl_bal + $cl_bal;
                    $mcl_balA[$i] = $mcl_balA[$i] + $cl_bal;


                    $camo = $camo + $pen;
                    $camo1 = $camo1 + $pen;


                    $camoA[$i] = $camoA[$i] + $pen;
                    $camo1A[$i] = $camo1A[$i] + $pen;

                    $i = $i + 1;
                }

                $cgroup = $row_rsPrInv['C_SUBGRO1'];
                $cgroup1 = $row_rsPrInv['C_SUBGRO2'];

                $table .= "</tr>";
            }


            $table .= "<tr><td></td><td  class='left'>Current Year Income/Loss</td>";
            $t = 1;
            while ($t <= $mon) {
                $table .= "<td  class='right'>" . number_format($mearn1A[$t], 2, ".", ",") . "</td>";
                $camo1A[$t] = $camo1A[$t] + $mearn1A[$t];
                $camoA[$t] = $camoA[$t] + $mearn1A[$t];
                $t = $t + 1;
            }
            $table .= "</tr>";


            $table .= "<tr><th colspan='2'></th>";
            $t = 1;
            while ($t <= $mon) {
                if ($camo1A[$t] >= 0) {
                    $table .= "<th class='right'>" . number_format($camo1A[$t], 2, ".", ",") . "</th>";
                } else {
                    $table .= "<th class='right'>(" . number_format(($camo1A[$t] * -1), 2, ".", ",") . ")</th>";
                }
                $t = $t + 1;
            }
            $table .= "</tr>";

            $table .= "<tr><th colspan='2'>Total " . $cgroup . "</th>";
            $t = 1;
            while ($t <= $mon) {
                if ($camoA[$t] >= 0) {
                    $table .= "<th class='right'>" . number_format($camoA[$t], 2, ".", ",") . "</th>";
                } else {
                    $table .= "<th class='right'>(" . number_format(($camoA[$t] * -1), 2, ".", ",") . ")</th>";
                }
                $t = $t + 1;
            }
            $table .= "</tr></table>



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
    $pdf_name = "PNLBS" . "_" . (date('Ymd g i'));


    $file_location = "pdfReports/" . $pdf_name . ".pdf";
    file_put_contents($file_location, $pdf);


     $data = file_get_contents($file_location);
  header("Content-type: application/octet-stream");
  header("Content-disposition: attachment;filename=YOURFILE.pdf");

  echo $data;





                }

                 if ($_GET['view1']=="excel")  {






				 $file = "rpt_pnlbs.xls";

        $f = fopen($file, "w+");

        fwrite($f, $table);


             $data = file_get_contents($file);
  header("Content-type: application/octet-stream");
  header("Content-disposition: attachment;filename=PNLS.xls");

		 echo $data;


                }




        }
    }
    ?>
    <script>

                            var tableToExcel = (function() {
            var uri = 'data:application/vnd.ms-excel;base64,'
                    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
                                        , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
                                , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
                                                             return function(table, name) {
                                                                                                                            if (!table.nodeType) table = document.getElementById(table)
                                                                                              var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
                                                                                                                window.location.href = uri + base64(format(template, ctx))
                                                                                                }
                                                                                              })()
</script>
