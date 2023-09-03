<?php

namespace App\Helpers;

class DateHelper
{
    public static function differentMinute($firstDate, $secondDate)
    {
        $firstDate = strtotime($firstDate); 
        $secondDate = strtotime($secondDate); 

        $menit = ($secondDate - $firstDate) / 60;

        if($menit <= 0){
            $menit = 0;
        }

        return $menit;
    }

    public static function differentDay($firstDate,$secondDate){
        $hari = (strtotime(date("Y-m-d",strtotime($secondDate))) - strtotime(date("Y-m-d",strtotime($firstDate)))) / 86400;

        if($hari <= 0){
            $hari = 1;
        }
        else{
            $hari += 1;
        }

        return $hari;
    }

    public static function date1to28(){
        $data = [];
        for($i = 1;$i<=28;$i++){
            $data[] = $i;
        }

        return $data;
    }
}
