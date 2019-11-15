<section class="content">
    <div class="box box-primary">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <div class="box-header with-border">
            <h3 class="box-title">GL Reports</h3>
        </div>

        <form role="form" name='form1' action="report_ledger_link1.php" target="_blank" method="GET" class="form-horizontal">
            <div class="box-body">

                <div class="form-group">
                    <label class="col-sm-1 control-label" for="from">From</label> 
                    <div class="col-sm-2">
                        <input type="text" value="<?php echo date('Y-m-d'); ?>" placeholder="From" id="dtfrom" name="dtfrom"  class="form-control dt">
                    </div>
                    <label class="col-sm-1 control-label" for="from">To</label> 
                    <div class="col-sm-2">
                        <input type="text" value="<?php echo date('Y-m-d'); ?>" placeholder="To" id="dtto" name = "dtto" class="form-control dt">
                    </div>
                    
                     <div class="col-sm-3 pull-right">
                        <select  id="view1" name="view1" class="form-control input-sm">
                            <option value='view'>Print View</option>
                            <option value='pdf'>PDF</option> 
                            <option value='excel'>Excel</option>    
                        </select>
                    </div>
                    
                </div>
                
                
                <div class="form-group">
                    <div class="col-sm-4">
                        <div class="radio">
                            <label>
                                <input type="radio" name="optionsRadios" id="op_glreport" value="op_glreport" checked="">
                                GL Report
                            </label>
                        </div>
                         
                        <div class="radio">
                            <label>
                                <input type="radio" name="optionsRadios" id="op_stamp" value="op_stamp">
                                Stamp Duty
                            </label>
                        </div>

                    </div>
                    <div class="col-sm-1">
                        <select id="currency" name="currency"    onchange='loadcur();' class="form-control input-sm">
                            <option value='LKR'>LKR</option>
                            <?php
                            $sql = "select * from mastercurrancy where currancy <> 'LKR'";
                            foreach ($conn->query($sql) as $row) {
                                echo "<option value='" . trim($row["currancy"]) . "'>" . $row["currancy"] . "</option>";
                            }
                            ?>
                        </select>

                    </div>
                </div>

<table class="table table-striped" style="width:550px;">
                    
                    <tr class='info'> 
                        <th style="width: 120px;">Ledger Code</th>
                        <th style="width: 420px;">Ledger Name</th>
                        <th style="width: 10px;"></th>
                    </tr>

                    <tr>

                        <td>
                            <input type="text" placeholder="Code" id="txt_gl_code" name="txt_gl_code"  class="form-control input-sm">
                        </td>
                        <td>
                            <input type="text" placeholder="Description" id="txt_gl_name" name="txt_gl_name"  class="form-control  input-sm">
                        </td>
                         
                        <td>
                            <a  href="search_ledg.php"  onClick="NewWindow(this.href, 'mywin', '800', '700', 'yes', 'center');
                                    return false" class="btn btn-default btn-sm"> <span class="fa fa-circle-o"></span> &nbsp; </a>
                        </td>
                        
                    </tr>
                </table>


<table class="table table-striped" style="width:550px;visibility :hidden;">
                    
                    <tr class='info'> 
                        <th style="width: 120px;">Customer Code</th>
                        <th style="width: 420px;">Customer Name</th>
                        <th style="width: 10px;"></th>
                    </tr>

<tr>

                        <td>
                        <input type="text" placeholder="Code" id="c_code" name="c_code" class="form-control  input-sm">
                    </td>
                   <td>
                        <input type="text" placeholder="Name" id="c_name" name="c_name" class="form-control  input-sm">
                    </td>
                    <td>
                        <a onfocus="this.blur()" onclick="NewWindow('serach_customer.php', 'mywin', '800', '700', 'yes', 'center');
                                return false" href="">
                            <input type="button" class="btn btn-default btn-sm" value="..." id="searchcust" name="searchcust">
                        </a>
                    </td>
                     </tr>
                </table>
                <div class="form-group">
                    
                     <input type="submit" class="btn btn-default fa fa-print" value="View">
                </div>






            </div>
        </form>
    </div>       
</div>
</section>