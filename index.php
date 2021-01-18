<?php

$database_name = "database.db";
$conn = new PDO('sqlite:' . $database_name);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "select * from files 
    where md5 in 
    (
        select md5 from files 
        group by md5 
        having count(md5) > 1
    ) 
    order by md5 asc;";

$query = $conn->prepare($sql);
// $estensione = "jpg";
// $query->bindParam(':estensione', $estensione);
$query->execute();

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>IGEA - PULIZIA</title>
</head>

<body>
    <h1>RIMOZIONE DOPPIONI</h1>

    <ul>
        <?php 
        while($fetch = $query->fetch()) {
        ?>
        <li><?= $fetch['dirname']. DIRECTORY_SEPARATOR .$fetch['basename']; ?></li>
        <?php 
        }
        ?>
    </ul>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
</body>

</html>