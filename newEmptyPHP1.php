<?php
include "search_custom_data.php";
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Pending Orders Report</title>

        <style>
            table
            {
                border-collapse:collapse;
            }
            table, td, th
            {
                border:1px solid black;
                font-family:Arial, Helvetica, sans-serif;
                padding:5px;
            }
            th
            {
                font-weight:bold;
                font-size:10px;

            }
            td
            {
                font-size:10px;

            }
        </style>

    </head>

    <body>

        <?php
        $stt = "<center><h3>Pending Orders</h3></center>";

        $serverName = "192.168.1.57";



        $connectionInfo = array("Database" => "WHEELS", "UID" => "sa", "PWD" => "123", "ReturnDatesAsStrings" => true);

        $conn = sqlsrv_connect($serverName, $connectionInfo);


        $sql_invp = "select * from invpara";
        $result_para = sqlsrv_query($conn, $sql_invp);
        if ($row_result_para = sqlsrv_fetch_array($result_para, SQLSRV_FETCH_ASSOC)) {
            $stt .= "<center><h4>" . $row_result_para['COMPANY'] . "</h4></center>";
        }


        $stt .= "<center><table border='1'>";

        $stt .= "<thead>";



        $stt .= "<tr><th width='50'>Dealer Code</th>"
                . "<th width='250'>Dealer Name</th>"
                . "<th width='50'>Pending Amo.</th>"
                . "<th width='50'>Limit</th>"
                . "<th width='200'>Balance</th>";
        $stt .= "</tr>";
        $stt .= "</thead>";



        $stt .= "<tbody>";
        $sql = "select C_CODE, CUS_NAME,SUM(PRICE * (QTY - rec_qty)) as amo from view_orders";
        $sql .= " WHERE     (brand <> 'AW US Racing LP')";
        $sql .= " GROUP BY C_CODE, CUS_NAME";

        $mstkno = "";
        $result_mas = sqlsrv_query($conn, $sql);
        while ($row_result_mas = sqlsrv_fetch_array($result_mas, SQLSRV_FETCH_ASSOC)) {

            $stt .= "<tr><td>" . $row_result_mas['C_CODE'] . "</td>"
                    . "<td>" . $row_result_mas['CUS_NAME'] . "</td>"
                    . "<td>" . $row_result_mas['amo'] . "</td>";

            $mout = get_cusout($row_result_mas['C_CODE'], '');

            $stt .= "<td>" . $mout . "</td>";


            $sql = "Select * from vendor where code='" . $cuscode . "' ";
            $mlimt = 0;
            $sql = sqlsrv_query($conn, $sql);
            if ($row = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {


                if (!is_null($row['LIMIT'])) {
                    $mlimt = $row['LIMIT'];
                }
                if (!is_null($row['limit1'])) {
                    $mlimt = $mlimt + $row['limit1'];
                }

                $stt .= "<td>" . $mlimt . "</td>";
            } 
            $stt .= "<td>" . ($mlimt - $mout) . "</td>";


            $stt .= "</tr>";
        }





        $stt .= "</tbody>";





        $stt .= "</table>";

        echo $stt;
        ?>





    </body>
</html>
