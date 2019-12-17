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


    document.getElementById('msg_box').innerHTML ="";
    document.getElementById('txt_currency').value = "";
    document.getElementById('txt_byexrate').value = "";
    document.getElementById('txt_localclient').value = "";
    document.getElementById('txt_gentsell').value = "";
    document.getElementById('txt_totalrate').value = "";

    getdt();
}

function getdt() {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "currency_data.php";
    url = url + "?Command=" + "getdt";
    url = url + "&ls=" + "new";

    xmlHttp.onreadystatechange = assign_dt;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);






}


function assign_dt() {
    document.getElementById('itemdetails').innerHTML = xmlHttp.responseText;
}







function getcode(cdata, cdata1, cdata2, cdata3, cdata4) {

    document.getElementById('txt_currency').value = cdata;
    document.getElementById('txt_byexrate').value = cdata1;
    document.getElementById('txt_localclient').value = cdata2;
    document.getElementById('txt_gentsell').value = cdata3;
    document.getElementById('txt_totalrate').value = cdata4;
}

function save_inv() {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    if (document.getElementById('txt_currency').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Currency Not Enterd</span></div>";
        return false;
    }
    if (document.getElementById('txt_byexrate').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Rate Not Selected</span></div>";
        return false;
    }
    if (document.getElementById('txt_localclient').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Rate Not Enterd</span></div>";
        return false;
    }
    if (document.getElementById('txt_gentsell').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Rate Not Enterd</span></div>";
        return false;
    }
    if (document.getElementById('txt_totalrate').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Rate Not Enterd</span></div>";
        return false;
    }


    var url = "currency_data.php";
    url = url + "?Command=" + "save_item";

    url = url + "&txt_currency=" + document.getElementById('txt_currency').value;
    url = url + "&txt_byexrate=" + document.getElementById('txt_byexrate').value;
    url = url + "&txt_localclient=" + document.getElementById('txt_localclient').value;
    url = url + "&txt_gentsell=" + document.getElementById('txt_gentsell').value;
    url = url + "&txt_totalrate=" + document.getElementById('txt_totalrate').value;




    xmlHttp.onreadystatechange = salessaveresult;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}

function salessaveresult() {
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {

        if (xmlHttp.responseText == "Saved") {
            document.getElementById('msg_box').innerHTML = "<div class='alert alert-success' role='alert'><span class='center-block'>Saved</span></div>";
             
        } else {
            document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>" + xmlHttp.responseText + "</span></div>";
        }
    }
}




