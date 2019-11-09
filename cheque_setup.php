<?php
include_once("./connection_sql.php");
?>	

<!-- Main content -->
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Cheque Setup Details</h3>
        </div>




        <div class="box-body">

            <div class="form-group">                    
                <a onclick="sess_chk('save', 'crn');" class="btn btn-success btn-sm">
                    <span class="fa fa-save"></span> &nbsp; Save
                </a>                 
            </div>

             <div id="msg_box"  class="span12 text-center"  >

             </div>

            <form name="form1" id="form1" target="_blank" action="disp_chq.php">            
                <input type="hidden" name="hiddencount" id="hiddencount" />                                                  

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="bank">Account</label>
                    <div class="col-sm-5">
                        <select name="com_bank" id="com_bank" onchange="load_bank();" class="form-control">
                            <option value=""></option>
                            <?php
                            $sql = "select * from lcodes where cat='B'";

                            foreach ($conn->query($sql) as $row) {
                                echo "<option value=\"" . $row["C_CODE"] . "\">" . $row["C_CODE"] . " " . $row["C_NAME"] . "</option>	";
                            }
                            ?>
                        </select>
                    </div>
                </div>





                <table class="table">    
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <th>Left</th>
                        <th>Top</th>
                        <th>Font Name</th>  
                        <th>Font Size</th>   
                    </tr>




                    <tr>
                        <td>1</td>
                        <td>Year 1</td>
                        <td><input type="text"  class="form-control" id="left1" name="left1"/></td>
                        <td><input type="text"  class="form-control" id="top1" name="top1"/></td>
                        <td><select name="font_name1" id="font_name1"  class="form-control">
                                <option value='Arial Narrow'>Arial Narrow</option>
                                <option value='Courier New'>Courier New</option>
                                <option value='Times New Roman'>Times New Roman</option>
                                <option value='Tahoma'>Tahoma</option>
                                <option value='Bodoni MT'>Bodoni MT</option>
                            </select></td>
                        <td><input type="text"  class="form-control" id="fontsize1" name="fontsize1"/></td>
                    </tr>



                    <tr>
                        <td>2</td>
                        <td>Year 2</td>
                        <td><input type="text"  class="form-control"  id="left2" name="left2"  /></td>
                        <td><input type="text"  class="form-control"  id="top2" name="top2"  /></td>
                        <td><select name="font_name2" id="font_name2"  class="form-control"  >
                                <option value='Arial Narrow'>Arial Narrow</option>
                                <option value='Courier New'>Courier New</option>
                                <option value='Times New Roman'>Times New Roman</option>
                                <option value='Tahoma'>Tahoma</option>
                                <option value='Bodoni MT'>Bodoni MT</option>
                            </select></td>
                        <td><input type="text"  class="form-control"  id="fontsize2" name="fontsize2"/></td>

                    </tr>
                    <tr>
                        <td>3</td>
                        <td>
                            Month 1
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="left3" name="left3"  />
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="top3" name="top3"  />
                        </td>
                        <td><select name="font_name3" id="font_name3"  class="form-control"  >
                                <option value='Arial Narrow'>Arial Narrow</option>
                                <option value='Courier New'>Courier New</option>
                                <option value='Times New Roman'>Times New Roman</option>
                                <option value='Tahoma'>Tahoma</option>
                                <option value='Bodoni MT'>Bodoni MT</option>
                            </select></td>
                        <td>
                            <input type="text"  class="form-control"  id="fontsize3" name="fontsize3"  />
                        </td>

                    </tr>
                    <tr>
                        <td>4</td>
                        <td>
                            Month 2
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="left4" name="left4"  />
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="top4" name="top4"  />
                        </td>
                        <td><select name="font_name4" id="font_name4"  class="form-control"  >
                                <option value='Arial Narrow'>Arial Narrow</option>
                                <option value='Courier New'>Courier New</option>
                                <option value='Times New Roman'>Times New Roman</option>
                                <option value='Tahoma'>Tahoma</option>
                                <option value='Bodoni MT'>Bodoni MT</option>
                            </select></td>
                        <td>
                            <input type="text"  class="form-control"  id="fontsize4" name="fontsize4"  />
                        </td>

                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Day 1</td>
                        <td>
                            <input type="text"  class="form-control"  id="left5" name="left5"  />
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="top5" name="top5"  />
                        </td>
                        <td><select name="font_name5" id="font_name5"  class="form-control"  >
                                <option value='Arial Narrow'>Arial Narrow</option>
                                <option value='Courier New'>Courier New</option>
                                <option value='Times New Roman'>Times New Roman</option>
                                <option value='Tahoma'>Tahoma</option>
                                <option value='Bodoni MT'>Bodoni MT</option>
                            </select></td>
                        <td>
                            <input type="text"  class="form-control"  id="fontsize5" name="fontsize5"  />
                        </td>

                    </tr>
                    <tr>
                        <td>6</td>
                        <td>
                            Day 2
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="left6" name="left6"  />
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="top6" name="top6"  />
                        </td>
                        <td><select name="font_name6" id="font_name6"  class="form-control"  >
                                <option value='Arial Narrow'>Arial Narrow</option>
                                <option value='Courier New'>Courier New</option>
                                <option value='Times New Roman'>Times New Roman</option>
                                <option value='Tahoma'>Tahoma</option>
                                <option value='Bodoni MT'>Bodoni MT</option>
                            </select></td>
                        <td>
                            <input type="text"  class="form-control"  id="fontsize6" name="fontsize6"  />
                        </td>

                    </tr>
                    <tr>
                        <td>7</td>
                        <td>
                            A/C Payee Only 1
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="left7" name="left7"  />
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="top7" name="top7"  />
                        </td>
                        <td><select name="font_name7" id="font_name7"  class="form-control"  >
                                <option value='Arial Narrow'>Arial Narrow</option>
                                <option value='Courier New'>Courier New</option>
                                <option value='Times New Roman'>Times New Roman</option>
                                <option value='Tahoma'>Tahoma</option>
                                <option value='Bodoni MT'>Bodoni MT</option>
                            </select></td>
                        <td>
                            <input type="text"  class="form-control"  id="fontsize7" name="fontsize7"  />
                        </td>

                    </tr>
                    <tr>
                        <td>8</td>
                        <td>
                            A/C Payee Only 2
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="left8" name="left8"  />
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="top8" name="top8"  />
                        </td>
                        <td><select name="font_name8" id="font_name8"  class="form-control"  >
                                <option value='Arial Narrow'>Arial Narrow</option>
                                <option value='Courier New'>Courier New</option>
                                <option value='Times New Roman'>Times New Roman</option>
                                <option value='Tahoma'>Tahoma</option>
                                <option value='Bodoni MT'>Bodoni MT</option>
                            </select></td>
                        <td>
                            <input type="text"  class="form-control"  id="fontsize8" name="fontsize8"  />
                        </td>

                    </tr>
                    <tr>
                        <td>9</td>
                        <td>
                            Account Payee
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="left9" name="left9"  />
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="top9" name="top9"  />
                        </td>
                        <td><select name="font_name9" id="font_name9"  class="form-control"  >
                                <option value='Arial Narrow'>Arial Narrow</option>
                                <option value='Courier New'>Courier New</option>
                                <option value='Times New Roman'>Times New Roman</option>
                                <option value='Tahoma'>Tahoma</option>
                                <option value='Bodoni MT'>Bodoni MT</option>
                            </select></td>
                        <td>
                            <input type="text"  class="form-control"  id="fontsize9" name="fontsize9"  />
                        </td>

                    </tr>
                    <tr>
                        <td>10</td>
                        <td>
                            Amount in word 1
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="left10" name="left10"  />
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="top10" name="top10"  />
                        </td>
                        <td><select name="font_name10" id="font_name10"  class="form-control"  >
                                <option value='Arial Narrow'>Arial Narrow</option>
                                <option value='Courier New'>Courier New</option>
                                <option value='Times New Roman'>Times New Roman</option>
                                <option value='Tahoma'>Tahoma</option>
                                <option value='Bodoni MT'>Bodoni MT</option>
                            </select></td>
                        <td>
                            <input type="text"  class="form-control"  id="fontsize10" name="fontsize10"  />
                        </td>

                    </tr>
                    <tr>
                        <td>11</td>
                        <td>
                            Amount in word 2
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="left11" name="left11"  />
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="top11" name="top11"  />
                        </td>
                        <td><select name="font_name11" id="font_name11"  class="form-control"  >
                                <option value='Arial Narrow'>Arial Narrow</option>
                                <option value='Courier New'>Courier New</option>
                                <option value='Times New Roman'>Times New Roman</option>
                                <option value='Tahoma'>Tahoma</option>
                                <option value='Bodoni MT'>Bodoni MT</option>
                            </select></td>
                        <td>
                            <input type="text"  class="form-control"  id="fontsize11" name="fontsize11"  />
                        </td>

                    </tr>
                    <tr>
                        <td>12</td>
                        <td>
                            Amount
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="left12" name="left12"  />
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="top12" name="top12"  />
                        </td>
                        <td><select name="font_name12" id="font_name12"  class="form-control"  >
                                <option value='Arial Narrow'>Arial Narrow</option>
                                <option value='Courier New'>Courier New</option>
                                <option value='Times New Roman'>Times New Roman</option>
                                <option value='Tahoma'>Tahoma</option>
                                <option value='Bodoni MT'>Bodoni MT</option>
                            </select></td>
                        <td>
                            <input type="text"  class="form-control"  id="fontsize12" name="fontsize12"  />
                        </td>

                    </tr>
                    <tr>
                        <td>13</td>
                        <td>
                            Barer 1
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="left13" name="left13"  />
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="top13" name="top13"  />
                        </td>
                        <td><select name="font_name13" id="font_name13"  class="form-control"  >
                                <option value='Arial Narrow'>Arial Narrow</option>
                                <option value='Courier New'>Courier New</option>
                                <option value='Times New Roman'>Times New Roman</option>
                                <option value='Tahoma'>Tahoma</option>
                                <option value='Bodoni MT'>Bodoni MT</option>
                            </select></td>
                        <td>
                            <input type="text"  class="form-control"  id="fontsize13" name="fontsize13"  />
                        </td>

                    </tr>
                    <tr>
                        <td>14</td>
                        <td>
                            Barer 2
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="left14" name="left14"  />
                        </td>
                        <td>
                            <input type="text"  class="form-control"  id="top14" name="top14"  />
                        </td>
                        <td><select name="font_name14" id="font_name14"  class="form-control"  >
                                <option value='Arial Narrow'>Arial Narrow</option>
                                <option value='Courier New'>Courier New</option>
                                <option value='Times New Roman'>Times New Roman</option>
                                <option value='Tahoma'>Tahoma</option>
                                <option value='Bodoni MT'>Bodoni MT</option>
                            </select></td>
                        <td>
                            <input type="text"  class="form-control"  id="fontsize14" name="fontsize14"  />
                        </td>

                    </tr>
                </table>
                <input type="submit" name="button" id="button" value="Preview" />
            </form>     
        </div>

</section>



<script src="js/cheque_setup_acc.js"></script>