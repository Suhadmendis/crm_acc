<?php
//192.168.5
if ((substr($_SERVER['REMOTE_ADDR'], 1, 9)) == "192.168.5") {
    $ip = "192.168.5.153:8090/mspace/";
} else {
    $ip = "124.43.17.130:8090/mspace/";
}

function get_title($url) {


    if ($url == "Airport") {
        return 'Airport';
        exit();
    }

    if ($url == "Debit_note") {
        return 'Debit_note';
        $mtype = "A";
    }
    if ($url == "Credit_note") {
        return 'Credit_note';
        $mtype = "A";
    }
    if ($url == "Payments") {
        return 'Payments';
        $mtype = "A";
    }
    if ($url == "costing") {
        return 'Costing';
        exit();
    }
    if ($url == "inv_opr") {
        return 'Invoice';
        exit();
    }
    if ($url == "Receipt_entry") {
        return 'Direct_receipt';
        $mtype = "A";
    }
    if ($url == "Return_Receipt") {
        return 'Return_Receipt';
        $mtype = "A";
    }
    if ($url == "Receipt") {
        return 'Receipt';
        $mtype = "A";
    }

    if ($url == "lcode") {
        return 'Account_master';
    }
    if ($url == "jou") {
        return 'Journal_Entry';
        $mtype = "A";
    }
    if ($url == "tb") {
        return 'trailbal';
    }
    if ($url == "out") {
        return 'rep_outstanding';
    }
    if ($url == "bank_rec") {
        return 'bank_rec';
    }
    if ($url == "inv") {
        return 'invoice';
    }
    if ($url == "cus") {
        return 'cusmas';
    }

    if ($url == "issu") {
        return 'issue_note';
    }
    if ($url == "user") {
        return 'new_user';
    }
    if ($url == "user_p") {
        return 'user_permission';
    }
    if ($url == "pnlbs") {
        return 'rep_pnlbs';
    }
    if ($url == "pr") {
        return 'pr';
    }
    if ($url == "bin") {
        return 'bin';
    }

    if ($url == "chq_setup") {
        return 'cheque_setup';
    }

    if ($url == "contra") {
        return 'contra';
    }

    if ($url == "rcs") {
        return 'RCS - Air';
        exit(); 
    }
    if ($url == "rcs_search") {
        return 'RCS Search';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>

    <title><?php echo get_title($_GET['url']); ?></title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="dist/css/font-awesome-4.7.0/css/font-awesome.min.css">  

    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="css/ionicons/css/ionicons.min.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="plugins/morris/morris.css">

    <!-- Date Picker -->
    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">

    <style>
        .form-group {
            margin-bottom: 8px;
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="home.php" class="logo"> <!-- mini logo for sidebar mini 50x50 pixels --> <span class="logo-mini"><b>C</b>CS</span> <!-- logo for regular state and mobile devices --> <span class="logo-lg"><b>CRIMSON</b>CS</span> </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <span class="sr-only">Toggle navigation</span> </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->

                        <!-- Notifications: style can be found in dropdown.less -->

                        <!-- Tasks: style can be found in dropdown.less -->

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                                <span class="hidden-xs"><?php echo $_SESSION['UserName']; ?>-2018</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                                    <p>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="well-sm"></div>    
                                    <!-- /.row -->
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
<?php
if ((substr($_SERVER['REMOTE_ADDR'], 1, 9)) == "192.168.5") {
    echo "<a target='_blank' href=\"http:\\\\192.168.5.153:8090\mspace\" class='btn btn-primary btn-file'>Myspace</a>";
} else {
    echo "<a target='_blank' href=\"http:\\\\124.43.17.130:8090\mspace\" class='btn btn-primary btn-file'>Myspace</a>";
}
?>

                                    </div>
                                    <div class="pull-right">
                                        <a onclick="logout();" class="btn btn-success btn-file">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                        <li>
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <!--<img src="" class="img-circle" alt="User Image">-->
                    </div>
                    <div class="pull-left info">
                        <p>
<?php
echo $_SESSION['UserName']
?>
                        </p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <!-- search form -->
                <form action="#" method="get" class="sidebar-form">
                    <div class="input-group">
<!--                            <input type="text" name="q" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
                            <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                                <i class="fa fa-search"></i>
                            </button> </span>-->
                    </div>
                </form>
                <!-- /.search form -->
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li class="header">
                        MAIN MENU
                    </li>
<?php
if (isset($_GET['url'])) {
    $murl = $_GET['url'];
} else {
    $murl = "";
}

$mgroup = "";
include './connection_sql.php';
$sql = "select * from view_menu where username ='" . $_SESSION["CURRENT_USER"] . "' and doc_view='1' order by grp,docid";
// echo $sql;
foreach ($conn->query($sql) as $row1) {
    if ($mgroup != $row1['grp']) {
        if ($mgroup != "") {
            echo "</ul>";
            echo "</li>";
        }
        echo "<li class='treeview'>
                                <a href='#'><i class='" . trim($row1['icon_class']) . "'></i> <span>" . $row1['grp'] . "</span> <i class='fa fa-angle-left pull-right'></i> </a>
                                <ul class='treeview-menu'>";
    }

    $mgroup = $row1['grp'];
    if ($murl == $row1['name']) {
        echo "<li class='active'>";
    } else {
        echo "<li>";
    }
    echo "<a href='home.php?url=" . trim($row1['name']) . "'><i class='fa fa-circle-o'></i>" . trim($row1['docname']) . "</a></li>";
}
echo "</ul>";
echo "</li>";
?>



            </section>
            <!-- /.sidebar -->
        </aside>
        <!-- Control Sidebar -->
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">

            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane" id="control-sidebar-home-tab">

                    <!-- /.control-sidebar-menu -->


                    <!-- /.control-sidebar-menu -->

                </div>
                <!-- /.tab-pane -->
                <!-- Stats tab content -->
                <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                <!-- /.tab-pane -->
                <!-- Settings tab content -->
                <div class="tab-pane" id="control-sidebar-settings-tab">

                </div>
                <!-- /.tab-pane -->
            </div>
        </aside>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">