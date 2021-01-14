<?php

function checkExtension($ext)
{
    return in_array($ext, ['jpg', 'png', 'tga', 'tiff', 'psd', 'pdf', 'jpeg', 'webp']);
}

function getDirContents($dir, &$results = array())
{
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            $path_parts = pathinfo($path);
            if (isset($path_parts['extension']) && checkExtension($path_parts['extension'])) {
                $results[] = [
                    'dirname' => $path_parts['dirname'],
                    'basename' => $path_parts['basename'],
                    'filename' => $path_parts['filename'],
                    'extension' => $path_parts['extension'],
                    'md5'  => md5_file($path),
                    'size' => filesize($path)
                ];
            }
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
            // SEGNO LE DIRECTORY $results[] = $path;
        }
    }

    return $results;
}

$immagini = getDirContents('c:\progetti\siti_laravel\igea');
$contatore = 0; 
foreach($immagini as $i) {
    $contatore++;
    echo $contatore." FILE: ".$i['dirname'].DIRECTORY_SEPARATOR.$i['basename']." MD5: ".$i['md5']." SIZE: ".$i['size']. "\n";
}

/*


*/