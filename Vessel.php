<!-- Main content -->
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Vessel</h3>
        </div>
        <form role="form" class="form-horizontal">
            <div class="box-body">

                <div class="form-group">
                    <a onclick="newent();" class="btn btn-default">
                        <span class="fa fa-user-plus"></span> &nbsp; New
                    </a>
                    <button type="button" class="btn btn-default">
                        <span class="fa fa-save"></span> &nbsp; Save
                    </button>
                    <button type="button" class="btn btn-default">
                        <span class="fa fa-trash-o"></span> &nbsp; Delete
                    </button>
                </div>

                <table  class='table'>

                    <tr>
                        <th style="width: 350px;">
                            Con ID
                        </th>
                        <th style="width: 350px;">
                            Vessel
                        </th>
                        <th style="width: 350px;">
                            IMO
                        </th>
                        <th style="width: 350px;">
                            Flag
                        </th>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" onkeyup="getbycode('con_id');" placeholder="Con ID" id="txt_conid" class="form-control">
                        </td>
                        <td>
                            <input type="text" onkeyup="getbycode('vessel');" placeholder="Vessel" id="txt_vessel" class="form-control">
                        </td>
                        <td>
                            <input type="text" onkeyup="getbycode('imp');" placeholder="IMO" id="txt_imo" class="form-control">
                        </td>
                        <td>
                            <input type="text" onkeyup="getbycode('flag');" placeholder="Flag" id="txt_flag" class="form-control">
                        </td>
                    </tr>


                </table>

                <div id="itemdetails">





                </div>
            </div>
        </form>
    </div>

</section>

<script src="js/vessel.js"></script>
