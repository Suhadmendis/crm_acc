var loc = "add";

var wage = document.getElementById("itemPrice");
wage.addEventListener("keydown", function (e) {
    if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
        add_tmp();
    }
});


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

function keyset(key, e) {

    if (e.keyCode == 13) {
        document.getElementById(key).focus();
    }
}

function got_focus(key) {
    document.getElementById(key).style.backgroundColor = "#000066";

}

function lost_focus(key) {
    document.getElementById(key).style.backgroundColor = "#000000";

}





function getno1() {
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "receipt_entry_data.php";
    url = url + "?Command=" + "getno";
    url = url + "&tmpno=" + document.getElementById('tmpno').value;
    url = url + "&sdate=" + document.getElementById('invdate').value;


    xmlHttp.onreadystatechange = assign_invno;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}

function add_tmp() {


    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    if ((document.getElementById('txt_entno').value != "")) {

        var url = "receipt_entry_data.php";
        url = url + "?Command=" + "add_tmp";
        url = url + "&invno=" + document.getElementById('txt_entno').value;
        url = url + "&itemCode=" + document.getElementById('txt_gl_code').value;
        url = url + "&itemDesc=" + document.getElementById('txt_gl_name').value;
        url = url + "&itemPrice=" + document.getElementById('itemPrice').value;

        url = url + "&tmpno=" + document.getElementById('tmpno').value;


        xmlHttp.onreadystatechange = showarmyresultdel;
        xmlHttp.open("GET", url, true);
        xmlHttp.send(null);

    } else if (document.getElementById('c_code').value == "") {
        alert("Please Select Customer");
    }

}

function showarmyresultdel() {
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table");
        document.getElementById('itemdetails').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;



        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("subtot");
        document.getElementById('subtot').value = XMLAddress1[0].childNodes[0].nodeValue;

        document.getElementById('txt_gl_code').value = "";
        document.getElementById('txt_gl_name').value = "";
        document.getElementById('itemPrice').value = "";
        document.getElementById('txt_gl_name').focus();
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
    if (document.getElementById('c_code').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Customer Not Selected</span></div>";
        return false;
    }
    if (document.getElementById('txt_amount').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Amount Not Enterd</span></div>";
        return false;
    }
    if (document.getElementById('subtot').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Amount Not Enterd</span></div>";
        return false;
    }
    a = parseFloat(document.getElementById('txt_amount').value);
    var b = document.getElementById('subtot').value;
    b = b.replace(",", "");
    b = b.replace(",", "");
    b = b.replace(",", "");
    b = parseFloat(b);
    if (a != b) {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Amount Mismatch</span></div>";
        return false;
    }

    var url = "receipt_entry_data.php";
    url = url + "?Command=" + "save_item";

    url = url + "&invno=" + document.getElementById('txt_entno').value;
    url = url + "&tmpno=" + document.getElementById('tmpno').value;
    url = url + "&entrydate=" + document.getElementById('invdate').value;

    url = url + "&customercode=" + document.getElementById('c_code').value;
    url = url + "&customername=" + document.getElementById('c_name').value;
    url = url + "&subtot=" + document.getElementById('subtot').value;
    url = url + "&tmpno=" + document.getElementById('tmpno').value;
    url = url + "&txt_heading=" + document.getElementById('txt_heading').value;
    url = url + "&txt_narration=" + document.getElementById('txt_narration').value;

    url = url + "&txt_chequeno=" + document.getElementById('cheq_no').value;
    url = url + "&payment_type=" + document.getElementById('payment_type').value;


    url = url + "&txt_payments=" + document.getElementById('txt_amount').value;
    url = url + "&bank=" + document.getElementById('bank').value;



    url = url + "&currency=" + document.getElementById('currency').value;


    url = url + "&txt_rate=" + document.getElementById('txt_rate').value;
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

function print_inv(cdata) {

    var url = "recepit_entry_print.php";
    url = url + "?tmp_no=" + document.getElementById('tmpno').value;
    url = url + "&action=" + cdata;

    window.open(url, '_blank');


}


function new_inv() {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    document.getElementById('txt_entno').value = "";
    document.getElementById('c_name').value = "";
    document.getElementById('c_code').value = "";

    document.getElementById('itemdetails').innerHTML = "";

    document.getElementById('txt_heading').value = "";

    document.getElementById('subtot').value = "";

    document.getElementById('txt_gl_code').value = "";
    document.getElementById('txt_gl_name').value = "";
    document.getElementById('itemPrice').value = "";


    document.getElementById('txt_narration').value = "";
    document.getElementById('cheq_no').value = "";
    document.getElementById('txt_amount').value = "";

    document.getElementById('msg_box').innerHTML = "";
    document.getElementById('filebox').innerHTML = "";
    document.getElementById('file-3').value = "";

    var url = "receipt_entry_data.php";
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

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("dt");
        document.getElementById('cheq_date').value = XMLAddress1[0].childNodes[0].nodeValue;
    }

}




function del_item(cdate) {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "receipt_entry_data.php";
    url = url + "?Command=" + "del_item";
    url = url + "&code=" + cdate;
    url = url + "&invno=" + document.getElementById('tmpno').value;
    ;
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

        var url = "receipt_entry_data.php";
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
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("C_CODE");
        opener.document.form1.c_code.value = XMLAddress1[0].childNodes[0].nodeValue;
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("C_NAME");
        opener.document.form1.c_name.value = XMLAddress1[0].childNodes[0].nodeValue;


        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("C_DATE");
        opener.document.form1.invdate.value = XMLAddress1[0].childNodes[0].nodeValue;



        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("paytype");
        opener.document.form1.payment_type.value = XMLAddress1[0].childNodes[0].nodeValue;




        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("currency");
        opener.document.form1.currency.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("txt_rate");
        opener.document.form1.txt_rate.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("txt_amount");
        opener.document.form1.txt_amount.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("txt_amount_lkr");
        // opener.document.form1.txt_amount_lkr.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("cheq_no");
        opener.document.form1.cheq_no.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("cheq_date");
        opener.document.form1.cheq_date.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("txt_heading");
        opener.document.form1.txt_heading.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("txt_narration");
        opener.document.form1.txt_narration.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("BANK");
        opener.document.form1.bank.value = XMLAddress1[0].childNodes[0].nodeValue;


        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table");
        window.opener.document.getElementById('itemdetails').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;




        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("txt_amount");
        window.opener.document.getElementById('txt_amount').value = XMLAddress1[0].childNodes[0].nodeValue;

        window.opener.document.getElementById('subtot').value = XMLAddress1[0].childNodes[0].nodeValue;


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

    var url = "receipt_entry_data.php";
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



function cancel_inv() {
    $('#myModal_c').modal('hide');

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    document.getElementById('msg_box').innerHTML = "";
    if (document.getElementById('c_name').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Select Entry</span></div>";
        return false;
    }
    if (document.getElementById('txt_amount').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Select Entry</span></div>";
        return false;
    }

    a = parseFloat(document.getElementById('txt_amount').value);
    
    var b = document.getElementById('subtot').value;
    b = b.replace(",", "");
    b = b.replace(",", "");
    
    b = parseFloat(b);
    if (a != b) {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'></span></div>";
        return false;
    }
    var url = "receipt_entry_data.php";
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