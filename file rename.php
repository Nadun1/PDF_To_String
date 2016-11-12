<?php
/**
 * Created by PhpStorm.
 * User: Nadun
 * Date: 11/10/2016
 * Time: 10:10 AM
 */

$filenames = array();
$val = 0;
$foldername ="C:Users/Nadun/Desktop/nlr76-80/New folder";

$dirs=glob($foldername . '/*' , GLOB_ONLYDIR);

foreach($dirs as $val ) {
    if (is_dir($val)) {
        if ($dh = opendir($val)) {
            while (($file = readdir($dh)) !== false) {
                $file=str_replace("  "," ",$file);
                $file=str_replace(" ","-",$file);
                echo $file;
                }
            }

        }
    }
