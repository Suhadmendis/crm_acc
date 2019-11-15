function GetXmlHttpObject()
{
    var xmlHttp = null;
    try
    {
        // Firefox, Opera 8.0+, Safari
        xmlHttp = new XMLHttpRequest();
    } catch (e)
    {
        // Internet Explorer
        try
        {
            xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e)
        {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    return xmlHttp;
}


function load_bank()
{
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null)
    {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "cheque_setup_data_acc.php";
    url = url + "?Command=" + "load_bank";
    url = url + "&com_bank=" + document.getElementById('com_bank').value;

    xmlHttp.onreadystatechange = passcusresult_load_bank;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}


function passcusresult_load_bank()
{
    var XMLAddress1;
    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
    {
        i = 1;
        while (i <= 14) {
            font_name = "font_name" + i;
            font_size = "font_size" + i;
            left_loc = "left_loc" + i;
            top_loc = "top_loc" + i;

            txtfont_name = "font_name" + i;
            txtfontsize = "fontsize" + i;
            txtleft = "left" + i;
            txttop = "top" + i;

            //alert(xmlHttp.responseText);
            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName(font_name);
            document.getElementById(txtfont_name).value = XMLAddress1[0].childNodes[0].nodeValue;

            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName(font_size);
            document.getElementById(txtfontsize).value = XMLAddress1[0].childNodes[0].nodeValue;

            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName(left_loc);
            document.getElementById(txtleft).value = XMLAddress1[0].childNodes[0].nodeValue;

            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName(top_loc);
            document.getElementById(txttop).value = XMLAddress1[0].childNodes[0].nodeValue;

            i = i + 1;
        }
    }
}


function save_inv()
{
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null)
    {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "cheque_setup_data_acc.php";
    url = url + "?Command=" + "save_crec";
    url = url + "&com_bank=" + document.getElementById("com_bank").value;

    var i = 1;
    while (i <= 14) {
        name = "left" + i;
        url = url + "&" + name + "=" + document.getElementById(name).value;
        i = i + 1;
    }

    var i = 1;
    while (i <= 14) {
        name = "top" + i;
        url = url + "&" + name + "=" + document.getElementById(name).value;
        i = i + 1;
    }

    var i = 1;
    while (i <= 14) {
        name = "font_name" + i;
        url = url + "&" + name + "=" + document.getElementById(name).value;
        i = i + 1;
    }

    var i = 1;
    while (i <= 14) {
        name = "fontsize" + i;
        url = url + "&" + name + "=" + document.getElementById(name).value;
        i = i + 1;
    }



    alert(url);
    xmlHttp.onreadystatechange = result_save_crec;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}

function result_save_crec()
{
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
    {
        alert(xmlHttp.responseText);
    }
}
 