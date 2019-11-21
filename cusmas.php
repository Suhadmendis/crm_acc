<section class="content">
    <div class="box box-primary">

        <div class="box-header with-border">
            <h3 class="box-title">Client Details</h3>
        </div>

        <form role="form" class="form-horizontal">
            <div class="box-body">

                <div class="form-group">
                    <a onclick="newent();" class="btn btn-default">
                        <span class="fa fa-user-plus"></span> &nbsp; New
                    </a>
                    <a onclick="save_inv();" class="btn btn-default">
                        <span class="fa fa-save"></span> &nbsp; Save
                    </a>


                    <a onclick="cancel_inv();" class="btn btn-default">
                        <span class="fa fa-trash-o"></span> &nbsp; Cancel
                    </a>
                </div>


                <div class="col-md-4" style="min-height: 750px;"><label>Clients Search</label> <br>

                    <table>
                        <tr>
                            <td>
                                <label>Client ID</label>
                                <input type="text" placeholder="Client ID" id="txt_ccode" class="form-control">
                            </td>
                            <td>
                                <label>Client Name</label>
                                <input type="text" placeholder="Client Name" id="txt_cname" class="form-control">                    
                            </td>
                        </tr>
                    </table>

                    <div id="itemdetails" >

                    </div>


                </div>


                <div class="col-md-4" style="min-height : 750px;">
                    <br>
                    <label>Client ID</label>
                    <div>
                        <input type="text" id="txt_clidnew" class="form-control"  placeholder="Client ID">

                    </div>

                    <div>
                        <label>Client Name</label>
                        <input type="text" id="txt_clnamenew" class="form-control" placeholder="Client Name">
                    </div>

                    <div>
                        <label>Client Address</label>
                        <textarea class="form-control" cols="55" rows="3" id="txt_cladnew" placeholder="Client Address"></textarea>
                    </div>

                    <div>
                        <label>Contact </label>
                        <input type="text" id="txt_clcontactnew" class="form-control" placeholder="Contact">
                    </div>

                    <div>
                        <label>Email</label>
                        <input type="Email" id="txt_clmailnew" class="form-control" placeholder="E-Mail">
                    </div>

                    <br>
                    <div>
                        <table>
                            <tr>
                                <td>

                                    <label>C.Code</label>
                                    <select id="c_code" class="form-control"><option>Nabil</option></select>
                                </td>
                                <td>
                                    <label>C.Name</label>
                                    <input type="text" class="form-control" id="txt_c_code">     

                                <td>
                                    <label>Press Add</label>
                                    <button type="button" class="btn btn-default" id="new_ccode">
                                        <span class="fa fa-user-plus"></span>New
                                    </button>
                                </td>
                                </td>
                            </tr>
                        </table>        
                    </div>
                    <br>


                    <div><label>Client Type</label>
                        <br>
                        <input type="checkbox" id="chexporter">Exporter &nbsp;&nbsp;
                        <input type="checkbox" id="chconsignee">Consignee &nbsp;&nbsp;
                        <input type="checkbox" id="chnotify">Notify Party &nbsp;&nbsp;
                        <input type="checkbox" id="chagent">Agent &nbsp;<br>
                        <input type="checkbox" id="chcarrier">Carrier &nbsp;&nbsp;
                        <input type="checkbox" id="chbroker">Broker &nbsp;&nbsp;
                        <input type="checkbox" id="">Fright Foward &nbsp;&nbsp;
                    </div>
                    <br>
                    <div>
                        <label>Client Type</label>
                        <br>
                        <input type="checkbox" id="chactive">Active client &nbsp;&nbsp;
                        <input type="checkbox" id="chpay">Payable &nbsp;&nbsp;
                        <input type="checkbox" id="chreceive">Receivable  &nbsp;&nbsp;
                    </div>
                </div>


                <div class="col-md-4"  style="min-height: 750px;"><label>Tax Information</label>
                    <br>

                    <label>VAT No</label> 
                    <input type="text" id="txt_clvatno" placeholder="VAT No" class="form-control">

                    <label>SVAT No</label>
                    <input type="text" id="txt_clsvatno" placeholder="SVAT No" class="form-control">

                    <br><label>Account Information LKR</label><br><br>
                    <table>
                        <tr>
                            <td>
                                <label>Bank Name</label>&nbsp;&nbsp;
                            </td>
                            <td>
                                <input type="text" id="txt_clbankname" placeholder="Bank Name" class="form-control">
                            </td>
                        </tr>

                        <tr>
                            <td>            
                                <label>Address</label>&nbsp;&nbsp;
                            </td>
                            <td>
                                <input type="text" id="txt_claddress" placeholder="Address" class="form-control">
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label>Acc No</label>&nbsp;&nbsp;
                            </td>
                            <td>
                                <input type="text" id="txt_claccno" placeholder="Acc No" class="form-control">
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label>Swift Code</label>&nbsp;&nbsp;
                            </td>
                            <td>
                                <input type="text" id="txt_clswiftcode" placeholder="Swift Code" class="form-control">
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label>Beneficiary</label>&nbsp;&nbsp;
                            </td>
                            <td>
                                <input type="text" id="txt_clbeneficiary" placeholder="Beneficiary" class="form-control">
                            </td>
                        </tr>                
                    </table>



                    <br><label>Account Information USD</label><br><br>
                    <table>
                        <tr>
                            <td>
                                <label>Bank Name</label>&nbsp;&nbsp;
                            </td>
                            <td>
                                <input type="text" id="txt_clbankname" placeholder="Bank Name" class="form-control">
                            </td>
                        </tr>

                        <tr>
                            <td>            
                                <label>Address</label>&nbsp;&nbsp;
                            </td>
                            <td>
                                <input type="text" id="txt_claddress" placeholder="Address" class="form-control">
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label>Acc No</label>&nbsp;&nbsp;
                            </td>
                            <td>
                                <input type="text" id="txt_claccno" placeholder="Acc No" class="form-control">
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label>Swift Code</label>&nbsp;&nbsp;
                            </td>
                            <td>
                                <input type="text" id="txt_clswiftcode" placeholder="Swift Code" class="form-control">
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label>Beneficiary</label>&nbsp;&nbsp;
                            </td>
                            <td>
                                <input type="text" id="txt_clbeneficiary" placeholder="Beneficiary" class="form-control">
                            </td>
                        </tr>                
                    </table>


                    <br><label>Introduuce By</label><br><br>
                    <table>
                        <tr>
                            <td>
                                <label>Group</label>&nbsp;&nbsp
                            </td>
                            <td>
                                <select id="group" class="form-control"><option>Test</option></select>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label>SCAC</label>&nbsp;&nbsp
                            </td>
                            <td>
                                <input type="text" id="txt_scac" size="20" placeholder="SCAC" class="form-control">
                            </td>
                        </tr>
                    </table>

                </div>       
            </div>
        </form>	

    </div>
</section>

<script src="js/cusmas.js"></script>