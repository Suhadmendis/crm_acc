<?php session_start();
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Outstanding Report</title>


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
            font-size: 10px;
            line-height: 1.32857;
        }
        .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
            border-top:1px solid #DDDDDD;
            line-height:1.2857;
            padding:1.5px;

            vertical-align:top;
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
        .table1 {
            border-collapse: collapse;
            border: 1px;
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
    </style>
</head>



<body> 
    <?php
    require_once ("./connection_sql.php");
    require_once './gl_posting.php';


    $sql_rspara = "select * from COMPANY_INFO";
    $result = $conn->query($sql_rspara);
    $row_rspara = $result->fetch();

    if ($_GET['optionsRadios'] == "op_statment") {




        $table = "";
        $table .= "<table   style='width: 660px;' class='table1'>
            <tr>
                <th class='bottom head' align='left'><img src='images/logo.JPG'>&nbsp;</th>
                <th class='bottom head' align='left' colspan='3'>&nbsp;" . $row_rspara['COMPANY'] . "</th> 
            </tr>
    
            <tr>               
               <th align='center' colspan='4'>" . $row_rspara['ADD1'] . " " . $row_rspara['ADD2'] . "</th> 
            </tr>


            <tr>
                  
                 <th class='bottom head' align='center' colspan='4'>Tel  :" . $row_rspara['TELE'] . " Fax :" . $row_rspara['FAX'] . "</th>
            </tr>

            <tr>
              <th colspan='4' ><center>Outstanding Statment as at " . $_GET["dtfrom"] . "</th>
               
            </tr></table>";

        $sql = "select * from vendor where code = '" . $_GET['c_code'] . "'";
        $result_ven = $conn->query($sql);
        $row_ven = $result_ven->fetch();

        $table .= "<tr>
                <th style='width: 660px;' colspan = '4' class='left'>&nbsp;</th>   
                 
            </tr>";

        $table .= "<table style='width: 660px;' class='table1'><tr>
                <th style='width: 80px;' class='left'>Customer :</th>
                <th style='width: 50px;' class='left'>" . $row_ven['CODE'] . "</th>
                <th style='width: 550px;'  class='left' colspan='2'>" . $row_ven['NAME'] . "</th>   
                 
            </tr>";
        $table .= "<tr>
                <th style='width: 30px;' class='left'></th>
                <th style='width: 50px;' colspan ='3' class='left'>" . $row_ven['ADD1'] . " " . $row_ven['ADD2'] . "</th>
                 
            </tr>";

        $table .= "</table><table style='width: 660px;' class='table1' border='1'><tr>
                <th style='width: 660px;' colspan = '4' class='left'>&nbsp;</th>   
                 
            </tr>";

        $sql = "select ref_no,c_code,sum(amo) as amo,name,type from view_c_master where ref_no <> ''";

        //if ($_GET['currency'] != "All") {
            $sql .= " and cur ='" . $_GET['currency'] . "'";
        //}

        if ($_GET['type'] != "All") {
            $sql .= " and type ='" . $_GET['type'] . "'";
        }
        $sql .= " and c_code= '" . $_GET['c_code'] . "'";
        $sql .= " group by ref_no,c_code,name,type having sum(amo) <>0 order by c_code,ref_no";

        $table .= "<table border='1'  style='width: 660px;' class='table1'>	
      	<tr>
        <th class='center' style='width: 70px;'>Date</th>
        <th class='center' style='width: 80px;'>Inv No</th>
	<th class='center' style='width: 20px;'>Days</th>
	<th class='center' style='width: 30px;'>Cur</th> 
	<th class='center' style='width: 90px;'>Invoice Value</th> 
	<th class='center' style='width: 90px;'>Outstanding</th>
        <th class='center' style='width: 90px;'>Running Balance</th>
        </tr>";
        $mamo = 0;
        foreach ($conn->query($sql) as $row) {

            if (abs($row['amo']) >= 0.01) {
                if ($row['type'] == "DEB") {
                    $sql = "select * from s_salma where ref_no = '" . $row['ref_no'] . "'";
                    $result = $conn->query($sql);
                    $row_s = $result->fetch();
                    $amo = $row_s['curamo'];
                    $sdate = $row_s['SDATE'];
                    $cur = $row_s['cur'];
                  
                }

                if ($row['type'] == "CRE") {
                    $sql = "select * from c_bal where refno = '" . $row['ref_no'] . "'";
                    $result = $conn->query($sql);
                    $row_s = $result->fetch();
                    $amo = $row_s["77777"] * -1;
                    $sdate = $row_s['SDATE'];
                    $cur = $row_s['CUR'];
                    
                  
                }
                $date1 = $_GET['dtfrom'];
                $date2 = $sdate;

                $diff = abs(strtotime($date2) - strtotime($date1));
                $days = floor($diff / (60 * 60 * 24));
                $table .= "
                <tr>
                <td>" . $sdate . "</td>
                <td>" . $row['ref_no'] . "</td>
                 
                <td class='center' >" . $days . "</td>
                <td>" . $cur . "</td> 
                <td class='right'>" . number_format($amo, 2, ".", ",") . "</th> 
                <td class='right'>" . number_format($row['amo'], 2, ".", ",") . "</td>";
                $mamo = $mamo + $row['amo'];
                $table .= "<td class='right'>" . number_format($mamo, 2, ".", ",") . "</td></tr>";
            }
        }
        $table .= "<tr><th class='center' colspan='7' style='width: 70px;'></th></tr>";
        $table .= "</table>";
        
        $table .= "<table  style='width: 660px;' class='table1'>
        <tr>
            <td style='width: 660px;' colspan='4'>&nbsp;</td>            
        </tr>
                

        <tr>
        <td colspan='2' class='left'>Name of Account :</td><td colspan='2' class='left'>TDL Holdings (Pvt) Limited </td>";
        if ($_GET['currency'] == "LKR") {
             $table .= "<tr><td style='width: 120px;'>Bank         :</td><td colspan='2'>Sampath Bank PLC</td></tr>";
        $table .= "<tr><td style='width: 120px;'>Account Number  :</td><td colspan='2'>0029 3002 8625</td></tr>";  
        $table .= "<tr><td style='width: 120px;'>Swift Code          :</td><td colspan='2'>BSAMLKLX</td></tr>";
        $table .= "</table>";
        } else {
            $table .= "<tr><td style='width: 120px;'>Bank         :</td><td colspan='2'>Sampath Bank PLC</td></tr>";
             $table .= "<tr><td style='width: 120px;'>Account Number  :</td><td colspan='2'>5029 30000 8708</td></tr>";    
         $table .= "<tr><td style='width: 120px;'>Swift Code          :</td><td colspan='2'>BSAMLKLX</td></tr>";
        
        
        $table .= "</table>";
            
        }
        
        $table .= "<table  style='width: 660px;' class='table1'>";
        $table .= "<tr><td>Appreciate your prompt action in settling the above outstanding. If you have already settle "
                . "<br>the above invoices, please ignore this statment</td></tr>";
        $table .= "<tr><td>Please quote invoice numbers when remitting</td>";
    }


    if ($_GET['optionsRadios'] == "op_summery") {
        $txtdes = "Outstanding Statment As At : " . date("Y-m-d", strtotime($_GET["dtfrom"]));


        $table = "";
        $table .= "<h3>" . $row_rspara['COMPANY'] . "</h3>";
        $table .= "<h4>" . $txtdes . "</h4>";

        $table .= "<table class='table' >	
      	<tr>
        <th class='center' style='width: 70px;'>Date</th>
        <th class='center' style='width: 80px;'>Inv No</th>
        <th class='center' style='width: 50px;'>Job No</th>
	<th class='center' style='width: 50px;'>Cus Code</th>
        <th class='center' style='width: 220px;'>Customer</th>
	<th class='center' style='width: 20px;'>Days</th>
	<th class='center' style='width: 30px;'>Cur</th> 
	<th class='center' style='width: 90px;'>Invoice Value</th> 
	<th class='center' style='width: 90px;'>Outstanding</th>
        <th class='center' style='width: 90px;'>30 Days</th>
        <th class='center' style='width: 90px;'>60 Days</th>
        <th class='center' style='width: 90px;'>90 Days</th>
        <th class='center' style='width: 90px;'>120 Days</th>
        <th class='center' style='width: 90px;'>Over 120 Days</th>
        </tr>";

        $m_code = "";
        $mcamo = 0;
        $mcamo1 = 0;
        $mamo = 0;
        $mamo1 = 0;
        $ma30 = 0;
        $ma60 = 0;
        $ma90 = 0;
        $ma120 = 0;
        $mao120 = 0;


        $sql = "select ref_no,c_code,sum(amo) as amo,name,type from view_c_master where ref_no <> '' and sdate<='" . $_GET['dtfrom'] . "'";

        if ($_GET['currency'] != "All") {
            $sql .= " and cur ='" . $_GET['currency'] . "'";
        }

        if ($_GET['type'] != "All") {
            $sql .= " and type ='" . $_GET['type'] . "'";
        }
        $sql .= " group by ref_no,c_code,name,type having sum(amo) <>0 order by c_code,ref_no";


        foreach ($conn->query($sql) as $row) {

            if (abs($row['amo']) >= 0.01) {
                if ($row['type'] == "DEB") {
                    $sql = "select * from s_salma where ref_no = '" . $row['ref_no'] . "'";
                    $result = $conn->query($sql);
                    $row_s = $result->fetch();
                    $amo = $row_s['curamo'];
                    if ($_GET['currency'] == "All") {
                        $amo = $row_s['curamo']*$row_s['rate'];
                    }
                    $sdate = $row_s['SDATE'];
                    $cur = $row_s['cur'];
                    $rate = $row_s['rate'];
                }

                if ($row['type'] == "CRE") {
                    $sql = "select * from c_bal where refno = '" . $row['ref_no'] . "'";
                    $result = $conn->query($sql);
                    $row_s = $result->fetch();
                    $amo = $row_s['curamo'] * -1;
                    if ($_GET['currency'] == "All") {
                        $amo = ($row_s['curamo']*$row_s['RATE']) * -1;
                    }
                    $rate = $row_s['RATE'];
                    $sdate = $row_s['SDATE'];
                    $cur = $row_s['CUR'];
                }

                if ($m_code != $row['c_code']) {
                    if ($m_code != "") {
                        $table .= "<tr><td colspan='7'></td><th class='right'>" . number_format($mcamo, 2, ".", ",") . "</th><th class='right'>" . number_format($mcamo1, 2, ".", ",") . "</th><th class='right'>" . number_format($mc30, 2, ".", ",") . "</th>
                <th class='right'>" . number_format($mc60, 2, ".", ",") . "</th>
                <th class='right'>" . number_format($mc90, 2, ".", ",") . "</th>
                <th class='right'>" . number_format($mc120, 2, ".", ",") . "</th>
                <th class='right'>" . number_format($mco120, 2, ".", ",") . "</th><tr>";
                    }
                    $mcamo = 0;
                    $mcamo1 = 0;

                    $mc30 = 0;
                    $mc60 = 0;
                    $mc90 = 0;
                    $mc120 = 0;
                    $mco120 = 0;
                }


                $m_code = $row['c_code'];

                $date1 = $_GET['dtfrom'];
                $date2 = $sdate;

                $diff = abs(strtotime($date2) - strtotime($date1));
                $days = floor($diff / (60 * 60 * 24));
                $bal = $row['amo'];
                
                 if ($_GET['currency'] == "All") {
                     $bal = $row['amo'] * $rate;
                 }         

                $table .= "
                <tr>
                <td>" . $sdate . "</td>
                <td>" . $row['ref_no'] . "</td>
                <td></td>
                <td>" . $row['c_code'] . "</td>
                <td>" . $row['NAME'] . "</td>
                <td class='center' >" . $days . "</td>
                <td>" . $cur . "</td> 
                <td class='right'>" . number_format($amo, 2, ".", ",") . "</th> 
                <td class='right'>" . number_format($bal, 2, ".", ",") . "</td>";

                $m30 = 0;
                $m60 = 0;
                $m90 = 0;
                $m120 = 0;
                $mo120 = 0;



                if ($days <= 30) {
                    $m30 = $bal;
                    $mc30 = $mc30 + $bal;
                    $ma30 = $ma30 + $bal;
                }
                if ($days > 30 and $days <= 60) {
                    $m60 = $bal;
                    $ma60 = $ma60 + $bal;
                    $mc60 = $mc60 + $bal;
                }
                if ($days > 60 and $days <= 90) {
                    $m90 = $bal;
                    $mc90 = $mc90 + $bal;
                    $ma90 = $ma90 + $bal;
                }
                if ($days > 90 and $days <= 120) {
                    $m120 = $bal;
                    $mc120 = $mc120 + $bal;
                    $ma120 = $ma120 + $bal;
                }
                if ($days > 120) {
                    $mo120 = $bal;
                    $mco120 = $mco120 + $bal;
                    $mao120 = $mao120 + $bal;
                }

                $table .= "<td class='right'>" . number_format($m30, 2, ".", ",") . "</td>
                <td class='right'>" . number_format($m60, 2, ".", ",") . "</td>
                <td class='right'>" . number_format($m90, 2, ".", ",") . "</td>
                <td class='right'>" . number_format($m120, 2, ".", ",") . "</td>
                <td class='right'>" . number_format($mo120, 2, ".", ",") . "</td>";
                $table .= "</tr>";

                $mcamo = $mcamo + $amo;
                $mcamo1 = $mcamo1 + $bal;
                $mamo = $mamo + $amo;
                $mamo1 = $mamo1 + $bal;
            }
        }
        $table .= "<tr><td colspan='7'></td><th class='right'>" . number_format($mcamo, 2, ".", ",") . "</th><th class='right'>" . number_format($mcamo1, 2, ".", ",") . "</th><th class='right'>" . number_format($mc30, 2, ".", ",") . "</th>
                <th class='right'>" . number_format($mc60, 2, ".", ",") . "</th>
                <th class='right'>" . number_format($mc90, 2, ".", ",") . "</th>
                <th class='right'>" . number_format($mc120, 2, ".", ",") . "</th>
                <th class='right'>" . number_format($mco120, 2, ".", ",") . "</th><tr>";
        $table .= "<tr><td colspan='7'></td><th class='right'>" . number_format($mamo, 2, ".", ",") . "</th><th class='right'>" . number_format($mamo1, 2, ".", ",") . "</th><th class='right'>" . number_format($ma30, 2, ".", ",") . "</th>
                <th class='right'>" . number_format($ma60, 2, ".", ",") . "</th>
                <th class='right'>" . number_format($ma90, 2, ".", ",") . "</th>
                <th class='right'>" . number_format($ma120, 2, ".", ",") . "</th>
                <th class='right'>" . number_format($mao120, 2, ".", ",") . "</th><tr>";
    }



    echo $table;
    