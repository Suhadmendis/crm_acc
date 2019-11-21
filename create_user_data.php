<?php
session_start();

////////////////////////////////////////////// Database Connector /////////////////////////////////////////////////////////////
 
require './connection_sql.php';
////////////////////////////////////////////// Write XML ////////////////////////////////////////////////////////////////////
header('Content-Type: text/xml');



/////////////////////////////////////// GetValue //////////////////////////////////////////////////////////////////////////
///////////////////////////////////// Registration /////////////////////////////////////////////////////////////////////////

if ($_GET["Command"] == "select_permission") {
    header('Content-Type: text/xml');
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";



    $ResponseXML = "";


    $ResponseXML .= " <salesdetails>";

    $ResponseXML .= "<balance_table><![CDATA[<table width=\"1000\" border=\"0\" class=\"form-matrix-table\">";
    $ResponseXML .= "<table width=\"1000\" border=\"1\" class=\"form-matrix-table\">";

    $i = 1;
    $ResponseXML .= " <tr>
      <th scope=\"col\"  width=\"300px\"><input type=\"text\"  class=\"label_purchase\" value=\"Form Name\" disabled=\"disabled\"/></th>
      <th scope=\"col\"><input type=\"text\"  class=\"label_purchase\" value=\"Category\" disabled=\"disabled\"/></th>
	  <th scope=\"col\"><input type=\"text\"  class=\"label_purchase\" value=\"View\" disabled=\"disabled\"/></th>
      <th scope=\"col\"><input type=\"text\"  class=\"label_purchase\" value=\"Feed\" disabled=\"disabled\"/></th>
      <th scope=\"col\"><input type=\"text\"  class=\"label_purchase\" value=\"Modify\" disabled=\"disabled\"/></th>
      <th scope=\"col\"><input type=\"text\"  class=\"label_purchase\" value=\"Price Edit\" disabled=\"disabled\"/></th>
      <th scope=\"col\"><input type=\"text\"  class=\"label_purchase\" value=\"Print\" disabled=\"disabled\"/></th>
    </tr>";

    $sql1 = "Select * from doc order by docid";
    $result1 = $db->RunQuery($sql1);
    while ($row1 = mysql_fetch_array($result1)) {
        $chkview = "chkview" . $i;
        $chkfeed = "chkfeed" . $i;
        $chkmod = "chkmod" . $i;
        $chkprice = "chkprice" . $i;
        $chkprint = "chkprint" . $i;



        $sql = "Select * from userpermission   where username='" . trim($_GET["user_name"]) . "' and docid=" . $row1["docid"] . "";
        $result = $db->RunQuery($sql);
        if ($row = mysql_fetch_array($result)) {
            $ResponseXML .= "	<tr>
      					<td>" . $row1["docid"] . ". " . $row1["docname"] . "</td>
						<td>" . $row1["grp"] . "</td>";
            if ($row["doc_view"] == 1) {
                $ResponseXML .= "<td align=\"center\"><input type=\"checkbox\" name=\"" . $chkview . "\" id=\"" . $chkview . "\" checked=\"checked\" /></td>";
            } else {
                $ResponseXML .= "<td align=\"center\"><input type=\"checkbox\" name=\"" . $chkview . "\" id=\"" . $chkview . "\" /></td>";
            }

            if ($row["doc_feed"] == 1) {
                $ResponseXML .= "<td align=\"center\"><input type=\"checkbox\" name=\"" . $chkfeed . "\" id=\"" . $chkfeed . "\" checked=\"checked\"/></td>";
            } else {
                $ResponseXML .= "<td align=\"center\"><input type=\"checkbox\" name=\"" . $chkfeed . "\" id=\"" . $chkfeed . "\" /></td>";
            }

            if ($row["doc_mod"] == 1) {
                $ResponseXML .= "<td align=\"center\"><input type=\"checkbox\" name=\"" . $chkmod . "\" id=\"" . $chkmod . "\"checked=\"checked\" /></td>";
            } else {
                $ResponseXML .= "<td align=\"center\"><input type=\"checkbox\" name=\"" . $chkmod . "\" id=\"" . $chkmod . "\" /></td>";
            }
            if ($row["price_edit"] == 1) {
                $ResponseXML .= "<td align=\"center\"><input type=\"checkbox\" name=\"" . $chkprice . "\" id=\"" . $chkprice . "\" checked=\"checked\"/></td>";
            } else {
                $ResponseXML .= "<td align=\"center\"><input type=\"checkbox\" name=\"" . $chkprice . "\" id=\"" . $chkprice . "\" /></td>";
            }
            if ($row["doc_print"] == 1) {
                $ResponseXML .= "<td align=\"center\"><input type=\"checkbox\" name=\"" . $chkprint . "\" id=\"" . $chkprint . "\" checked=\"checked\"/></td>";
            } else {
                $ResponseXML .= "<td align=\"center\"><input type=\"checkbox\" name=\"" . $chkprint . "\" id=\"" . $chkprint . "\" /></td>";
            }


            $ResponseXML .= "</tr>";
        } else {
            $ResponseXML .= "	<tr>
      					<td>" . $row1["docid"] . ". " . $row1["docname"] . "</td>";


            $ResponseXML .= "<td align=\"center\"><input type=\"checkbox\" name=\"" . $chkview . "\" id=\"" . $chkview . "\" /></td>";
            $ResponseXML .= "<td align=\"center\"><input type=\"checkbox\" name=\"" . $chkfeed . "\" id=\"" . $chkfeed . "\" /></td>";
            $ResponseXML .= "<td align=\"center\"><input type=\"checkbox\" name=\"" . $chkmod . "\" id=\"" . $chkmod . "\" /></td>";
            $ResponseXML .= "<td align=\"center\"><input type=\"checkbox\" name=\"" . $chkprice . "\" id=\"" . $chkprice . "\" /></td>";
            $ResponseXML .= "<td align=\"center\"><input type=\"checkbox\" name=\"" . $chkprint . "\" id=\"" . $chkprint . "\" /></td>";


            $ResponseXML .= "</tr>";
        }
        $i = $i + 1;
    }

    $ResponseXML .= "   </table>]]></balance_table>";
    $ResponseXML .= "<mcount><![CDATA[" . $i . "]]></mcount>";

    $ResponseXML .= " </salesdetails>";
    echo $ResponseXML;
}

if ($_GET["Command"] == "save_inv") {


    $sql = "select * from user_mast where user_name='" . $_GET["user_name"] . "'";
    $result = $conn->query($sql);
    if ($row1 = $result->fetch()) {
        echo "User Found !!!";
    } else {
        $sql = "insert into user_mast(user_name, user_pass) values ('" . $_GET["user_name"] . "', '" . md5($_GET["pass1"]) . "')";
        $result = $conn->query($sql);
        echo "Saved";
    }
}
?>