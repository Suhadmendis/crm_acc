<?php

session_start();

////////////////////////////////////////////// Database Connector /////////////////////////////////////////////////////////////
require_once ("connection_sql.php");

////////////////////////////////////////////// Write XML ////////////////////////////////////////////////////////////////////
header('Content-Type: text/xml');

date_default_timezone_set('Asia/Colombo');

if (isset($_GET["Command"])) {
    if ($_GET["Command"] == "getrecdt") {


        $dbgrid = "<table class=\"table\"><tr>
                        <th style=\"width: 120px;\">Ref No</th>
                        <th style=\"width: 150px;\">Date</th>
                        <th style=\"width: 150px;\">Cheque No</th>
                        <th style=\"width: 120px;\">Amount</th>
                        <th style=\"width: 80px;\">Rec.</th>
                        <th style=\"width: 80px;\">Date</th> 
                        <th>Detail</th>
                    </tr>";
        $crgrid = "<table class=\"table\"><tr>
                        <th style=\"width: 120px;\">Ref No</th>
                        <th style=\"width: 150px;\">Date</th>
                        <th style=\"width: 150px;\">Cheque No</th>
                        <th style=\"width: 120px;\">Amount</th>
                        <th style=\"width: 80px;\">Rec.</th>
                        <th style=\"width: 80px;\">Date</th> 
                        <th>Detail</th>
                        <th style=\"width: 40px;\">ID</th>
                    </tr>";

        $dtpDate = $_GET['dtto'];
        $dtpDate1 = date("Y-m", strtotime($_GET["dtto"]));
        $dtpDate1 .= "-01";
        
        $month1 = date("m", strtotime($_GET["dtto"]));
        $month1_y = date("Y", strtotime($_GET["dtto"]));


        $sql = "Select * from ledger where ((month(l_date)<'" . $month1 . "' and l_FLAG2='1' and year(l_date)<='" . $month1_y . "' and ( (l_month> " . $month1 . " and l_year= " . $month1_y . ")) or l_year> " . $month1_y . "  ) OR (month(l_date)<'" . $month1 . "' and l_FLAG2='0' and year(l_date)<='" . $month1_y . "' )OR (YEAR(l_date)<'" . $month1_y . "' and l_FLAG2='0' ) OR month(l_date)='" . $month1 . "' OR (l_year='" . $month1_y . "' and l_month='" . $month1 . "')) and l_flag<>'OPB' and L_FLAG1='DEB' and l_code='" . Trim($_GET['bank']) . "' order by l_date";
        $i = 1;
        foreach ($conn->query($sql) as $row) {

            $id_deb = "id_deb" . $i;
            $dt_deb = "dt_deb" . $i;
            if (($row['L_FLAG2'] == 0 or $row['tmpRecDate'] >= $dtpDate or $row['tmpRecDate']>=$dtpDate1) and ( $row['L_DATE'] <= $dtpDate)) {
                $dbgrid .= "<tr>
                        <td>" . $row['L_REFNO'] . "</td>
                        <td>" . $row['L_DATE'] . "</td>
                        <td>" . $row['chno'] . "</td>
                        <td align='right'>" . number_format($row['curamo'], 2, ".", ",") . "</td>";


                if (!is_null($row['tmpRecDate'])) {
                    if ($row['tmpRecDate'] > $dtpDate) {
                        $dbgrid .= "<td><input type='checkbox' id = '" . $dt_deb . "'></td>";
                    } else {
                        $dbgrid .= "<td><input type='checkbox' checked id = '" . $dt_deb . "'></td>";
                    }
                } else {
                    $dbgrid .= "<td><input type='checkbox'  id = '" . $dt_deb . "'></td>";
                }


                $dbgrid .= "<td>" . $row['tmpRecDate'] . "</td><td>" . $row['L_LMEM'] . "</td>
                        <td><input id ='" . $id_deb . "' value='" . $row['ID'] . "'></td>
                    </tr>";
                $i = $i + 1;
            }
        }

        // echo $sql;
        // echo ":: AKILA ::";

        $sql = "Select * from ledger where ((month(l_date)<'" . $month1 . "' and l_FLAG2='1' and year(l_date)<='" . $month1_y . "' and ( (l_month> " . $month1 . " and l_year= " . $month1_y . ")) or l_year> " . $month1_y . "  ) OR (month(l_date)<'" . $month1 . "' and l_FLAG2='0' and year(l_date)<='" . $month1_y . "' )OR (YEAR(l_date)<'" . $month1_y . "' and l_FLAG2='0' ) OR month(l_date)='" . $month1 . "' OR (l_year='" . $month1_y . "' and l_month='" . $month1 . "')) and l_flag<>'OPB' and L_FLAG1='cre' and l_code='" . Trim($_GET['bank']) . "' order by l_date";
        $t = 1;
        foreach ($conn->query($sql) as $row) {
            $id_cre = "id_cre" . $t;
            $dt_cre = "dt_cre" . $t;

            if (($row['L_FLAG2'] == 0 or $row['tmpRecDate'] >= $dtpDate or $row['tmpRecDate']>=$dtpDate1) and ( $row['L_DATE'] <= $dtpDate)) {
                $crgrid .= "<tr>
                        <td>" . $row['L_REFNO'] . "</td>
                        <td>" . $row['L_DATE'] . "</td>
                        <td>" . $row['chno'] . "</td>
                         <td align='right'>" . number_format($row['curamo'], 2, ".", ",") . "</td>";

                if (!is_null($row['tmpRecDate'])) {

                    if ($row['tmpRecDate'] > $dtpDate) {
                        $crgrid .= "<td><input type='checkbox'  id = '" . $dt_cre . "'></td>";
                    } else {
                        $crgrid .= "<td><input type='checkbox' checked id = '" . $dt_cre . "'></td>";
                    }
                } else {
                    $crgrid .= "<td><input type='checkbox'  id = '" . $dt_cre . "'></td>";
                }

                $crgrid .= "<td>" . $row['tmpRecDate'] . "</td><td>" . $row['L_LMEM'] . "</td>
                        <td><input id ='" . $id_cre . "' value='" . $row['ID'] . "'></td>
                    </tr>";
                $t = $t + 1;
            }
            // echo $row['tmpRecDate'];
            // echo "AAKK";
        }

        // echo $sql;
        // echo ":: AKILA ::";

        $ResponseXML = "";
        $ResponseXML .= "<salesdetails>";
        $ResponseXML .= "<dbgrid><![CDATA[" . $dbgrid . "]]></dbgrid>";
        $ResponseXML .= "<count><![CDATA[" . $i . "]]></count>";
          
        $sql = "select * from bank_bal  where   bdate='" . $dtpDate . "'  and code='" . Trim($_GET['bank']) . "' order by id desc";
        $sql1 = $conn->query($sql);
        if ($row1 = $sql1->fetch()) {
            $ResponseXML .= "<bank_bal><![CDATA[" . $row1['CLOSE_BALANCE'] . "]]></bank_bal>";               
        } else {
            $ResponseXML .= "<bank_bal><![CDATA[0]]></bank_bal>";
        }
        
        $sql = "select * from bank_bal  where   bdate<'" . $dtpDate . "'  and code='" . Trim($_GET['bank']) . "' order by id desc ";
        $sql1 = $conn->query($sql);
        if ($row1 = $sql1->fetch()) {
            $ResponseXML .= "<last_dt><![CDATA[" . $row1['BDATE'] . "]]></last_dt>";               
        } else {
            $ResponseXML .= "<last_dt><![CDATA[.]]></last_dt>";
        }
        

        
         
        
        
        $ResponseXML .= "<crgrid><![CDATA[" . $crgrid . "]]></crgrid>";
        $ResponseXML .= "<count1><![CDATA[" . $t . "]]></count1>";
        $ResponseXML .= "</salesdetails>";
        echo $ResponseXML;
    }
} else {

    if ($_POST["Command"] == "save_item") {


        $txtBankCode = $_POST['bank'];
        $dtpDate = $_POST['dtto'];
        $txtBankClosBal = $_POST['bank_bal'];

        $month1 = date("m", strtotime($_POST["dtto"]));
        $month1_y = date("Y", strtotime($_POST["dtto"]));


        try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->beginTransaction();
            $i = 1;
            $count = $_POST['count'];
            while ($_POST["count"] > $i) {
                $id_deb = "id_deb" . $i;
                $dt_deb = "dt_deb" . $i;

                if ($_POST[$dt_deb] == '1') {
                    $sql = "update ledger set l_year ='" . $month1_y . "',l_month='" . $month1 . "', l_flag2 = '1',recdate='" . $dtpDate . "',tmprecdate='" . $dtpDate . "' where id ='" . $_POST[$id_deb] . "'";
                } else {
                    $sql = "update ledger set l_year ='',l_month='',l_flag2 = '0',recdate='',tmprecdate = null where id ='" . $_POST[$id_deb] . "'";
                }
                $result = $conn->query($sql);
                $i = $i + 1;
            }

            $i = 1;
            $count = $_POST['count1'];
            while ($_POST["count1"] > $i) {
                $id_cre = "id_cre" . $i;
                $dt_cre = "dt_cre" . $i;

                if ($_POST[$dt_cre] == '1') {
                    $sql = "update ledger set l_year ='" . $month1_y . "',l_month='" . $month1 . "', l_flag2 = '1',recdate='" . $dtpDate . "',tmprecdate='" . $dtpDate . "' where id ='" . $_POST[$id_cre] . "'";
                } else {
                    $sql = "update ledger set l_year ='',l_month='',l_flag2 = '0',recdate='',tmprecdate = null where id ='" . $_POST[$id_cre] . "'";
                }
                $result = $conn->query($sql);

                $i = $i + 1;
            }


            $sql = "delete from bank_bal  where   bdate='" . $dtpDate . "'  and code='" . Trim($_POST['bank']) . "' ";
            $result = $conn->query($sql);

            $sql = "Insert into bank_bal(CODE,  BDATE,  CLOSE_BALANCE, BMonth, BYear) Values 
            ('" . Trim($_POST['bank']) . "', '" . $dtpDate . "','" . $_POST['bank_bal'] . "', '" . $month1 . "', '" . $month1_y . "' )";
            $result = $conn->query($sql);


            $conn->commit();
            echo "Saved";
        } catch (Exception $e) {
            $conn->rollBack();
            echo $e;
        }
    }
}