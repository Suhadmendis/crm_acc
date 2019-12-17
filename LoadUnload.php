<!-- Main content -->
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Loading / Unloading Places</h3>
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
                            ID
                        </th>
                        <th style="width: 350px;">
                            Place
                        </th>
                        <th style="width: 350px;">
                            Inactive
                        </th>
                          <th style="width: 350px;">
                            Desc 2
                        </th>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" placeholder="Code" id="txt_aircode" class="form-control">
                        </td>
                        <td>
                            <input type="text" placeholder="Place" id="txt_airname" class="form-control">
                        </td>
                        <td>
                            <input type="text" placeholder="Active" id="txt_refcode" class="form-control">
                        </td>
                         <td>
                            <input type="text" placeholder="Ref" id="txt_refcode" class="form-control">
                        </td>
                    </tr>
                </table>

                <div id="itemdetails">





                </div>




            </div>
        </form>
    </div>

</section>
<script src="js/loadunload.js"></script>

