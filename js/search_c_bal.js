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

function new_inv() {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    document.getElementById('msg_box').innerHTML = "";


    document.getElementById('pnl').checked = true;
    document.getElementById('bank').checked = false;

    document.getElementById('txt_entno').value = "";
    document.getElementById('txt_accname').value = "";
    document.getElementById('filebox').innerHTML = "";
    document.getElementById('file-3').value = "";
    document.getElementById('txt_remarks').value = "";

    document.getElementById('txt_Opening').value = "";
    document.getElementById('currency').value = "LKR";
    document.getElementById('txt_rate').value = "1";

    document.getElementById('txt_gl_code').value = "";
    document.getElementById('txt_gl_name').value = "";


}


function save_inv() {
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }
    document.getElementById('msg_box').innerHTML = "";

    if (document.getElementById('txt_entno').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Acoount Code Not Enterd</span></div>";
        return false;
    }
    if (document.getElementById('txt_accname').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Acoount Name Not Enterd</span></div>";
        return false;
    }

    var url = "account_master_data.php";
    url = url + "?Command=" + "save_item";
    url = url + "&txt_entno=" + document.getElementById('txt_entno').value;
    url = url + "&txt_gl_name=" + escape(document.getElementById('txt_accname').value);


    if (document.getElementById('bank').checked == true) {
        url = url + "&bank=on";
    } else {
        url = url + "&bank=off";
    }

    if (document.getElementById('manu').checked == true) {
        url = url + "&acctype=M";
    }
    if (document.getElementById('pnl').checked == true) {
        url = url + "&acctype=P";
    }
    if (document.getElementById('bal').checked == true) {
        url = url + "&acctype=B";
    }
    url = url + "&acType=" + document.getElementById('acType').value;
    url = url + "&acType1=" + document.getElementById('acType1').value;
    url = url + "&txt_Opening=" + document.getElementById('txt_Opening').value;
    url = url + "&txt_Opening=" + document.getElementById('txt_Opening').value;
    url = url + "&dtpOpenDate=" + document.getElementById('dtpOpenDate').value;
    url = url + "&currency=LKR";
    url = url + "&rate=1";
    url = url + "&paccno=" + document.getElementById('txt_gl_code').value;
    url = url + "&txt_remarks=" + document.getElementById('txt_remarks').value;




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



function update_cust_list(stname)
{


    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null)
    {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "account_master_data.php";
    url = url + "?Command=" + "search_custom";


    if (document.getElementById('cusno').value != "") {
        url = url + "&mstatus=cusno";
    } else if (document.getElementById('customername').value != "") {
        url = url + "&mstatus=customername";
    }

    url = url + "&cusno=" + document.getElementById('cusno').value;
    url = url + "&customername=" + document.getElementById('customername').value;
    url = url + "&stname=" + stname;


    xmlHttp.onreadystatechange = showcustresult;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);


}

function showcustresult()
{
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
    {

        document.getElementById('filt_table').innerHTML = xmlHttp.responseText;
    }
}

function custno(custno, stname)
{
    try {


        xmlHttp = GetXmlHttpObject();
        if (xmlHttp == null)
        {
            alert("Browser does not support HTTP Request");
            return;
        }

        var url = "search_c_bal_data.php";
        url = url + "?Command=" + "pass_cash_rec";
        url = url + "&custno=" + custno;
        url = url + "&stname=" + stname;

        xmlHttp.onreadystatechange = passcusresult_final_acc;

        xmlHttp.open("GET", url, true);
        xmlHttp.send(null);
    } catch (err) {
        alert(err.message);
    }
}

function passcusresult_final_acc()
{
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
    {

        // XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("stname");
        // var stname = XMLAddress1[0].childNodes[0].nodeValue;
// console.log(JSON.parse(xmlHttp.responseText));
        
        var obj = JSON.parse(xmlHttp.responseText);

        if (obj.stname === "vou") {
            console.log(obj.REFNO);
            opener.document.getElementById("c_ref").value = obj.REFNO;
            opener.document.getElementById("Amount").value = obj.AMOUNT;
            opener.document.getElementById("Balance").value = obj.BALANCE;
            opener.document.getElementById("c_des").value = "";
            opener.document.getElementById("c_date").value = obj.SDATE;

        }



        if (obj.stname != "") {
            self.close();
        }
        
    }
}
