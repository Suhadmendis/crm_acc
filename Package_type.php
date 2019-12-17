<!-- Main content -->
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Package Types</h3>
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
                            Package Type
                        </th>
                        <th style="width: 350px;">
                            Description
                        </th>
                        <th style="width: 350px;">
                            Ref Code
                        </th>

                    </tr>

                    <tr>
                        <td>
                            <input type="text" placeholder="Package Type" id="txt_aircode" class="form-control">
                        </td>
                        <td>
                            <input type="text" placeholder="Description" id="txt_airname" class="form-control">
                        </td>
                        <td>
                            <input type="text" placeholder="Ref Code" id="txt_refcode" class="form-control">
                        </td>

                    </tr>
                </table>


  			
                <div id="itemdetails">





                </div>
            </div>
        </form>
    </div>

</section>

<script src="js/Package_type.js"></script>