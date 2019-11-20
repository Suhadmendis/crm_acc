<?php

 

function ac_year($date) {
    
    
     $month1 = date("m", strtotime($date));
     $month1_y = date("Y", strtotime($date));
    
     if ($month1<=3) {
         $ac_year = $month1_y -1;
     } else {
         $ac_year = $month1_y;
     }
     
     return $ac_year;
         
}