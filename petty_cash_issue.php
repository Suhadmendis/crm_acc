<?php
include './connection_sql.php';
?>
<!-- Main content -->
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Petty Cash Issue</h3>
        </div>  
        <form  name= "form1"  role="form" class="form-horizontal">
            <div class="box-body">

                <div class="form-group">
                    <a onclick="sess_chk('new', 'crn');" class="btn btn-default btn-sm">
                        <span class="fa fa-user-plus"></span> &nbsp; New
                    </a>

                    <a onclick="sess_chk('save', 'crn');" class="btn btn-primary btn-sm">
                        <span class="fa fa-save"></span> &nbsp; Save
                    </a>

                    <a onclick="print_inv('');" class="btn btn-default btn-sm">
                        <span class="fa fa-print"></span> &nbsp; Print
                    </a> 

                    <a onclick="sess_chk('cancel', 'crn');" class="btn btn-danger btn-sm">
                        <span class="fa fa-trash-o"></span> &nbsp; Cancel
                    </a>
                </div>

                <div id="msg_box"  class="span12 text-center"  ></div>
                <input type="hidden"  id="tmpno" >
                <div class="form-group">
                    <label class="col-sm-1 control-label" for="txt_entno">Entry No</label>
                    <div class="col-sm-3">
                        <input type="text" placeholder="Entry No" id="txt_entno" class="form-control input-sm">
                    </div>

                    <div class="col-sm-1">
                        <a onfocus="this.blur()" onclick="NewWindow('search_pt_issue.php?stname=pc_iss', 'mywin', '800', '700', 'yes', 'center');
                                return false" href="">
                            <input type="button" class="btn btn-default" value="..." id="searchcust" name="searchcust">
                        </a>
                    </div>

                    <label class="col-sm-1 control-label" for="invdate">Date</label>
                    <div class="col-sm-2">
                        <input type="text" onchange="getno1();"  class="form-control dt" placeholder="Date" id="invdate" value="<?php echo date('Y-m-d'); ?>" class="form-control input-sm">
                    </div>
                </div>

                <div class="form-group">

                    <label class="col-sm-1 control-label">Heading</label>
                    <div class="col-sm-5">
                        <textarea class="form-control" rows="4" id="txt_remarks" placeholder="Remark"></textarea>
                    </div>
                </div>

               
                <div class="form-group">
                    <label class="col-sm-1 control-label" for="txt_entno">IOU</label>
                    <div class="col-sm-3">
                        <input type="text" placeholder="IOU Ref" id="iou_ref" class="form-control input-sm">
                    </div>

                    <div class="col-sm-1">
                        <a onfocus="this.blur()" onclick="NewWindow('search_petticash_iou.php?stname=pc_iss', 'mywin', '800', '700', 'yes', 'center');
                                return false" href="">
                            <input type="button" class="btn btn-default" value="..." id="searchcust" name="searchcust">
                        </a>
                    </div>

                 
                </div>

                <div class="form-group">
                      <label class="col-sm-1 control-label">Reason</label>
                    <div class="col-sm-5">
                        <textarea class="form-control" rows="4" id="txt_reason" placeholder="Reason"></textarea>
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

                </div>


                <table class="table table-striped">
                    <tr class='success'>
                        <th colspan="6">Debit</th>

                    </tr>

                    <tr   class='info'>
                        <th class="info" style="width: 120px;">Code</th>
                        <th style="width: 420px;">Account</th>
                        <th class="info">Description</th>
                        <th class="info" style="width: 10px;"></th>
                        <th class="info"style="width: 120px;">Amount</th>
                        <th class="info" style="width: 10px;"></th>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" placeholder="Code" id="txt_gl_code" class="form-control input-sm">
                        </td>
                        <td>
                            <input type="text" placeholder="Account" id="txt_gl_name" class="form-control input-sm">
                        </td>
                        <td>
                            <input type="text" placeholder="Description" id="txt_gl_name3" class="form-control  input-sm">
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
                        <td></td>
                        <td></td>
                        <th>Total</th>
                        <td style="width: 180px;"><input type="text" placeholder="Total" id="subtot" disabled="disabled" class="form-control input-sm"></td>
                        <td></td>
                    </tr>
                </table>

                <table class="table table-striped">
                    <tr  class='success'>
                        <th colspan="6">Credit</th>

                    </tr>
                    <tr   class='info'>
                        <th style="width: 120px;">Code</th>
                        <th style="width: 420px;">Account</th>
                        <th class="info">Description</th>
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
                            <input type="text" placeholder="Description" id="txt_gl_name4" class="form-control input-sm">
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
                        <td></td>
                        <td></td>
                        <th>Total</th>
                        <td style="width: 180px;"><input type="text" placeholder="Total" id="subtot1" disabled="disabled" class="form-control input-sm"></td>
                        <td></td>
                    </tr>
                </table>


<div class="form-group" hidden="" style="visibility: hidden" id="filup">
                        <label class="col-sm-1 control-label" for="file-3">File Box</label>
                        <label class="btn btn-default" for="file-3">
                        <input id="file-3" name="file-3" multiple="true" type="file" >
                        Select Files

                        </label>
                        <a  class="btn btn-primary" onclick="uploadfile('crn');" class="btn"/>Upload</a>
                    </div>




                    <div hidden="" id="filebox" >

                    </div>






            </div>
        </form>
    </div>

</section>

<script src="js/petty_cash_issue.js">

</script>
<script>
    new_inv();
</script>
<?php
include 'login.php';
include './cancell.php';
?>
    
