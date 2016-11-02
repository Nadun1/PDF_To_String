<?php
/**0
 * Created by PhpStorm.
 * User: Nadun
 * Date: 10/19/2016
 * Time: 9:33 AM
 */

//error_reporting(E_ERROR | E_PARSE);
include('class.pdf2text.php');
$filenames = array();
$val=0;
$dir = "C:/xampp/htdocs/wordpress/files";
$dir1= $dir."/";
/**
 *
 */
function returnDir(){

// Open a directory, and read its contents
    if (is_dir($GLOBALS['dir'])){
        if ($dh = opendir($GLOBALS['dir'])){
            while (($file = readdir($dh)) !== false){
                if(strpos($file,".pdf")){
                    //echo(returnYear($file));
                    //$filenames = array();
                    array_push($GLOBALS['filenames'],$file);
                    //print_r($filenames);
                    //echo "filename:" . $file . "<br>";
                }
            }
            //print_r($GLOBALS['filenames']);
            foreach($GLOBALS['filenames'] as $val){
                //echo(count($GLOBALS['filenames']))."<br>"; //count size of the array
                echo(returnYear($val))."<br>";
                //echo $val."<br>"; //print variables in array
                echo(title($val))."<br>";
                echo(returnContent($val))."<br>";
            }
            closedir($dh);
        }
    }
}

function returnYear($val){
    $a = new PDF2Text();
    $a->setFilename($GLOBALS['dir1'] . $val);
    $a->decodePDF();
    $textstring = $a->output();
    //echo (gettype($textstring));
    //year
    $year = substr($textstring, 165, 4);
    //echo $textstring;
    //print year
    $set1 = "-01-01 09:00:00";
    $year = "$year$set1";
    return $year;
    //echo $year;
}

function title($val){
    $a = new PDF2Text();
    $a->setFilename($GLOBALS['dir1'] . $val);
    $a->decodePDF();
    $textstring = $a->output();
    //echo (gettype($textstring));
    //year
    $title = substr($textstring,164,26);
    //echo $textstring;  //print title
    return $title;  //return title
}

function returnContent($val){
    $a = new PDF2Text();
    $a->setFilename($GLOBALS['dir1'] . $val);
    $a->decodePDF();
    $textstring = $a->output();
    // $embed = "[pdf-embedder url='http://localhost/wordpress/wp-content/uploads/2016/10/Law-Report-part-3.pdf' title='law-report-part-3']";
    //$var1="<p hidden=0>";
    //$var2 = "</p>";
    //$textstring = $var1.$textstring.$var2;
    //$textstring = $embed.$var1.$textstring.$var2;
    return $textstring;

}



//echo(returnYear());
returnDir();
//returnContent();
//echo(title());
//print_r($filenames);