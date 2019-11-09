<?php
include './connection_sql.php';
?>
<section class="content">
    <div class="box box-primary">

        <div class="box-header with-border">
            <h3 class="box-title">Bank Reconciliation</h3>
        </div>

        <form role="form" name ="form1" class="form-horizontal">
            <div class="box-body">


                <div class="form-group">
                    <a onclick="sess_chk('new', 'crn');" class="btn btn-default btn-sm">
                        <span class="fa fa-user-plus"></span> &nbsp; New
                    </a>

                    <a onclick="sess_chk('save', 'crn');" class="btn btn-success btn-sm">
                        <span class="fa fa-save"></span> &nbsp; Save
                    </a>

                    <a  onclick="sess_chk('print', 'crn');" class="btn btn-default btn-sm">
                        <span class="fa fa-print"></span> &nbsp; Print
                    </a> 
 

                </div>
                <input type="hidden"  id="tmpno" >
                <div id="msg_box"  class="span12 text-center"  >

                </div>



                <div class="form-group">
                        
                    <label class="col-sm-2 control-label" for="dtfrom">Date</label>                    
                    <div class="col-sm-2">
                        <input type="text" placeholder=""  onchange="getrecdt();"  id="dtto" value="<?php echo date('Y-m-d'); ?>" class="form-control dt  input-sm">
                    </div>

                    <label class="col-sm-2 control-label" for="currency1">Currency</label>
                    <div class="col-sm-1">
                        <select id="currency1"    onchange='getrecdt();' class="form-control input-sm">
                            <option value='LKR'>LKR</option>
                            <?php
                            $sql = "select * from mastercurrancy where currancy <> 'LKR'";
                            foreach ($conn->query($sql) as $row) {
                                echo "<option value='" . $row["currancy"] . "'>" . $row["currancy"] . "</option>";
                            }
                            ?>
                        </select>

                    </div>
                    <div class="col-sm-1">
                        <input type="text" placeholder="Rate" id="txt_rate1" value='1' disabled="disabled" class="form-control input-sm">
                    </div>    
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="lastdt">Last Rec. Date</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="" id="lastdt" value="<?php echo date('Y-m-d'); ?>" class="form-control dt  input-sm">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="bank">Account</label>
                    <div class="col-sm-5">
                        <select id="bank" onchange="getrecdt();" class="form-control">
                            <option selected value=''>-</option>    
                            <?php
                            $sql = "select * from lcodes where cat ='B'";
                            foreach ($conn->query($sql) as $row) {
                                echo "<option value='" . $row["C_CODE"] . "'>" . $row["C_NAME"] . "</option>";
                            }
                            ?>

                        </select>
                    </div>
                     <label class="col-sm-2 control-label" for="bank">Closing Balance</label>
                     <div class="col-sm-2">
                     <input type="text" placeholder="" id="bank_bal"  class="form-control input-sm">
                     </div>
                </div>


                <div class="container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#debit">DEBIT</a></li>
                        <li><a data-toggle="tab" href="#credit">CREDIT</a></li>

                    </ul>

                    <div class="tab-content">
                        <div id="debit" class="tab-pane fade in active">
                            <input type="hidden" value="0"  id="count" > 

                            <div id="itemdetails" >

                            </div>

                        </div>


                        <div id="credit" class="tab-pane fade">
                            <input type="hidden" value="0"  id="count1" >    




                            <div id="itemdetails1" >

                            </div>

                        </div>

                    </div>


                    <div class="form-group"  style="visibility: hidden" id="filup">
                        <label class="col-sm-1 control-label" for="file-3">File Box</label>
                        <label class="btn btn-default" for="file-3">
                            <input id="file-3" name="file-3" multiple="true" type="file" >
                            Select Files

                        </label>
                        <a  class="btn btn-primary" onclick="uploadfile('crn');" class="btn"/>Upload</a>
                    </div>




                    <div id="filebox" >

                    </div>


                </div>

            </div>       
        </form>
    </div>    

</section>
<script src="js/bank_rec.js?v1">

</script>
<script>
    new_inv();
</script>
<?php
include 'login.php';
include './cancell.php';
?>
    