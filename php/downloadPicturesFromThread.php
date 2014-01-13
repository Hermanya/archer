<?php
    if(empty($_GET['thread_id'])){
        header("Location: ../index.php");
        return;
    }
    $thread_id = $_GET['thread_id'];
    $zipname = '../content/'.$thread_id.'/thread-'.$thread_id.'.zip';
    $zip = new ZipArchive;
    $zip->open($zipname, ZipArchive::CREATE);
    if ($handle = opendir('../content/'.$thread_id)) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && $entry != "resized") {
            $zip->addFile($entry);
        }
      }
      closedir($handle);
    }

    $zip->close();

    header('Content-Type: application/zip');
    header("Content-Disposition: attachment; filename='thread-".$thread_id.".zip'");
    header('Content-Length: ' . filesize($zipname));
    header("Location: ../content/".$thread_id."/thread-".$thread_id.".zip");

    ?>