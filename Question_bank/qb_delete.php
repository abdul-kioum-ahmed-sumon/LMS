<?php
    // Check if the "id" parameter is set in the URL
    if (isset($_GET["id"])){
        // Get the value of the "id" parameter from the URL
        $id = $_GET["id"];
        // Database connection details
        $db_server= "localhost";
        $db_user = "root";
        $db_pass = "";
        $db_name = "lms";
        $conn = "";
    // Establish a connection to the MySQL database
            $conn = mysqli_connect($db_server,
            $db_user,
            $db_pass,$db_name);
            // Construct SQL query to delete the record with the specified ID
            $sql = "DELETE FROM previous_questions WHERE id=$id";
        // Execute the SQL query
            $conn->query($sql);
    }
    // Redirect the user to another page after deletion
    header("location: qb_read.php");
    
?>

