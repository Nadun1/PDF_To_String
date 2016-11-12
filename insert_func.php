<?php
//error_reporting(E_ERROR | E_PARSE);
$filenames = array();
$val = 0;
$dir ="my/";
$dir1 = "my/";
$new_post=array();



echo is_dir($dir);

function returnDir()
{
echo "ok";
// Open a directory, and read its contents


    if (is_dir($GLOBALS['dir'])) {
        if ($dh = opendir($GLOBALS['dir'])) {
            while (($file = readdir($dh)) !== false) {
                echo "ok2";
                if (strpos($file, ".pdf")) {
                    array_push($GLOBALS['filenames'], $file);
                }
            }


            //print_r($GLOBALS['filenames']);
            foreach ($GLOBALS['filenames'] as $val) {
                $title=substr($val, 17);
                $name1=$GLOBALS['dir1'].substr($val, 0,-3)."docx";
                $content=read_file_docx($name1,$val);
                $new_post[post_title] = $title;
                $new_post[post_content] = $content;
                $new_post[post_date] = date('Y-m-d H:i:s');
                $new_post[post_type] = "publish";
                $new_post[post_category] = array(10);

                 //wp_insert_post( $new_post );
                print_r($new_post) ;



            }
        }
    }
}

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
    $var1="<p hidden=0>";
    $var2 = "</p>";
    $var3 = '<a href="ftp.lawnetsl.com//NLR & SLR, PDF & Word Zip/PDF/NLR/NLR V 01/".$val>'.$val.'</a>'.'<br>';
    return $var3.$var1.nl2br($striped_content).$var2;
}



            returnDir();