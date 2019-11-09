<!-- Main content -->
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Stock Card</h3>
        </div>
        <form name= "form1" role="form" class="form-horizontal">
            <div class="box-body">
                <input type="hidden" id="tmpno" class="form-control">
                <input type="hidden" id="item_count" class="form-control">

                <div id="msg_box"  class="span12 text-center"  ></div>
                <div class="form-group">
                    <label class="col-sm-1 control-label" for="invno">Item Code</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Item Code" id="txt_code" class="form-control  input-sm">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Description" id="txt_descript" class="form-control  input-sm">
                    </div>
                    <div class="col-sm-1">
                        <a onfocus="this.blur()" onclick="NewWindow('serach_item_bin.php', 'mywin', '800', '700', 'yes', 'center');
                                return false" href="">
                            <input type="button" class="btn btn-default" value="..." id="searchcust" name="searchcust">
                        </a>
                    </div>
                </div>

                <div id="itemdetails" >

                </div>


            </div>
        </form>
    </div>

</section>
<script src="js/po.js"></script>


<script>

                            function load_home(cdata) {
                                if (cdata != '') {
                                    $('#myModal_c').modal('show');
                                    document.getElementById("content").innerHTML = '<object style="width:700px;height:500px;float:center"  type="text/html" data="' + cdata + '"></object>';
                                }
                            }
</script>


<?php
include './popup.php';
?>