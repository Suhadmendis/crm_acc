<section class="content">
    <div class="box box-primary">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <div class="box-header with-border">
            <h3 class="box-title">PNL & Balance Sheet</h3>
        </div>

        <form role="form" action="report_pnlbs.php" target="_blank" method="GET" class="form-horizontal">
            <div class="box-body">

                <div class="form-group">
                    <div class="col-sm-4">
                        <div class="radio">
                            <label>
                                <input type="radio" name="optionsRadios" id="op_statment" value="op_pnl" checked="">
                                Income Statment
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="optionsRadios" id="op_agedet" value="op_bs">
                                Balance Sheet
                            </label>
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3">
                        <select  id="view" name="view" class="form-control input-sm">
                            <option value='view'>View</option>
                            <option value='monthly'>Monthly</option> 
                            <option disabled value='yearly'>Yearly</option>    
                        </select>
                    </div>
                </div>     

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
                            <!--<option value='pdf'>PDF</option> -->
                            <!--<option value='excel'>Excel</option>    -->
                        </select>
                    </div>
                    
                    
                </div>

                <div class="form-group">

                    <input type="submit" class="btn btn-default fa fa-print" value="View">
                </div>






            </div>
        </form>
    </div>       
</div>
</section>