<!-- Main content -->
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Airport Details</h3>
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
                            Airport Code
                        </th>
                        <th style="width: 350px;">
                            Airport
                        </th>
                        <th style="width: 350px;">
                            Country
                        </th>
                        <th style="width: 350px;">
                            Town
                        </th>
                        <th style="width: 350px;">
                            Other
                        </th>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" onkeyup="getbycode('code');" placeholder="Airport Code" id="txt_aircode" class="form-control">
                        </td>
                        <td>
                            <input type="text" onkeyup="getbycode('name');" placeholder="Airport" id="txt_airname" class="form-control">
                        </td>
                        <td>
                            <input type="text" onkeyup="getbycode('country');" placeholder="Country" id="txt_country" class="form-control">
                        </td>
                        <td>
                            <input type="text" onkeyup="getbycode('town');" placeholder="Town" id="txt_town" class="form-control">
                        </td>
                        <td>
                            <input type="text" onkeyup="getbycode('other');" placeholder="Other" id="txt_other" class="form-control">
                        </td>
                    </tr>


                </table>


                <div id="itemdetails">





                </div>



            </div>
        </form>
    </div>

</section>

<script src="js/airport.js"></script>
