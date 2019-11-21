<!-- Main content -->
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Purchase Order</h3>
        </div>
        <form name= "form1" role="form" class="form-horizontal">
            <div class="box-body">
                <input type="hidden" id="tmpno" class="form-control">
                <input type="hidden" id="item_count" class="form-control">

                <div class="form-group">
                    <a onclick="sess_chk('new', 'crn');" class="btn btn-default btn-sm">
                        <span class="fa fa-user-plus"></span> &nbsp; New
                    </a>
                    <a onclick="sess_chk('save', 'crn');" class="btn btn-success btn-sm">
                        <span class="fa fa-save"></span> &nbsp; Save
                    </a>

                    <a onclick="sess_chk('print', 'crn');" class="btn btn-default btn-sm">
                        <span class="fa fa-print"></span> &nbsp; Print
                    </a>

                    <a onclick="sess_chk('cancel', 'crn');" class="btn btn-danger btn-sm">
                        <span class="fa fa-trash-o"></span> &nbsp; Cancel
                    </a>

                </div>
                <div id="msg_box"  class="span12 text-center"  ></div>
                <div class="form-group">
                    <label class="col-sm-1 control-label" for="invno">PO No</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="PO NO" id="txt_entno" class="form-control  input-sm">
                    </div>
                    <div class="col-sm-1">
                      <a onfocus="this.blur()" onclick="NewWindow('serach_po.php', 'mywin', '800', '700', 'yes', 'center');
                                return false" href="">
                            <input type="button" class="btn btn-default" value="..." id="searchcust" name="searchcust">
                        </a>
                    </div>
                    <label class="col-sm-2 control-label" for="invdate">Date</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Date" id="invdate" value="<?php echo date('Y-m-d'); ?>" class="form-control dt input-sm">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-1 control-label" for="c_code">Supplier</label>
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
                    <label class="col-sm-1 control-label" for="cus_address">Contact</label>
                    <div class="col-sm-5">
                        <input type="text" placeholder="Name" id="cus_address" class="form-control input-sm">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-1 control-label" for="txt_remarks">Remark</label>
                    <div class="col-sm-5">
                        <input type="text" placeholder="Remarks" id="txt_remarks" class="form-control input-sm">
                    </div>

                    <div class="col-sm-1">
                        &nbsp;
                    </div>
                    <div class="col-sm-5">
                
                        <label  class="col-sm-4 control-label" ><input type="radio" onclick="del_item('.');"  id="non" name="optradio" value="non">&nbsp;Non Vat</label>
                    
                        <label  class="col-sm-3 control-label" ><input type="radio" onclick="del_item('.');" id="svat"name="optradio" value="svat">&nbsp;SVAT</label>
                       
                    </div>
                </div>





                <table class="table table-striped">
                    <tr class='info'>
                        <th style="width: 120px;">Item</th>
                        <th>Description</th>
                        <th style="width: 10px;"></th>
                        <th style="width: 120px;">Qty</th>
                        <th style="width: 120px;">Rate</th>
                        <th style="width: 100px;"></th>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" placeholder="Item" id="itemCode" class="form-control input-sm">
                        </td>
                        <td>
                            <input type="text" placeholder="Description" id="itemDesc" class="form-control input-sm">
                        </td>
                        <td>
                            <a href="" onclick="NewWindow('serach_item.php', 'mywin', '800', '700', 'yes', 'center');
                                    return false" onfocus="this.blur()">
                                <input type="button" name="searchcusti" id="searchcusti" value="..." class="btn btn-default btn-sm">
                            </a>
                        </td>
                        <td>
                            <input type="text" placeholder="Qty" id="qty" class="form-control input-sm">
                        </td>
                        <td>
                            <input type="text"  placeholder="Rate" id="itemPrice" class="form-control input-sm">
                        </td>
                        <td><a onclick="add_tmp();" class="btn btn-default btn-sm"> <span class="fa fa-plus"></span> &nbsp; </a></td>
                    </tr>

                </table>

                <div id="itemdetails" >

                </div>

                <table id='subtotal' class="table">
                    <tr>
                        <td></td>
                        <td></td>
                        <th>Sub Total</th>

                        <td></td>                        
                        <td style="width: 150px;"><input type="text" placeholder="Sub Total" id="subtot" class="form-control input-sm"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <th>SVAT</th>
                        <td></td>                        
                        <td style="width: 150px;"><input type="text" placeholder="SVAT" id="svattot" class="form-control input-sm"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <th>Grand Total</th>

                        <td></td>                        
                        <td style="width: 150px;"><input type="text" placeholder="Grand Total" id="gtot" class="form-control input-sm"></td>
                    </tr>
                </table>		

            </div>
        </form>
    </div>

</section>
<script src="js/po.js"></script>
<script>
                            new_inv();
</script>
<?php
include 'login.php';
include './cancell.php';
?>
    