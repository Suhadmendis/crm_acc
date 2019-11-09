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


function getno1() {
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }
    
    var url = "payment_data.php";
    url = url + "?Command=" + "getno";
    url = url + "&tmpno=" + document.getElementById('tmpno').value ;
    url = url + "&sdate=" + document.getElementById('invdate').value ;
    
    
    xmlHttp.onreadystatechange = assign_invno;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
    
}

function opn_bal() {
    var url = 'serach_bal.php?cur=' + document.getElementById('currency1').value + '&c_code=' + document.getElementById('c_code').value;

    NewWindow(url, 'mywin', '800', '700', 'yes', 'center');
}




function balview(custno, stname)
{
    try {


        xmlHttp = GetXmlHttpObject();
        if (xmlHttp == null)
        {
            alert("Browser does not support HTTP Request");
            return;
        }

        var url = "payment_data.php";
        url = url + "?Command=" + "pass_bal";
        url = url + "&refno=" + custno;
        url = url + "&stname=" + stname;

        xmlHttp.onreadystatechange = pass_bal_result;

        xmlHttp.open("GET", url, true);
        xmlHttp.send(null);
    } catch (err) {
        alert(err.message);
    }
}

function pass_bal_result()
{
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
    {



        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("refno");
        opener.document.form1.txt_pref.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("curamo");
        opener.document.form1.txt_pamo.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("curbal");
        opener.document.form1.txt_pbal.value = XMLAddress1[0].childNodes[0].nodeValue;



        self.close();
    }

}


function add_bal() {


    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    if ((document.getElementById('txt_entno').value != "")) {

        var url = "payment_data.php";
        url = url + "?Command=" + "add_bal";
        url = url + "&txt_pref=" + document.getElementById('txt_pref').value;
        url = url + "&txt_pinv=" + document.getElementById('txt_pinv').value;
        url = url + "&txt_pamo=" + document.getElementById('txt_pamo').value;

        url = url + "&txt_pbal=" + document.getElementById('txt_pbal').value;
        url = url + "&txt_ppay=" + document.getElementById('txt_ppay').value;

        url = url + "&tmpno=" + document.getElementById('tmpno').value;


        xmlHttp.onreadystatechange = showarmyresultbal;
        xmlHttp.open("GET", url, true);
        xmlHttp.send(null);

    } else if (document.getElementById('c_code').value == "") {
        alert("Please Select Customer");
    }

}


function showarmyresultbal() {
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table");
        document.getElementById('inv_details').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;



        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("subtot");
        document.getElementById('txt_payments').value = XMLAddress1[0].childNodes[0].nodeValue;


        document.getElementById('txt_pref').value = "";
        document.getElementById('txt_pinv').value = "";
        document.getElementById('txt_pamo').value = "";
        document.getElementById('txt_pbal').value = "";
        document.getElementById('txt_ppay').value = "";

        document.getElementById('ser_bal').focus();
    }
}



function add_tmp() {


    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    if ((document.getElementById('txt_entno').value != "")) {

        var url = "payment_data.php";
        url = url + "?Command=" + "add_tmp";
        url = url + "&invno=" + document.getElementById('txt_entno').value;
        url = url + "&itemCode=" + document.getElementById('txt_gl_code').value;
        url = url + "&itemDesc=" + document.getElementById('txt_gl_name').value;

        var desc;
        if (document.getElementById('txt_gl_name1').value == "") {
            desc = document.getElementById('txt_narration').value;
        } else {
            desc = document.getElementById('txt_gl_name1').value;
        }
        url = url + "&itemDesc1=" + desc;
        url = url + "&itemPrice=" + document.getElementById('itemPrice').value;

        url = url + "&tmpno=" + document.getElementById('tmpno').value;


        xmlHttp.onreadystatechange = showarmyresultdel;
        xmlHttp.open("GET", url, true);
        xmlHttp.send(null);

    } else if (document.getElementById('c_code').value == "") {
        alert("Please Select Customer");
    }

}

function showarmyresultdel() {
    var XMLAddress1;

    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {



        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table");
        document.getElementById('itemdetails').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;



        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("subtot");
        document.getElementById('subtot').value = XMLAddress1[0].childNodes[0].nodeValue;

        document.getElementById('txt_gl_code').value = "";
        document.getElementById('txt_gl_name1').value = "";
        document.getElementById('txt_gl_name').value = "";
        document.getElementById('itemPrice').value = "";
        document.getElementById('cmd_glcode').focus();
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
    if (document.getElementById('c_name').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Customer Not Selected</span></div>";
        return false;
    }
    if (document.getElementById('txt_payments').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Amount Not Enterd</span></div>";
        return false;
    }
    if (document.getElementById('subtot').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Amount Not Enterd</span></div>";
        return false;
    }

    var a = (document.getElementById('txt_payments').value);
    var b = document.getElementById('subtot').value;
    b = b.replace(",", "");
    b = b.replace(",", "");
    b = parseFloat(b);
    a = a.replace(",", "");
    a = a.replace(",", "");
    if (a != b) {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Amount Mismatch</span></div>";
        return false;
    }

    var url = "payment_data.php";
    url = url + "?Command=" + "save_item";

    url = url + "&invno=" + document.getElementById('txt_entno').value;
    url = url + "&tmpno=" + document.getElementById('tmpno').value;
    url = url + "&invdate=" + document.getElementById('invdate').value;

    url = url + "&customercode=" + document.getElementById('c_code').value;
    url = url + "&customername=" + escape(document.getElementById('c_name').value);
    url = url + "&subtot=" + document.getElementById('subtot').value;
    url = url + "&tmpno=" + document.getElementById('tmpno').value;
    url = url + "&txt_narration=" + escape(document.getElementById('txt_narration').value);
    url = url + "&cheq_no=" + document.getElementById('cheq_no').value;
    url = url + "&txt_svat=" + parseFloat(document.getElementById('txt_svat').value);

    url = url + "&txt_payments=" + a;
    url = url + "&bank=" + document.getElementById('bank').value;
    
    if (document.getElementById('acpay').checked == true) {
            url = url + "&acpay=1";
    } else {
            url = url + "&acpay=0";
    }


    url = url + "&txt_bankamo=" + parseFloat(document.getElementById('txt_bankamo').value);



    url = url + "&currency=" + document.getElementById('currency').value;
    url = url + "&currency1=" + document.getElementById('currency1').value;

    url = url + "&chq_barer=" + document.getElementById('chq_barere').value;


    url = url + "&txt_rate=" + document.getElementById('txt_rate').value;
    url = url + "&txt_rate1=" + document.getElementById('txt_rate1').value;

    xmlHttp.onreadystatechange = salessaveresult;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}

function salessaveresult() {
    var XMLAddress1;
    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete") {

        if (xmlHttp.responseText == "Saved") {
            document.getElementById('msg_box').innerHTML = "<div class='alert alert-success' role='alert'><span class='center-block'>Saved</span></div>";
            amoword_cal();
            print_inv("save");
            amoword_cal();
            setTimeout("location.reload(true);", 500);
        } else {
            document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>" + xmlHttp.responseText + "</span></div>";
        }
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

        var url = "payment_data.php";
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
        
         
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("chq_barere");
        opener.document.form1.chq_barere.value = XMLAddress1[0].childNodes[0].nodeValue;
        
        
        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("tmp_no");
        opener.document.form1.tmpno.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("C_REFNO");
        opener.document.form1.txt_entno.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("C_CODE");
        opener.document.form1.c_code.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("name");
        opener.document.form1.c_name.value = XMLAddress1[0].childNodes[0].nodeValue;


        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("cheq_no");
        opener.document.form1.cheq_no.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("svat");
        opener.document.form1.txt_svat.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("ac_payee");
        if (XMLAddress1[0].childNodes[0].nodeValue == "1") {
            opener.document.form1.acpay.checked = true;
        } else {
            opener.document.form1.acpay.checked = false;
        }



        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("txt_bankamo");
        opener.document.form1.txt_bankamo.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("amount");
        opener.document.form1.txt_payments.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("code");
        opener.document.form1.bank.value = XMLAddress1[0].childNodes[0].nodeValue;


        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("C_DATE");
        opener.document.form1.invdate.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("txt_remarks");
        opener.document.form1.txt_narration.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("currency");
        opener.document.form1.currency.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("txt_rate");
        opener.document.form1.txt_rate.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("currency1");
        opener.document.form1.currency1.value = XMLAddress1[0].childNodes[0].nodeValue;

        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("txt_rate1");
        opener.document.form1.txt_rate1.value = XMLAddress1[0].childNodes[0].nodeValue;



        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table");
        window.opener.document.getElementById('itemdetails').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;


        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("sales_table1");
        window.opener.document.getElementById('inv_details').innerHTML = XMLAddress1[0].childNodes[0].nodeValue;



        XMLAddress1 = xmlHttp.responseXML.getElementsByTagName("subtot");
        window.opener.document.getElementById('subtot').value = XMLAddress1[0].childNodes[0].nodeValue;


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

function new_inv() {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    document.getElementById('txt_entno').value = "";
    document.getElementById('c_name').value = "";
    document.getElementById('c_code').value = "";

    document.getElementById('itemdetails').innerHTML = "";
    document.getElementById('inv_details').innerHTML = "";
  document.getElementById('chq_barere').value = "";



    document.getElementById('subtot').value = "";

    document.getElementById('txt_gl_code').value = "";

    document.getElementById('txt_gl_name').value = "";
    document.getElementById('txt_gl_name1').value = "";
    document.getElementById('itemPrice').value = "";
    document.getElementById('acpay').checked = false;

    document.getElementById('txt_bankamo').value = "";

    document.getElementById('txt_narration').value = "";
    document.getElementById('cheq_no').value = "";
    document.getElementById('txt_payments').value = "";

    document.getElementById('inv_details').value = "";



    document.getElementById('txt_pref').value = "";
    document.getElementById('txt_pinv').value = "";
    document.getElementById('txt_pamo').value = "";
    document.getElementById('txt_pbal').value = "";
    document.getElementById('txt_ppay').value = "";

    document.getElementById('txt_svat').value = "";

    document.getElementById('file-3').value = "";
    document.getElementById('filebox').innerHTML = "";
    document.getElementById('filup').style.visibility = "hidden";







    document.getElementById('msg_box').innerHTML = "";


    var url = "payment_data.php";
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




function del_item(cdate) {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "payment_data.php";
    url = url + "?Command=" + "del_item";
    url = url + "&code=" + cdate;
    url = url + "&invno=" + document.getElementById('tmpno').value;

    xmlHttp.onreadystatechange = showarmyresultdel;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);

}





function del_bal(cdate) {




    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    if ((document.getElementById('txt_entno').value != "")) {

        var url = "payment_data.php";
        url = url + "?Command=" + "add_bal";
        url = url + "&code=" + cdate;
        url = url + "&tmpno=" + document.getElementById('tmpno').value;
        url = url + "&com1=del";


        xmlHttp.onreadystatechange = showarmyresultbal;
        xmlHttp.open("GET", url, true);
        xmlHttp.send(null);

    } else if (document.getElementById('c_code').value == "") {
        alert("Please Select Customer");
    }



}




function print_inv(cdata) {
    amoword_cal();
    var url = "payment_print.php";
    url = url + "?tmp_no=" + document.getElementById('tmpno').value;
    url = url + "&action=" + cdata;

    window.open(url, '_blank');


}

function print_chq() {
amoword_cal();
    var url = "payment_chq_print.php";
    url = url + "?tmp_no=" + document.getElementById('tmpno').value;

    
    url = url + "&txt_amo=" + document.getElementById('txt_amoinword').value;

    window.open(url, '_blank');


}

function update_list(stname) {

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null)
    {
        alert("Browser does not support HTTP Request");
        return;
    }

    var url = "payment_data.php";
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


function cancel_inv() {
    $('#myModal_c').modal('hide');

    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) {
        alert("Browser does not support HTTP Request");
        return;
    }

    document.getElementById('msg_box').innerHTML = "";
    if (document.getElementById('c_name').value == "") {
        document.getElementById('msg_box').innerHTML = "<div class='alert alert-warning' role='alert'><span class='center-block'>Select Entry</span></div>";
        return false;
    }

    var url = "payment_data.php";
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


function amoword_cal() {

    var M_TXT = "";


    M_INPUT = document.getElementById('txt_payments').value;

    M_INPUTLEN = M_INPUT.length;
    //Cents.........................................................................
    m_cent = "";
    ii = 0;
    m_ok = false;

    TXT_DEBTOT = document.getElementById('txt_payments').value;

    while (ii < TXT_DEBTOT.length) {
        next = ii + 1;

        if (m_ok == true) {
            m_cent = m_cent + TXT_DEBTOT.substring(ii, next);
        }
        if (TXT_DEBTOT.substring(ii, next) == ".") {
            m_ok = true;
        }

        ii = ii + 1;
    }
    m_say = "";
    m_say1 = "";
    m_amo = m_cent.substring(0, 2);

    M_AMO1 = m_cent.substring(0, 1) + "0";
    m_amo2 = m_cent.substring(1, 2);

    if (m_amo <= 19) {
        document.getElementById('Text3').value = m_amo;
        BEL_ninten();
        m_say = document.getElementById('Text3').value;
    } else {
        document.getElementById('Text3').value = M_AMO1;
        BEL_TY();
        m_say = document.getElementById('Text3').value;

        document.getElementById('Text3').value = m_amo2;
        BEL_ninten();
        m_say1 = document.getElementById('Text3').value;

    }
    m_cent = m_say + " " + m_say1;
    if (m_cent != " ") {
        document.getElementById('txt_amoinword').value = " And Cents " + m_cent;
    } else {
        document.getElementById('txt_amoinword').value = m_cent;
    }
    //1-99..........................................................................
    m_say = "";
    m_say1 = "";


    m_say = "";
    m_say1 = "";
    m_amo = M_INPUT.substring(M_INPUTLEN - 5, M_INPUTLEN - 3); //Mid(M_INPUT, M_INPUTLEN - 1, 2)

    M_AMO1 = M_INPUT.substring(M_INPUTLEN - 5, M_INPUTLEN - 4) + "0"; //Mid(M_INPUT, M_INPUTLEN - 1, 1) + "0"
    m_amo2 = M_INPUT.substring(M_INPUTLEN - 4, M_INPUTLEN - 3) //Mid(M_INPUT, M_INPUTLEN, 1)

    if (m_amo <= 19) {
        document.getElementById('Text3').value = m_amo;
        BEL_ninten();
        m_say = document.getElementById('Text3').value;
    } else {

        document.getElementById('Text3').value = M_AMO1;
        BEL_TY();
        m_say = document.getElementById('Text3').value;

        document.getElementById('Text3').value = m_amo2;
        BEL_ninten();
        m_say1 = document.getElementById('Text3').value;

    }

    m_bel99 = m_say + " " + m_say1;

    document.getElementById('txt_amoinword').value = m_bel99 + " " + document.getElementById('txt_amoinword').value;

    //99-999..........................................................................


    m_bel999 = "";
    i = 1;
    document.getElementById('Text3').value = M_INPUT.substring(M_INPUTLEN - 6, M_INPUTLEN - 5); //Val(Mid(M_INPUT, M_INPUTLEN - 2, 1))

    //alert(document.getElementById('Text3').value);
    if (document.getElementById('Text3').value > 0) {
        BEL_ninten();
        m_bel999 = document.getElementById('Text3').value;
    }
    if (m_bel999.trim() != "") {
        document.getElementById('txt_amoinword').value = m_bel999 + " Hundred " + document.getElementById('txt_amoinword').value;
    }



    //.....Thousand.............................................................................
    m_say = "";
    m_say1 = "";

    m_amo = "";
    M_AMO1 = "";
    m_amo2 = "";

    if (M_INPUTLEN >= 8) {
        m_amo = M_INPUT.substring(M_INPUTLEN - 8, M_INPUTLEN - 6); // Mid(M_INPUT, M_INPUTLEN - 4, 2)

        M_AMO1 = M_INPUT.substring(M_INPUTLEN - 8, M_INPUTLEN - 7) + "0";  //Mid(M_INPUT, M_INPUTLEN - 4, 1) + "0"
        m_amo2 = M_INPUT.substring(M_INPUTLEN - 7, M_INPUTLEN - 6); // Mid(M_INPUT, M_INPUTLEN - 3, 1)
    } else if (M_INPUTLEN == 7) {
        m_amo = M_INPUT.substring(M_INPUTLEN - 7, M_INPUTLEN - 6); // Mid(M_INPUT, M_INPUTLEN - 4, 2)

        M_AMO1 = "0";
        //M_AMO1 = M_INPUT.substring(M_INPUTLEN - 7, M_INPUTLEN-6)+"0";  //Mid(M_INPUT, M_INPUTLEN - 4, 1) + "0"
        m_amo2 = M_INPUT.substring(M_INPUTLEN - 7, M_INPUTLEN - 6); // Mid(M_INPUT, M_INPUTLEN - 3, 1)

    }

    if (m_amo <= 19) {
        document.getElementById('Text3').value = m_amo;
        BEL_ninten();
        m_say = document.getElementById('Text3').value;


    } else {
        document.getElementById('Text3').value = M_AMO1;
        // alert(M_AMO1);
        BEL_TY();
        m_say = document.getElementById('Text3').value;
        // alert(m_amo2);
        document.getElementById('Text3').value = m_amo2;
        BEL_ninten();
        m_say1 = document.getElementById('Text3').value;
    }
    m_bel1000 = m_say + " " + m_say1;


    if (m_bel1000.trim() != "") {
        document.getElementById('txt_amoinword').value = m_bel1000 + " Thousand " + document.getElementById('txt_amoinword').value;
    }

    //....Lack..............................................................................
    m_say = "";


    m_amo = M_INPUT.substring(M_INPUTLEN - 9, M_INPUTLEN - 8);//  Mid(M_INPUT, M_INPUTLEN - 5, 1)

    if (m_amo <= 9) {

        document.getElementById('Text3').value = m_amo;
        BEL_ninten();
        m_say = document.getElementById('Text3').value;

        m_amoH = M_INPUT.substring(M_INPUTLEN - 8, M_INPUTLEN - 6)
        m_amoH1 = M_INPUT.substring(M_INPUTLEN - 6, M_INPUTLEN - 3)

    }
    m_bel100000 = m_say;

    //alert(m_bel100000);
    debtot = document.getElementById('txt_payments').value;
    if (m_bel100000.trim() != "") {
        if (debtot.length >= 9) {
            var txt_amoinword = document.getElementById('txt_amoinword').value;
            a = txt_amoinword.search('Thousand')
            // alert(document.getElementById('txt_amoinword').value);

            if (debtot >= 100000) {
                if (m_amoH > 0) {
                    document.getElementById('txt_amoinword').value = m_bel100000 + " Hundred " + document.getElementById('txt_amoinword').value;
                } else {
                    if (m_amoH1 > 0) {
                        document.getElementById('txt_amoinword').value = m_bel100000 + " Hundred Thousand And " + document.getElementById('txt_amoinword').value;
                    } else {
                        document.getElementById('txt_amoinword').value = m_bel100000 + " Hundred Thousand " + document.getElementById('txt_amoinword').value;
                    }
                }
            } else {
                document.getElementById('txt_amoinword').value = m_bel100000 + " Hundred  " + document.getElementById('txt_amoinword').value;
            }
            /*if (Number(a)>0){
             document.getElementById('txt_amoinword').value=m_bel100000+" Hundred  "+document.getElementById('txt_amoinword').value;
             } else {
             document.getElementById('txt_amoinword').value=m_bel100000+" Hundred  "+document.getElementById('txt_amoinword').value+" Thousand";  
             }*/
        } else {
            document.getElementById('txt_amoinword').value = m_bel100000 + " Hundred  " + document.getElementById('txt_amoinword').value;
        }
    }

    //.....Million.............................................................................
    m_say = "";
    m_say1 = "";

    m_amo = "";
    M_AMO1 = "";
    m_amo2 = "";


    if (M_INPUTLEN == 11) {
        m_amo = M_INPUT.substring(M_INPUTLEN - 11, M_INPUTLEN - 9); // Mid(M_INPUT, M_INPUTLEN - 4, 2)

        M_AMO1 = M_INPUT.substring(M_INPUTLEN - 11, M_INPUTLEN - 10) + "0";  //Mid(M_INPUT, M_INPUTLEN - 4, 1) + "0"
        m_amo2 = M_INPUT.substring(M_INPUTLEN - 10, M_INPUTLEN - 9); // Mid(M_INPUT, M_INPUTLEN - 3, 1)
    } else if (M_INPUTLEN == 10) {
        m_amo = M_INPUT.substring(M_INPUTLEN - 10, M_INPUTLEN - 9); // Mid(M_INPUT, M_INPUTLEN - 4, 2)

        M_AMO1 = "0";
        //M_AMO1 = M_INPUT.substring(M_INPUTLEN - 7, M_INPUTLEN-6)+"0";  //Mid(M_INPUT, M_INPUTLEN - 4, 1) + "0"
        m_amo2 = M_INPUT.substring(M_INPUTLEN - 10, M_INPUTLEN - 9); // Mid(M_INPUT, M_INPUTLEN - 3, 1)

    }

    if (m_amo <= 19) {
        document.getElementById('Text3').value = m_amo;
        BEL_ninten();
        m_say = document.getElementById('Text3').value;
    } else {
        document.getElementById('Text3').value = M_AMO1;
        BEL_TY();
        m_say = document.getElementById('Text3').value;

        document.getElementById('Text3').value = m_amo2;
        BEL_ninten();
        m_say1 = document.getElementById('Text3').value;
    }

    m_overmil = m_say + " " + m_say1;
    if (m_overmil.trim() != "") {
        document.getElementById('txt_amoinword').value = m_overmil + " Million " + document.getElementById('txt_amoinword').value;
    }

    document.getElementById('txt_amoinword').value = document.getElementById('txt_amoinword').value + "  Only ";

}



function BEL_ninten() {
    m_amo = document.getElementById('Text3').value;
    if (m_amo == 0) {
        M_TXT = "";
    }
    if (m_amo == 1) {
        M_TXT = "One";
    }
    if (m_amo == 2) {
        M_TXT = "Two";
    }
    if (m_amo == 3) {
        M_TXT = "Three";
    }
    if (m_amo == 4) {
        M_TXT = "Four";
    }
    if (m_amo == 5) {
        M_TXT = "Five";
    }
    if (m_amo == 6) {
        M_TXT = "Six";
    }
    if (m_amo == 7) {
        M_TXT = "Seven";
    }
    if (m_amo == 8) {
        M_TXT = "Eight";
    }
    if (m_amo == 9) {
        M_TXT = "Nine";
    }
    if (m_amo == 10) {
        M_TXT = "Ten";
    }
    if (m_amo == 11) {
        M_TXT = "Eleven";
    }
    if (m_amo == 12) {
        M_TXT = "Twelve";
    }
    if (m_amo == 13) {
        M_TXT = "Thirteen";
    }
    if (m_amo == 14) {
        M_TXT = "Fourteen";
    }
    if (m_amo == 15) {
        M_TXT = "Fifteen";
    }
    if (m_amo == 16) {
        M_TXT = "Sixteen";
    }
    if (m_amo == 17) {
        M_TXT = "Seventeen";
    }
    if (m_amo == 18) {
        M_TXT = "Eighteen";
    }
    if (m_amo == 19) {
        M_TXT = "Nineteen";
    }
    document.getElementById('Text3').value = M_TXT;
}

function  BEL_TY() {
    m_amo = document.getElementById('Text3').value;
    if ((m_amo >= 20) && (m_amo < 30)) {
        M_TXT = "Twenty";
    }
    if ((m_amo >= 30) && (m_amo < 40)) {
        M_TXT = "Thirty";
    }
    if ((m_amo >= 40) && (m_amo < 50)) {
        M_TXT = "Forty";
    }
    if ((m_amo >= 50) && (m_amo < 60)) {
        M_TXT = "Fifty";
    }
    if ((m_amo >= 60) && (m_amo < 70)) {
        M_TXT = "Sixty";
    }
    if ((m_amo >= 70) && (m_amo < 80)) {
        M_TXT = "Seventy";
    }
    if ((m_amo >= 80) && (m_amo < 90)) {
        M_TXT = "Eighty";
    }
    if ((m_amo >= 90) && (m_amo < 99)) {
        M_TXT = "Ninety";
    }
    document.getElementById('Text3').value = M_TXT;
}

