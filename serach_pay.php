<?php session_start();
?>
<!DOCTYPE html>
    <head>
        <meta charset=utf-8" />


        <title>Search Payment Entry</title>

        <script language="JavaScript" src="js/payment.js"></script>
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
                        
                        <th width="200">Naration</th> 
                        <th width="200">Amount</th> 
                    </tr>
                    <?php
                    include './connection_sql.php';



                    $sql = "select REFNO, BDATE,NARATION,ID,AMOUNT from paymas ORDER BY REFNO desc limit 50";

                    foreach ($conn->query($sql) as $row) {
                        $cuscode = $row["REFNO"];


                        echo "<tr>               
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['REFNO'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['BDATE'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['NARATION'] . "</a></td>
                              <td onclick=\"crnview('$cuscode', '$stname');\">" . $row['AMOUNT'] . "</a></td>
                               
                            </tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>
