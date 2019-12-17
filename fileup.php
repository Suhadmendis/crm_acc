<?php

session_start();
require_once ("connectioni.php");

//print_r($_FILES['file']);

$target_dir = "uploads/";

$target_dir = "uploads/";


$mrefno = str_replace("/", "-", $_POST['refno']);

if ($_POST['type'] == "led") {
    $target_dir = $target_dir . "ACCOUNTS" . "/";
    if (!file_exists($target_dir)) {
        if (mkdir($target_dir, 0777, true)) {
            
        }
    }
    $mloc1 = "ACCOUNTS";
    $target_dir = $target_dir . "LEDGERS" . "/";
    if (!file_exists($target_dir)) {
        if (mkdir($target_dir, 0777, true)) {
            
        }
    }
    $mloc2 =""; 
    $mloc3 = $mrefno;
}

if ($_POST['type'] == "crn") {
    $target_dir = $target_dir . "ACCOUNTS" . "/";
    if (!file_exists($target_dir)) {
        if (mkdir($target_dir, 0777, true)) {
            
        }
    }
    $mloc1 = "ACCOUNTS";
    $target_dir = $target_dir . "CREDIT NOTE" . "/";
    if (!file_exists($target_dir)) {
        if (mkdir($target_dir, 0777, true)) {
            
        }
    }
    $mloc2 = "CREDIT NOTE";
    $target_dir = $target_dir . $mrefno . "/";
    if (!file_exists($target_dir)) {
        if (mkdir($target_dir, 0777, true)) {
            
        }
    }
    $mloc3 = $mrefno;
}

if ($_POST['type'] == "arn") {
    $target_dir = $target_dir . "ACCOUNTS" . "/";
    if (!file_exists($target_dir)) {
        if (mkdir($target_dir, 0777, true)) {
            
        }
    }
    $mloc1 = "ACCOUNTS";
    $target_dir = $target_dir . "ARRIVAL NOTE" . "/";
    if (!file_exists($target_dir)) {
        if (mkdir($target_dir, 0777, true)) {
            
        }
    }
    $mloc2 = "ARRIVAL NOTE";
    $target_dir = $target_dir . $mrefno . "/";
    if (!file_exists($target_dir)) {
        if (mkdir($target_dir, 0777, true)) {
            
        }
    }
    $mloc3 = $mrefno;
}

$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

$mok = "no";
//while ($mok == "ok") {
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $mok = "ok";
} else {
    $mok = "ok";
}
//} 
if ($mok == "ok") {

    if (file_exists($_FILES["file"]["tmp_name"])) {
        $lastmod = date("F d Y g:i:s A", filemtime($_FILES["file"]["tmp_name"]));
    }

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "The file " . basename($_FILES["file"]["name"]) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    
    $sql = "insert into docs (loc,file_name,user_nm,folder,las_modifi,loc1,loc2,loc3,refno) values ('" . $target_file . "','" . basename($_FILES["file"]["name"]) . "','','','" . $lastmod . "','" . $mloc1 . "','" . $mloc2 . "','" . $mloc3 . "','" . $_POST['refno'] . "')";
    $result = mysqli_query($GLOBALS['dbinv'], $sql);
}