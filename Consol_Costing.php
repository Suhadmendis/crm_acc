<!-- Main content -->
<section class="content">

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Consol Costing</h3>
		</div>
		<form role="form" class="form-horizontal">
			<div class="box-body">

				<div class="form-group">
					<button type="button" class="btn btn-default">
						<span class="fa fa-user-plus"></span> &nbsp; New
					</button>
					<button type="button" class="btn btn-default">
						<span class="fa fa-save"></span> &nbsp; Save
					</button>
                    <button type="button" class="btn btn-default">
						<span class="fa fa-unlock"></span> &nbsp; Edit
					</button>
					<button type="button" class="btn btn-default">
						<span class="fa fa-print"></span> &nbsp; Print Cheque
					</button>
                    <button type="button" class="btn btn-default">
						<span class="fa fa-print"></span> &nbsp; Print Voucher
					</button>
                    <button type="button" class="btn btn-default">
						<span class="fa fa-refresh"></span> &nbsp; Refresh
					</button>
                    <button type="button" class="btn btn-default">
						<span class="fa fa-trash-o"></span> &nbsp; Delete
					</button>
				</div>
                
    
                <div class="form-group">
					<label class="col-sm-1 control-label" for="carrier_code">Costing</label>
					<div class="col-sm-2">
						<input type="text" placeholder="Costing" id="txt_costing" class="form-control">
                        </div>
                        <div class="col-sm-1">
						<input type="button" class="btn btn-default" value="..." id="searchcost" name="searchcost">
                       </div>
                    <div class="col-sm-2">
						 <input type="date" placeholder="Date" id="entrydate" value="<?php echo date('Y-m-d'); ?>" class="form-control">
					</div>
                    <div class="col-sm-1">
						 <select id="currency" class="form-control">
                         <option>LKR</option>
                         <option>USD</option>
                         <option>EURO</option>
                         </select> 
					</div>
                    <div class="col-sm-1">
						<input type="text" placeholder="Amount" id="txt_amount" class="form-control">
                        </div>
                    <label class="col-sm-1 control-label" for="carrier_code">Consol No</label>
					<div class="col-sm-2">
						<input type="text" placeholder="Consol No" id="txt_consolno" class="form-control">
                        </div>
                        <div class="col-sm-1">
						<input type="button" class="btn btn-default" value="..." id="searchconsol" name="searchconsol">
                       </div>
					
				</div>                          
			</div>
		</form>
	</div>

</section>
