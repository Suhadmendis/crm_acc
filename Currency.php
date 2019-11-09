<!-- Main content -->
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Currency</h3>
        </div>
        <form role="form" class="form-horizontal">
            <div class="box-body">

                <div class="form-group">
                    <a onclick="newent();" class="btn btn-default">
                        <span class="fa fa-user-plus"></span> &nbsp; New
                    </a>
                    <a onclick="save_inv();" class="btn btn-success">
                        <span class="fa fa-user-plus"></span> &nbsp; Save
                    </a>
                     
                </div>
                
                
                <div id="msg_box"  class="span12 text-center"  >

                </div>

                <table  class='table'>

                    <tr>
                        <th style="width: 350px;">
                            Currency
                        </th>
                        <th style="width: 350px;">
                            ByExRate
                        </th>
                        <th style="width: 350px;">
                            LocalClientSell
                        </th>
                        <th style="width: 350px;">
                            gentSellRate
                        </th>
                        <th style="width: 350px;">
                            TotalRate
                        </th>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" onkeyup="getbycode('currency');" placeholder="Currency" id="txt_currency" class="form-control">
                        </td>
                        <td>
                            <input type="text" onkeyup="getbycode('byexrate');" placeholder="ByExRate" id="txt_byexrate" class="form-control">
                        </td>
                        <td>
                            <input type="text" onkeyup="getbycode('localclient');" placeholder="LocalClientSell" id="txt_localclient" class="form-control">
                        </td>
                        <td>
                            <input type="text" onkeyup="getbycode('gentsell');" placeholder="gentSellRate" id="txt_gentsell" class="form-control">
                        </td>
                        <td>
                            <input type="text" onkeyup="getbycode('totalrate');" placeholder="TotalRate" id="txt_totalrate" class="form-control">
                        </td>
                    </tr>


                </table>



                <div id="itemdetails">





                </div>
            </div>
        </form>
    </div>

</section>

<script src="js/currency.js"></script>
