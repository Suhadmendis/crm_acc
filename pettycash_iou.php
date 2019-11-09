<?php
include './connection_sql.php';
?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


<section class="content">
    <div class="box box-primary">

        <div class="box-header with-border">
            <h3 class="box-title">Petty Cash IOU<?php
                $sql = "SELECT docname from doc_acc WHERE name = '" . $_GET['url'] . "'";
                $result = $conn->query($sql);
                $row = $result->fetch();

                echo $row['docname'];
                ?>      </h3>
        </div>

        <form role="form" name ="form1" class="form-horizontal">
            <div class="box-body">


                <div class="form-group">
                    <a style="margin-left: 10px;"  onclick="new_inv();" class="btn btn-default btn-sm">
                        <span class="fa fa-user-plus"></span> &nbsp; New
                    </a>

                    <a  id="savebtn"  onclick="save_inv();" class="btn btn-success btn-sm">
                        <span class="fa fa-save"></span> &nbsp; Save 
                    </a>

                    <a onclick="NewWindow('search_petticash_iou.php?stname=main', 'mywin', '800', '700', 'yes', 'center');" class="btn btn-info btn-sm">
                        <span class="glyphicon glyphicon-search"></span> &nbsp; FIND
                    </a>

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
                    <label class='col-sm-2' for='c_code'>Ref No.</label>
                    <div class='col-sm-2'>
                        <input type='text' placeholder='Ref No.'  id='ref' class='form-control Name  input-sm' disabled="">
                        <input type='text' placeholder=''  id='uniq' class='form-control Name hidden input-sm ' disabled="">
                        <!--hidden-->
                    </div>
                </div>


                <!--                <div class='form-group'></div>
                                <div class='form-group-sm'>
                                    
                                </div>-->

               
                <div class='form-group'></div>
                <div class='form-group-sm'>
                   
                     <label class='col-sm-2' for='c_code'>Date</label>
                    <div class='col-sm-3'>
                        <input type='date' placeholder='Date'  id='pdate' class='form-control Name input-sm'>
                    </div>
                </div>

                 <div class='form-group'></div>
                <div class='form-group-sm'>
                    <label class='col-sm-2' for='c_code'>Reason</label>
                    <div class='col-sm-4'>
                        <input type='text' placeholder='Reason'  id='reason' class='form-control Name  input-sm'>
                    </div>

                </div>
                 
                 
                
                <div class='form-group'></div>
                <div class='form-group-sm'>
                     <label class='col-sm-2' for='c_code'>Settlement Date</label>              
                    <div class='col-sm-3'>
                        <input type='date' placeholder='Settlement Date'  id='settledate' class='form-control Name  input-sm'>
                    </div>
                    <label class='col-sm-2' for='c_code'>Amount</label>
                    <div class='col-sm-2'>
                        <input type='text' placeholder='Amount'  id='amount' class='form-control Name  input-sm'>
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
<script src="js/pettycash_iou.js"></script>



<?php
include 'login.php';
include './cancell.php';
?>
<script>
                        new_inv();
</script> 