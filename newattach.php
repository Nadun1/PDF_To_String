<?php
//[insert_php]
$pdfDirectory  ='wp-content/uploads/2016/11';
$wordDirectory  = 'wp-content/uploads/2016/11/';
$filenames = array();
$scan = scandir($pdfDirectory);

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
    //$text = str_replace('</p> ', '', nl2br($striped_content));
    $var1="<p hidden=0>"."<br>";
    $var2 = "</p>";
    //$var3 = '<a href="http://localhost/wordpress/2016/11/05/2/"' .$val.'>'.$val.'-2/'.'</a>'.'<br>';
    //$var3 = .$val.'</a>';
    return $var1.nl2br($striped_content).$var2;
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
    $valname = substr($val,0,-4);
    $name1=$wordDirectory.substr($val, 0,-3)."docx";
    $content=read_file_docx($name1,$valname);
    $new_post[post_title] = $title;
    $new_post[post_content] = $content;
    $new_post[post_date] = date('Y-m-d H:i:s');
    $new_post[post_type] = "post";
    $new_post[post_category] = array(10);
    $new_post[post_status] = "publish";
    $new_post[ping_status] = "open";


    $postid = wp_insert_post( $new_post );
    // print_r($new_post) ;

    $file = $pdfDirectory.$val;
    $filetype = wp_check_filetype( basename( $filename ), null );
    $wp_upload_dir = wp_upload_dir();
    $attachment = array(
        'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
        'post_mime_type' => $filetype['type'],
        'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
        'post_content'   => '<a href="http://localhost/wordpress/2016/11/05/"'.($postid + 1).'"/002-nlr-nlr-v-01-gabriel-appuhamy-v-pelis-perera-appuhamyet-al/" rel="attachment wp-att-44">002-nlr-nlr-v-01-gabriel-appuhamy-v-pelis-perera-appuhamyet-al</a>',
        'post_status'    => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $filename, $postid );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
    wp_update_attachment_metadata( $attach_id, $attach_data );

    set_post_thumbnail( $postid, $attach_id );


}
//[/insert_php]