var loc = "add";
var wage = document.getElementById("qty");
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


function print_inv(cdata) {

    var url = "issue_print.php";
    url = url + "?tmp_no=" + document.getElementById('tmpno').value;
    url = url + "&action=" + cdata;

    window.open(url, '_blank');


}

function add_tmp() {


    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }
    if ((document.getElementById('tmpno').value != "")) {

        var url = "issue_note_data.php";
        url = url + "?Command=" + "setitem";
        url = url + "&Command1=" + "add_tmp";
        url = url + "&invno=" + document.getElementById('txt_entno').value;
        url = url + "&itemCode=" + document.getElementById('itemCode').value;
        url = url + "&itemDesc=" + document.getElementById('itemDesc').value;
        url = url + "&qty=" + document.getElementById('qty').value;
        url = url + "&tmpno=" + document.getElementById('tmpno').value;
        var vattype;
        if (document.getElementById('non').checked == true) {
            vattype = "non";
        }

        if (document.getElementById('svat').checked == true) {
            vattype = "svat";
        }
        url = url + "&vat=" + vattype;
         document.getElementById('itemCode').value = "";
        document.getElementById('itemDesc').value = "";
        document.getElementById('qty').value = "";

        loc = "add";
        xmlHttp.onreadystatechange = showarmyresultdel;
        xmlHttp.open("GET", url, true);
        xmlHttp.send(null);

    }
}

function showarmyresultdel() {
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {

//        alert(xmlHttp.responseText);

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table");
        document.getElementById('itemdetails').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("item_count");
        document.getElementById('item_count').value = XMLAddress1[0].childNodes[0].nodeValue;

       if (loc=="add") {
         document.getElementById('searchcusti').focus();  
       } else {
         document.getElementById('qty').focus();  
       }
        
    }
}



function save_inv() {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    if (document.getElementById('tmpno').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>New Not Selected</span></div>";
        return false;
    }
    /*
     if (document.getElementById('c_name').value == "") {
     document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Customer Not Selected</span></div>";
     return false;
     }
     if (document.getElementById('subtot').value == "") {
     document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Amount Not Enterd</span></div>";
     return false;
     }
     */


    var url = "issue_note_data.php";
    url = url + "?Command=" + "save_item";

    url = url + "&txt_entno=" + document.getElementById('txt_entno').value;
    url = url + "&tmpno=" + document.getElementById('tmpno').value;
    url = url + "&txt_remarks=" + document.getElementById('txt_remarks').value;

    url = url + "&invdate=" + document.getElementById('invdate').value;
    var vattype;
    if (document.getElementById('non').checked == true) {
        vattype = "IIN";
    }

    if (document.getElementById('svat').checked == true) {
        vattype = "IOU";
    }
    url = url + "&ttype=" + vattype;
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

    var paymethod;

    document.getElementById('txt_entno').value = "";
    document.getElementById('svat').checked = true;
    document.getElementById('itemdetails').innerHTML = "";
    document.getElementById('msg_box').innerHTML = "";
    document.getElementById('itemCode').value = "";
    document.getElementById('itemDesc').value = "";
    document.getElementById('itemPrice').value = "";
    document.getElementById('qty').value = "";


    var url = "issue_note_data.php";
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




function del_item(cdate) {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "issue_note_data.php";
    url = url + "?Command=" + "setitem";
    url = url + "&Command1=" + "del_item";
    url = url + "&itemCode=" + cdate;
    url = url + "&invno=" + document.getElementById('txt_entno').value;
    url = url + "&tmpno=" + document.getElementById('tmpno').value;
    var vattype;
    if (document.getElementById('non').checked == true) {
        vattype = "non";
    }

    if (document.getElementById('svat').checked == true) {
        vattype = "svat";
    }
    url = url + "&vat=" + vattype;

    xmlHttp.onreadystatechange = showarmyresultdel;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}




function update_list(stname) {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null)
    {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "issue_note_data.php";
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



function crnview(custno, stname)
{
    try {


        xmlHttp = GetXmlHttpObject();
        if (xmlHttp == null)
        {
            alert("Browser does not support HTTP Request");
            return;
        }
        var url = "issue_note_data.php";
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
//        alert(xmlHttp.responseText);
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("tmp_no");
        opener.document.form1.tmpno.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("C_REFNO");
        opener.document.form1.txt_entno.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("C_DATE");
        opener.document.form1.invdate.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("txt_remarks");
        opener.document.form1.txt_remarks.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table");
        window.opener.document.getElementById('itemdetails').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("item_count");
        opener.document.form1.item_count.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("trn_type");
        if (XMLAddress1[0].childNodes[0].nodeValue == "IOU") {
            window.opener.document.getElementById('svat').checked = true;
        } else {
            window.opener.document.getElementById('non').checked = true;
        }

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("msg");
        if (XMLAddress1[0].childNodes[0].nodeValue != "") {
            window.opener.document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>" + XMLAddress1[0].childNodes[0].nodeValue + "</span></div>";
        }
        self.close();
    }
}


function pass_arn_result()
{
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
    {
        alert(xmlHttp.responseText);

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("REFNO");
        opener.document.form1.orderno1.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("SUP_CODE");
        opener.document.form1.c_code.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("SUP_NAME");
        opener.document.form1.c_name.value = XMLAddress1[0].childNodes[0].nodeValue;

        //wait_2
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("count");
        opener.document.form1.arn_item_count.value = XMLAddress1[0].childNodes[0].nodeValue;

//        alert("count value "+opener.document.form1.count.value);

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table");
        window.opener.document.getElementById('invdt').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;

        self.close();
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
    if (document.getElementById('txt_entno').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Select Entry</span></div>";
        return false;
    }

    var url = "issue_note_data.php";
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

function setitem(c1, c2, c3) {

    del_item(c1);
    loc ="del";
    document.getElementById('itemCode').value = c1;
    document.getElementById('itemDesc').value = c2;
    document.getElementById('qty').value = c3;

}