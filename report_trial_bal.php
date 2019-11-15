<?php session_start();
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Trial Balance</title>


    <style>
        @media print {
            a[href]:after {
                content: none !important;
            }
        }
        a:link, a:visited {

            text-decoration: none;
            color:#000000;
        }
        

        a:hover {
            text-decoration: underline;
        }
        body {
            color: #333;
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            font-size: 12px;
            line-height: 1.42857;
        }
        .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
            border-top:1px solid #DDDDDD;
            line-height:1.42857;
            padding:2px;
            vertical-align:top;
        }
        .right {
            text-align: right;
        }    
        .center {
            text-align: center;
        }
    </style>
</head>

<body> 
    <?php
    require_once ("./connection_sql.php");
    require_once './gl_posting.php';


    $sql_rspara = "select * from COMPANY_INFO";
    $result = $conn->query($sql_rspara);
    $row_rspara = $result->fetch();

    $txtdes = "TB Report From : " . date("Y-m-d", strtotime($_GET["dtfrom"])) . "    To: " . date("Y-m-d", strtotime($_GET["dtto"]));
    
    
    $table = "";
      $table .=  "<h3>" .  $row_rspara['COMPANY']  . "</h3>";
      $table .=  "<h4>" .  $txtdes  . "</h4>";

    
  
   
  

    $DEB_amt = 0;
    $CRE_amt = 0;



    $table .= "<table class='table' >
		
      	<tr>
        <th class='center'  style='width: 90px;'>Ledger</th>
        <th class='center'  style='width: 300px;'>Account Description</th>
        <th class='center'  style='width: 90px;'>Opening Balance</th>
	<th class='center'  style='width: 90px;'>Debit</th>
        <th class='center'  style='width: 90px;'>Credit</th>
	<th class='center' style='width: 90px;'>Closing Balance</th>     		
        </tr>";

    $deb = 0;
    $cre = 0;

    $bal = 0;
    $totbal = 0;
    $cl_deb = 0;
    $cl_cre = 0;

    $open_tot_deb = 0;
    $open_tot_cre = 0;
    $mcl_bal = 0;



    $ayear = ac_year($_GET["dtfrom"]);
    $dtb = $ayear . "-04-01";


    $sql = "update ledger set l_amount1 = l_amount where l_flag1 = 'DEB'";
    $result = $conn->query($sql);

    $sql = "update ledger set l_amount1 = l_amount *-1 where l_flag1 = 'CRE'";
    $result = $conn->query($sql);



    $mearn = 0;
    $sql = "select  sum(L_AMOUNT1) as amo ,C_SUBGRO1 from view_ledger where C_SUBGRO1 <> '' ";
    $sql .= " and acyear <'" . $ayear . "' group by C_SUBGRO1";

    foreach ($conn->query($sql) as $row_rsPrInv) {
        if (trim($row_rsPrInv['C_SUBGRO1']) == "Revenue") {
            $mearn = $mearn - $row_rsPrInv['amo'] * -1;
        }
        if (trim($row_rsPrInv['C_SUBGRO1']) == "Expenses") {
            $mearn = $mearn + $row_rsPrInv['amo'];
        }
    }




      $sql_rsPrInv = "select *  from lcodes   order by C_TYPE,c_code";

	$ac_type ="";
    foreach ($conn->query($sql_rsPrInv) as $row_rsPrInv) {
        if ($row_rsPrInv["C_GROUP"] == "D") {

if ($ac_type != $row_rsPrInv['C_TYPE']) {
				 $table .= "<tr><td colspan=6>" . $row_rsPrInv['C_TYPE'] . "</th></tr>";
			}	


            $ac_type = $row_rsPrInv['C_TYPE'];



            $bF = 0;
            $OpCrAmu = 0;
            $OpDbAmu = 0;
            $OpBalAm = 0;
            $OpLnkAmt = 0;

            $sql_opCR = "select sum(l_amount)as ctot from ledger where  l_flag1='CRE' and l_code='" . $row_rsPrInv["C_CODE"] . "' and (l_date<'" . $_GET["dtfrom"] . "'  )";
            if ($ac_type != "B") {
                $sql_opCR .= " and acyear ='" . $ayear . "'";
            }


            $result = $conn->query($sql_opCR);
            if ($row_opCR = $result->fetch()) {
                $OpCrAmu = $OpCrAmu + $row_opCR["ctot"];
            }

            $sql_opDb = "select sum(l_amount)as dtot from ledger where  l_flag1='DEB' and l_code='" . $row_rsPrInv["C_CODE"] . "'  and (l_date<'" . $_GET["dtfrom"] . "' ) ";
            if ($ac_type != "B") {
                $sql_opDb .= " and acyear ='" . $ayear . "'";
            }
            $result = $conn->query($sql_opDb);
            if ($row_opDb = $result->fetch()) {
                $OpDbAmu = $OpDbAmu + $row_opDb["dtot"];
            }
            $bF = $OpBalAm + $OpDbAmu - $OpCrAmu + $OpLnkAmt;


            ////////  
            $DbAmu = 0;
            $CrAmu = 0;
            $sql_opCR = "select sum(l_amount)as ctot from ledger where  l_flag1='CRE'  and acyear ='" . $ayear . "' and l_code='" . $row_rsPrInv["C_CODE"] . "' and (l_date>='" . $_GET["dtfrom"] . "' and l_date<='" . $_GET['dtto'] . "')";
            if ($ac_type != "B") {
                $sql_opCR .= " and acyear ='" . $ayear . "'";
            }
            $result = $conn->query($sql_opCR);
            if ($row_opCR = $result->fetch()) {
                $CrAmu = $row_opCR["ctot"];
            }

            $sql_opDb = "select sum(l_amount)as dtot from ledger where  l_flag1='DEB' and acyear ='" . $ayear . "' and l_code='" . $row_rsPrInv["C_CODE"] . "'  and (l_date>='" . $_GET["dtfrom"] . "'  and l_date<='" . $_GET['dtto'] . "' ) ";
             if ($ac_type != "B") {
                $sql_opDb .= " and acyear ='" . $ayear . "'";
            }
            $result = $conn->query($sql_opDb);
            if ($row_opDb = $result->fetch()) {
                $DbAmu = $row_opDb["dtot"];
            }
            $pen = $DbAmu - $CrAmu;

            ////////////////////////////// 


            if (($bF != 0) or (($pen != 0) or ($CrAmu>0 or $DbAmu>0) )) {
                $url = "report_ledger_link.php?txtAccCode=" . $row_rsPrInv["C_CODE"] . "&txtAccName=" . $row_rsPrInv["C_NAME"] . "&repdatefrom=" . $_GET["dtfrom"] . "&repdateto=" . $_GET["dtto"];

                $table .= "<tr><td><a target='_blank' href='" . $url . "'>" . $row_rsPrInv["C_CODE"] . "</a></td>
			<td><a target='_blank' href='" . $url . "'>" . $row_rsPrInv["C_NAME"] . "</a></td>";

                if ($row_rsPrInv['C_CODE'] == "TDL-7000.002") {
                    $bF = $bF + $mearn;
                }

                $open_tot_deb = $open_tot_deb + $bF;
                if ($bF >= 0) {
                    $table .= "<td class='right'>" . number_format($bF, 2, ".", ",") . "</td>";
                } else {
                    $table .= "<td class='right'>(" . number_format((-1 * $bF), 2, ".", ",") . ")</td>";
                }

                //echo $bF;
                //echo $row_rsPrInv["pen"];
               
                     
                        if ($DbAmu>0) {
                        $table .= "<td class='right'>" . number_format($DbAmu, 2, ".", ",") . "</td>";
                        } else {
                        $table .= "<td class='right'></td>";    
                        }
                        
                        
                        if ($CrAmu>0) {
                        $table .= "<td class='right'>" . number_format($CrAmu, 2, ".", ",") . "</td>";
                        } else {
                        $table .= "<td class='right'></td>";    
                        }
                       
                        
                        
                        $deb = $deb + $DbAmu;
                        $cre = $cre + $CrAmu;
                    
                
                $bal = $bal + $bF + $pen;

                $cl_bal = $bF + $pen;

                if ($cl_bal >= 0) {
                    $table .= "<td class='right'>" . number_format($cl_bal, 2, ".", ",") . "</td>";
                } else {
                    $table .= "<td class='right'>(" . number_format(-1 * $cl_bal, 2, ".", ",") . ")</td>";
                }

                $mcl_bal = $mcl_bal + $cl_bal;

                $table .= "</tr>";
            }
        } else {
            $table .= "<tr><th>" . $row_rsPrInv["C_CODE"] . "</th>
                           <th colspan=5>" . $row_rsPrInv["C_NAME"] . "</th>";
        }
    }
    $cre_plus = -1 * $cre;
    
    
    $table .= "<tr><td colspan=2>&nbsp;</td>
		<th  class='right'>" . number_format($open_tot_deb, 2, ".", ",") . "</th>
		<th  class='right'>" . number_format($deb, 2, ".", ",") . "</th>
		<th  class='right'>" . number_format($cre_plus, 2, ".", ",") . "</th>
		<th  class='right'>" . number_format($mcl_bal, 2, ".", ",") . "</th></tr>";
    $table .= "</table>
 
</body>
</html>";

    echo $table;

    