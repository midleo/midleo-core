<?php
class CallFunct{
  public static function isWeekend($date) { 
    return (date('N', strtotime($date)) >= 6); 
  }
  public static function secondsToTime($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a days and %h hours');
  }
  public static function returnBetweenDates( $startDate, $endDate, $starttime, $endtime ){
    $startDate = strtotime(  $startDate );
    $endDate   = strtotime(  $endDate );

    if( strtotime(date('Y-m-d',$endDate)) > strtotime(date('Y-m-d',$startDate)) ){
        while( strtotime(date('Y-m-d',$endDate)) >= strtotime(date('Y-m-d',$startDate)) ){ 
            if(strtotime(date('Y-m-d',$endDate)) == strtotime(date('Y-m-d',$startDate))){ 
				$dateArr[date( 'Y-m-d', $startDate )][] = CallFunct::get_working_hours(date('Y-m-d H:i:s',$startDate),date('Y-m-d H:i:s',$endDate), $starttime, $endtime);
              $startDate = strtotime( ' +1 day ', $startDate );
            } else {
				$dateArr[date( 'Y-m-d', $startDate )][] = CallFunct::get_working_hours(date('Y-m-d H:i:s',$startDate),date('Y-m-d',$startDate)." ".date("H:i:s",strtotime($endtime)), $starttime, $endtime);
                $startDate= strtotime(date('Y-m-d',$startDate)." ".date("H:i:s",strtotime($starttime)));
				$startDate = strtotime( ' +1 day ', $startDate );
            }
        }
    }else{
		$dateArr[date( 'Y-m-d', $startDate )][] = CallFunct::get_working_hours(date('Y-m-d H:i:s',$startDate),date('Y-m-d H:i:s',$endDate),$starttime,$endtime);
    }
    return $dateArr;  
}
  public static function get_working_hours($ini_str,$end_str,$starttime, $endtime ){
    $ini_time = [date("G",strtotime($starttime)),date("i",strtotime($starttime))]; //hr, min
    $end_time = [date("G",strtotime($endtime)),date("i",strtotime($endtime))]; //hr, min
    $ini = date_create($ini_str);
    $ini_wk = date_time_set(date_create($ini_str),$ini_time[0],$ini_time[1]);
    $end = date_create($end_str);
    $end_wk = date_time_set(date_create($end_str),$end_time[0],$end_time[1]);
    $workdays_arr = CallFunct::get_workdays($ini,$end);
    $workdays_count = count($workdays_arr);
    $workday_seconds = (($end_time[0] * 60 + $end_time[1]) - ($ini_time[0] * 60 + $ini_time[1])) * 60;
    $ini_seconds = 0;
    $end_seconds = 0;
    if(in_array($ini->format('Y-m-d'),$workdays_arr)) $ini_seconds = $ini->format('U') - $ini_wk->format('U');
    if(in_array($end->format('Y-m-d'),$workdays_arr)) $end_seconds = $end_wk->format('U') - $end->format('U');
    $seconds_dif = $ini_seconds > 0 ? $ini_seconds : 0;
    if($end_seconds > 0) $seconds_dif += $end_seconds;
    $working_seconds = ($workdays_count * $workday_seconds) - $seconds_dif;
	return $working_seconds / 3600; //return hrs
}
  public static function get_workdays($ini,$end){
    $skipdays = [6,0]; //saturday:6; sunday:0
    $skipdates = []; //eg: ['2016-10-10'];
    $current = clone $ini;
    $current_disp = $current->format('Y-m-d');
    $end_disp = $end->format('Y-m-d');
    $days_arr = [];
    while($current_disp <= $end_disp){
        if(!in_array($current->format('w'),$skipdays) && !in_array($current_disp,$skipdates)){
            $days_arr[] = $current_disp;
        }
        $current->add(new DateInterval('P1D')); //adds one day
        $current_disp = $current->format('Y-m-d');
    }
    return $days_arr;
}
  
}
