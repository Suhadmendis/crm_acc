<?php
include './connection_sql.php';
?>
<!-- Main content -->
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Payment In Cheque</h3>
        </div>
        <form name= "form1"  role="form" class="form-horizontal">
            <div class="box-body">

                <div class="form-group">
                    <a onclick="sess_chk('new', 'pch');" class="btn btn-default btn-sm">
                        <span class="fa fa-user-plus"></span> &nbsp; New
                    </a>
                    <a onclick="sess_chk('save', 'pch');" class="btn btn-success btn-sm">
                        <span class="fa fa-save"></span> &nbsp; Save
                        
                        <?php
    header("refresh: 3;");
?>


                    </a>                    
                     <a  onclick="sess_chk('print1', 'rec');" class="btn btn-default btn-sm">
                        <span class="fa fa-print"></span> &nbsp; Print Cheque
                    </a>
                     <a  onclick="sess_chk('print', 'rec');" class="btn btn-default btn-sm">
                        <span class="fa fa-print"></span> &nbsp; Print Voucher
                    </a>
                    <a onclick="sess_chk('cancel', 'crn');" class="btn btn-danger btn-sm">
                        <span class="fa fa-trash-o"></span> &nbsp; Cancel
                    </a>
                </div>
                <div id="msg_box"  class="span12 text-center"  ></div>

                <div class="form-group">
                    <label class="col-sm-1 control-label" for="carrier_code">Entry No</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Entry No" id="txt_entno" class="form-control input-sm">
                    </div>
                    <div class="col-sm-1 nopadding">
                        <a onfocus="this.blur()" onclick="NewWindow('serach_pay.php', 'mywin', '800', '700', 'yes', 'center');
                                return false" href="">
                            <input type="button" class="btn btn-default btn-sm" value="..." id="searchcust" name="searchcust">
                        </a>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" onchange="getno1();"  id="invdate" value="<?php echo date('Y-m-d'); ?>" class="form-control dt  input-sm">
                    </div>
                    <div class="col-sm-2">
                        <span style="float:right;"><input type="checkbox" id="acpay"> A/C Pay Only </span>
                    </div>
                    <div class="col-sm-2">
                        <span style="float:right;"><input type="checkbox" id="continues"> Continues Cheque</span>
                    </div>
                </div>

                <input type="hidden" value="" id="tmpno" >
                <input type="hidden" value="" id="Text3" >
                <input type="hidden" value="" id="txt_amoinword" >


                
                <div class="form-group">
                    <label class="col-sm-1 control-label" for="name">Customer</label>
                    <div class="col-sm-1">
                        <input type="text" placeholder="Code" id="c_code" class="form-control  input-sm">
                    </div>
                    <div class="col-sm-3 nopadding">
                        <input type="text" placeholder="Name" id="c_name" class="form-control  input-sm">
                    </div>
                    <div class="col-sm-1">
                        <a onfocus="this.blur()" onclick="NewWindow('serach_customer.php', 'mywin', '800', '700', 'yes', 'center');
                                return false" href="">
                            <input type="button" class="btn btn-default btn-sm" value="..." id="searchcust" name="searchcust">
                        </a>
                    </div>
                    <label class="col-sm-1 control-label" for="contact">Pay Cur.</label>
                    <div class="col-sm-1">
                        <select id="currency"  onchange='loadcur();' class="form-control  input-sm">
                            <option value='LKR'>LKR</option>
                            <?php
                            $sql = "select * from mastercurrancy where currancy <> 'LKR'";
                            foreach ($conn->query($sql) as $row) {
                                echo "<option value='" . trim($row["currancy"]) . "'>" . trim($row["currancy"]) . "</option>";
                            }
                            ?>
                        </select> 
                    </div>
                    <div class="col-sm-1">
                        <input type="text" placeholder="Payment" value="1" id="txt_rate" class="form-control  input-sm">
                    </div>
                    <label class="col-sm-1 control-label" for="contact">Invoice Cur.</label>
                    <div class="col-sm-1">
                        <select id="currency1"    onchange='loadcur1();'  class="form-control  input-sm">
                            <option value='LKR'>LKR</option>
                            <?php
                            $sql = "select * from mastercurrancy where currancy <> 'LKR'";
                            foreach ($conn->query($sql) as $row) {
                                echo "<option value='" . trim($row["currancy"]) . "'>" . trim($row["currancy"]) . "</option>";
                            }
                            ?>
                        </select> 
                    </div>
                    <div class="col-sm-1">
                        <input type="text" placeholder="Rate" id="txt_rate1" value='1'  class="form-control input-sm">
                    </div>   
                </div>       
                <div class="form-group">
                    <label class="col-sm-1 control-label" for="name">Cheque Barere</label>
                    <div class="col-sm-3">
                        <input type="text" placeholder="Name on Cheque" id="chq_barere" class="form-control  input-sm">
                    </div>
                     
                </div>


                <table class="table table-striped">


                    <tr class='info'> 

                        <th style="width: 80px;">Ref No</th>
                        <th style="width: 80px;">Invoice No</th>


                        <th style="width: 80px;">Amount</th>
                        <th style="width: 80px;">Balance</th>
                        <th style="width: 80px;">Payment</th>
                        <th style="width: 1px;"></th>
                        <th style="width: 1px;"></th>
                    </tr>

                    <tr>


                        <td>
                            <input type="text" placeholder="Ref No" id="txt_pref" disabled class="form-control  input-sm">
                        </td>
                        <td>
                            <input type="text" placeholder="Inv No" id="txt_pinv"disabled class="form-control  input-sm">
                        </td>


                        <td>
                            <input type="text" placeholder="Amount" id="txt_pamo" disabled class="form-control  input-sm">
                        </td>
                        <td>
                            <input type="text" placeholder="Balance" id="txt_pbal" disabled class="form-control input-sm ">
                        </td>
                        <td>
                            <input type="text" placeholder="Payment" id="txt_ppay" class="form-control  input-sm">
                        </td>

                        <td><a onclick="opn_bal();" id="ser_bal" class="btn btn-default btn-sm"> <span class="fa fa-search-plus"></span> &nbsp; </a></td>
                        <td><a onclick="add_bal();" class="btn btn-default btn-sm"> <span class="fa fa-plus"></span> &nbsp; </a></td>
                    </tr>
                </table>

                <div id="inv_details" >

                </div>



                <div class="form-group">

                    <label class="col-sm-1 control-label">Narration</label>
                    <div class="col-sm-5">
                        <textarea class="form-control" rows="4" id="txt_narration" placeholder="Narration"></textarea>
                    </div>

                   

                    <label class="col-sm-1 control-label">Cheque No</label>
                    <div class="col-sm-1">
                        <input type="text" placeholder="Cheque No" id="cheq_no" class="form-control  input-sm">
                    </div>
                     
                  

                    <label class="col-sm-2 control-label">SVAT</label>   
                    <div class="col-sm-1">
                        <input type="text" placeholder="SVAT" id="txt_svat" class="form-control  input-sm">
                    </div>    
                     
                   
                    

               	</div>

                
                
                
                

                <div class="form-group">
                    <label class="col-sm-1 control-label" for="contact">Bank</label>
                    <div class="col-sm-5">
                        <select id="bank" class="form-control  input-sm">

                            <?php
                            $sql = "select * from lcodes where cat = 'B'";
                            foreach ($conn->query($sql) as $row) {
                                echo "<option value='" . $row["C_CODE"] . "'>" . $row["C_NAME"] . "</option>";
                            }
                            ?>

                        </select>

                    </div>
                     <label class="col-sm-1 control-label">Payment</label>
                    <div class="col-sm-1">
                        <input type="text" placeholder="Payments" id="txt_payments" class="form-control  input-sm">
                    </div> 
                    <label class="col-sm-2 control-label">Bank Amount</label>
                    <div class="col-sm-1">
                        <input type="text" placeholder="Bank Amount" id="txt_bankamo" class="form-control  input-sm">
                    </div> 
                </div>


                <table class="table table-striped">
                    <tr class='success'>
                        <th colspan="5">Debit</th>
                    </tr>

                    <tr class='info'> 
                        <th style="width: 120px;">Code</th>
                        <th style="width: 420px;">Account</th>
                        <th>Description</th>
                        <th style="width: 10px;"></th>
                        <th style="width: 120px;">Amount</th>
                        <th style="width: 10px;"></th>
                    </tr>

                    <tr>

                        <td>
                            <input type="text" placeholder="Code" id="txt_gl_code" class="form-control input-sm">
                        </td>
                        <td>
                            <input type="text" placeholder="Description" id="txt_gl_name" class="form-control  input-sm">
                        </td>
                         <td>
                            <input type="text" placeholder="Description" id="txt_gl_name1" class="form-control  input-sm">
                        </td>
                        <td>
                            <a  href="search_ledg.php"  onClick="NewWindow(this.href, 'mywin', '800', '700', 'yes', 'center');
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
                        <td style="width: 180px;"><input type="text" placeholder="Total" id="subtot" class="form-control input-sm"></td>
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
<script src="js/payment.js?v1.1"></script>

<script>
                            new_inv();
</script>
<?php
include 'login.php';
include './cancell.php';
?>
    