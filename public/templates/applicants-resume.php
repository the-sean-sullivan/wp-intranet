<?php

    $file = basename(urldecode($_GET['file']));
    $fileDir = '/path/to/file/folder/';

    if (file_exists($fileDir . $file)) :
        $file_type = substr($file, -3);
        $contents = file_get_contents($fileDir . $file);

        if ( $file_type == 'pdf' ) :
            header('Content-type: application/pdf');
        else :
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file . '"');
        endif;

        echo $contents;

    endif;
