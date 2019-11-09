<section class="content">
    <div class="box box-primary">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <div class="box-header with-border">
            <h3 class="box-title">Trail Balance</h3>
        </div>

        <form role="form" action="report_trial_bal.php" target="_blank" method="GET" class="form-horizontal">
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
                </div>

                <div class="form-group">
                    
                     <input type="submit" class="btn btn-default fa fa-print" value="View">
                </div>






            </div>
        </form>
    </div>       
</div>
</section>