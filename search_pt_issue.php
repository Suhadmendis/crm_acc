<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


        <title>Search Journal Entry</title>

        <script language="JavaScript" src="js/petty_cash_issue.js"></script>
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
                        
                        <th width="200">Remarks</th> 
                        
                    </tr>
                    <?php
                    include './connection_sql.php';



                    $sql = "select REFNO, BDATE,DETAILS,ID from ledmas where type = 'PCI' ORDER BY ID desc limit 50";

                    foreach ($conn->query($sql) as $row) {
                        $cuscode = $row["REFNO"];


                        echo "<tr>               
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['REFNO'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['BDATE'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['DETAILS'] . "</a></td>
                               
                            </tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>
