<?php
include './connection_sql.php';
?>
<!-- Main content -->
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Account Master</h3>
        </div>
        <form  name= "form1" role="form" class="form-horizontal">
            <div class="box-body">

                <div class="form-group">
                    <a onclick="sess_chk('new', 'crn');" class="btn btn-default btn-sm">
                        <span class="fa fa-user-plus"></span> &nbsp; New
                    </a>

                    <a onclick="sess_chk('save', 'crn');" class="btn btn-success btn-sm">
                        <span class="fa fa-save"></span> &nbsp; Save
                    </a>
                </div>

                <div id="msg_box"  class="span12 text-center"  >

                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="carrier_code">Account Code</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Account Code" id="txt_entno" class="form-control input-sm">
                    </div>
                    <div class="col-sm-1">
                        <a onfocus="this.blur()" onclick="NewWindow('search_ledg.php?stname=mas', 'mywin', '800', '700', 'yes', 'center');
                                return false" href="">
                            <input type="button" class="btn btn-default btn-sm" value="..." id="searchcust" name="searchcust">
                        </a>            
                    </div>



                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">Account Name</label>
                    <div class="col-sm-4">
                        <input type="text" placeholder="Account Name" id="txt_accname" class="form-control input-sm">
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label" for="Remarks">Remarks</label>
                    <div class="col-sm-4">
                        <input type="text" placeholder="Remarks" id="txt_remarks" class="form-control input-sm">
                    </div>

                </div>



                <div class="form-group">
                    <label class="col-sm-2 control-label" for="address">Account Type</label>
                    <div class="col-sm-6">

                        <label><input type="radio" name="optradio" id="manu" value="Manufacturing"> Manufacturing </label>
                        <label><input type="radio" name="optradio" id="pnl" value="PNL"> PNL Account </label>
                        <label><input type="radio" name="optradio" id="bal" value="Balance"> Balance Sheet Account </label>

                    </div>

                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="acType"></label>
                    <div class="col-sm-3">
                        <select name="acType"  id="acType" class="form-control input-sm">
                            <option value="Assets">Assets</option>
                            <option value="Liabilities">Liabilities</option>
                            <option value="Equity">Equity</option>
                            <option value="Revenue">Revenue</option>
                            <option value="Expenses">Expenses</option>
                            <option value="Taxation">Taxation</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="acType1"></label>
                    <div class="col-sm-3">
                        <select name="acType1"  id="acType1" class="form-control input-sm">
                            <?php
                            $sql = "select * from lcodes_cat";
                            foreach ($conn->query($sql) as $row) {
                                echo "<option value='" . $row["cat"] . "'>" . $row["cat"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label" for="bank"></label>
                    <div class="col-sm-5">                    
                        <label> <input type="checkbox" id="bank"> Payment Account </label>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label" for="txt_Opening">Opening Balance</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Opening Balance" id="txt_Opening" class="form-control input-sm">
                    </div>

                    <label class="col-sm-1 control-label" for="carrier_code">As at</label>
                    <div class="col-sm-2">
                        <input type="date" placeholder="Date" id="dtpOpenDate" value="<?php echo date('Y-m-d'); ?>" class="form-control input-sm">
                    </div>

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

                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="txt_gl_code">Parent Account</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Code" id="txt_gl_code" class="form-control input-sm">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" placeholder="Description" id="txt_gl_name" class="form-control input-sm">

                    </div>
                    <div class="col-sm-1">
                        <a  href="search_ledg.php"  id="cmd_glcode"  onClick="NewWindow(this.href, 'mywin', '800', '700', 'yes', 'center');
                                return false" class="btn btn-default btn-sm"> <span class="fa fa-circle-o"></span> &nbsp; </a>
                    </div>
                </div>   


                <br /> <br />

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="file-3">File Box</label>
                    <label class="btn btn-default" for="file-3">
                        <input id="file-3" name="file-3" multiple="true" type="file" >
                        Select Files

                    </label>
                    <a  class="btn btn-primary" onclick="uploadfile('led');" class="btn"/>Upload</a>
                </div>




                <div id="filebox" >

                </div>
            </div>
        </form>
    </div>

</section>

<script src="js/account_master.js">

</script>
<script>
    new_inv();
</script>
<?php
include 'login.php';
?>
    