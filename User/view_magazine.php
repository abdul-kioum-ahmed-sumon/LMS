<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .pdf{
            height: 100vh;
            width: 100%;
            margin: 10px 0;
        }
    </style>
</head>
<?php 
include"connect.php";
$magazine_id = $_GET['id'];

$magazine = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM Magazine WHERE magazine_id = '$magazine_id'"));

?>
<body>

  <embed class="pdf" src="<?php echo $magazine['description'] ?>"  type="">
    
</body>
</html>