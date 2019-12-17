<?php session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


        <title>Search Item</title>



        <link rel="stylesheet" href="css/bootstrap.min.css">

            <script language="JavaScript" src="js/comman.js"></script>
            <script language="JavaScript" src="js/po_item.js"></script>


    </head>

    <body>

        <table width="735"   class="table table-bordered">

            <?php
            if (isset($_GET["stname"])) {
                $stname = $_GET["stname"];
            } else {
                $stname = "";
            }
            ?>

            <tr>

                <td width="122" ><input type="text" size="20" name="cusno"  id="cusno" value=""  class="form-control" tabindex="1" onkeyup="<?php echo "update_list('$stname')"; ?>" onkeypress="update_list('$stname', '$stname');"/></td>
                <td width="603" ><input type="text" size="70" name="customername" id="customername" value=""  class="form-control" onkeyup="<?php echo "update_list('$stname')"; ?>"/></td>
        </table>    
        <div id="filt_table" class="CSSTableGenerator">  <table width="735"    class="table table-bordered">
                <tr>
                    <td width="121"    > Item No </td>
                    <td width="424"   > Item Description </td>
                    <td width="100"  > Amount </td>

                </tr>
                <?php
                include_once './connectioni.php';

                $stname = "";
                if (isset($_GET['stname'])) {
                    $stname = $_GET["stname"];
                }


                $sql = "SELECT * from itemmasterentry  order by itcode limit 50";

                $result = mysqli_query($GLOBALS['dbinv'], $sql);

                while ($row = mysqli_fetch_array($result)) {

                    echo "<tr>
                              <td onclick=\"itno_undeliver('" . $row['itcode'] . "','" . $stname . "');\">" . $row['itcode'] . "</a></td>
                              <td onclick=\"itno_undeliver('" . $row['itcode'] . "','" . $stname . "');\">" . $row['itname'] . "</a></td>
                    <td onclick=\"itemno('" . $row['itcode'] . "','" . $stname . "');\">" . $row['price'] . "</a></td> 
                            </tr>";
                }
                ?>
            </table>
        </div>

    </body>
</html>

<script>

document.getElementById("customername").focus();

</script>