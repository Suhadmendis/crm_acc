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
   
    getdt();
}

function getdt() {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "cusmas_data.php";
    url = url + "?Command=" + "getdt";
    url = url + "&ls=" + "new";

    xmlHttp.onreadystatechange = assign_dt;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}


function assign_dt() {
    document.getElementById('itemdetails').innerHTML = xmlHttp.responseText;
}


function getbycode(cdata) {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "cusmas_data.php";
    url = url + "?Command=" + "getdt";

    url = url + "&ls=" + cdata;

    url = url + "&txt_aircode=" + document.getElementById('txt_aircode').value;

    url = url + "&txt_airname=" + document.getElementById('txt_airname').value;
    url = url + "&txt_country=" + document.getElementById('txt_country').value;
    url = url + "&txt_town=" + document.getElementById('txt_town').value;
    url = url + "&txt_other=" + document.getElementById('txt_other').value;

    xmlHttp.onreadystatechange = assign_dt;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}

function getbyname() {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "cusmas_data.php";
    url = url + "?Command=" + "getdt";
    url = url + "&ls=" + "name";

    url = url + "&txt_aircode=" + document.getElementById('txt_aircode').value;
    url = url + "&txt_airname=" + document.getElementById('txt_airname').value;
    url = url + "&txt_country=" + document.getElementById('txt_country').value;
    url = url + "&txt_town=" + document.getElementById('txt_town').value;
    url = url + "&txt_other=" + document.getElementById('txt_other').value;

    xmlHttp.onreadystatechange = assign_dt;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}


function getcode(cdate) {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "cusmas_data.php";
    url = url + "?Command=" + "getcode";
    url = url + "&code=" + cdate;

    xmlHttp.onreadystatechange = assign_data;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}


function assign_data() {

    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("code");
        document.getElementById('txt_aircode').value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("port");
        document.getElementById('txt_airname').value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("country");
        document.getElementById('txt_country').value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("town");
        document.getElementById('txt_town').value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("other");
        document.getElementById('txt_other').value = XMLAddress1[0].childNodes[0].nodeValue;


        window.scrollTo(0, 0);
    }
}



function custno(custno, stname)
{
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null)
    {
        alert("Browser does not support HTTP Request");
        return;
    }
    var url = "search_custom_data.php";
    url = url + "?Command=" + "pass_quot";
    url = url + "&custno=" + custno;
    url = url + "&stname=" + stname;
    url = url + "&cur=" + document.getElementById('cur').value;




    xmlHttp.onreadystatechange = passcusresult_quot;

    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);


}


function passcusresult_quot()
{
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
    {
       // alert(xmlHttp.responseText);


        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("id");
        opener.document.form1.c_code.value = XMLAddress1[0].childNodes[0].nodeValue;


        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("str_customername");
        opener.document.form1.c_name.value = XMLAddress1[0].childNodes[0].nodeValue;


        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("stname");
        var stname = XMLAddress1[0].childNodes[0].nodeValue;
        
        
        
        if ((stname =="rec") || (stname =="cont")) {
         XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("tb");    
         window.opener.document.getElementById('invdt').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;   
         
         XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("count");    
         opener.document.form1.count.value = XMLAddress1[0].childNodes[0].nodeValue;

         if (stname =="cont") {
             
         XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("tb1");    
         window.opener.document.getElementById('invdt1').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;   
         
         XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("count1");    
         opener.document.form1.count1.value = XMLAddress1[0].childNodes[0].nodeValue;

         }
         
        }
        
        
        if ((stname =="inv")) {
            
            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("add");
        opener.document.form1.cus_address.value = XMLAddress1[0].childNodes[0].nodeValue;
            
        }


        self.close();
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


    var url = "search_custom_data.php";
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