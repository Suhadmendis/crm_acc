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

function newent() {
    document.getElementById("txt_line_no").value = "";
    document.getElementById("txt_descri").value = "";
    document.getElementById("txt_gl_code").value = "";
    document.getElementById("txt_rtype").value = "";


    getdt();
}

function getdt() {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "exp_data.php";
    url = url + "?Command=" + "getdt";


    xmlHttp.onreadystatechange = assign_dt;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}


function assign_dt() {
    var XMLAddress1;
    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table");
        document.getElementById('itemdetails').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("item_count");
        document.getElementById('txt_line_no').value = XMLAddress1[0].childNodes[0].nodeValue;

    }
}



function view_dett()
{

    var url = "report_expenses.php";
    url = url + "?dtfrom=" + document.getElementById('dtfrom').value;
    url = url + "&dtto=" + document.getElementById('dtto').value;    
    url = url + "&rtype=detail";
    window.open(url, '_blank');

}

function view_summ()
{

    var url = "report_expenses.php";
    url = url + "?dtfrom=" + document.getElementById('dtfrom').value;
    url = url + "&dtto=" + document.getElementById('dtto').value;
    url = url + "&rtype=summ";
    window.open(url, '_blank');

}


function getcode(cdate) {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "exp_data.php";
    url = url + "?Command=" + "getcode";
    url = url + "&code=" + cdate;

    xmlHttp.onreadystatechange = assign_data;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}


function assign_data() {

    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {


        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("line_no");
        document.getElementById('txt_line_no').value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("descri");
        document.getElementById('txt_descri').value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("gl_code");
        document.getElementById('txt_gl_code').value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("rtype");
        document.getElementById('txt_rtype').value = XMLAddress1[0].childNodes[0].nodeValue;


        window.scrollTo(0, 0);
    }
}

function add_tmp() {


    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    if (document.getElementById('txt_line_no').value != "") {

        var url = "exp_data.php";
        url = url + "?Command=" + "add_tmp";
        url = url + "&line_no=" + document.getElementById('txt_line_no').value;
        url = url + "&descri=" + document.getElementById('txt_descri').value;
        url = url + "&gl_code=" + document.getElementById('txt_gl_code').value;
        url = url + "&rtype=" + document.getElementById('txt_rtype').value;

        xmlHttp.onreadystatechange = showarmyresultdel;
        xmlHttp.open("GET", url, true);
        xmlHttp.send(null);
    }

}

function showarmyresultdel() {
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {
        document.getElementById("txt_line_no").value = "";
        document.getElementById("txt_descri").value = "";
        document.getElementById("txt_gl_code").value = "";
        document.getElementById("txt_rtype").value = "";
        getdt();

    }
}

function del_item(cdate) {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "exp_data.php";
    url = url + "?Command=" + "del_item";
    url = url + "&code=" + cdate;

    xmlHttp.onreadystatechange = showarmyresultdel;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}





