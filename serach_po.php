<?php session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


        <title>Search PO</title>

        <script language="JavaScript" src="js/po.js"></script>
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">


    </head>

    <body>
        <div class="container">

            <table border="0" class="table table-bordered">

                <tr>
                    <?php
                    if (isset($_GET["stname"])) {
                        $stname = $_GET["stname"];
                    } else {
                        $stname = "";
                    }
                    ?>
                    <td width="122" ><input type="text" size="20" name="cusno" id="cusno" value=""  class="form-control" tabindex="1" onkeyup="<?php echo "update_list('$stname')"; ?>" onkeypress="update_list('$stname', '$stname');"/></td>
                    <td width="603" ><input type="text" size="70" name="customername" id="customername" value=""  class="form-control" onkeyup="<?php echo "update_list('$stname')"; ?>"/></td>
            </table>    
            <div id="filt_table">  <table class="table table-bordered">
                    <tr>
                        <th width="121">Reference No</th>
                        <th width="121">Date</th>
                        <th width="100">Code</th> 
                        <th width="200">Name</th> 
                        <th width="121">Amount</th>  
                    </tr>
                    <?php
                    include './connection_sql.php';

                    $phrase = "";
                    if($stname == "arn"){
                        $phrase = " where cancel='0' ";
                    }
                    $sql = "select REFNO, SDATE,SUP_CODE,SUP_NAME,AMOUNT from s_ordmas $phrase ORDER BY REFNO desc limit 50";

                    foreach ($conn->query($sql) as $row) {
                        $cuscode = $row["REFNO"];


                        echo "<tr>               
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['REFNO'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['SDATE'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['SUP_CODE'] . "</a></td>
                                  <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['SUP_NAME'] . "</a></td>
                                      <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['AMOUNT'] . "</a></td>
                            </tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>
