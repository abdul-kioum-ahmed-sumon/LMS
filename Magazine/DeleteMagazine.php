<?php
    session_start();
    
    if (isset($_GET["id"])){

            include "database.php";
            $id=$_GET["id"];
            $sql = "DELETE FROM Magazine WHERE $id=magazine_id";
            $conn->query($sql);
    }
    $_SESSION['message']= "Magazine Deleted successfully";
    
    header("location: Magazine.php");
    
?>