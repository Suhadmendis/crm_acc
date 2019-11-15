<!-- Modal -->
<div class="modal fade" id="myModal_c" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Confirm Cancel</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="form-signin">

                        <div class="modal-body">
                            <p>Cancel this entry?&hellip;</p>
                        </div>
                        <input type="hidden" id="action">
                        <input type="hidden" id="form">

                    </div>
                </div> <!-- /container -->
                <span   id="txterror">

                </span>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button class="btn btn-primary"  onclick="cancel_inv();">Confirm</button>
            </div>
        </div>
    </div>
</div>
<script src="js/user.js"></script>