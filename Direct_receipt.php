<?php
include './connection_sql.php';
?>

<!-- Main content -->
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Direct Receipt</h3>
        </div>
        <form role="form" name="form1" class="form-horizontal">

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
                <div id="msg_box" class="span12 text-center"  ></div>

                <div class="form-group">

                    <label class="col-sm-1 control-label" for="carrier_code">Entry No</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Entry No" id="txt_entno" class="form-control input-sm">
                    </div>
                    <div class="col-sm-1">
                        <a onfocus="this.blur()" onclick="NewWindow('serach_drec.php', 'mywin', '800', '700', 'yes', 'center');
                                return false" href="">
                            <input type="button" class="btn btn-default btn-sm" value="..." id="searchcust" name="searchcust">
                        </a>
                    </div>


                    <label class="col-sm-1 control-label" for="entrydate">Date</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Date" onchange="getno1();" id="invdate" value="<?php echo date('Y-m-d'); ?>" class="form-control dt  input-sm">
                    </div>

                    <label class="col-sm-1 control-label" for="contact">Currency</label>
                    <div class="col-sm-1">
                        <select id="currency"  onchange='loadcur();' class="form-control input-sm">
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
                        <input type="text" placeholder="" id="txt_rate" value="1"  class="form-control  input-sm">
                    </div>

                </div>



                <div class="form-group">
                    <label class="col-sm-1 control-label" for="from">From</label> 
                    <div class="col-sm-1">
                        <input type="text" placeholder="Code" id="c_code" class="form-control  input-sm">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" placeholder="Name" id="c_name" class="form-control  input-sm">
                    </div>
                    <div class="col-sm-1">
                        <a onfocus="this.blur()" onclick="NewWindow('serach_customer.php', 'mywin', '800', '700', 'yes', 'center');
                                return false" href="">
                            <input type="button" class="btn btn-default btn-sm" value="..." id="searchcust" name="searchcust">
                        </a>
                    </div>
                </div>


                <input type="hidden" placeholder="Heading" id="txt_heading" class="form-control input-sm">


                <div class="form-group">
                    <label class="col-sm-1 control-label">Naration</label>
                    <div class="col-sm-5">
                        <textarea class="form-control  input-sm"  rows="2" id="txt_narration" placeholder="Naration"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-1 control-label" for="bank">Account</label>
                    <div class="col-sm-5">
                        <select id="bank" class="form-control input-sm">

                            <?php
                            $sql = "select * from lcodes where cat = 'B'";
                            foreach ($conn->query($sql) as $row) {
                                echo "<option value='" . $row["C_CODE"] . "'>" . $row["C_NAME"] . "</option>";
                            }
                            ?>

                        </select>
                    </div>
                </div>

                <div class="form-group">

                    <label class="col-sm-1 control-label" for="payment">Type</label>
                    <div class="col-sm-2">
                        <select id="payment_type" class="form-control  input-sm">
                            <option>Direct</option>
                            <option>Visa</option>
                            <option>Cash</option>
                            <option>Cheque</option>
                        </select> 
                    </div>

                    <label class="col-sm-1 control-label" for="invoice data">Cheque</label>
                    <div class="col-sm-2">
                        <input type="text" id="cheq_no" placeholder="No" class="form-control  input-sm">
                    </div>


                    <div class="col-sm-2">
                        <input type="text" placeholder="Date" id="cheq_date" value="" class="form-control dt  input-sm">
                    </div>

                    <label class="col-sm-2 control-label" for="Amount">Amount</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Amount" id="txt_amount" class="form-control  input-sm">
                    </div>

                </div>






                <table class="table table-striped">
                    <tr>
                        <th style="width: 120px;">Code</th>
                        <th>Description</th>
                        <th style="width: 10px;"></th>
                        <th style="width: 120px;">Amount</th>
                        <th style="width: 10px;"></th>
                    </tr>

                    <tr>

                        <td>
                            <input type="text" placeholder="Code" id="txt_gl_code" class="form-control  input-sm">
                        </td>
                        <td>
                            <input type="text" placeholder="Description" id="txt_gl_name" class="form-control  input-sm">
                        </td>
                        <td>
                            <a  href="search_ledg.php"  id="cmd_glcode"  onClick="NewWindow(this.href, 'mywin', '800', '700', 'yes', 'center');
                                    return false" class="btn btn-default btn-sm"> <span class="fa fa-circle-o"></span> &nbsp; </a>
                        </td>
                        <td>
                            <input type="text" placeholder="Amount" id="itemPrice" class="form-control  input-sm">
                        </td>
                        <td><a onclick="add_tmp();" class="btn btn-default btn-sm"> <span class="fa fa-plus"></span> &nbsp; </a></td>
                    </tr>
                </table>

                <div id="itemdetails" >

                </div>
                <table class="table">
                    <tr>
                        <td></td>
                        <td></td>
                        <th>Total</th>
                        <td style="width: 180px;"><input type="text" placeholder="Total" id="subtot" class="form-control  input-sm"></td>
                        <td></td>
                    </tr>
                </table>



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
<script src="js/receipt_entry.js?v1.0"></script>

<script>
                        new_inv();
</script>
<?php
include 'login.php';
include './cancell.php';
?>
    