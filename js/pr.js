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
    if (document.getElementById('txt_amount').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Select Entry</span></div>";
        return false;
    }
    if (document.getElementById('txt_amount_lkr').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Select Entry</span></div>";
        return false;
    }
    a = parseFloat(document.getElementById('txt_amount').value);
    var b = document.getElementById('subtot').value;
    b = b.replace(",", "");
    b = parseFloat(b);
    if (a != b) {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'></span></div>";
        return false;
    }
    var url = "pr_data.php";
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
    if (document.getElementById('total_value').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Amount Not Enterd</span></div>";
        return false;
    }
    var url = "pr_data.php";
    var params = "Command=" + "save_item";
    params = params + "&invno=" + document.getElementById('txt_entno').value;
    params = params + "&cus_code=" + escape(document.getElementById('c_code').value);
    params = params + "&c_name=" + escape(document.getElementById('c_name').value);
    params = params + "&total_value=" + document.getElementById('total_value').value;
    params = params + "&invdate=" + document.getElementById('invdate').value;
    params = params + "&tmpno=" + document.getElementById('tmpno').value;
    params = params + "&orderno1=" + document.getElementById('orderno1').value;

    params = params + "&count=" + document.getElementById('arn_item_count').value;
    params = params + "&LCNO=" + document.getElementById('LCNO').value;




    var count = document.getElementById('arn_item_count').value;
    var i = 1;


    while (i < count) {
        itemcode = "itemcode" + i;
        itemname = "itemname" + i;
        ord_qty = "ord_qty" + i;
        qty = "qty" + i;
        cost = "cost" + i;
        subtotal = "subtotal" + i;

        params = params + "&" + itemcode + "=" + document.getElementById(itemcode).innerHTML;
        params = params + "&" + itemname + "=" + document.getElementById(itemname).innerHTML;
        params = params + "&" + ord_qty + "=" + document.getElementById(ord_qty).innerHTML;
        params = params + "&" + qty + "=" + document.getElementById(qty).value;
        params = params + "&" + cost + "=" + document.getElementById(cost).value;
        params = params + "&" + subtotal + "=" + document.getElementById(subtotal).value;
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

    var url = "pr_print.php";
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
    document.getElementById('itemdetails').innerHTML = "";
    document.getElementById('itemdetails1').innerHTML = "";
    document.getElementById('subtot').value = "";
    document.getElementById('txt_gl_code').value = "";
    document.getElementById('txt_gl_name').value = "";
    document.getElementById('itemPrice').value = "";
    document.getElementById('txt_gl_code1').value = "";
    document.getElementById('txt_gl_name1').value = "";
    document.getElementById('itemPrice1').value = "";
    document.getElementById('orderno1').value = "";
    document.getElementById('total_value').value = "";
    document.getElementById('subtot1').value = "";
    document.getElementById('filebox').innerHTML = "";
    document.getElementById('invdt').innerHTML = "";
    document.getElementById('file-3').value = "";
    document.getElementById('filup').style.visibility = "hidden"; 
    document.getElementById('LCNO').value = ""; 
 
 
    var url = "pr_data.php";
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


function crnview(custno, stname)
{
    try {


        xmlHttp = GetXmlHttpObject();
        if (xmlHttp == null)
        {
            alert("Browser does not support HTTP Request");
            return;
        }

        var url = "pr_data.php";
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

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("REFNO");
        opener.document.form1.txt_entno.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("ORDNO");
        opener.document.form1.orderno1.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("SUP_CODE");
        opener.document.form1.c_code.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("SUP_NAME");
        opener.document.form1.c_name.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("SDATE");
        opener.document.form1.invdate.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("LCNO");
        opener.document.form1.LCNO.value = XMLAddress1[0].childNodes[0].nodeValue;





//        alert("count value "+opener.document.form1.count.value);

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table0");
        window.opener.document.getElementById('invdt').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("AMOUNT");
        opener.document.form1.total_value.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table");
        window.opener.document.getElementById('itemdetails').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table1");
        window.opener.document.getElementById('itemdetails1').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("subtot");
        window.opener.document.getElementById('subtot').value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("subtot1");
        window.opener.document.getElementById('subtot1').value = XMLAddress1[0].childNodes[0].nodeValue;

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

    var url = "pr_data.php";
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


function calamo() {

    var count = document.getElementById('count').value;
    var i = 1;

    var mamo = parseFloat(document.getElementById('txt_amount_lkr').value);
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

        }
        i = i + 1;
    }
}






function cal_subtot()
{

    var totcount = parseFloat(document.getElementById("arn_item_count").value);
    var i = 1;
    var tot = 0;
    while (i < totcount) {

        var qty = 'qty' + i;
        var cost = 'cost' + i;
        var subtotal = 'subtotal' + i;
        

        if (document.getElementById(qty).value == "") {
            qty_val = 0;
        } else {
            qty_val = parseFloat(document.getElementById(qty).value);
        }

        if (document.getElementById(cost).value == "") {
            cost_val = 0;
        } else {
            cost_val = parseFloat(document.getElementById(cost).value);
        }

        document.getElementById(subtotal).value = qty_val * cost_val;


        var subtotalind = 'subtotal' + i;
        if (document.getElementById(subtotalind).value != '') {
            tot = parseFloat(tot) + parseFloat(document.getElementById(subtotalind).value);
        }
        i = i + 1;
    }

    document.getElementById("total_value").value = tot;
}

 