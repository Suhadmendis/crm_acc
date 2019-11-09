<?php
session_start();

require_once ("connectioni.php");

header('Content-Type: text/xml');

date_default_timezone_set('Asia/Colombo');

if ($_GET["Command"] == "getdt") {

	$tb = "";
	$tb .= "<table class='table table-hover'>";

	if ($_GET["ls"] == "new") {
		$sql = "select * from masterclient order by id desc limit 50";
	} else {

		if ($_GET["ls"] == "code") {
			$sql = "select * from MasterAirPort where code like '" . Trim($_GET["txt_aircode"]) . "%' order by id desc limit 50";
		} 
		if ($_GET["ls"] == "name") {		
			$sql = "select * from MasterAirPort where port like '" . Trim($_GET["txt_airname"]) . "%' order by id desc limit 50";
		}
		if ($_GET["ls"] == "country") {		
			$sql = "select * from MasterAirPort where COUNTRY like '" . Trim($_GET["txt_country"]) . "%' order by id desc limit 50";
		}
		if ($_GET["ls"] == "town") {		
			$sql = "select * from MasterAirPort where TOWN like '" . Trim($_GET["txt_town"]) . "%' order by id desc limit 50";
		}
		if ($_GET["ls"] == "other") {		
			$sql = "select * from MasterAirPort where OTHER like '" . Trim($_GET["txt_airname"]) . "%' order by id desc limit 50";
		}


	}
	 
	$result = mysqli_query($GLOBALS['dbinv'], $sql);
	IF ($result) {
		echo mysqli_error($GLOBALS['dbinv']);
	}
	$tb .= "<tr>";
	$tb .= "<td style=\"width: 350px;\"></td>";
	$tb .= "<td style=\"width: 500px;\"></td>";
	$tb .= "</tr>";

	while ($row = mysqli_fetch_array($result)) {
		$tb .= "<tr>";
		$tb .= "<td onclick=\"getcode('" . $row['Clid'] . "')\">" . $row['Clid'] . "</td>";
		$tb .= "<td onclick=\"getcode('" . $row['ClName'] . "')\">" . $row['ClName'] . "</td>";		
		$tb .= "</tr>";
	}
	$tb .= "</table>";

	echo $tb;
}

if ($_GET["Command"] == "getcode") {

	$ResponseXML = "";
	$ResponseXML .= "<salesdetails>";

	$sql = "select * from MasterAirPort where code = '" . Trim($_GET["code"]) . "' limit 1";
	 
	
	$result = mysqli_query($GLOBALS['dbinv'], $sql);
	IF ($row = mysqli_fetch_array($result)) {
		$ResponseXML .= "<code><![CDATA[" . $row['CODE'] . "]]></code>";
		$ResponseXML .= "<port><![CDATA[" . $row['PORT'] . "]]></port>";
		$ResponseXML .= "<country><![CDATA[" . $row['COUNTRY'] . "]]></country>";
		$ResponseXML .= "<town><![CDATA[" . $row['TOWN'] . "]]></town>";
		$ResponseXML .= "<other><![CDATA[" . $row['OTHER'] . "]]></other>";
	}
	$ResponseXML .= "</salesdetails>";

	echo $ResponseXML;

}


?>