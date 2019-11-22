<?php session_start();
?>
<!DOCTYPE html>
    <head>
         <meta charset="UTF-8">


        <title>Search Credit Note</title>

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
                        <th width="100">Code</th> 
                        <th width="200">Name</th> 
                        <th width="121">Amount</th>  
                    </tr>
                    <?php
                    include './connection_sql.php';



                    $sql = "select REFNO, SDATE,CUSCODE,name,curbal,ID from view_cbal where curbal > 0.01  and cancell=0 ORDER BY ID desc limit 50";

                    foreach ($conn->query($sql) as $row) {
                        $cuscode = $row["REFNO"];


                        echo "<tr>               
                              <td onclick=\"balview('$cuscode', '$stname');\">" . $row['REFNO'] . "</a></td>
                              <td onclick=\"balview('$cuscode', '$stname');\">" . $row['SDATE'] . "</a></td>
                              <td onclick=\"balview('$cuscode', '$stname');\">" . $row['CUSCODE'] . "</a></td>
                              <td onclick=\"balview('$cuscode', '$stname');\">" . $row['NAME'] . "</a></td>
                              <td onclick=\"balview('$cuscode', '$stname');\">" . $row['curbal'] . "</a></td>
                            </tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>
