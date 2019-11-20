<!-- Main content -->
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Return Cheque Entry</h3>
        </div>
        <form role="form" name="form1" class="form-horizontal">

            <div class="box-body">

                <div class="form-group">
                   <a onclick="sess_chk('new', 'dre');" class="btn btn-default btn-sm">
                        <span class="fa fa-user-plus"></span> &nbsp; New
                    </a>

                    <a onclick="sess_chk('save', 'dre');" class="btn btn-success btn-sm">
                        <span class="fa fa-save"></span> &nbsp; Save
                    </a>

                    <a  onclick="sess_chk('print', 'dre');" class="btn btn-default btn-sm">
                        <span class="fa fa-print"></span> &nbsp; Print
                    </a> 

                    <a onclick="sess_chk('cancel', 'dre');" class="btn btn-danger btn-sm">
                        <span class="fa fa-trash-o"></span> &nbsp; Cancel
                    </a>
                </div>

                <input type="hidden"  id="tmpno" >
                <div id="msg_box" class="span12 text-center"  ></div>
                <div class="form-group">

                    <label class="col-sm-1 control-label" for="entry_code">Entry No</label>

                    <div class="col-sm-2">
                        <input type="text" placeholder="Entry No" id="txt_entno" class="form-control input-sm">
                    </div>

                    <div class="col-sm-1">
                        <input type="button" class="btn btn-default btn-sm" value="..." id="searchentry" name="searchentry">
                    </div>                  

                    <label class="col-sm-1 control-label" for="Entry No">Date</label>
                    <div class="col-sm-2">
                        <input type="text" id="entrydate" value="<?php echo date('Y-m-d'); ?>" class="form-control  input-sm dt">
                    </div>


                </div>



                <div class="form-group">
                    <label class="col-sm-1 control-label" for="name">Customer</label>
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



                <div class="form-group">
                    <label class="col-sm-2 control-label">Old Cheque</label>
                    <div class="col-sm-1">
                        <input type="checkbox" id="oldcheque">
                    </div>
                </div>


                <div class="form-group">

                    <label class="col-sm-1 control-label" for="entry_code">Cheque No</label>

                    <div class="col-sm-2">
                        <input type="text" placeholder="Cheque No" id="txt_chequeno" class="form-control  input-sm">
                    </div>

                    <div class="col-sm-1">
                        <input type="button" class="btn btn-default btn-sm" value="..." id="searchcheque" name="searchcheque">
                    </div>                  

                    <label class="col-sm-1 control-label" for="invoice data">Date</label>
                    <div class="col-sm-2">
                        <input type="text"  id="chedate" value="<?php echo date('Y-m-d'); ?>" class="form-control  input-sm">
                    </div>

                    <label class="col-sm-2 control-label" for="entry_code">Cheque Amount</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Cheque Amount" id="txt_payments" class="form-control  input-sm">
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
                        <td style="width: 180px;"><input type="text" placeholder="Total" id="subtot" class="form-control  input-sm"></td>
                        <td></td>
                    </tr>
                </table>




            </div>  
        </form>
    </div>

</section>
<script src="js/return_cheque.js"></script>

<script>
                            new_inv();
</script>
<?php
include 'login.php';
?>