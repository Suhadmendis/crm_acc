<?php

session_start();

////////////////////////////////////////////// Database Connector /////////////////////////////////////////////////////////////
require_once ("connection_sql.php");

////////////////////////////////////////////// Write XML ////////////////////////////////////////////////////////////////////
header('Content-Type: text/xml');

date_default_timezone_set('Asia/Colombo');

if ($_GET["Command"] == "save_item") {

    try {

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();


        $sql = "delete from lcodes where c_code = '" . $_GET['txt_entno'] . "'";
        $conn->exec($sql);
        $ctype = "";


        if ($_GET['bank'] == "on") {
            $ctype = "B";
        }

        $sql_lcode = "Insert Into lcodes(c_code, c_name, c_type, paccno, c_opbal, C_DATE ,c_remark,cur,rate,C_SUBGRO1,cat,C_SUBGRO2) Values "
                . "('" . trim($_GET["txt_entno"]) . "', '" . trim($_GET["txt_gl_name"]) . "', '" . $_GET['acctype'] . "','" . $_GET['paccno'] . "','" . $_GET['txt_Opening'] . "','" . $_GET["dtpOpenDate"] . "','" . $_GET["txt_remarks"] . "','" . $_GET['currency'] . "','" . $_GET['rate'] . "','" . $_GET['acType'] . "','" . $ctype . "','" . $_GET['acType1'] . "')";

        $conn->exec($sql_lcode);

        $mrefno = "BF/" . trim($_GET["txt_entno"]);
        $sql = "delete from ledger where l_refno = '" . $mrefno . "' and l_flag ='OPB'";
         $conn->exec($sql);

        $amo = $_GET['txt_Opening'] * $_GET["rate"];
        if ($_GET['txt_Opening'] < 0) {
            $mflag = "CRE";
        } else {
            $mflag = "DEB";
        }

        $sql = "insert into ledger (l_refno, l_date, l_code, l_amount, l_flag, l_flag1,L_LMEM,Currency,rate,curamo) value
                   ('" . $mrefno . "','" . $_GET["dtpOpenDate"] . "','" . trim($_GET["txt_entno"]) . "','" . abs($amo) . "','OPB','" . $mflag . "','Opening Balance','" . $_GET["currency"] . "','" . $_GET["rate"] . "','" . abs($_GET['txt_Opening']) . "')";
        $conn->exec($sql);


        $conn->commit();
        echo "Saved";
    } catch (Exception $e) {
        $conn->rollBack();
        echo $e;
    }
}


if ($_GET["Command"] == "search_custom") {


    $ResponseXML = "";




    $ResponseXML .= "<table   class=\"table table-bordered\">
                            <tr>
                              <th width=\"121\">Account Code</th>
                              <th width=\"424\">Account Name</th>
                           
   							</tr>";

    if ($_GET["mstatus"] == "cusno") {
        $letters = $_GET['cusno'];
        //$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
        $sql = "select c_code,c_name from lcodes where  c_code like '%$letters%' ORDER BY c_code";
    } else if ($_GET["mstatus"] == "customername") {
        $letters = $_GET['customername'];
        //$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
        $sql = "select c_code,c_name from lcodes where  c_name like '%$letters%' ORDER BY c_code";
    } else {

        $letters = $_GET['customername'];
        //$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
        $sql = "select c_code,c_name from lcodes where  c_name like '%$letters%' ORDER BY c_code";
    }



    foreach ($conn->query($sql) as $row) {
        $cuscode = $row["c_code"];
        $stname = $_GET["stname"];

        $ResponseXML .= "<tr>
                              <td onclick=\"ledgno('$cuscode', '$stname');\">" . $row['c_code'] . "</a></td>
                              <td onclick=\"ledgno('$cuscode', '$stname');\">" . $row['c_name'] . "</a></td>
                                            	
                            </tr>";
    }


    $ResponseXML .= "   </table>";


    echo $ResponseXML;
}



if ($_GET["Command"] == "pass_cash_rec") {

    header('Content-Type: text/xml');
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";

    $ResponseXML = "";
    $ResponseXML .= "<salesdetails>";

    if ($_GET['stname'] == "mas") {
        $sql = "select * from lcodes where c_code='" . $_GET["ledgno"] . "'";
    } else {
        $sql = "select C_CODE, C_NAME from lcodes where c_code='" . $_GET["ledgno"] . "'";
    }

    $result = $conn->query($sql);
    if ($row = $result->fetch()) {
        $ResponseXML .= "<c_code><![CDATA[" . $row['C_CODE'] . "]]></c_code>";
        $ResponseXML .= "<c_name><![CDATA[" . $row['C_NAME'] . "]]></c_name>";
        $ResponseXML .= "<stname><![CDATA[" . $_GET['stname'] . "]]></stname>";



        if ($_GET['stname'] == "mas") {



            $prev = "";
            $sql = "select * from docs where refno = '" . $row["C_CODE"] . "'";
            foreach ($conn->query($sql) as $row2) {

                $filetype = pathinfo($row2['loc'], PATHINFO_EXTENSION);
                $filetype = "application/" . $filetype;

                $prev .= "<div data-fileindex='3' width='160px' height='160px' id='preview-1474208198337-3' class='col-sm-2'>
                    
                
                    <object width='160px' height='160px' type='" . $filetype . "' data='" . $row2['loc'] . "'>
                        <div  class='file-preview-other'>
                            <span  class='file-icon-4x'><i    class='glyphicon glyphicon-king'></i></span>
                        </div>
                    </object>                        

                    <div width='160px' class='file-thumbnail-footer'>
                        <div  title='" . $row2['file_name'] . "'  class='file-footer-caption'>" . $row2['file_name'] . "</div>

                        <div  class='file-actions'>
                            <div class='file-footer-buttons'>
                                <a href='" . $row2['loc'] . "' download='" . $row2['file_name'] . "'><i class='glyphicon glyphicon-circle-arrow-down'></i></a>
                                <button title='Remove file' class='kv-file-remove btn btn-xs btn-default' type='button'><i class='glyphicon glyphicon-trash text-danger'></i></button>
                            </div>
                            <div class='clearfix'></div>
                        </div>
                    </div>
                </div> ";
            }


            $ResponseXML .= "<filebox><![CDATA[" . $prev . "]]></filebox>";
            $ResponseXML .= "<c_type><![CDATA[" . trim($row['C_TYPE']) . "]]></c_type>";
            $ResponseXML .= "<txt_Opening><![CDATA[" . $row['C_OPBAL'] . "]]></txt_Opening>";
            $ResponseXML .= "<dtpOpenDate><![CDATA[" . $row['C_DATE'] . "]]></dtpOpenDate>";
            $ResponseXML .= "<cur><![CDATA[" . $row['cur'] . "]]></cur>";
            $ResponseXML .= "<rate><![CDATA[" . $row['rate'] . "]]></rate>";
            $ResponseXML .= "<txt_gl_code><![CDATA[" . $row['PAccNo'] . "]]></txt_gl_code>";
            $ResponseXML .= "<c_remark><![CDATA[" . $row['C_REMARK'] . "]]></c_remark>";
            $ResponseXML .= "<acctype><![CDATA[" . trim($row['CAT']) . "]]></acctype>";
            $ResponseXML .= "<actype><![CDATA[" . $row['C_SUBGRO1'] . "]]></actype>";
            $ResponseXML .= "<actype1><![CDATA[" . $row['C_SUBGRO2'] . "]]></actype1>";


            $sql = "select C_NAME from lcodes where c_code='" . $row['PAccNo'] . "'";
            $result1 = $conn->query($sql);
            if ($row1 = $result1->fetch()) {
                $ResponseXML .= "<txt_gl_name><![CDATA[" . $row1['C_NAME'] . "]]></txt_gl_name>";
            } else {
                $ResponseXML .= "<txt_gl_name><![CDATA[]]></txt_gl_name>";
            }
        }
    }
    $ResponseXML .= "</salesdetails>";
    echo $ResponseXML;
}


if ($_GET["Command"] == "get_list") {
    
    
    $result = array();
    $sql = "select c_code,c_name from lcodes where  c_name like '%". $_GET['term'] . "%' ORDER BY c_code limit 10";
    foreach ($conn->query($sql) as $items) {
        array_push($result, array("id" => $items['c_code'], "label" => $items['c_code'] . '-' . $items['c_name'], "name" => $items['c_name']));
//array_push($result, array("id"=>$value, "label"=>$key, "value" => strip_tags($key)));
    }

// json_encode is available in PHP 5.2 and above, or you can install a PECL module in earlier versions
    echo json_encode($result);
}



 