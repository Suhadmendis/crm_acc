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
    document.getElementById("py_no").value = "";
    document.getElementById("manual_ref").value = "";
//    document.getElementById("date").value = "";
    document.getElementById("payment_cash_book").value = "";
    document.getElementById("currency_code").value = "";
    document.getElementById("rate").value = "";
    document.getElementById("payment_type").value = "";
    document.getElementById("cheque_no_ref").value = "";
    document.getElementById("chq_date_ref").value = "";
    document.getElementById("chq_bank_ref").value = "";
    document.getElementById("total_amount_of_payment").value = "";
    document.getElementById("allocation_percentage").value = "";
    document.getElementById("allocated").value = "";
    document.getElementById("to_be_allocated").value = "";
    document.getElementById("payee_name").value = "";
    document.getElementById("remark").value = "";



    getdt();

}

function getdt() {
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "pay_voucher_data.php";
    url = url + "?Command=" + "getdt";
    url = url + "&ls=" + "new";

    xmlHttp.onreadystatechange = get_dt;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}

function get_dt() {
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
    {
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("py_no");
        document.getElementById("py_no").value = XMLAddress1[0].childNodes[0].nodeValue;
        
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("id3");
        document.getElementById("uniq").value = XMLAddress1[0].childNodes[0].nodeValue;
//      
    }
}  






function save_inv() {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }



//
//


    var url = "pay_voucher_data.php";
    url = url + "?Command=" + "save_content";
    url = url + "&py_no=" + document.getElementById("py_no").value;

    url = url + "&manual_ref=" + document.getElementById("manual_ref").value;
    url = url + "&date=" + document.getElementById("date").value;
    url = url + "&payment_cash_book=" + document.getElementById("payment_cash_book").value;
    url = url + "&currency_code=" + document.getElementById("currency_code").value;
    url = url + "&rate=" + document.getElementById("rate").value;
    url = url + "&payment_type=" + document.getElementById("payment_type").value;
    url = url + "&cheque_no_ref=" + document.getElementById("cheque_no_ref").value;
    url = url + "&chq_date_ref=" + document.getElementById("chq_date_ref").value;
    url = url + "&chq_bank_ref=" + document.getElementById("chq_bank_ref").value;
    url = url + "&total_amount_of_payment=" + document.getElementById("total_amount_of_payment").value;
    url = url + "&allocation_percentage=" + document.getElementById("allocation_percentage").value;
    url = url + "&allocated=" + document.getElementById("allocated").value;
    url = url + "&to_be_allocated=" + document.getElementById("to_be_allocated").value;
    url = url + "&payee_name=" + document.getElementById("payee_name").value;
    url = url + "&remark=" + document.getElementById("remark").value;
    url = url + "&uniq=" + document.getElementById("uniq").value;
    url = url + "&user=" + "dev";
    url = url + "&c_ref=" + document.getElementById("c_ref").value;


    xmlHttp.onreadystatechange = added;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}


function added() {
    var XMLAddress1;
    if (xmlHttp.readyState === 4 || xmlHttp.readyState === "complete") {

        if (xmlHttp.responseText == "Saved") {
            document.getElementById('msg_box').innerHTML = "<div class='alert alert-success' role='alert'><span class='center-block'>Saved</span></div>";

            //document.getElementById('filup').style.visibility = "visible";
            $("#msg_box").hide().slideDown(200).delay(1500);
            $("#msg_box").slideUp(200);



        } else {
            document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>" + xmlHttp.responseText + "</span></div>";
        }
    }
}

function add_tmp() {
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "pay_voucher_data.php";
    url = url + "?Command=" + "setitem";
    url = url + "&Command1=" + "add_tmp";

//    url = url + "&aodnumber=" + mid;
    url = url + "&py_no=" + document.getElementById('py_no').value;
    url = url + "&debitaccount=" + document.getElementById('debitaccount').value;
    url = url + "&accountname=" + document.getElementById('accountname').value;
    url = url + "&total_amount_of_payment=" + document.getElementById('total_amount_of_payment').value;
    url = url + "&percentage_apply=" + document.getElementById('percentage_apply').value;
    url = url + "&amount=" + document.getElementById('amount').value;
    url = url + "&jobno=" + document.getElementById('jobno').value;
    url = url + "&supplier_in_no=" + document.getElementById('supplier_in_no').value;
    url = url + "&supplier_inv_date=" + document.getElementById('supplier_inv_date').value;
    url = url + "&uniq=" + document.getElementById('uniq').value;

    xmlHttp.onreadystatechange = aodtmp;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);


}


function aodtmp() {
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table");
        document.getElementById('itemdetails').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;
       
       
          XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("alAmount");
        var all = parseFloat(XMLAddress1[0].childNodes[0].nodeValue) || 0;
        document.getElementById('allocated').value = all.toFixed(2);


        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("tbAmount");
        var tb = parseFloat(XMLAddress1[0].childNodes[0].nodeValue) || 0;
        document.getElementById('to_be_allocated').value = tb.toFixed(2);
     
    }
}


function remove_tmp(id) {
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "pay_voucher_data.php";
    url = url + "?Command=" + "removerow";

    url = url + "&uniq=" + document.getElementById('uniq').value;
    url = url + "&py_no=" + document.getElementById('py_no').value;
    url = url + "&id=" + id;


    xmlHttp.onreadystatechange = removeAdo;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);


}


function removeAdo() {
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table");
        document.getElementById('itemdetails').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;

    }
}






function resCal() {

    var total_amount_of_receipt = parseFloat(document.getElementById("total_amount_of_payment").value) || 0;

    document.getElementById("to_be_allocated").value = total_amount_of_receipt.toFixed(2);



}




function perCal(sw,event) {

//    var challocated = parseFloat(document.getElementById("allocated").value) || 0;
//    var chamount = parseFloat(document.getElementById("amount").value) || 0;
//    var temptot = parseFloat(challocated + chamount) || 0;
//    var chto_be_allocated = parseFloat(document.getElementById("to_be_allocated").value) || 0;
//    alert(chto_be_allocated);
//
//    if (temptot < chto_be_allocated) {
    if (sw === "per") {

        var total_amount_of_receipt = parseFloat(document.getElementById("total_amount_of_payment").value) || 0;
        var percentageapply = parseFloat(document.getElementById("percentage_apply").value) || 0;

        var amount = total_amount_of_receipt / 100;
        amount = amount * percentageapply;

        document.getElementById("amount").value = amount.toFixed(2);

    } else if (sw === "am") {

        var total_amount_of_receipt = parseFloat(document.getElementById("total_amount_of_payment").value) || 0;
        var amount = parseFloat(document.getElementById("amount").value) || 0;

        var per = total_amount_of_receipt / 100;

        per = amount / per;
        
        document.getElementById("percentage_apply").value = per.toFixed(2);


    }

    var toboA = parseFloat(document.getElementById("to_be_allocated").value) || 0;
    var tempA = parseFloat(document.getElementById("amount").value) || 0;


    // alert("to bo " + toboA + "," + "temp " + tempA);
    if (toboA < tempA) {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-danger' role='alert'><span class='center-block'>Incorrect Amount</span></div>";


        $("#msg_box").hide().slideDown(200).delay(1500);
        $("#msg_box").slideUp(200);

        document.getElementById("percentage_apply").value = "";
        document.getElementById("amount").value = "";


    }
    
    
    var x = event.which || event.keyCode;


    if (x === 13) {
        add_tmp();
    }

}