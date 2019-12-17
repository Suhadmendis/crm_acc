
<?php
include './connection_sql.php';
?>
<!-- Main content -->
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Debit Note</h3>
        </div>
        <form  name= "form1"  role="form" class="form-horizontal">
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

                    <a onclick="sess_chk('cancel', 'crn');" class="btn btn-danger btn-sm">
                        <span class="fa fa-trash-o"></span> &nbsp; Cancel
                    </a>

                </div>

                <div id="msg_box"  class="span12 text-center"  >

                </div>
                <div class="form-group">
                    <label class="col-sm-1 control-label" for="carrier_code">Entry No</label>
                    <div class="col-sm-3">
                        <input type="text" placeholder="Ref No" id="txt_entno" class="form-control input-sm">
                    </div>
                    <div class="col-sm-1">
                        <a onfocus="this.blur()" onclick="NewWindow('serach_dbn.php', 'mywin', '800', '700', 'yes', 'center');
                                return false" href="">
                            <input type="button" class="btn btn-default btn-sm" value="..." id="searchcust" name="searchcust">
                        </a>
                    </div>
                    <label class="col-sm-2 control-label" for="carrier_code">Date</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Date" onchange="getno1();"  id="invdate" value="<?php echo date('Y-m-d'); ?>" class="form-control dt">
                    </div>
                </div>
                <input type="hidden"  id="tmpno" >
                <div class="form-group">
                    <label class="col-sm-1 control-label" for="name">Customer</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Code" id="c_code" class="form-control input-sm">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" placeholder="Name" id="c_name" class="form-control input-sm">
                    </div>
                    <div class="col-sm-1">
                        <a onfocus="this.blur()" onclick="NewWindow('serach_customer.php', 'mywin', '800', '700', 'yes', 'center');
                                return false" href="">
                            <input type="button" class="btn btn-default btn-sm" value="..." id="searchcust" name="searchcust">
                        </a>
                    </div>

                </div>

                <div class="form-group">
                    <label class="col-sm-1 control-label" for="address">Remaks</label>
                    <div class="col-sm-5">
                        <textarea placeholder="Remarks" id="txt_remarks" class="form-control input-sm"></textarea>

                    </div>
                </div>



                <div class="form-group">
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
                        <input type="text" placeholder="Rate" value="1" id="txt_rate" disabled="disabled" class="form-control input-sm">
                    </div>
                    <div class="col-sm-1">
                        <input type="text" placeholder="Currency" id="txt_amount" onkeyup="calrate();" class="form-control input-sm">
                    </div>
                    <div class="col-sm-1">
                        <input type="text" placeholder="LKR" id="txt_amount_lkr" disabled="disabled"   class="form-control input-sm">
                    </div>
                </div>



                <div class="form-group">
                    <label class="col-sm-1 control-label" for="contact">VAT Link</label>
                    <div class="col-sm-3">
                        <select id="Vat_link" class="form-control input-sm">
                            <option value="ZERO RATED">ZERO RATED</option>
                            <option value="VAT">VAT</option>
                            <option value="SVAT">SVAT</option>
                        </select> 
                    </div>

                </div>


                <table class="table table-striped">
                    <tr class='success'>
                        <th colspan="5">Debit</th>

                    </tr>

                    <tr class='info'> 
                        <th style="width: 120px;">Code</th>
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
                            <input type="text" placeholder="Description" id="txt_gl_name" class="form-control input-sm">
                        </td>
                        <td>
                            <a  href="search_ledg.php"  id="cmd_glcode"  onClick="NewWindow(this.href, 'mywin', '800', '700', 'yes', 'center');
                                    return false" class="btn btn-default btn-sm"> <span class="fa fa-circle-o"></span> &nbsp; </a>
                        </td>
                        <td>
                            <input type="text" placeholder="Amount" id="itemPrice" class="form-control input-sm">
                        </td>
                        <td><a onclick="add_tmp('CN1');" class="btn btn-default btn-sm"> <span class="fa fa-plus"></span> &nbsp; </a></td>
                    </tr>
                </table>

                <div id="itemdetails" >

                </div>
                <table class="table">
                    <tr>
                        <td></td>
                        <td></td>
                        <th>Total</th>
                        <td style="width: 180px;"><input type="text" placeholder="Total" id="subtot" disabled="disabled" class="form-control input-sm"></td>
                        <td></td>
                    </tr>
                </table>

                <table class="table table-striped">
                    <tr  class='success'>
                        <th colspan="5">Credit</th>

                    </tr>
                    <tr  class='info'>
                        <th style="width: 120px;">Code</th>
                        <th>Description</th>
                        <th style="width: 10px;"></th>
                        <th style="width: 120px;">Amount</th>
                        <th style="width: 10px;"></th>
                    </tr>

                    <tr>

                        <td>
                            <input type="text" placeholder="Code" id="txt_gl_code1" class="form-control input-sm">
                        </td>
                        <td>
                            <input type="text" placeholder="Description" id="txt_gl_name1" class="form-control input-sm">
                        </td>
                        <td>
                            <a  href="search_ledg.php?stname=p2"  id="cmd_glcode1"  onClick="NewWindow(this.href, 'mywin', '800', '700', 'yes', 'center');
                                    return false" class="btn btn-default btn-sm"> <span class="fa fa-circle-o"></span> &nbsp; </a>
                        </td>
                        <td>
                            <input type="text" placeholder="Amount" id="itemPrice1" class="form-control input-sm">
                        </td>
                        <td><a onclick="add_tmp('CN2');" class="btn btn-default btn-sm"> <span class="fa fa-plus"></span> &nbsp; </a></td>
                    </tr>
                </table>

                <div id="itemdetails1" >

                </div>
                <table class="table">
                    <tr>
                        <td></td>
                        <td></td>
                        <th>Total</th>
                        <td style="width: 180px;"><input type="text" placeholder="Total" id="subtot1" disabled="disabled" class="form-control input-sm"></td>
                        <td></td>
                    </tr>
                </table>




                


                    <div class="form-group" id="filup"  style="visibility: hidden" >
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

<script src="js/debit_note.js">

</script>
<script>
    new_inv();
</script>
<?php
include 'login.php';
include './cancell.php';
?>
    

 
