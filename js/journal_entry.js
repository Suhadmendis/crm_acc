function GetXmlHttpObject() {
    var xmlHttp = null;
    try {
        // Firefox, Opera 8.0+, Safari
        xmlHttp = new XMLHttpRequest();
    } catch (e) {
// Internet Explorer
        try {
            xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    return xmlHttp;
}

function getno1() {
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }
    
    var url = "journal_entry_data.php";
    url = url + "?Command=" + "getno";
    url = url + "&tmpno=" + document.getElementById('tmpno').value ;
    url = url + "&sdate=" + document.getElementById('invdate').value ;
    
    
    xmlHttp.onreadystatechange = assign_invno;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
    
}


function add_tmp(cdata) {


    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    if ((document.getElementById('txt_entno').value != "") && (document.getElementById('tmpno').value != "")) {

        var url = "journal_entry_data.php";
        url = url + "?Command=" + "add_tmp";
        if (cdata == "CN1") {
            url = url + "&itemCode=" + document.getElementById('txt_gl_code').value;
            url = url + "&itemDesc=" + document.getElementById('txt_gl_name').value;
            url = url + "&itemPrice=" + document.getElementById('itemPrice').value;
            url = url + "&txt_gl_name1=" + document.getElementById('txt_gl_name3').value;
            
        } else {
            url = url + "&itemCode=" + document.getElementById('txt_gl_code1').value;
            url = url + "&itemDesc=" + document.getElementById('txt_gl_name1').value;
            url = url + "&itemPrice=" + document.getElementById('itemPrice1').value;
            url = url + "&txt_gl_name1=" + document.getElementById('txt_gl_name4').value;
        }
        url = url + "&invno=" + document.getElementById('txt_entno').value;
        url = url + "&tmpno=" + document.getElementById('tmpno').value;
        url = url + "&form=" + cdata;
        xmlHttp.onreadystatechange = showarmyresultdel;
        xmlHttp.open("GET", url, true);
        xmlHttp.send(null);
    }


}

function showarmyresultdel() {
    var XMLAddress1;
    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("form");
        if (XMLAddress1[0].childNodes[0].nodeValue == "CN1") {
            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table");
            document.getElementById('itemdetails').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;
            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("subtot");
            document.getElementById('subtot').value = XMLAddress1[0].childNodes[0].nodeValue;
            document.getElementById('txt_gl_code').value = "";
            document.getElementById('txt_gl_name').value = "";
            document.getElementById('itemPrice').value = "";
            document.getElementById('txt_gl_name3').value = "";
            document.getElementById('cmd_glcode').focus();
        } else {
            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table");
            document.getElementById('itemdetails1').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;
            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("subtot");
            document.getElementById('subtot1').value = XMLAddress1[0].childNodes[0].nodeValue;
            document.getElementById('txt_gl_code1').value = "";
            document.getElementById('txt_gl_name1').value = "";
            document.getElementById('itemPrice1').value = "";            
            document.getElementById('txt_gl_name4').value = "";
            document.getElementById('cmd_glcode1').focus();
        }
    }
}

function cancel_inv() {
    $('#myModal_c').modal('hide');

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    document.getElementById('msg_box').innerHTML = "";
    
    a = (document.getElementById('subtot1').value);
    b = (document.getElementById('subtot').value);
    a = a.replace(",", "");
    b = b.replace(",", "");
    a = parseFloat(a);
    b = parseFloat(b);
    if (a != b) {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'></span></div>";
        return false;
    }
    var url = "journal_entry_data.php";
    url = url + "?Command=" + "del_inv";
    url = url + "&crnno=" + document.getElementById('txt_entno').value;
    url = url + "&tmpno=" + document.getElementById('tmpno').value;
    xmlHttp.onreadystatechange = cancel_result;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}

function cancel_result() {
    var XMLAddress1;
    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {


        if (xmlHttp.responseText == "ok") {
            document.getElementById('msg_box').innerHTML = "<div class='alert alert-success' role='alert'><span class='center-block'>Cancelled</span></div>";
            setTimeout(function () {
                window.location.reload(1);
            }, 3000);
        } else {
            if (xmlHttp.responseText != "") {
                document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>" + xmlHttp.responseText + "</span></div>";
            }
        }
    }
}


function save_inv() {





    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }
    document.getElementById('msg_box').innerHTML = "";
    if (document.getElementById('tmpno').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>New Not Selected</span></div>";
        return false;
    }
   
    
    a = (document.getElementById('subtot1').value);
    b = (document.getElementById('subtot').value);
    a = a.replace(",", "");
    b = b.replace(",", "");
    a = parseFloat(a);
    b = parseFloat(b);
    if (a != b) {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Amount Mismatch</span></div>";
        return false;
    }
    var url = "journal_entry_data.php";
    url = url + "?Command=" + "save_item";
    url = url + "&crnno=" + document.getElementById('txt_entno').value;
    url = url + "&cur=" + document.getElementById('currency').value;
    url = url + "&Rate=" + document.getElementById('txt_rate').value; 
    url = url + "&remark=" + escape(document.getElementById('txt_remarks').value);
    url = url + "&crndate=" + document.getElementById('invdate').value;
    url = url + "&tmpno=" + document.getElementById('tmpno').value;
    xmlHttp.onreadystatechange = salessaveresult;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}

function salessaveresult() {
    var XMLAddress1;
    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {

        if (xmlHttp.responseText == "Saved") {
            document.getElementById('msg_box').innerHTML = "<div class='alert alert-success' role='alert'><span class='center-block'>Saved</span></div>";
            print_inv('save');
            document.getElementById('filup').style.visibility = "visible";
        } else {
            document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>" + xmlHttp.responseText + "</span></div>";
        }
    }
}




function new_inv() {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    document.getElementById('txt_entno').value = "";
    
    document.getElementById('msg_box').innerHTML = "";
    document.getElementById('itemdetails').innerHTML = "";
    document.getElementById('itemdetails1').innerHTML = "";
    document.getElementById('subtot').value = "";
    document.getElementById('txt_gl_code').value = "";
    document.getElementById('txt_gl_name').value = "";
    document.getElementById('itemPrice').value = "";

    document.getElementById('txt_gl_code1').value = "";
    document.getElementById('txt_gl_name1').value = "";
    document.getElementById('itemPrice1').value = "";
 
    document.getElementById('txt_remarks').value = "";

    document.getElementById('filup').style.visibility = "hidden";

    document.getElementById('filebox').innerHTML = "";
    document.getElementById('file-3').value = "";
     document.getElementById('subtot1').value = "";
    var url = "journal_entry_data.php";
    url = url + "?Command=" + "new_inv";
    xmlHttp.onreadystatechange = assign_invno;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}

function assign_invno() {



    var XMLAddress1;
    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("invno");
        document.getElementById('txt_entno').value = XMLAddress1[0].childNodes[0].nodeValue;
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("tmpno");
        document.getElementById('tmpno').value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("dt");
        document.getElementById('invdate').value = XMLAddress1[0].childNodes[0].nodeValue;


    }

}




function del_item(cdate, cdate1) {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "journal_entry_data.php";
    url = url + "?Command=" + "del_item";
    url = url + "&code=" + cdate;
    url = url + "&invno=" + document.getElementById('tmpno').value;
    url = url + "&form=" + cdate1;
    xmlHttp.onreadystatechange = showarmyresultdel;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}

 


function crnview(custno, stname)
{
    try {


        xmlHttp = GetXmlHttpObject();
        if (xmlHttp == null)
        {
            alert("Browser does not support HTTP Request");
            return;
        }

        var url = "journal_entry_data.php";
        url = url + "?Command=" + "pass_rec";
        url = url + "&refno=" + custno;
        url = url + "&stname=" + stname;

        xmlHttp.onreadystatechange = pass_rec_result;

        xmlHttp.open("GET", url, true);
        xmlHttp.send(null);
    } catch (err) {
        alert(err.message);
    }
}

function pass_rec_result()
{
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
    {
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("tmp_no");
        opener.document.form1.tmpno.value = XMLAddress1[0].childNodes[0].nodeValue;
        
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("C_REFNO");
        opener.document.form1.txt_entno.value = XMLAddress1[0].childNodes[0].nodeValue;
        
     

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("C_DATE");
        opener.document.form1.invdate.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("txt_remarks");
        opener.document.form1.txt_remarks.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("currency");
        opener.document.form1.currency.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("txt_rate");
        opener.document.form1.txt_rate.value = XMLAddress1[0].childNodes[0].nodeValue;

         
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table");
        window.opener.document.getElementById('itemdetails').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table1");
        window.opener.document.getElementById('itemdetails1').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("subtot");
        window.opener.document.getElementById('subtot').value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("subtot1");
        window.opener.document.getElementById('subtot1').value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("msg");
        if (XMLAddress1[0].childNodes[0].nodeValue != "") {
            window.opener.document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>" + XMLAddress1[0].childNodes[0].nodeValue + "</span></div>";
        }
         XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("filebox");
        window.opener.document.getElementById('filebox').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;

        window.opener.document.getElementById('filup').style.visibility = "visible";
        self.close();
    }
}

function settext() {

}

function update_list(stname) {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null)
    {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "journal_entry_data.php";
    url = url + "?Command=" + "update_list";
    url = url + "&refno=" + document.getElementById('cusno').value;
    url = url + "&cusname=" + document.getElementById('customername').value;
    url = url + "&stname=" + stname;

    xmlHttp.onreadystatechange = showitemresult;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}


function showitemresult()
{
    var XMLAddress1;
    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
    {
        document.getElementById('filt_table').innerHTML = xmlHttp.responseText;
    }
}




function print_inv(cdata) {

    var url = "journal_print.php";
    url = url + "?tmp_no=" + document.getElementById('tmpno').value;
    url = url + "&action=" + cdata;
    
    window.open(url,'_blank');


}

