<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cheque Display</title>
</head>


<body>

<style type="text/css">
<!--




</style>



<?php
 
	include('connection_sql.php');
	
	//$ResponseXML = "";
	//$ResponseXML .= "<salesdetails>";
	
	$txt_bea=str_replace("~", "&", $_GET['txt_bea']);
	
	$sql="select * from cheque_setup where bank_code='" . trim($_GET["com_cas"]) . "'";
	foreach ($conn->query($sql) as $row) {
		echo "<style type=\"text/css\">

.cl".$row["id"]." {
	font-size: ".$row["font_size"]."px;
	left:".$row["left_loc"]."px;
	top:".$row["top_loc"]."px;
	font-family:".$row["font_name"].";
	position:absolute;
}
</style>";
	
	$name="cl".$row["id"];
	
		if ($row["id"]=="1"){
			echo "<div  id=\"".$row["font_name"]."\" class=\"".$name."\" />".substr($_GET["chqdate"], 2, 1)."</div>";
		}
		
		if ($row["id"]=="2"){
			echo "<div  id=\"".$row["font_name"]."\" class=\"".$name."\" />".substr($_GET["chqdate"], 3, 1)."</div>";
		}	
		
		if ($row["id"]=="3"){
			echo "<div  id=\"".$row["font_name"]."\" class=\"".$name."\" />".substr($_GET["chqdate"], 5, 1)."</div>";
		}
		
		if ($row["id"]=="4"){
			echo "<div  id=\"".$row["font_name"]."\" class=\"".$name."\" />".substr($_GET["chqdate"], 6, 1)."</div>";
		}	
		
		if ($row["id"]=="5"){
			echo "<div  id=\"".$row["font_name"]."\" class=\"".$name."\" />".substr($_GET["chqdate"], 8, 1)."</div>";
		}
		
		if ($row["id"]=="6"){
			echo "<div  id=\"".$row["font_name"]."\" class=\"".$name."\" />".substr($_GET["chqdate"], 9, 1)."</div>";
		}	
		
		if ($row["id"]=="7"){
	
			
			if ($_GET["Check1"]=="true"){
				echo "<div  id=\"".$row["font_name"]."\" class=\"".$name."\" />A/C Payee Only</div>";
			}	
		}	
		
		if ($row["id"]=="9"){
			echo "<div  id=\"".$row["font_name"]."\" class=\"".$name."\" />".$txt_bea."</div>";
		}
		
		if ($row["id"]=="10"){
			echo "<div  id=\"".$row["font_name"]."\" class=\"".$name."\" />".$_GET["txt_amoinword"]."</div>";
		}
		
		if ($row["id"]=="12"){
			echo "<div  id=\"".$row["font_name"]."\" class=\"".$name."\" />".number_format($_GET["TXT_DEBTOT"], 2, ".", ",")."</div>";
		}	
	}

 ?>   

</body>
</html>
