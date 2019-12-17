<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Cheque Display</title>
        <script>
            
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


        </script>
        
    </head>


    <body>

        <style type="text/css">
            <!--




        </style>



        <?php
        include './connection_sql.php';
        //php_intl    

        $sql = "Select * from paymas where tmp_no='" . $_GET['tmp_no'] . "'";
        $result = $conn->query($sql);
 
        if (!$row = $result->fetch()) {
            exit();
        }


        if ($row['chq_barer'] != "") {
        $txt_bea = $row['chq_barer'];    
        } else {
        $txt_bea = $row['Barer'];
        }
        

        $sql_rspaymas = "select * from cheque_setup where bank_code='" . $row['CODE'] . "'";

        foreach ($conn->query($sql_rspaymas) as $row_rspaymas) {

            echo "<style type=\"text/css\">

.cl" . $row_rspaymas["id"] . " {
	font-size: " . $row_rspaymas["font_size"] . "px;
	left:" . $row_rspaymas["left_loc"] . "px;
	top:" . $row_rspaymas["top_loc"] . "px;
	font-family:" . $row_rspaymas["font_name"] . ";
	position:absolute;
}
</style>";

            $name = "cl" . $row_rspaymas["id"];

            if ($row_rspaymas["id"] == "1") {
                echo "<div  id=\"" . $row_rspaymas["font_name"] . "\" class=\"" . $name . "\" />" . substr($row["BDATE"], 2, 1) . "</div>";
            }

            if ($row_rspaymas["id"] == "2") {
                echo "<div  id=\"" . $row_rspaymas["font_name"] . "\" class=\"" . $name . "\" />" . substr($row["BDATE"], 3, 1) . "</div>";
            }

            if ($row_rspaymas["id"] == "3") {
                echo "<div  id=\"" . $row_rspaymas["font_name"] . "\" class=\"" . $name . "\" />" . substr($row["BDATE"], 5, 1) . "</div>";
            }

            if ($row_rspaymas["id"] == "4") {
                echo "<div  id=\"" . $row_rspaymas["font_name"] . "\" class=\"" . $name . "\" />" . substr($row["BDATE"], 6, 1) . "</div>";
            }

            if ($row_rspaymas["id"] == "5") {
                echo "<div  id=\"" . $row_rspaymas["font_name"] . "\" class=\"" . $name . "\" />" . substr($row["BDATE"], 8, 1) . "</div>";
            }

            if ($row_rspaymas["id"] == "6") {
                echo "<div  id=\"" . $row_rspaymas["font_name"] . "\" class=\"" . $name . "\" />" . substr($row["BDATE"], 9, 1) . "</div>";
            }

            if ($row_rspaymas["id"] == "7") {


                if ($row["AC_Payee"] == "1") {
                    echo "<div style='border-bottom-style: solid;border-top-style: solid;' id=\"" . $row_rspaymas["font_name"] . "\" class=\"" . $name . "\" />A/C Payee Only</div>";
                }
            }

            if ($row_rspaymas["id"] == "9") {
                echo "<div  id=\"" . $row_rspaymas["font_name"] . "\" class=\"" . $name . "\" />" . $txt_bea . "</div>";
            }
            $f = $_GET['txt_amo'];


            if ($row_rspaymas["id"] == "10") {
                echo "<div  id=\"" . $row_rspaymas["font_name"] . "\" class=\"" . $name . "\" />" . wordwrap($f, 60,"<br>\n<br>\n") . "-</div>";
            }

            if ($row_rspaymas["id"] == "12") {
                echo "<div  id=\"" . $row_rspaymas["font_name"] . "\" class=\"" . $name . "\" />**" . number_format($row["AMOUNT"], 2, ".", ",") . "</div>";
            }
        }
        ?>   
            <input id="Text3" name="Text3" type="hidden">
            <input id="txt_amoinword" name="txt_amoinword" type="hidden">
            
            <input id="txt_payments" name="txt_payments" value="<?php echo $row["AMOUNT"]; ?>" type="hidden">
            
            <script>
                amoword_cal();
                <?php
    header("refresh: 3;");
?>
            </script>
            
    </body>
</html>