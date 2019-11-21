<!-- Main content -->
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">PO Items</h3>
        </div>
        <form role="form" class="form-horizontal">
            <div class="box-body">

                <div class="form-group">
                    <a onclick="newent();" class="btn btn-default">
                        <span class="fa fa-user-plus"></span> &nbsp; New
                    </a>
                     <a onclick="save_inv();" class="btn btn-success">
                        <span class="fa fa-save"></span> &nbsp; Save
                    </a>
                     
                </div>
                
                <div id="msg_box"  class="span12 text-center"  >

                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="carrier_code">Item Code</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Item Code" id="txt_itcode" class="form-control">
                    </div>
                    <div class="col-sm-1">
                        <span style="float:right;"><input type="checkbox" id="active">Active</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">Description</label>
                    <div class="col-sm-5">
                        <input type="text" placeholder="Description" id="txt_description" class="form-control">

                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="address">Amount</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Amount" id="txt_amount" class="form-control">
                    </div>
                </div>			

                <div id="itemdetails">





                </div>
                
                
                
            </div>
        </form>
    </div>

</section>
<script src="js/item_mas.js"></script>
