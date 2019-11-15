<section class="content">
    <div class="box box-primary">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <div class="box-header with-border">
            <h3 class="box-title">Help Desk</h3>
        </div>

        <div class="container">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#New_tickets">New Tickets</a></li>
                <li><a data-toggle="tab" href="#User_feedback">User feedback</a></li>
                <li><a data-toggle="tab" href="#IT">IT Conformation</a></li>
            </ul>

            <div class="tab-content">

                <div id="New_tickets" class="tab-pane fade in active">
                    <h3>New Tickets</h3>

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
                            </div>



                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="carrier_code">Ticket No</label>
                                <div class="col-sm-2">
                                    <input type="text" placeholder="Ticket No" id="txt_ticketno" class="form-control">
                                </div>
                                <div class="col-sm-1">
                                    <input type="button" class="btn btn-default" value="..." id="searchcust" name="searchcust">
                                </div>
                                <label class="col-sm-2 control-label" for="carrier_code">Priority</label>
                                <div class="col-sm-2">
                                    <select id="user_name" class="form-control">
                                        <option>High</option>
                                        <option>Medium</option>
                                        <option>Low</option>
                                    </select>
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">Name</label>
                                <div class="col-sm-3">
                                    <select id="user_name" class="form-control">
                                        <option>Nabil</option>
                                        <option>Isuru</option>
                                        <option>Shan</option>
                                        <option>Minolee</option>
                                    </select>                 
                                </div>
                                <label class="col-sm-2 control-label" for="invdate">Date</label>
                                <div class="col-sm-2">
                                    <input type="date" placeholder="Date" id="invdate" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="address">User Name</label>
                                <div class="col-sm-3">
                                    <input type="text" placeholder="User Name" id="txt_username" class="form-control">
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="contact">Department</label>
                                <div class="col-sm-3">
                                    <select id="department" class="form-control">
                                        <option>Lucky Plaza</option>
                                        <option>Finance & Admin</option>
                                        <option>Operations</option>
                                        <option>Sales</option>
                                        <option>Management</option>
                                    </select>
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="address">E-Mail</label>
                                <div class="col-sm-3">
                                    <input type="text" placeholder="E-Mail" id="txt_e-mail" class="form-control">
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="contact">Subjects</label>
                                <div class="col-sm-3">
                                    <select id="c_code" class="form-control">
                                        <option>Hardware</option>
                                        <option>Software</option>
                                        <option>Networking</option>
                                        <option>Internet</option>
                                    </select>
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="contact">Issue In brife</label>
                                <div class="col-sm-3">
                                    <select id="brife" class="form-control">
                                        <option>Application Error</option>
                                        <option>Printer Issue</option>
                                        <option>Scanner Issue</option>
                                        <option>System Issue</option>
                                    </select>
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="address">Issue In Details</label>
                                <div class="col-sm-3">
                                    <input type="text"  placeholder="Issue In Details" id="txt_details" class="form-control">
                                </div>
                            </div>


                        </div>
                    </form>
                </div>


                <div id="User_feedback" class="tab-pane fade">
                    <h3>User Feedback</h3>

                    <form role="form" class="form-horizontal">
                        <div class="box-body">

                            <div class="form-group">
                                <button type="button" class="btn btn-default">
                                    <span class="fa fa-save"></span> &nbsp; Conform
                                </button>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="carrier_code">Ticket No</label>
                                <div class="col-sm-2">
                                    <select id="ticket_no" class="form-control">
                                        <option></option>
                                        <option></option>
                                        <option></option>
                                    </select>			
                                </div>

                                <label class="col-sm-2 control-label" for="invdate">Date</label>
                                <div class="col-sm-2">
                                    <input type="date" placeholder="Date" id="invdate" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">Name</label>
                                <div class="col-sm-3">
                                    <input type="text" placeholder="Name" id="txt_name" class="form-control">
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="address">User Name</label>
                                <div class="col-sm-3">
                                    <input type="text" placeholder="User Name" id="txt_username" class="form-control">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="address">Department</label>
                                <div class="col-sm-3">
                                    <input type="text" placeholder="Department" id="txt_department" class="form-control">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="address">Issue</label>
                                <div class="col-sm-4">
                                    <input type="text" placeholder="Issue" id="txt_issue" class="form-control">
                                    <br />
                                    &nbsp;
                                    <input type="radio" name="radio" id="radio" value="radio" /> Great Work
                                    &nbsp;
                                    <input type="radio" name="radio" id="radio" value="radio" /> Good Work
                                    &nbsp;
                                    <input type="radio" name="radio" id="radio" value="radio" /> Unsatisfy
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="address">Comments</label>
                                <div class="col-sm-3">
                                    <input type="text" placeholder="Comments" id="txt_comments" class="form-control">
                                </div>
                            </div>

                        </div>
                    </form>

                </div>

                <div id="IT" class="tab-pane fade">
                    <h3>IT Conformation</h3>

                    <form role="form" class="form-horizontal">
                        <div class="box-body">

                            <div class="form-group">
                                <button type="button" class="btn btn-default">
                                    <span class="fa fa-save"></span> &nbsp; Solved
                                </button>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="carrier_code">Ticket No</label>
                                <div class="col-sm-3">
                                    <select id="ticket_no" class="form-control">
                                        <option></option>
                                        <option></option>
                                        <option></option>
                                    </select>			
                                </div>

                                <label class="col-sm-2 control-label" for="invdate">Date</label>
                                <div class="col-sm-2">
                                    <input type="date" placeholder="Date" id="invdate" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">Name</label>
                                <div class="col-sm-3">
                                    <input type="text" placeholder="Name" id="txt_name" class="form-control">
                                </div>

                                <label class="col-sm-2 control-label" for="address">Responded By</label>
                                <div class="col-sm-3">
                                    <input type="text" placeholder="Responded By" id="txt_responded" class="form-control">
                                </div>

                            </div>



                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="address">User Name</label>
                                <div class="col-sm-3">
                                    <input type="text" placeholder="User Name" id="txt_username" class="form-control">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="address">Department</label>
                                <div class="col-sm-3">
                                    <input type="text" placeholder="Department" id="txt_department" class="form-control">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="address">E_mail</label>
                                <div class="col-sm-3">
                                    <input type="text" placeholder="E_mail" id="txt_email" class="form-control">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="address">Comments & Action</label>
                                <div class="col-sm-3">
                                    <input type="text" placeholder="Comments & Action" id="txt_comments" class="form-control">
                                </div>
                            </div>


                        </div>
                    </form>

                </div>


            </div>
        </div>       
    </div>
</section>