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

function chpass() {
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    if (document.getElementById('nwpass').value != document.getElementById('nwpass1').value) {
        alert("Wrong Password");
        return;
    }

    var url = "mspace_data.php";
    url = url + "?Command=" + "chpass";
    url = url + "&nwpass=" + document.getElementById('nwpass').value;
    url = url + "&nwpass1=" + document.getElementById('nwpass1').value;
    url = url + "&oldpass=" + document.getElementById('oldpass').value;

    xmlHttp.onreadystatechange = result_viewg;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}

function result_viewg() {
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "200") {
        document.getElementById('p1').innerHTML = "";
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("amou");
        document.getElementById('p1').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;
    }
}

function updt() {
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "mspace_data.php";
    url = url + "?Command=" + "updt";
    url = url + "&info=" + document.getElementById('info').value;

    xmlHttp.onreadystatechange = result_viewg1;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}

function result_viewg1() {
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "200") {
        document.getElementById('p2').innerHTML = "";
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("amou");
        document.getElementById('p2').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;
    }
}

function nwuser() {
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "mspace_data.php";
    url = url + "?Command=" + "nwuser";
    url = url + "&uname=" + document.getElementById('uname').value;
    url = url + "&upass=" + document.getElementById('upass').value;
    url = url + "&epfno=" + document.getElementById('epfno').value;

    url = url + "&cleave=" + document.getElementById('cleave').value;
    url = url + "&aleave=" + document.getElementById('aleave').value;
    url = url + "&mleave=" + document.getElementById('mleave').value;



    xmlHttp.onreadystatechange = result_viewg2;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}

function result_viewg2() {
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "200") {
        document.getElementById('p1').innerHTML = "";
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("amou");
        document.getElementById('p1').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;
    }
}

function arnno_gin(arno, st)
{
    //alert("ok");
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null)
    {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "mspace_data.php";
    url = url + "?Command=" + "pass_arnno_gin";
    url = url + "&arnno=" + arno;
    url = url + "&st=" + st;
    //alert(url);

    xmlHttp.onreadystatechange = result_pass_arnno_gin;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}

function result_pass_arnno_gin()
{
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
    {



        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("username");
        opener.document.form1.uname.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("epfno");
        opener.document.form1.epfno.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("st");


        if (XMLAddress1[0].childNodes[0].nodeValue == "") {
            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("userpass");
            opener.document.form1.upass.value = XMLAddress1[0].childNodes[0].nodeValue;




            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("annual");
            opener.document.form1.aleave.value = XMLAddress1[0].childNodes[0].nodeValue;

            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("medica");
            opener.document.form1.mleave.value = XMLAddress1[0].childNodes[0].nodeValue;

            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("casua");
            opener.document.form1.cleave.value = XMLAddress1[0].childNodes[0].nodeValue;


            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("tabe");
            window.opener.document.getElementById('atabel').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;

            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("count");
            window.opener.document.getElementById('count').value = XMLAddress1[0].childNodes[0].nodeValue;

        }
        self.close();


    }
}

function saveme()
{
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null)
    {
        alert("Browser does not support HTTP Request");
        return;
    }



    var url = "mspace_data.php";
    url = url + "?Command=" + "allwance";
    url = url + "&count=" + document.getElementById('count').value;
    url = url + "&epfno=" + document.getElementById('epfno').value;


    var i = 1;
    while (i <= (parseInt(document.getElementById('count').value))) {
        qus = "q" + i;
        all = "a" + i;
        url = url + "&" + qus + "=" + document.getElementById(qus).value;
        url = url + "&" + all + "=" + document.getElementById(all).value;
        i = i + 1;
    }

    xmlHttp.onreadystatechange = result_viewgsm;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}




function result_viewgsm()
{
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "200")
    {

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("tabe");
        document.getElementById('atabel').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("count");
        document.getElementById('count').value = XMLAddress1[0].childNodes[0].nodeValue;


    }
}


function del(qid)
{
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null)
    {
        alert("Browser does not support HTTP Request");
        return;
    }



    var url = "mspace_data.php";
    url = url + "?Command=" + "delal";
    url = url + "&qid=" + qid;
    url = url + "&epfno=" + document.getElementById('epfno').value;


    xmlHttp.onreadystatechange = result_viewgsm;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}
