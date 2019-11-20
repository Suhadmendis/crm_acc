<?php
include './connection_sql.php';
?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


<section class="content">
    <div class="box box-primary">

        <div class="box-header with-border">
            <h3 class="box-title">Payment Approval</h3>
        </div>

        <form role="form" name ="form1" class="form-horizontal">
            <div class="box-body">


                <div class="form-group">
                    <a style="margin-left: 10px;"  onclick="new_inv()" class="btn btn-default btn-sm">
                        <span class="fa fa-user-plus"></span> &nbsp; New
                    </a>

                    <a  id="savebtn"  onclick="save_inv();" class="btn btn-success btn-sm">
                        <span class="fa fa-save"></span> &nbsp; Save
                    </a>

                    <a onclick="NewWindow('search_payment_voucher.php?stname=code', 'mywin', '800', '700', 'yes', 'center');" class="btn btn-info btn-sm">
                        <span class="glyphicon glyphicon-search"></span> &nbsp; FIND
                    </a>
                    <button style="float: right;" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Open Modal</button>



                    <!--                    <a style="float: right;margin-right: 10px;" onclick="NewWindow('list_Manuel_AOD.php', 'mywin', '800', '700', 'yes', 'center');" class="btn btn-info btn-sm">
                                            <span class="glyphicon glyphicon-search"></span> &nbsp; List
                                        </a>
                                        <a onclick="NewWindow('Search_Manuel_AOD.php', 'mywin', '800', '700', 'yes', 'center');" class="btn btn-info btn-sm">
                                            <span class="glyphicon glyphicon-search"></span> &nbsp; FIND
                                        </a>-->

                    <a  onclick="print();" class="btn btn-default btn-sm">
                        <span class="fa fa-print"></span> &nbsp; Print
                    </a> 


                </div>
                <div id="msg_box"></div>

                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-2' for='c_code'>PY No.</label>
                    <div class='col-sm-2'>
                        <input type='text' placeholder='PY No.'  id='py_no' class='form-control Name  input-sm' disabled="">
                        
                    </div>
                </div>

                <!-- Modal -->
                <div id="myModal" class="modal fade" role="dialog">
                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                      </div>
                      <div class="modal-body">
                        

                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-3' for='c_code'>CB Ref</label>
                    <div class='col-sm-5'>
                        <input type='text' placeholder='CB Ref'  id='CB_ref' class='form-control Name  input-sm' >
                        
                    </div>
                </div>

                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-3' for='c_code'>Bill num</label>
                    <div class='col-sm-5'>
                        <input type='text' placeholder='Bill num'  id='Bill_num' class='form-control Name  input-sm' >
                        
                    </div>
                </div>

                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-3' for='c_code'>Bill Date</label>
                    <div class='col-sm-5'>
                        <input type='text' placeholder='Bill Date'  id='Bill_Date' class='form-control Name  input-sm' >
                        
                    </div>
                </div>

                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-3' for='c_code'>Amount</label>
                    <div class='col-sm-5'>
                        <input type='text' placeholder='Amount'  id='amo' class='form-control Name  input-sm' >
                        
                    </div>
                </div>

                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-3' for='c_code'>Remark</label>
                    <div class='col-sm-5'>
                        <input type='text' placeholder='Remark'  id='remark' class='form-control Name  input-sm' >
                        
                    </div>
                </div>

                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-3' for='c_code'>Category</label>
                    <div class='col-sm-5'>
                        <input type='text' placeholder='Category'  id='category' class='form-control Name  input-sm' >
                        
                    </div>
                </div>

















                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>

                  </div>
                </div>

                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-2' for='c_code'>Manual Ref</label>
                    <div class='col-sm-2'>
                        <input type='text' placeholder='Manual Ref'  id='manual_ref' class='form-control Name  input-sm'>
                    </div>
                    <label class='col-sm-1' for='c_code'></label>
                    <label class='col-sm-2' for='c_code'>Date</label>
                    <div class='col-sm-2'>
                        <input type='date' placeholder='date'  id='date' value='<?php echo date("Y-m-d"); ?>' class='form-control Name  input-sm'>
                    </div>
                </div>

                <!--                <div class='form-group'></div>
                                <div class='form-group-sm'>
                                   
                                </div>-->






                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-2' for='c_code'>Payment Cash Book</label>
                    <div class='col-sm-2'>
                        <input type='text' placeholder='Payment Cash Book'  id='payment_cash_book' class='form-control Name  input-sm'>
                    </div>
                    <div class='col-sm-1'>
                        <a  href="search_ledg.php?stname=payment_voucher"   onClick="NewWindow(this.href, 'mywin', '800', '700', 'yes', 'center');
                                return false" class="btn btn-default btn-sm"> <span class="fa fa-circle-o"></span> &nbsp; </a>
                    </div>
                </div>
                 <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-2' for='c_code'></label>
                    <div class='col-sm-2'>
                        <input type="text" placeholder="Description" id="txt_gl_name" class="form-control input-sm">
                    </div>

                </div>


                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-2' for='c_code'>C Bal Ref</label>
                    <div class='col-sm-2'>
                        <input type='text' placeholder='c Bal Ref'  id='c_ref' class='form-control Name  input-sm'>
                    </div>
                    <div class='col-sm-1'>
                        <a  href="search_c_bal.php?stname=vou"   onClick="NewWindow(this.href, 'mywin', '800', '700', 'yes', 'center');
                                return false" class="btn btn-default btn-sm"> <span class="fa fa-circle-o"></span> &nbsp; </a>
                    </div>
                    <label class='col-sm-2' for='c_code'>Amount</label>
                    <div class='col-sm-2'>
                        <input type="text" placeholder="Description" id="Amount" class="form-control input-sm">
                    </div>
                    <label class='col-sm-1' for='c_code'>Balance</label>
                    <div class='col-sm-2'>
                        <input type="text" placeholder="Description" id="Balance" class="form-control input-sm">
                    </div>
                </div>


                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-2' for='c_code'></label>
                    <div class='col-sm-2'>
                        <input type="text" placeholder="Description" id="c_des" class="form-control input-sm">
                    </div>
                    <label class='col-sm-1' for='c_code'></label>
                    <label class='col-sm-2' for='c_code'>C BAL Date</label>
                    <div class='col-sm-2'>
                        <input type="text" placeholder="Description" id="c_date" class="form-control input-sm">
                    </div>
                    <!-- <label class='col-sm-1' for='c_code'>HELLO_1</label>
                    <div class='col-sm-2'>
                        <input type="text" placeholder="Description" id="txt_gl_name" class="form-control input-sm">
                    </div> -->

                </div>

                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-2' for='c_code'>Currency code</label>
                    <!--                    <div class='col-sm-2'>
                                            <input type='text' placeholder='Currency code'  id='currency_code' class='form-control Name  input-sm'>
                                        </div>-->
                    <div class='col-sm-2'>
                        <select class="form-control form-control input-sm" id='currency_code'>
                            <option value="LKR">LKR</option>
                            <option value="USD">USD</option> 
                        </select>
                    </div>
                    <label class='col-sm-1' for='c_code'></label>
                    <label class='col-sm-2' for='c_code'>Rate</label>
                    <div class='col-sm-2'>
                        <input type='text' placeholder='Rate'  id='rate' class='form-control Name  input-sm'>
                    </div>
                </div>

                <!--                <div class='form-group'></div>
                                <div class='form-group-sm'>
                                    
                                </div>-->

                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-2' for='c_code'>Payment Type</label>
                    <!--                    <div class='col-sm-2'>
                                            <input type='text' placeholder='Payment Type'  id='payment_type' class='form-control Name  input-sm'>
                                        </div>-->

                    <div class='col-sm-2'>
                        <select class="form-control form-control input-sm" id='payment_type'>
                            <option value="Cash">Cash</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Direct Debit">Direct Debit</option>
                            <option value="Credit Card">Credit Card</option> 
                            <option value="TT">TT</option> 
                        </select>
                    </div>
                    <label class='col-sm-1' for='c_code'></label>
                    <label class='col-sm-2' for='c_code'>Cheque No./Ref</label>
                    <div class='col-sm-2'>
                        <input type='text' placeholder='Cheque No./Ref'  id='cheque_no_ref' class='form-control Name  input-sm'>
                    </div>
                </div>



                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <div class='col-sm-5'></div>
                    <label class='col-sm-2' for='c_code'>Chq. Date/Ref</label>
                    <div class='col-sm-2'>
                        <input type='text' placeholder='Chq. Date/Ref'  id='chq_date_ref' class='form-control Name  input-sm'>
                    </div>
                </div>

                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-2' for='c_code'>Chq. Bank/Ref</label>
                    <div class='col-sm-2'>
                        <input type='text' placeholder='Chq. Bank/Ref'  id='chq_bank_ref' class='form-control Name  input-sm'>
                    </div>
                </div>

                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-2' for='c_code'>Allocation Percentage</label>
                    <!--                    <div class='col-sm-2'>
                                            <input type='text' placeholder='allocation_percentage'  id='allocation_percentage' class='form-control Name  input-sm'>
                                        </div>-->
                    <div class='col-sm-2'>
                        <select class="form-control form-control input-sm" id='allocation_percentage'>
                            <option value="20">20%</option>
                            <option value="40">40%</option>
                            <option value="60">60%</option>
                            <option value="80">80%</option> 
                            <option value="100">100%</option> 
                        </select>
                    </div>
                </div>

                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-2' for='c_code'>Total amount of Payment</label>
                    <div class='col-sm-2'>
                        <input type='text' placeholder='Total amount of Payment' onkeyup="resCal();"  id='total_amount_of_payment' class='form-control Name  input-sm'>
                    </div>
                    <label class='col-sm-1' for='c_code'></label>
                    <label class='col-sm-2' for='c_code'>Allocated</label>
                    <div class='col-sm-2'>
                        <input type='text' placeholder='allocated'  id='allocated' class='form-control Name  input-sm'>
                    </div>
                </div>

                <!--                <div class='form-group'></div>
                                <div class='form-group-sm'>
                                    
                                </div>-->

                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <div class='col-sm-5'></div>
                    <label class='col-sm-2' for='c_code'>to_be_allocated</label>
                    <div class='col-sm-2'>
                        <input type='text' placeholder='to_be_allocated'  id='to_be_allocated' class='form-control Name  input-sm'>
                    </div>
                </div>

                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-2' for='c_code'>Payee Name</label>
                    <!--                    <div class='col-sm-4'>
                                            <input type='text' placeholder='payee_name'  id='payee_name' class='form-control Name  input-sm'>
                                        </div>-->
                    <div class='col-sm-2'>
                        <input id="payee_name" class='col-sm-12 form-control input-sm' list="temp1" name="temp1">
                        <datalist id="temp1">
                            <option value="Firefox">
                            <option value="Chrome">
                        </datalist>

                    </div>
                </div>

                <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-2' for='c_code'>Remark</label>
                    <div class='col-sm-6'>
                        <input type='text' placeholder='remark'  id='remark' class='form-control Name  input-sm'>
                    </div>
                </div>

            </div>  

   
        </form>


        <br>
        <br>
        <br>
        <br>

    </div>    

</section>
<script src="js/pay_voucher.js"></script>
<!--<script src="js/payment_voucher.js"</script>-->



<!--<script src="js/Manuel_aod_table.js">
</script>-->
<?php
include 'login.php';
include './cancell.php';
?>
<script>
                            new_inv();
</script> 