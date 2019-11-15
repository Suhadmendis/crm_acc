<?php
include './connection_sql.php';
?>
<section class="content">
    <div class="box box-primary">

        <div class="box-header with-border">
            <h3 class="box-title">Purchase Received</h3>
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

                    <a onclick="sess_chk('cancel', 'crn');" class="btn btn-danger btn-sm">
                        <span class="fa fa-trash-o"></span> &nbsp; Cancel
                    </a>

                </div>
                <input type="hidden"  id="tmpno" >
                <div id="msg_box"  class="span12 text-center"  >

                </div>

                <div class="form-group">

                    <label class="col-sm-2 control-label" for="Receipt_code">ARN No</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="ARN No" id="txt_entno" class="form-control input-sm">
                    </div>
                    <div class="col-sm-1">
                        <a onfocus="this.blur()" onclick="NewWindow('serach_arn.php', 'mywin', '800', '700', 'yes', 'center');
                                return false" href="">
                            <input type="button" class="btn btn-default" value="..." id="searchcust" name="searchcust">
                        </a>
                    </div>


                    <label class="col-sm-1 control-label" for="name">Supplier</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Code" id="c_code" class="form-control input-sm">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" placeholder="Name" id="c_name" class="form-control input-sm">
                    </div> 
                </div>

                <div class="form-group">

                    <label class="col-sm-2 control-label" for="Receipt No">Date</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="" id="invdate" value="<?php echo date('Y-m-d'); ?>" class="form-control dt  input-sm">
                    </div>
                    <div class="col-sm-1">
                        <input type="hidden" placeholder="count" id="arn_item_count" value="hidden count" class="form-control input-sm">
                    </div>

                    <label class="col-sm-1 control-label" for="name">Order No</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="" id="orderno1" class="form-control input-sm">
                    </div>
                    <div class="col-sm-1">
                        <a onfocus="this.blur()" onclick="NewWindow('serach_po.php?stname=arn', 'mywin', '800', '700', 'yes', 'center');
                                return false" href="">
                            <input type="button" class="btn btn-default" value="..." id="searchcust" name="searchcust">
                        </a>
                    </div>

                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="LCNO">Invoice</label>
                     <div class="col-sm-2">
                        <input type="text" placeholder="" id="LCNO" class="form-control input-sm">
                    </div>
                    
                </div>
                
                
            </div>









            <div class="container">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#invoices">Item Details</a></li>
                    <li><a data-toggle="tab" href="#glpost">GL Posting</a></li>

                </ul>

                <div class="tab-content">


                    <div id="invoices" class="tab-pane fade in active">
                        <h3>Item Details</h3>
                        <input type="hidden" value="3"  id="count" > 
                        <form role="form" class="form-horizontal">
                            <div id='invdt' class="box-body">

                            </div>

                            <table id='subtotal' class="table">
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <th>Sub Total</th>

                                    <td></td>                        
                                    <td style="width: 150px;"><input type="text" placeholder="Sub Total" id="total_value" class="form-control input-sm"></td>
                                </tr>
                            </table>
                        </form>
                    </div>


                    <div id="glpost" class="tab-pane fade">
                        <h3>GL Posting</h3>



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
                                <td><a onclick="add_tmp('ARN1');" class="btn btn-default btn-sm"> <span class="fa fa-plus"></span> &nbsp; </a></td>
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
                                <td><a onclick="add_tmp('ARN2');" class="btn btn-default btn-sm"> <span class="fa fa-plus"></span> &nbsp; </a></td>
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


                    </div>


                </div>


                <div class="form-group"  style="visibility: hidden" id="filup">
                    <label class="col-sm-1 control-label" for="file-3">File Box</label>
                    <label class="btn btn-default" for="file-3">
                        <input id="file-3" name="file-3" multiple="true" type="file" >
                        Select Files

                    </label>
                    <a  class="btn btn-primary" onclick="uploadfile('arn');" class="btn"/>Upload</a>
                </div>




                <div id="filebox" >

                </div>


            </div>

    </div>       
</form>


</section>
<script src="js/pr.js">

</script>
<script>
    new_inv();
</script>
<?php
include 'login.php';
include './cancell.php';
?>
    