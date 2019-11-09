<?php
include './connection_sql.php';
?>
<section class="content">
    <div class="box box-primary">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <div class="box-header with-border">
            <h3 class="box-title">Outstanding</h3>
        </div>

        <form role="form" name ='form1' action="report_outstanding.php" target="_blank" method="GET" class="form-horizontal">
            <div class="box-body">



                <div class="form-group">
                    <label class="col-sm-1 control-label" for="from">As At</label> 
                    <div class="col-sm-2">
                        <input type="text" value="<?php echo date('Y-m-d'); ?>" placeholder="From" id="dtfrom" name="dtfrom"  class="form-control dt">
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-1 control-label" for="c_code">Customer</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Code" name="c_code" id="c_code" class="form-control  input-sm">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" placeholder="Name" name = "c_name" id="c_name" class="form-control input-sm">
                    </div>
                    <div class="col-sm-1">


                        <a onfocus="this.blur()" onclick="NewWindow('serach_customer.php', 'mywin', '800', '700', 'yes', 'center');
                                return false" href="">
                            <input type="button" class="btn btn-default" value="..." id="searchcust" name="searchcust">
                        </a>



                    </div>
                </div>

               <div class="form-group">
                    <label class="col-sm-1 control-label" for="contact">Currency</label>
                    <div class="col-sm-1">
                        <select id="currency" name ="currency"  class="form-control input-sm">
                            <option value='LKR'>LKR</option>
                            <?php
                            $sql = "select * from mastercurrancy where currancy<> 'LKR'";
                            foreach ($conn->query($sql) as $row) {
                                echo "<option value='" . trim($row["currancy"]) . "'>" . $row["currancy"] . "</option>";
                            }
                            ?>
                            <option value='All'>All</option>
                        </select> 
                    </div>
                </div>     
                
                
                <div class="form-group">
                    <div class="col-sm-1"></div>

                    <div class="col-sm-2">
                        <select id="type" name="type" class="form-control">
                            <option value="All">All</option>
                            <option value="DEB">Receivable</option>
                            <option value="CRE">Payable</option>
                        </select>  
                    </div>
                </div>    


                <div class="form-group">
                    <div class="col-sm-4">
                        <div class="radio">
                            <label>
                                <input type="radio" name="optionsRadios" id="op_statment" value="op_statment" checked="">
                                Statment
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="optionsRadios" id="op_agedet" value="op_summery">
                                Age Analysis Details
                            </label>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-2">
                        <input type="submit" class="btn btn-default fa fa-print" value="View">
                    </div>
                </div>

            </div>
        </form>
    </div>       
</div>
</section>