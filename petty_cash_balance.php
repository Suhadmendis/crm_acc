<?php
include './connection_sql.php';
?>
<!-- Main content -->
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Petty Cash Balance</h3>
        </div>
        <form  name= "form1"  role="form" class="form-horizontal">
            <div class="box-body">


              

<div class="col-md-6">
           <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">Form</th>
                  <th scope="col">Ref</th>
                  <th scope="col">Bill</th>
                  <th scope="col">Balance</th>
                </tr>
              </thead>
              <tbody>
                            <?php
                                $sql = "select * from pettycash_iou";
                                foreach ($conn->query($sql) as $row) {
                                    echo "<tr>
                                      <td>IOU</td>
                                      <td>".$row['ref']."</td>
                                      <td>".$row['ref']."</td>
                                      <td>".$row['amount']."</td>
                                    </tr>";
                                  
                                }
                            ?>
                
            
              </tbody>
            </table>


</div>

<div class="col-md-6">
           <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">Form</th>
                  <th scope="col">Ref</th>
                  <th scope="col">Bill</th>
                  <th scope="col">Balance</th>
                </tr>
              </thead>
              <tbody>
                            <?php
                                $sql = "select * from pettycash_iou";
                                foreach ($conn->query($sql) as $row) {
                                    echo "<tr>
                                      <td>IOU</td>
                                      <td>".$row['ref']."</td>
                                      <td>".$row['ref']."</td>
                                      <td>".$row['amount']."</td>
                                    </tr>";
                                  
                                }
                            ?>
                
            
              </tbody>
            </table>


</div>

<div class="col-md-8">
           <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">Ref</th>
                  <th scope="col">Paid to</th>
                  <th scope="col">Date</th>
                  <th scope="col">Amount</th>
                  <th scope="col">No. of days</th>
                  <th scope="col">Bill No</th>
                  <th scope="col">Description</th>
                  
                </tr>
              </thead>
              <tbody>
                            <?php
                                $sql = "select * from pettycash_iou";
                                foreach ($conn->query($sql) as $row) {
$datetime1 = date_create($row['pdate']); 
$datetime2 = date_create($row['settledate']); 
  
$interval = date_diff($datetime1, $datetime2); 

  
                                    echo "<tr>
                                      
                                      <td>".$row['ref']."</td>
                                      <td>".$row['person']."</td>
                                      <td>".$row['pdate']."</td>
                                      <td>".$row['amount']."</td>
                                      <td>".$interval->format('%R%a')."</td>
                                      <td>".$row['ref']."</td>
                                      <td>".$row['reason']."</td>
                                    </tr>";
                                  
                                }
                            ?>
                
            
              </tbody>
            </table>


</div> 
               
              
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

                  





            </div>
        </form>
    </div>

</section>

<!-- <script src="js/petty_cash_posting.js"> -->

</script>
<script>
    // new_inv();
</script>
<?php
include 'login.php';
include './cancell.php';
?>
    
