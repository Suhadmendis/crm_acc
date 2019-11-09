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
    url = url + "&currency=" + document.getElementById('currency').value;
    url = url + "&rate=" + document.getElementById('txt_rate').value;
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

function ledgno(custno, stname)
{
    try {


        xmlHttp = GetXmlHttpObject();
        if (xmlHttp == null)
        {
            alert("Browser does not support HTTP Request");
            return;
        }

        var url = "account_master_data.php";
        url = url + "?Command=" + "pass_cash_rec";
        url = url + "&ledgno=" + custno;
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
        
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("stname");
        if (XMLAddress1[0].childNodes[0].nodeValue == "mas") {
            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("c_code");
            opener.document.form1.txt_entno.value = XMLAddress1[0].childNodes[0].nodeValue;

            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("c_name");
            opener.document.form1.txt_accname.value = XMLAddress1[0].childNodes[0].nodeValue;

            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("filebox");
            window.opener.document.getElementById('filebox').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;

            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("txt_Opening");
            opener.document.form1.txt_Opening.value = XMLAddress1[0].childNodes[0].nodeValue;

            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("dtpOpenDate");
            opener.document.form1.dtpOpenDate.value = XMLAddress1[0].childNodes[0].nodeValue;

            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("acctype");
            if (XMLAddress1[0].childNodes[0].nodeValue == "B") {
                opener.document.form1.bank.checked = true;
            } else {
                opener.document.form1.bank.checked = false;
            }
            
            
            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("c_type");
            if (XMLAddress1[0].childNodes[0].nodeValue == "B") {
                opener.document.form1.bal.checked = true;
            }  
            if (XMLAddress1[0].childNodes[0].nodeValue == "P") {
                opener.document.form1.pnl.checked = true;
            }
            if (XMLAddress1[0].childNodes[0].nodeValue == "M") {
                opener.document.form1.man.checked = true;
            }


            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("actype");
            opener.document.form1.acType.value = XMLAddress1[0].childNodes[0].nodeValue;    

            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("actype1");
            opener.document.form1.acType1.value = XMLAddress1[0].childNodes[0].nodeValue;    


            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("cur");
            opener.document.form1.currency.value = XMLAddress1[0].childNodes[0].nodeValue;
            
            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("rate");
            opener.document.form1.txt_rate.value = XMLAddress1[0].childNodes[0].nodeValue;
            
             XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("c_remark");
            opener.document.form1.txt_remarks.value = XMLAddress1[0].childNodes[0].nodeValue;
                     
            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("txt_gl_code");
            opener.document.form1.txt_gl_code.value = XMLAddress1[0].childNodes[0].nodeValue;
            
            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("txt_gl_name");
            opener.document.form1.txt_gl_name.value = XMLAddress1[0].childNodes[0].nodeValue;
            

        } else if (XMLAddress1[0].childNodes[0].nodeValue == "p2") {
            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("c_code");
            opener.document.form1.txt_gl_code1.value = XMLAddress1[0].childNodes[0].nodeValue;

            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("c_name");
            opener.document.form1.txt_gl_name1.value = XMLAddress1[0].childNodes[0].nodeValue;

            opener.document.form1.itemPrice1.focus();
        } else if (XMLAddress1[0].childNodes[0].nodeValue ==  "payment_voucher") {
            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("c_code");
            opener.document.form1.payment_cash_book.value = XMLAddress1[0].childNodes[0].nodeValue;

            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("c_name");
            opener.document.form1.txt_gl_name.value = XMLAddress1[0].childNodes[0].nodeValue;

        } else {
            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("c_code");
            opener.document.form1.txt_gl_code.value = XMLAddress1[0].childNodes[0].nodeValue;

            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("c_name");
            opener.document.form1.txt_gl_name.value = XMLAddress1[0].childNodes[0].nodeValue;

            XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("stname");
            if (XMLAddress1[0].childNodes[0].nodeValue =! "p1") {
                opener.document.form1.itemPrice.focus();
            }
        }
     
        
        self.close();
    }
}
