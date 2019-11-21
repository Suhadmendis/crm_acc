<?php

session_start();







include_once("connectioni.php");

if ($_GET["Command"] == "update_list") {
    $ResponseXML = "";
    $ResponseXML .= "<table class=\"table\">
	            <tr>
                        <th width=\"121\">Item No</th>
                        <th width=\"424\"> Item Description </th>
                        
                        <th width=\"121\">Amount</th>  
                    </tr>";


    $sql = "SELECT * from itemmasterentry where itcode <> ''";
    if ($_GET['refno'] != "") {
        $sql .= " and itcode like '%" . $_GET['refno'] . "%'";
    }
    if ($_GET['cusname'] != "") {
        $sql .= " and itname like '%" . $_GET['cusname'] . "%'";
    }
    $stname = $_GET['stname'];

    $sql .= " ORDER BY itcode limit 50";
    $result = mysqli_query($GLOBALS['dbinv'], $sql);
    while ($row = mysqli_fetch_array($result)) {
        $cuscode = $row["itcode"];


        $ResponseXML .= "<tr>               
                              <td onclick=\"itno('$cuscode', '$stname');\">" . $row['itcode'] . "</a></td>
                              <td onclick=\"itno('$cuscode', '$stname');\">" . $row['itname'] . "</a></td>
                              <td onclick=\"itno('$cuscode', '$stname');\">" . $row['price'] . "</a></td>
                            </tr>";
    }
    $ResponseXML .= "</table>";
    echo $ResponseXML;
}

if ($_GET["Command"] == "search_item") {



    $ResponseXML = "";




    $ResponseXML .= "<table width=\"735\" border=\"0\" class=\"form-matrix-table\">
                            <tr>
                              <td width=\"121\"  background=\"images/headingbg.gif\" ><strong><font color=\"#FFFFFF\">Item No</font></strong></td>
                              <td width=\"424\"  background=\"images/headingbg.gif\"><strong><font color=\"#FFFFFF\">Item Description</font></strong></td>
                               <td width=\"150\"  background=\"images/headingbg.gif\"><strong><font color=\"#FFFFFF\">Brand Name</font></strong></td>
                              <td width=\"150\"  background=\"images/headingbg.gif\"><strong><font color=\"#FFFFFF\">Stock In Hand</font></strong></td>
                             <td width=\"150\"  background=\"images/headingbg.gif\"><strong><font color=\"#FFFFFF\">Price</font></strong></td>
   							</tr>";


    $sql = "SELECT * from s_mas where stk_no <> ''";
    if ($_GET["mstatus"] == "itno") {
        $letters = $_GET['itno'];
        $sql .= " and STK_NO like  '$letters%'";
    } elseif ($_GET["mstatus"] == "itemname") {
        $letters = $_GET['itemname'];
        $sql .= " and DESCRIPT like  '$letters%'";
    } else {
        $letters = $_GET['itemname'];
        $sql .= " and DESCRIPT like  '$letters%'";
    }
    if ($_GET["checkbox"] == "true") {
        $sql .= " and QTYINHAND>0";
    }
    if ($_GET["brand"] != "All") {
        $sql .= " and BRAND_NAME ='" . $_GET["brand"] . "'";
    }
    $sql .= " order by STK_NO ";

    $sql = mysqli_query($GLOBALS['dbinv'], $sql);



    while ($row = mysqli_fetch_array($sql)) {

        $ResponseXML .= "<tr>
                              <td onclick=\"itno('" . $row['STK_NO'] . "');\">" . $row['STK_NO'] . "</a></td>
                              <td onclick=\"itno('" . $row['STK_NO'] . "');\">" . $row['DESCRIPT'] . "</a></td>
							   <td onclick=\"itno('" . $row['STK_NO'] . "');\">" . $row['BRAND_NAME'] . "</a></td>
							   <td onclick=\"itno('" . $row['STK_NO'] . "');\">" . number_format($row['QTYINHAND'], 0, ".", ",") . "</a></td>
							    <td onclick=\"itno('" . $row['STK_NO'] . "');\">" . number_format($row['SELLING'], 2, ".", ",") . "</a></td>";



        $ResponseXML .= "</tr>";
    }

    $ResponseXML .= "   </table>";


    echo $ResponseXML;
    //}
}




$GLOBALS["display_val"] = "";
if ($_GET["Command"] == "pass_itno") {
    header('Content-Type: text/xml');
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";

    $sql = mysqli_query($GLOBALS['dbinv'], "Select * from itemmasterentry where itcode='" . $_GET['itno'] . "' ") or die(mysqli_error());
    if ($row = mysqli_fetch_array($sql)) {


        $ResponseXML .= "<STK_NO><![CDATA[" . $row['itcode'] . "]]></STK_NO>";
        $ResponseXML .= "<DESCRIPT><![CDATA[" . $row['itname'] . "]]></DESCRIPT>";
    }
    display();

    //$ResponseXML .= display();

    $ResponseXML .= $GLOBALS["display_val"];

    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}

function display() {

    $return_val = "<bin_table><![CDATA[<table style=\"width:660px;\" class=\"table\">
			<tr>
                            <th class=\"info\" style=\"width:100px;\">Ref No</th>
                            <th class=\"info\" style=\"width:80px;\">Date</th>
                            <th class=\"info\" style=\"width:130px;\">Document Type</th>
                            <th class=\"info\" style=\"width:70px;\">Stk In</th>
                            <th class=\"info\" style=\"width:70px;\">Stk Out</th>
                            <th class=\"info\" style=\"width:70px;\">Stk Bal</th>
                        </tr>";




    $sql = mysqli_query($GLOBALS['dbinv'], "select * from s_trn where  STK_NO='" . $_GET["itno"] . "' and LEDINDI!='GINR' and LEDINDI!='GINI' and LEDINDI!='VGI' and LEDINDI!='VGR' ORDER BY sdate,id") or die(mysqli_error());






    $i = 0;
    $M_BAL = 0;
    while ($row = mysqli_fetch_array($sql)) {


        $refno = $row["REFNO"];
        $sdate = $row["SDATE"];
        $doc_type = "";
        $fcolor = "";
        $url ="";

        if ($row["LEDINDI"] == "ARN") {
            
            $sql_i = "select * from s_purmas where refno = '" . $refno  . "'";
            $result_i = mysqli_query($GLOBALS['dbinv'],$sql_i);
            $row_i = mysqli_fetch_array($result_i);
            
            $url = "pr_print.php?tmp_no=" . $row_i['tmp_no'] . "&action=";
            
            $doc_type = "Purchase Received";
            $fcolor = "#ff0000";
        }

        if ($row["LEDINDI"] == "IOU") {
            
            $sql_i = "select * from inreq_mas where refno = '" . $refno  . "'";
            $result_i = mysqli_query($GLOBALS['dbinv'],$sql_i);
            $row_i = mysqli_fetch_array($result_i);
            
            $url = "issue_print.php?tmp_no=" . $row_i['tmp_no'] . "&action=";
            
            $doc_type = "Issued";
            $fcolor = "#00ff00";
        }

        if ($row["LEDINDI"] == "IIN") {
            
            $sql_i = "select * from inreq_mas where refno = '" . $refno  . "'";
            $result_i = mysqli_query($GLOBALS['dbinv'],$sql_i);
            $row_i = mysqli_fetch_array($result_i);
            
            $url = "issue_print.php?tmp_no=" . $row_i['tmp_no'] . "&action=";
            
            
            $doc_type = "Return";
            $fcolor = "#0000ff";
        }

        //==stock out
        $qty4 = 0;
        if (($row["LEDINDI"] == "INV") or ( $row["LEDINDI"] == "ORC") or ( $row["LEDINDI"] == "GINI") or ( $row["LEDINDI"] == "ARR") or ( $row["LEDINDI"] == "IOU")) {
            $qty4 = $row["QTY"];
            $M_BAL = $M_BAL - $row["QTY"];
            ;
        }

        //===stock in
        $qty3 = 0;
        if (($row["LEDINDI"] == "ARN") or ( $row["LEDINDI"] == "GINR") or ( $row["LEDINDI"] == "CRN") or ( $row["LEDINDI"] == "GRN") or ( $row["LEDINDI"] == "IIN")) {
            $qty3 = $row["QTY"];
            $M_BAL = $M_BAL + $row["QTY"];
        }
        $qty5 = $M_BAL;


        $return_val .= "<tr  bgcolor=\"#ffffff\" >
                        <td onclick=\"load_home('" . $url . "');\"><span style='color:" . $fcolor . ";'>" . $refno . "</span></td>
                        <td onclick=\"load_home('" . $url . "');\"><span style='color:" . $fcolor . ";'>" . $sdate . "</span></td>
                        <td onclick=\"load_home('" . $url . "');\"><span style='color:" . $fcolor . ";'>" . $doc_type . "</span></td>
                        <td onclick=\"load_home('" . $url . "');\"><span style='color:" . $fcolor . ";'>" . number_format($qty3, 0, ".", ",") . "</span></td>
                        <td onclick=\"load_home('" . $url . "');\"><span style='color:" . $fcolor . "; align:right;'>" . number_format($qty4, 0, ".", ",") . "</span></td>
                        <td onclick=\"load_home('" . $url . "');\"><span style='color:" . $fcolor . "; align:right;'>" . number_format($M_BAL, 0, ".", ",") . "</span></td>
                </tr>	";

        $i = $i + 1;
    }



    $i = 1;
    while ($i < 15) {
        $return_val .= "<tr >
	  						<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>	";
        $i = $i + 1;
    }
    $return_val .= "</table>]]></bin_table>";

    $GLOBALS["display_val"] = $return_val;
}

mysqli_close($GLOBALS['dbinv']);
?>
