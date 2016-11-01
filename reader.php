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
$val = 0;
$dir = "C:/xampp/htdocs/wordpress/files";
$dir1 = $dir . "/";
/**
 *
 */


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wordpress-test";


$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
    echo('ok');
}


function returnDir()
{

// Open a directory, and read its contents
    if (is_dir($GLOBALS['dir'])) {
        if ($dh = opendir($GLOBALS['dir'])) {
            while (($file = readdir($dh)) !== false) {
                if (strpos($file, ".pdf")) {
                    //echo(returnYear($file));
                    //$filenames = array();
                    array_push($GLOBALS['filenames'], $file);
                    //print_r($filenames);
                    //echo "filename:" . $file . "<br>";
                }
            }
            //print_r($GLOBALS['filenames']);
            foreach ($GLOBALS['filenames'] as $val) {
                //echo(count($GLOBALS['filenames']))."<br>"; //count size of the array


                $yearDate = returnYear($val);
                $title = title($val);
                $content = returnContent($val);


                echo('start<br>');
                insertDatatoDatabase($yearDate,$title,$content);


            }
            closedir($dh);
        }
    }
}

function returnYear($val)
{
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

function title($val)
{
    $a = new PDF2Text();
    $a->setFilename($GLOBALS['dir1'] . $val);
    $a->decodePDF();
    $textstring = $a->output();
    //echo (gettype($textstring));
    //year
    $title = substr($textstring, 164, 26);
    //echo $textstring;  //print title
    return $title;  //return title
}

function returnContent($val)
{
    $a = new PDF2Text();
    $a->setFilename($GLOBALS['dir1'] . '$val');
    $a->decodePDF();
    $textstring = $a->output();
    //$embed = "[pdf-embedder url='http://localhost/wordpress/wp-content/uploads/2016/10/'Law-Report-part-3.pdf' title='law-report-part-3']";
    //$var1="<p hidden=0>";
    //$var2 = "</p>";
    //$textstring = $var1.$textstring.$var2;
    //$textstring = $embed.$var1.$textstring.$var2;
    return $textstring;

}

function insertDatatoDatabase($date, $title, $content)
{
    echo $title.'<br>';
    $con = $GLOBALS['conn'];

    $sql = "INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES ('', '1', '$date', '2016-10-21 02:38:21', '$content', '$title', '', 'inherit', 'closed', 'closed', '', '229-revision-v1', '', '', '2016-10-21 02:38:21', '2016-10-21 02:38:21', '', '229', 'http://localhost/wordpress/2016/10/21/229-revision-v1/', '0', 'revision', '', '0')";
    if ($con->query($sql) === TRUE) {
        echo "";
    } else {
        echo "Error: " . $sql . " " . $con->error;
    }

    echo 'end<br>';
    $con->close();
}


//echo(returnYear());
returnDir();
//returnContent();
//echo(title());
//print_r($filenames);