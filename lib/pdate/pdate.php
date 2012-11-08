<?php
// Copyright (C) 2009  Vahid sohrabloo (iranphp.org) 
// 
// This program is free software; you can redistribute it and/or 
// modify it under the terms of the GNU General Public License 
// as published by the Free Software Foundation; either version 2 
// of the License, or (at your option) any later version. 
// 
// This program is distributed in the hope that it will be useful, 
// but WITHOUT ANY WARRANTY; without even the implied warranty of 
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
// GNU General Public License for more details. 
// 
// A copy of the GNU General Public License is available from: 
// 
//    <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">http://www.gnu.org/copyleft/gpl.html</a> 
// 


// Version 1.1

$pdateWeekName=array(
						"&#1588;&#1606;&#1576;&#1607;",
						"&#1740;&#1705;&#1588;&#1606;&#1576;&#1607;",
						"&#1583;&#1608;&#1588;&#1606;&#1576;&#1607;",
						"&#1587;&#1607; &#1588;&#1606;&#1576;&#1607;",
						"&#1670;&#1607;&#1575;&#1585;&#1588;&#1606;&#1576;&#1607;",
						"&#1662;&#1606;&#1580; &#1588;&#1606;&#1576;&#1607;",
						"&#1580;&#1605;&#1593;&#1607;");
$pdateMonthName=array(
						"",
						"&#1601;&#1585;&#1608;&#1585;&#1583;&#1740;&#1606;",
						"&#1575;&#1585;&#1583;&#1740;&#1576;&#1607;&#1588;&#1578;",
						"&#1582;&#1585;&#1583;&#1575;&#1583;",
						"&#1578;&#1740;&#1585;",
						"&#1605;&#1585;&#1583;&#1575;&#1583;",
						"&#1588;&#1607;&#1585;&#1740;&#1608;&#1585;",
						"&#1605;&#1607;&#1585;",
						"&#1570;&#1576;&#1575;&#1606;",
						"&#1570;&#1584;&#1585;",
						"&#1583;&#1740;",
						"&#1576;&#1607;&#1605;&#1606;",
						"&#1575;&#1587;&#1601;&#1606;&#1583;");
$MonthDays=array(0,31,31,31,31,31,31,30,30,30,30,30,29);

function pdate($format,$timestamp=""){
			global $pdateMonthName,$pdateWeekName,$MonthDays;
			if($timestamp===""){
				$timestamp=time();
			}
			
			// Create need date parametrs
			$date=date("Y-m-d-w",$timestamp);
			list( $gYear, $gMonth, $gDay ,$gWeek) = explode ('-',$date);
			list( $pYear, $pMonth, $pDay ) = gregorian_to_jalali($gYear, $gMonth, $gDay);
			$pWeek=$gWeek+1;
			if($pWeek==7)$pWeek=0;
			
			$lenghFormat=strlen($format);
			$i=0;
			$result="";
			while($i<$lenghFormat){
				$par=$format{$i};
				if($par=='\\'){
					$result.=$format{++$i};
					$i++;
					continue;
				}
				switch($par){
					//Day
					case 'd':
						$result.=($pDay<10)?"0".$pDay:$pDay;
					break;
					
					case 'D':
						$result.=substr($pdateWeekName[$pWeek],0,2);
					break;
					
					case 'j':
						$result.=$pDay;
					break;
					
					case 'l':
						$result.=$pdateWeekName[$pWeek];
					break;
					
					case 'N':
						$result.=$pWeek+1;
					break;
					
					case 'w':
						$result.=$pWeek;
					break;
					
					case 'z':
						$result.=DayOfYear($pYear,$pMonth,$pDay);
					break;
					
					//Week
					case 'W':
						$result.=ceil(DayOfYear($pYear,$pMonth,$pDay)/7);
					break;
					
					//Month
					case 'F':
						$result.=$pdateMonthName[$pMonth];
					break;
					
					case 'm':
						$result.=($pMonth<10)?"0".$pMonth:$pMonth;
					break;
					
					case 'M':
						$result.=substr($pdateMonthName[$pMonth],0,6);
					break;
					
					case 'n':
						$result.=$pMonth;
					break;
					
					case 't':
						$result.=(isKabise($pYear) and $pMonth==12)?30:$MonthDays[$pMonth];
					break;
					
					//Years
					case 'L':
						$result.=(int)isKabise($pYear);
					break;
					
					case 'Y':
					case 'o':
						$result.=$pYear;
					break;
					
					case 'y':
						$result.=substr($pYear,2);
					break;
					
					//Time
					case 'a':
					case 'A':
						if(date('a',$timestamp)=='am'){
							$result.=($par=='a')?'&#1602;.&#1592;':'&#1602;&#1576;&#1604; &#1575;&#1586; &#1592;&#1607;&#1585;';
						}
						else{
							$result.=($par=='a')?'&#1576;.&#1592;':'&#1576;&#1593;&#1583; &#1575;&#1586; &#1592;&#1607;&#1585;';
						}
					break;
					
					case 'B':
					case 'g':
					case 'G':
					case 'h':
					case 'H':
					case 's':
					case 'u':
					case 'i':

					//Timezone
					case 'e':
					case 'I':
					case 'O':
					case 'P':
					case 'T':
					case 'Z':
							$result.=date($par,$timestamp);
					break;
					
					//Full Date/Time
					
					case 'c':
							$result.=$pYear."-".$pMonth."-".$pDay."T".date("H::i:sP",$timestamp);
					break;
					
					case 'r':
							$result.=substr($pdateWeekName[$pWeek],0,2)."&#1548; ".$pDay." ".substr($pdateMonthName[$pMonth],0,6)." ".$pYear." ".date("H::i:s P",$timestamp);
					break;
					case 'U':
							$result.=$timestamp;
					break;
					default:
					$result.=$par;
					
					
					
				}
				$i++;
			}
			return $result;
			
			
}


function pstrftime($format,$timestamp=""){
			global $pdateMonthName,$pdateWeekName,$MonthDays;
			if($timestamp===""){
				$timestamp=time();
			}
			// Create need date parametrs
			$date=date("Y-m-d-w",$timestamp);
			list( $gYear, $gMonth, $gDay ,$gWeek) = explode ('-',$date);
			list( $pYear, $pMonth, $pDay ) = gregorian_to_jalali($gYear, $gMonth, $gDay);
			$pWeek=$gWeek+1;
			if($pWeek==7)$pWeek=0;
			
			$lenghFormat=strlen($format);
			$i=0;
			$result="";
			while($i<$lenghFormat){
					$par=$format{$i};
					if($par=="%"){
						$type=$format{++$i};
						switch($type){
							//Day
							case 'a':
								$result.=substr($pdateWeekName[$pWeek],0,2)	;
							break;
							
							case 'A':
								$result.=$pdateWeekName[$pWeek];
							break;
							
							case 'd':
								$result.=($pDay<10)?"0".$pDay:$pDay;
							break;
							
							case 'e':
								$result.=$pDay;
							break;
							
							case 'j':
								$dayinM=DayOfYear($pYear,$pMonth,$pDay);
								$result.=($dayinM<10)?"00".$dayinM:(($dayinM<100)?"0".$dayinM:$dayinM);
							break;
							
							case 'u':
								$result.=$pWeek+1;
							break;
							
							case 'w':
								$result.=$pWeek;
							break;
							
							//Week
							case 'U':
								$result.=floor(DayOfYear($pYear,$pMonth,$pDay)/7);
							break;
							
							case 'V':
							case 'W':
								$result.=ceil(DayOfYear($pYear,$pMonth,$pDay)/7);
							break;
							
							//Month
							case 'b':
							case 'h':
								$result.=substr($pdateMonthName[$pMonth],0,6);
							break;
							
							case 'B':
								$result.=$pdateMonthName[$pMonth];
							break;
							
							case 'm':
								$result.=($pMonth<10)?"0".$pMonth:$pMonth;
							break;
							
							//Year
							case 'C':
								$result.=ceil($pYear/100);
							break;
							
							case 'g':
							case 'y':
								$result.=substr($pYear,2);
							break;
							
							case 'G':
							case 'Y':
								$result.=$pYear;
							break;
							
							//Time
							case 'H':
							case 'I':
							case 'l':
							case 'M':
							case 'R':
							case 'S':
							case 'T':
							case 'X':
							case 'z':
							case 'Z':
								$result.=strftime("%".$type,$timestamp);
							break;
							case 'p':
							case 'P':
							case 'r':
								if(date('a',$timestamp)=='am'){
									$result.=($type=='p')?'&#1602;.&#1592;':(($type=='P')?'&#1602;&#1576;&#1604; &#1575;&#1586; &#1592;&#1607;&#1585;':strftime("%I:%M:%S &#1602;&#1576;&#1604; &#1575;&#1586; &#1592;&#1607;&#1585;",$timestamp));
								}
								else{
									$result.=($type=='p')?'&#1576;.&#1592;':(($type=='P')?'&#1576;&#1593;&#1583; &#1575;&#1586; &#1592;&#1607;&#1585;':strftime("%I:%M:%S &#1576;&#1593;&#1583; &#1575;&#1586; &#1592;&#1607;&#1585;",$timestamp));
								}
							break;
							
							//Time and Date Stamps
							case 'c':
								$result.=substr($pdateWeekName[$pWeek],0,2)." ".substr($pdateMonthName[$pMonth],0,6)." ".$pDay." ".strftime("%T",$timestamp)." ".$pYear;
							break;
							
							case 'D':
							case 'x':
								$result.=(($pMonth<10)?"0".$pMonth:$pMonth)."/".(($pDay<10)?"0".$pDay:$pDay)."/".substr($pYear,2);
							break;
							
							case 'F':
								$result.=$pYear."-".(($pMonth<10)?"0".$pMonth:$pMonth)."-".(($pDay<10)?"0".$pDay:$pDay);
							break;
							
							case 's':
								$result.=$timestamp;
							break;
							
							//Miscellaneous
							case 'n':
								$result.="\n";
							break;
							
							case 't':
								$result.="\t";
							break;
							
							case '%':
								$result.="%";
							break;
							
							default: $result.="%".$type;
							
							
						}
					}
					else{
						$result.=$par;
					}
					$i++;
			}
			return $result;
}
function DayOfYear($pYear,$pMonth,$pDay){
			global $MonthDays;
			$days=0;
			for($i=1;$i<$pMonth;$i++){
				$days+=$MonthDays[$i];
			}
			return $days+$pDay;
}

function isKabise($year)
{
	$mod=$year%33;
	if($mod==1 or $mod==5 or $mod==9 or $mod==13 or $mod==17 or $mod==22 or $mod==26 or $mod==30)return true;
	return false;
}

function pmktime($hour=0,$minute=0,$second=0,$month=0,$day=0,$year=0,$is_dst=-1){
	
	if($hour==0 && $minute==0 && $second==0 && $month==0 && $day==0 && $year==0) return time();
	
	list($year,$month,$day)=jalali_to_gregorian($year,$month,$day);
	
	return mktime($hour,$minute,$second,$month,$day,$year,$is_dst);
}

function pcheckdate($month, $day, $year){
	global $MonthDays;
	if($month<1 || $month>12 || $year<1  || $year>32767 || $day<1){
		return false;
	}
	if($day>$MonthDays[$month]){
		if($month!=12 && $day!=12 && !isKabise($year)){
			return false;
		}
	}
	return true;
}


function pgetdate($timestamp="")
{
	if($timestamp==="")
		$timestamp=mktime();
	list($seconds,$minutes,$hours,$mday,$wday,$mon,$year,$yday,$weekday,$month)=explode("-",pdate("s-i-G-j-w-n-Y-z-l-F",$timestamp));
	return array(
		0=>$timestamp,	
		"seconds"=>$seconds,
		"minutes"=>$minutes,
		"hours"=>$hours,
		"mday"=>$mday,
		"wday"=>$wday,
		"mon"=>$mon,
		"year"=>$year,
		"yday"=>$yday,
		"weekday"=>$weekday,		
		"month"=>$month,
	);
}


function next_day($fyear, $fmonth, $fday) 
{ 
  return date ("Y-m-d", mktime (0,0,0,$fmonth,$fday+1,$fyear)); 
} 


// Copyright (C) 2000  Roozbeh Pournader and Mohammad Toossi 
// 
// This program is free software; you can redistribute it and/or 
// modify it under the terms of the GNU General Public License 
// as published by the Free Software Foundation; either version 2 
// of the License, or (at your option) any later version. 
// 
// This program is distributed in the hope that it will be useful, 
// but WITHOUT ANY WARRANTY; without even the implied warranty of 
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
// GNU General Public License for more details. 
// 
// A copy of the GNU General Public License is available from: 
// 
//    <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">http://www.gnu.org/copyleft/gpl.html</a> 
// 


function div($a,$b) {
    return (int) ($a / $b);
}

function gregorian_to_jalali ($g_y, $g_m, $g_d) 
{
    $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); 
    $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);     
    


   

   $gy = $g_y-1600; 
   $gm = $g_m-1; 
   $gd = $g_d-1; 

   $g_day_no = 365*$gy+div($gy+3,4)-div($gy+99,100)+div($gy+399,400); 

   for ($i=0; $i < $gm; ++$i) 
      $g_day_no += $g_days_in_month[$i]; 
   if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0))) 
      /* leap and after Feb */ 
      $g_day_no++; 
   $g_day_no += $gd; 

   $j_day_no = $g_day_no-79; 

   $j_np = div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */ 
   $j_day_no = $j_day_no % 12053; 

   $jy = 979+33*$j_np+4*div($j_day_no,1461); /* 1461 = 365*4 + 4/4 */ 

   $j_day_no %= 1461; 

   if ($j_day_no >= 366) { 
      $jy += div($j_day_no-1, 365); 
      $j_day_no = ($j_day_no-1)%365; 
   } 

   for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i) 
      $j_day_no -= $j_days_in_month[$i]; 
   //$jm = sprintf("%02s",$i+1) ;    
   //$jd = sprintf("%02s",$j_day_no+1); 
   
   $jm = $i+1 ;    
   $jd = $j_day_no+1; 

   return array($jy, $jm, $jd); 
} 

function jalali_to_gregorian($j_y, $j_m, $j_d) 
{ 
    $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); 
    $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
    
   

   $jy = $j_y-979; 
   $jm = $j_m-1; 
   $jd = $j_d-1; 

   $j_day_no = 365*$jy + div($jy, 33)*8 + div($jy%33+3, 4); 
   for ($i=0; $i < $jm; ++$i) 
      $j_day_no += $j_days_in_month[$i]; 

   $j_day_no += $jd; 

   $g_day_no = $j_day_no+79; 

   $gy = 1600 + 400*div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */ 
   $g_day_no = $g_day_no % 146097; 

   $leap = true; 
   if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */ 
   { 
      $g_day_no--; 
      $gy += 100*div($g_day_no,  36524); /* 36524 = 365*100 + 100/4 - 100/100 */ 
      $g_day_no = $g_day_no % 36524; 

      if ($g_day_no >= 365) 
         $g_day_no++; 
      else 
         $leap = false; 
   } 

   $gy += 4*div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */ 
   $g_day_no %= 1461; 

   if ($g_day_no >= 366) { 
      $leap = false; 

      $g_day_no--; 
      $gy += div($g_day_no, 365); 
      $g_day_no = $g_day_no % 365; 
   } 

   for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++) 
      $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap); 
   //$gm = sprintf("%02s",$i+1); 
   //$gd = sprintf("%02s",$g_day_no+1); 
   
	$gm = $i+1;
	$gd = $g_day_no+1;
   return array($gy, $gm, $gd); 
}