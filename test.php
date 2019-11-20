<?php

 header("Content-type: text/plain");
 $filenm = "ssss.csv";
   header("Content-Disposition: attachment; filename=$filemn");

   // do your Db stuff here to get the content into $content
   print "Serial No,TIN No,Invoice Date,Invoice No,Tax Credit / Tax Debit Note,Date of Tax Credit / Tax Debit Note,Tax Credit No. / Tax Debit Note No.,Value of Tax Credit Note / Tax Debit Note,VAT Amount\n";
   

