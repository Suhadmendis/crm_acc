<?php
include './CheckCookie.php';
$cookie_name = "user";
if (isset($_COOKIE[$cookie_name])) {
    $mo = chk_cookie($_COOKIE[$cookie_name]);
    if ($mo != "ok") {
        header('Location: ' . "index.php");
        exit();
    }
} else {
    header('Location: ' . "index.php");
    exit();
}
$mtype = "";
include "header.php";

if (isset($_GET['url'])) {
    if ($_GET['url'] == "Airport") {
        include_once './Airport.php';
    }
    if ($_GET['url'] == "po") {
        include_once './po.php';
    }
    if ($_GET['url'] == "cusmas") {
        include_once './cusmas.php';
    }
    if ($_GET['url'] == "Carrier") {
        include_once './Carrier.php';
    }
    if ($_GET['url'] == "Container") {
        include_once './Container.php';
    }
    if ($_GET['url'] == "LoadUnload") {
        include_once './LoadUnload.php';
    }
    if ($_GET['url'] == "Package_type") {
        include_once './Package_type.php';
    }
    if ($_GET['url'] == "Charge_code") {
        include_once './Charge_code.php';
    }
    if ($_GET['url'] == "item-master") {
        include_once './item-master.php';
    }
    if ($_GET['url'] == "item-master1") {
        include_once './item-master_1.php';
    }
    if ($_GET['url'] == "Currency") {
        include_once './Currency.php';
    }
    if ($_GET['url'] == "Vessel") {
        include_once './Vessel.php';
    }
    if ($_GET['url'] == "Sales_rep") {
        include_once './Sales_rep.php';
    }
    if ($_GET['url'] == "helpdesk") {
        include_once './helpdesk.php';
    }
    if ($_GET['url'] == "Debit_note") {
        include_once './Debit_note.php';
        $mtype="A";
    }

    if ($_GET['url'] == "Credit_note") {
        include_once './Credit_note.php';
        $mtype="A";
    }
    if ($_GET['url'] == "Payments") {
        include_once './Payments.php';
        $mtype="A";
    }
    if ($_GET['url'] == "Consol_Costing") {
        include_once './Consol_Costing.php';
    }
    if ($_GET['url'] == "Receipt_entry") {
        include_once './Direct_receipt.php';
        $mtype="A";
    }
    if ($_GET['url'] == "Return_Receipt") {
        include_once './Return_Receipt.php';
        $mtype="A";
    }
    if ($_GET['url'] == "Receipt") {
        include_once './Receipt.php';
        $mtype="A";
    }

    if ($_GET['url'] == "lcode") {
        include_once './Account_master.php';
    }

    if ($_GET['url'] == "petty_iou") {
        include_once './pettycash_iou.php';
    }


    if ($_GET['url'] == "jou") {
        include_once './Journal_Entry.php';
        $mtype="A";
    }


    if ($_GET['url'] == "journal") {
        include_once './Journal_En.php';
        $mtype="A";
    }

    if ($_GET['url'] == "jou1") {
        include_once './petty_cash_issue.php';
        $mtype="A";
    }

    if ($_GET['url'] == "jou2") {
        include_once './petty_cash_posting.php';
        $mtype="A";
    }

     if ($_GET['url'] == "balance") {
        include_once './petty_cash_balance.php';
        $mtype="A";
    }

    if ($_GET['url'] == "tb") {
        include_once './trailbal.php';
        
    }
    if ($_GET['url'] == "out") {
        include_once './rep_outstanding.php';
    }
    if ($_GET['url'] == "bank_rec") {
        include_once './bank_rec.php';
    }
    if ($_GET['url'] == "inv") {
        include_once './invoice.php';
    }
    if ($_GET['url'] == "cus") {
        include_once './cusmas.php';
    }

    if ($_GET['url'] == "issu") {
        include_once './issue_note.php';
    }
    if ($_GET['url'] == "user") {
        include_once './new_user.php';
    }
    if ($_GET['url'] == "user_p") {
        include_once './user_permission.php';
    }
    if ($_GET['url'] == "pnlbs") {
        include_once './rep_pnlbs.php';
    }
    if ($_GET['url'] == "pr") {
        include_once './pr.php';
    }
    if ($_GET['url'] == "bin") {
        include_once './bin.php';
    }

  if ($_GET['url'] == "chq_setup") {
        include_once './cheque_setup.php';
    }
    
    if ($_GET['url'] == "contra") {
        include_once './contra.php';
    }
    
     if ($_GET['url'] == "tb1") {
        include_once './rep_glreport.php';
    }

     if ($_GET['url'] == "vou") {
        include_once './pay_voucher.php';
        $mtype="A";
    }
    
} else {

    include_once './fpage.php';
}

include_once './footer.php';
?>

</body>
</html>


<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('.dt').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });
</script>
<?php
if ($mtype=="A") {
    include './autocomple_gl.php';
}
?>
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="js/comman.js"></script>


<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>   <!-- minified -->
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
 
     <!-- minified -->
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<script src="js/user.js"></script>




<script>
    $("body").addClass("sidebar-collapse");
</script>    



