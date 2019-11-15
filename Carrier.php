<!-- Main content -->
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Carrier Details</h3>
        </div>
        <form role="form" class="form-horizontal">
            <div class="box-body">

                <div class="form-group">
                    <a onclick="newent();" class="btn btn-default">
                        <span class="fa fa-user-plus"></span> &nbsp; New
                    </a>
                    <button type="button" class="btn btn-default">
                        <span class="fa fa-save"></span> &nbsp; Save
                    </button>
                    <button type="button" class="btn btn-default">
                        <span class="fa fa-trash-o"></span> &nbsp; Delete
                    </button>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="carrier_code">Carrier Code</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Carrier Code" id="txt_c.code" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">Name</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Name" id="txt_name" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="address">Address</label>
                    <div class="col-sm-5">
                        <input type="text" placeholder="Address" id="txt_address" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="contact">Contact</label>
                    <div class="col-sm-5">
                        <input type="text" placeholder="Contact" id="txt_contact" class="form-control">
                    </div>
                </div>
                
                
                
                 <div id="itemdetails">





                </div>

            </div>
        </form>
    </div>

</section>
<script src="js/carrier.js"></script>
