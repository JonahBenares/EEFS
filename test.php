<?php
date_default_timezone_set("Asia/Hong_Kong");
$directory= "http://localhost/EEFS/test/";

$smallest_time=INF;

$oldest_file='';

if ($handle = opendir($directory)) {

    while (false !== ($file = readdir($handle))) {

        $time=filemtime($directory.'/'.$file);

        if (is_file($directory.'/'.$file)) {

            if ($time < $smallest_time) {
                $oldest_file = $file;
                $smallest_time = $time;
            }
        }
    }
    closedir($handle);
}

echo $oldest_file;
?>
