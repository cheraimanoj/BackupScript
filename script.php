<?php
$dir_paths = "/root/testfolder/*.tar";

$files = array();
foreach (glob($dir_paths) as $file) {
    $matches = array();
    preg_match('/(\d{4}-\d{2}-\d{2})/', $file, $matches);
    $files[] = $file;
}
removeFiles($files);
/*** Remove files older than 395days ,and keeps only each months last monday ,current month all mondays and last 7days files ***/
function removeFiles($files){
    $last_7days = array();
    for ($i=0; $i<7; $i++)
    {
        $days = date("Y-m-d",strtotime($i." days ago"));
        $last_7days[$days] = $days;
    }

    foreach($files as $k=>$v){
        $matches = array();
        preg_match('/(\d{4}-\d{2}-\d{2})/', $v, $matches);
        if(array_key_exists($matches[0],$last_7days)){
            unset($files[$k]);
        }else{
            $cur_month = date('m');
            $file_month = date('m',strtotime($matches[0]));

            $date_stamp = strtotime($matches[0]);
            $stamp = date('l', $date_stamp);

            if((strtotime($matches[0]) < strtotime(date('Y-m-d', strtotime("-395 days"))))){
                continue;//unset($files[$k]);
            }else if(($stamp == 'Monday') && ($cur_month==$file_month)){
                unset($files[$k]);
            }else if( (date('Y-m-d', strtotime(' last Monday of '.date('F',$date_stamp)." ".date('Y',$date_stamp)))) == $matches[0]){
                unset($files[$k]);
            }
        }
    }

    foreach($files as $f){
        unlink($f);
    }
}

?>