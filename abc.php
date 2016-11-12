<?php
/**
 * Created by PhpStorm.
 * User: Nadun
 * Date: 10/24/2016
 * Time: 11:26 AM
 */


$pdfDirectory  ='wp-content/uploads/2016/11';
$wordDirectory  = 'wp-content/uploads/2016/11/';
$filenames = array();
$scan = scandir($pdfDirectory);
$args = array(
    'posts_per_page'   => 150,
    'offset'           => 0,
    'category'         => '',
    'category_name'    => '',
    'orderby'          => 'date',
    'order'            => 'DESC',
    'include'          => '',
    'exclude'          => '',
    'meta_key'         => '',
    'meta_value'       => '',
    'post_type'        => 'attachment',
    'post_mime_type'   => 'application/pdf',
    'post_parent'      => '',
    'author'	   => '',
    'author_name'	   => '',
    'post_status'      => 'inherit',
    'suppress_filters' => true
);
$posts_array = get_posts( $args );


function read_file_docx($filename,$val){

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
    $var1="<p hidden=0>"."<br>";
    $var2 = "</p>";
    $var3 = '<a href="ftp.lawnetsl.com//NLR & SLR, PDF & Word Zip/PDF/NLR/NLR V 01/".$val>'.$val.'</a>'.'<br>';
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

    $name1=$wordDirectory.substr($val, 0,-3)."docx";
    $content=read_file_docx($name1,$val);
    $new_post[post_title] = $title;
    $new_post[post_content] = $content;
    $new_post[post_date] = date('Y-m-d H:i:s');
    $new_post[post_type] = "post";
    $new_post[post_category] = array(10);
    $new_post[post_status] = "publish";
    $new_post[ping_status] = "open";


    wp_insert_post( $new_post );
    // print_r($new_post) ;



}