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



function getrecdt() {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null)
    {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "bank_rec_data.php";
    url = url + "?Command=" + "getrecdt";
    url = url + "&bank=" + document.getElementById('bank').value;
    url = url + "&dtto=" + document.getElementById('dtto').value;
    url = url + "&cur=" + document.getElementById('currency1').value;



    xmlHttp.onreadystatechange = showitemresult;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}


function showitemresult()
{
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
    {


        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("dbgrid");
        document.getElementById('itemdetails').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("count");
        document.getElementById('count').value = XMLAddress1[0].childNodes[0].nodeValue;


        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("crgrid");
        document.getElementById('itemdetails1').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("count1");
        document.getElementById('count1').value = XMLAddress1[0].childNodes[0].nodeValue;


        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("bank_bal");
        document.getElementById('bank_bal').value = XMLAddress1[0].childNodes[0].nodeValue;


        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("last_dt");
        document.getElementById('lastdt').value = XMLAddress1[0].childNodes[0].nodeValue;



    }
}


function new_inv() {
    
     
    document.getElementById('msg_box').innerHTML = "";
    document.getElementById('itemdetails').innerHTML = "";
    document.getElementById('itemdetails1').innerHTML = "";
    document.getElementById('bank_bal').value =""; 
     document.getElementById('bank').value =""; 
    
}

function print_inv(cdata) {

    var url = "bank_rec_print.php";
    url = url + "?bank=" + document.getElementById('bank').value;
    
    url = url + "&dtto=" + document.getElementById('dtto').value;
    
    url = url + "&bank_bal=" + escape(document.getElementById('bank_bal').value);
    url = url + "&action=" + cdata;
 url = url + "&cur=" + document.getElementById('currency1').value;


    window.open(url, '_blank');


}

function save_inv() {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }
    document.getElementById('msg_box').innerHTML = "";
    if (document.getElementById('bank').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Bank Not Selected</span></div>";
        return false;
    }

    var url = "bank_rec_data.php";
    var params = "Command=" + "save_item";

    params = params + "&dtto=" + document.getElementById('dtto').value;
    params = params + "&bank=" + escape(document.getElementById('bank').value);
    params = params + "&bank_bal=" + escape(document.getElementById('bank_bal').value);

    params = params + "&count=" + escape(document.getElementById('count').value);
    params = params + "&count1=" + escape(document.getElementById('count1').value);


    var count = document.getElementById('count').value;
    var i = 1;
    while (count > i) {
        var id_deb = "id_deb" + i;
        var dt_deb = "dt_deb" + i;
        params = params + '&' + id_deb + '=' + document.getElementById(id_deb).value;
        if (document.getElementById(dt_deb).checked == true) {
            params = params + '&' + dt_deb + '=1';
        } else {
            params = params + '&' + dt_deb + '=0';
        }

        i = i + 1;
    }

    var count1 = document.getElementById('count1').value;
    var t = 1;
    while (count1 > t) {
        var id_cre = "id_cre" + t;
        var dt_cre = "dt_cre" + t;
        params = params + '&' + id_cre + '=' + document.getElementById(id_cre).value;
        if (document.getElementById(dt_cre).checked == true) {
            params = params + '&' + dt_cre + '=1';
        } else {
            params = params + '&' + dt_cre + '=0';
        }

        t = t + 1;
    }


    xmlHttp.open("POST", url, true);

    xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlHttp.setRequestHeader("Content-length", params.length);
    xmlHttp.setRequestHeader("Connection", "close");

    xmlHttp.onreadystatechange = salessaveresult;

    xmlHttp.send(params);

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