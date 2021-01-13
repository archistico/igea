<?php
function getDirContents($dir, &$results = array()) {
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            $results[] = [
                'path' => $path,
                'md5'  => md5_file($path)
        ];
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
            // SEGNO LE DIRECTORY $results[] = $path;
        }
    }

    return $results;
}

var_dump(getDirContents('/home/archemi/igea'));