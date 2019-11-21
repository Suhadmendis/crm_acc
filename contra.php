<?php
include './connection_sql.php';
?>
<section class="content">
    <div class="box box-primary">

        <div class="box-header with-border">
            <h3 class="box-title">Contra Entry</h3>
        </div>

        <form role="form" name ="form1" class="form-horizontal">
            <div class="box-body">


                <div class="form-group">
                    <a onclick="sess_chk('new', 'rec');" class="btn btn-default btn-sm">
                        <span class="fa fa-user-plus"></span> &nbsp; New
                    </a>

                    <a onclick="sess_chk('save', 'rec');" class="btn btn-success btn-sm">
                        <span class="fa fa-save"></span> &nbsp; Save
                    </a>

                    <a  onclick="sess_chk('print', 'rec');" class="btn btn-default btn-sm">
                        <span class="fa fa-print"></span> &nbsp; Print
                    </a> 

                    <a onclick="sess_chk('cancel', 'rec');" class="btn btn-danger btn-sm">
                        <span class="fa fa-trash-o"></span> &nbsp; Cancel
                    </a>

                </div>
                <input type="hidden"  id="tmpno" >
                <div id="msg_box"  class="span12 text-center"  >

                </div>

                <div class="form-group">

                    <label class="col-sm-2 control-label" for="Receipt_code">Reference No</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Reference No" id="txt_entno" class="form-control input-sm">
                    </div>
                    <div class="col-sm-1">
                        <a onfocus="this.blur()" onclick="NewWindow('serach_con.php', 'mywin', '800', '700', 'yes', 'center');
                                return false" href="">
                            <input type="button" class="btn btn-default" value="..." id="searchcust" name="searchcust">
                        </a>
                    </div>


                    <label class="col-sm-1 control-label" for="name">Customer</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Code" id="c_code" class="form-control input-sm">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" placeholder="Name" id="c_name" class="form-control input-sm">
                    </div>
                    <div class="col-sm-1">
                        <a onfocus="this.blur()" onclick="set_inv();
                                return false" href="">
                            <input type="button" class="btn btn-default" value="..." id="searchcust" name="searchcust">
                        </a>
                    </div>

                </div>

                <div class="form-group">

                    <label class="col-sm-2 control-label" for="Receipt No">Date</label>
                    <div class="col-sm-2">
                        <input type="text"  id="invdate" value="<?php echo date('Y-m-d'); ?>" class="form-control dt  input-sm">
                    </div>

                    <label class="col-sm-2 control-label" for="receipt">Currency</label>
                    <div class="col-sm-1">
                        <select id="currency"    onchange='loadcur();' class="form-control input-sm">
                            <option value='LKR'>LKR</option>
                            <?php
                            $sql = "select * from mastercurrancy where currancy <> 'LKR'";
                            foreach ($conn->query($sql) as $row) {
                                echo "<option value='" . trim($row["currancy"]) . "'>" . $row["currancy"] . "</option>";
                            }
                            ?>
                        </select>

                    </div>
                    <div class="col-sm-1">
                        <input type="text" placeholder="Rate" id="txt_rate" value='1' class="form-control input-sm">
                    </div>    
                </div>






                <div class="container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#invoices">Credit Notes/Overpayments</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="credit" class="tab-pane fade in active">
                             
                            <input type="hidden" value="3"  id="count1" > 
                            <form role="form" class="form-horizontal">
                                <div id='invdt1' class="box-body">

                                </div>
                            </form>
                        </div>
                        
                        <div class="col-sm-4">
                        
                        </div>
                        <div class="col-sm-3">
                        <input type="text" placeholder="Total" value =0 id="txt_amount_lkr" disabled="disabled" class="form-control input-sm">
                        </div>
                    </div>
                </div>





                <div class="container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#invoices">Debit Notes/Invoices</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="credit" class="tab-pane fade in active">
                            
                            <input type="hidden" value="3"  id="count" > 
                            <form role="form" class="form-horizontal">
                                <div id='invdt' class="box-body">

                                </div>
                            </form>
                        </div>
                        <div class="col-sm-4">
                        
                        </div>
                        <div class="col-sm-3">
                        <input type="text" placeholder="Total" value =0 id="txt_amount_lkr1" disabled="disabled" class="form-control input-sm">
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
        </form>
    </div>


</section>
<script src="js/contra.js">

</script>
<script>
    new_inv();
</script>
<?php
include 'login.php';
include './cancell.php';
?>
    
