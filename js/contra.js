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


function set_inv() {
    var url = "serach_customer.php?stname=cont&cur=" +  document.getElementById('currency').value;
    NewWindow(url, 'mywin', '800', '700', 'yes', 'center');                          
}

 

function cancel_inv() {
    $('#myModal_c').modal('hide');

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    document.getElementById('msg_box').innerHTML = "";
    if (document.getElementById('c_code').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Select Entry</span></div>";
        return false;
    }
    
     
    var url = "contra_data.php";
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
     
    if (document.getElementById('txt_amount_lkr').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Amount Not Enterd</span></div>";
        return false;
    }
    a = parseFloat(document.getElementById('txt_amount_lkr').value);
    var b = document.getElementById('txt_amount_lkr1').value;
    b = b.replace(",", "");
    b = parseFloat(b);
    if (a != b) {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Amount Mismatch</span></div>";
        return false;
    }
    
    var url = "contra_data.php";
    var params = "Command=" + "save_item";
    params = params + "&crnno=" + document.getElementById('txt_entno').value;
    params = params + "&cus_code=" + escape(document.getElementById('c_code').value);
    params = params + "&c_name=" + escape(document.getElementById('c_name').value);

    params = params + "&cur=" + document.getElementById('currency').value;
    params = params + "&Rate=" + document.getElementById('txt_rate').value;
    params = params + "&crndate=" + document.getElementById('invdate').value;
    params = params + "&tmpno=" + document.getElementById('tmpno').value;

    params = params + "&count=" + document.getElementById('count').value;

    var count = document.getElementById('count').value;
    var i = 1;
    while (count > i) {
        var refno = "refno" + i;
        var pay = "pay" + i;
        if (isNaN(document.getElementById(pay).value) == false) {
            params = params + '&' + refno + '=' + document.getElementById(refno).value;
            params = params + '&' + pay + '=' + document.getElementById(pay).value;
        }
        i = i + 1;
    }

    
    params = params + "&count1=" + document.getElementById('count1').value;

    var count = document.getElementById('count1').value;
    var i = 1;
    while (count > i) {
        var refno = "crefno" + i;
        var pay = "cpay" + i;
        if (isNaN(document.getElementById(pay).value) == false) {
            params = params + '&' + refno + '=' + document.getElementById(refno).value;
            params = params + '&' + pay + '=' + document.getElementById(pay).value;
        }
        i = i + 1;
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

function print_inv(cdata) {

    var url = "contra_print.php";
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
    document.getElementById('msg_box').innerHTML = "";

  

 
  
    document.getElementById('invdt').innerHTML = "";
    document.getElementById('invdt1').innerHTML = "";
    
    document.getElementById('file-3').value = "";
    document.getElementById('filebox').innerHTML = "";
    document.getElementById('filup').style.visibility = "hidden";

 

    var url = "contra_data.php";
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




function del_item(cdate, cdate1) {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "contra_data.php";
    url = url + "?Command=" + "del_item";
    url = url + "&code=" + cdate;
    url = url + "&invno=" + document.getElementById('tmpno').value;
    url = url + "&form=" + cdate1;
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

        var url = "contra_data.php";
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

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("name");
        opener.document.form1.c_name.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("C_DATE");
        opener.document.form1.invdate.value = XMLAddress1[0].childNodes[0].nodeValue;

      

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("currency");
        opener.document.form1.currency.value = XMLAddress1[0].childNodes[0].nodeValue;
              
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("txt_rate");
        opener.document.form1.txt_rate.value = XMLAddress1[0].childNodes[0].nodeValue;

    

    

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table3");
        window.opener.document.getElementById('invdt').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;


        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table4");
        window.opener.document.getElementById('invdt1').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;



         

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

    var url = "contra_data.php";
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

function calamo1() {

    var count = document.getElementById('count1').value;
    var i = 1;

    var mamo = 0;
   
    while (count > i) {

        var bal = "cbal" + i;
        var pay = "cpay" + i;


        if (document.getElementById(pay).value != "") {
            if (parseFloat(document.getElementById(bal).value) < parseFloat(document.getElementById(pay).value)) {
                document.getElementById(pay).value = 0;
            }

            
            mamo = mamo + parseFloat(document.getElementById(pay).value);

        }
        i = i + 1;
    }
    document.getElementById('txt_amount_lkr').value = mamo;
    
}

function calamo() {

    var count = document.getElementById('count').value;
    var i = 1;

    var mamo = parseFloat(document.getElementById('txt_amount_lkr').value);
    var mpaid = 0; 
    while (count > i) {

        var bal = "bal" + i;
        var pay = "pay" + i;
        var cashbal = "cashbal" + i;

        if (document.getElementById(pay).value != "") {
            if (parseFloat(document.getElementById(bal).value) < parseFloat(document.getElementById(pay).value)) {
                document.getElementById(pay).value = 0;
            }

            if (parseFloat(document.getElementById(pay).value) > mamo) {
                document.getElementById(pay).value = 0;
            }


            document.getElementById(cashbal).value = (mamo - parseFloat(document.getElementById(pay).value));
            mamo = mamo - parseFloat(document.getElementById(pay).value);
            mpaid = parseFloat(mpaid) + parseFloat(document.getElementById(pay).value);
        }
        i = i + 1;
    }
    document.getElementById('txt_amount_lkr1').value = mpaid;       
}


function calrate_r() {
    document.getElementById('txt_amount_lkr').value = roundToTwo((document.getElementById('txt_rate1').value / document.getElementById('txt_rate').value) * document.getElementById('txt_amount').value);
}


function roundToTwo(num) {
    return +(Math.round(num + "e+2") + "e-2");
}