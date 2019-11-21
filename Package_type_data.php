<?php
session_start();

require_once ("connectioni.php");

header('Content-Type: text/xml');

date_default_timezone_set('Asia/Colombo');

if ($_GET["Command"] == "getdt") {

	$tb = "";
	$tb .= "<table class='table table-hover'>";

	if ($_GET["ls"] == "new") {
		$sql = "select * from Package_type order by id desc limit 50";
	} else {

		if ($_GET["ls"] == "pack_type") {
			$sql = "select * from Package_type where packtype like '" . Trim($_GET["txt_packtype"]) . "%' order by id desc limit 50";
		} 
		if ($_GET["ls"] == "description") {		
			$sql = "select * from Package_type where description like '" . Trim($_GET["txt_description"]) . "%' order by id desc limit 50";
		}
		if ($_GET["ls"] == "refcode") {		
			$sql = "select * from Package_type where refcode like '" . Trim($_GET["txt_refcode"]) . "%' order by id desc limit 50";
		}

	}
	 
	$result = mysqli_query($GLOBALS['dbinv'], $sql);
	IF (!$result) {
		echo mysqli_error($GLOBALS['dbinv']);
	}
	$tb .= "<tr>";
	$tb .= "<td style=\"width: 350px;\"></td>";
	$tb .= "<td style=\"width: 350px;\"></td>";
	$tb .= "<td style=\"width: 350px;\"></td>";
	$tb .= "</tr>";

	while ($row = mysqli_fetch_array($result)) {
		$tb .= "<tr>";
		$tb .= "<td onclick=\"getcode('" . $row['packtype'] . "')\">" . $row['packtype'] . "</td>";
		$tb .= "<td onclick=\"getcode('" . $row['packtype'] . "')\">" . $row['description'] . "</td>";
		$tb .= "<td onclick=\"getcode('" . $row['packtype'] . "')\">" . $row['refcode'] . "</td>";
		
		$tb .= "</tr>";
	}
	$tb .= "</table>";

	echo $tb;
}

if ($_GET["Command"] == "getcode") {

	$ResponseXML = "";
	$ResponseXML .= "<salesdetails>";

	$sql = "select * from Package_type where code = '" . Trim($_GET["packtype"]) . "' limit 1";
	 
	
	$result = mysqli_query($GLOBALS['dbinv'], $sql);
	IF ($row = mysqli_fetch_array($result)) {
		$ResponseXML .= "<packtype><![CDATA[" . $row['packtype'] . "]]></packtype>";
		$ResponseXML .= "<description><![CDATA[" . $row['description'] . "]]></description>";
		$ResponseXML .= "<refcode><![CDATA[" . $row['refcode'] . "]]></refcode>";
	}
	$ResponseXML .= "</salesdetails>";

	echo $ResponseXML;

}


?>