<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

function inserisciDB($lista_immagini, $db)
{
    $contatore = 0;
    foreach ($lista_immagini as $i) {
        $contatore++;
        echo $contatore . " FILE: " . $i['dirname'] . DIRECTORY_SEPARATOR . $i['basename'] . " MD5: " . $i['md5'] . " SIZE: " . $i['size'] . "\n";
        
        $query = "INSERT INTO files (id, dirname, basename, filename, extension, md5, size) VALUES (null, :dirname, :basename, :filename, :extension, :md5, :size);";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':dirname', $i['dirname'], PDO::PARAM_STR, 255);
        $stmt->bindParam(':basename', $i['basename'], PDO::PARAM_STR, 255);
        $stmt->bindParam(':filename', $i['filename'], PDO::PARAM_STR, 255);
        $stmt->bindParam(':extension', $i['extension'], PDO::PARAM_STR, 255);
        $stmt->bindParam(':md5', $i['md5'], PDO::PARAM_STR, 255);
        $stmt->bindParam(':size', $i['size'], PDO::PARAM_INT);
        $stmt->execute();
    }
}

$percorso_default = 'c:\progetti\siti_laravel\igea';
$percorso = readline("Inserire il percorso completo di ricerca (vuoto = $percorso_default): ");
if (empty($percorso)) {
    $percorso = $percorso_default;
}

$database_name = "database.db";
$db = new PDO('sqlite:' . $database_name);
$query = "CREATE TABLE IF NOT EXISTS files (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE, dirname TEXT, basename TEXT, filename TEXT, extension TEXT, md5 TEXT, size INTEGER)";
$db->exec($query);

$immagini = getDirContents($percorso);
inserisciDB($immagini, $db);
