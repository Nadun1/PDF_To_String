<?php
/**
 * Created by PhpStorm.
 * User: Nadun
 * Date: 10/24/2016
 * Time: 11:26 AM
 */


$pdfDirectory  ='C:/xampp/htdocs/wordpress/wp-content/uploads/2016/pdf/NLR V 01';
$wordDirectory  = 'C:/xampp/htdocs/wordpress/wp-content/uploads/2016/word/NLR V 01/';
$filenames = array();
$scan = opendir($pdfDirectory);
$cat_ids = array( 'NLR' );

function read_file_docx($filename,$name2){

    $striped_content = '';
    $content = '';

    if(!$filename || !file_exists($filename)) return false;

    $zip = zip_open($filename);

    if (!$zip || is_numeric($zip)) return false;

    while ($zip_entry = zip_read($zip)) {

        if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

        if (zip_entry_name($zip_entry) != "word/document.xml") continue;

        $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

        zip_entry_close($zip_entry);
    }// end while

    zip_close($zip);


    $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
    $content = str_replace('</w:r></w:p>', "\r\n", $content);
    $striped_content = strip_tags($content);
    //$striped_content=str_replace("</p>","",$striped_content1);
    $var1="NadunReplacecont"."<br>";
    $var2 = "</p>";
    $var3 = '<a href="http://localhost/wordpress/2016/11/06/aaa/'.$name2.'/"'.'>'.$name2.'</a>'.'<br>';
    return $var3.$var1.nl2br($striped_content).$var2;
}

///////////////////////////////////////////////////////////// main functions /////////////////

foreach($scan as $file)
{
    if (!is_dir($file))
    {
        if (strpos($file, ".pdf")) {
            array_push($filenames, $file);
        }

    }
}

$new_post = array();
foreach ($filenames as $val) {
    $title=substr($val, 17);
    $title = substr($title, 0,-4);
    $name5=substr($val, 0,-4);
    $name4=str_replace(".","",$name5);
    $name2=str_replace(" ","-",$name4);
    $name1=$wordDirectory.substr($val, 0,-3)."docx";
    $content=read_file_docx($name1,$name2);
    //$content=str_replace("</p>","",$content1);
    $new_post[post_title] = $title;
    $new_post[post_content] = $content;
    $new_post[post_date] = date('Y-m-d H:i:s');
    $new_post[post_type] = "post";
    $new_post[post_category] = array(10);
    $new_post[post_status] = "publish";
    $new_post[ping_status] = "open";

    echo $content."<br>";
    //wp_insert_post( $new_post );

    // print_r($new_post) ;
}