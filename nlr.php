<?php
/**
 * Created by PhpStorm.
 * User: Nadun
 * Date: 11/12/2016
 * Time: 9:51 AM
 */
$pdfDirectory  ='wp-content/uploads/NLR & SLR, PDF & Word Zip/PDF/NLR/NLR V 60';
$wordDirectory  = 'wp-content/uploads/NLR & SLR, PDF & Word Zip/Word/WORD DOCUMENTS/NEW LAW REPORT/NLR V 60/';
$filenames = array();
$scan = scandir($pdfDirectory);
$brk=0;

$post_ID=get_the_ID();
function read_file_docx($filename,$name2,$name3){



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
    $var2 = "NadunReplacecontEnd";
    //$var3 = '<a href="http://lawnetsl.com/wordpress/2016/11/06/aaa/'.$name2.'/"'.'>'.$name2.'</a>'.'<br>';
    $var3 = '<a href="http://www.lawnetsl.com/wp-content/uploads/2016/11/sllr'.$name3.'"'.'>'.$name3.'</a>'.'<br>';
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
    $name4=str_replace("  "," ",$val);
    $name6=str_replace(" ","-",$name4);
    $name7=str_replace(",","",$name6);
    $name8=str_replace(")","",$name7);
    $name9=str_replace("&","",$name8);
    $name10=str_replace("(","",$name9);
    $name2=str_replace("--","-",$name10);
    $name1=$wordDirectory.substr($val, 0,-3)."docx";
    $content=read_file_docx($name1,$name6,$name2);
    //$content=str_replace("</p>","",$content1);

    $new_post[post_author] = 1;
    $new_post[post_title] = $title;
    $new_post[post_content] = $content;
    $new_post[post_date] = '1977-12-31 12:00:00';
    $new_post[post_type] = "post";
    $new_post[post_category] = array(10);
    $new_post[post_status] = "publish";
    $new_post[ping_status] = "open";

    wp_insert_post( $new_post );
    $cat_ids = array( 'NLR','NLR V 60','1977' );
    wp_set_object_terms( $post_ID, $cat_ids, 'category' );
    $post_ID=$post_ID+1;

}

wp_set_object_terms( $post_ID, $cat_ids, 'category' );
$post_ID=$post_ID+1;
wp_set_object_terms( $post_ID, $cat_ids, 'category' );