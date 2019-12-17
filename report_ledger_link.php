<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Ledger Accounts Details</title>
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
        </style><style>
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
        include('connection_sql.php');
        require_once './gl_posting.php';
        date_default_timezone_set('Asia/Colombo');

        $sql_rspara = "select * from COMPANY_INFO";
        $result = $conn->query($sql_rspara);
        $row_rspara = $result->fetch();

        $sql = "delete from ledprint ";
        $result = $conn->query($sql);
        $txtAccCode = $_GET["txtAccCode"];

        $sql = "select * from lcodes where c_code = '" . $txtAccCode . "'";
        $result = $conn->query($sql);
        if ($row = $result->fetch()) {
            $ac_type = $row['C_TYPE'];
        }


        $ayear = ac_year($_GET["repdatefrom"]);
        $dtb = $ayear . "-04-01";


        $OpDbAmu = 0;
        $OpCrAmu = 0;
        $OpBalAm = 0;
        $OpDbAmu = 0;
        $OpLnkAmt = 0;

        $sql_opCR = "select sum(l_amount)as ctot from ledger where  l_flag1='CRE' and l_code='" . $txtAccCode . "' and (l_date<'" . $_GET["repdatefrom"] . "'  ) and l_yearfl='0'";
        if ($ac_type != "B") {
            $sql_opCR .= " and acyear ='" . $ayear . "'";
        }


        $result = $conn->query($sql_opCR);
        if ($row_opCR = $result->fetch()) {
            $OpCrAmu = $OpCrAmu + $row_opCR["ctot"];
        }

        $sql_opDb = "select sum(l_amount)as dtot from ledger where  l_flag1='DEB' and l_code='" . $txtAccCode . "'  and (l_date<'" . $_GET["repdatefrom"] . "' ) and l_yearfl='0' ";
        if ($ac_type != "B") {
            $sql_opDb .= " and acyear ='" . $ayear . "'";
        }
        $result = $conn->query($sql_opDb);
        if ($row_opDb = $result->fetch()) {
            $OpDbAmu = $OpDbAmu + $row_opDb["dtot"];
        }


        $bF = $OpBalAm + $OpDbAmu - $OpCrAmu + $OpLnkAmt;


        $txtdes = " Account : </b> " . $_GET["txtAccCode"] . " - " . $_GET["txtAccName"] . "   " . "<br><b>Date From</b> - " . $_GET["repdatefrom"] . " <b>To</b> " . $_GET["repdateto"];
        ;
        $table = "";
        $table .= "<h3>" . $row_rspara['COMPANY'] . "</h3>";
        $table .= "<h4>" . $txtdes . "</h4>";

        echo $table;



        $DEB_amt = 0;
        $CRE_amt = 0;



        echo "<table  class='table'>

      	<tr>
        <th width=\"70\">Date</th>
        <th width=\"200\">Refno</th>
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

        echo "<tr><td>" . $_GET["repdatefrom"] . "</td>
				<td>B/F</td>
				<td>Opening Balance</td>";

        if ($bF > 0) {
            echo "<td align=right>" . number_format($bF, 2, ".", ",") . "</td>";
            echo "<td></td>";
            $totdebamt = $totdebamt + $bF;
        } else {
            echo "<td></td>";
            echo "<td align=right>" . number_format($bF, 2, ".", ",") . "</td>";
            $totcreamt = $totcreamt + $bF;
        }

        if ($bF > 0) {
            echo "<td align=right>" . number_format($bF, 2, ".", ",") . "</td>";
        } else {
            echo "<td align=right>(" . number_format((-1 * $bF), 2, ".", ",") . ")</td>";
        }


        $baltot = $baltot + $bF;

        echo "</tr>";


        $sql_rsPrInv = "select * from ledger where    acyear = '" . $ayear . "' and l_code = '" . $txtAccCode . "' and ( l_date >='" . $_GET["repdatefrom"] . "') and  ( l_date <= '" . $_GET["repdateto"] . "' ) and l_yearfl='0' order by L_DATE,l_refno";

        //$sql_rsPrInv = "select *  from ledprint order by sdate, refno";
        foreach ($conn->query($sql_rsPrInv) as $row_rsPrInv) {

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



            $bal = $debamt - $creamt;
            $baltot = $baltot + $bal;



            echo "<tr><td>" . $row_rsPrInv["L_DATE"] . "</td>
				<td>" . $row_rsPrInv["L_REFNO"] . "</td>
				<td>" . $row_rsPrInv["L_LMEM"] . "</td>";

            echo "<td align=right>" . number_format($debamt, 2, ".", ",") . "</td>";

            echo "<td align=right>" . number_format($creamt, 2, ".", ",") . "</td>";

            if ($baltot > 0) {
                echo "<td align=right>" . number_format($baltot, 2, ".", ",") . "</td>";
            } else {
                echo "<td align=right>(" . number_format((-1 * $baltot), 2, ".", ",") . ")</td>";
            }
            echo "</tr>";


            $totdebamt = $totdebamt + $debamt;
            $totcreamt = $totcreamt + $creamt;
        }
        echo "<tr><td colspan=3>&nbsp;</td><td align=right><b>" . number_format($totdebamt, 2, ".", ",") . "</b></td><td align=right><b>" . number_format($totcreamt, 2, ".", ",") . "</b></td>";
        if ($baltot > 0) {
            echo "<td align=right><b>" . number_format($baltot, 2, ".", ",") . "</b></td></tr>";
        } else {
            echo "<td align=right><b>(" . number_format((-1 * $baltot), 2, ".", ",") . ")</b></td></tr>";
        }
        ?>



        </table>
    </body>
</html>
