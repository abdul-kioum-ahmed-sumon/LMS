<?php
    
    // Check if the "id" parameter is set in the URL
    if (isset($_GET["id"])){
        // Include the file containing database connection details
       include "db_connection.php";
               // Get the value of the "id" parameter from the URL
       $id = $_GET["id"];
       // SQL query to delete the FAQ with the specified id
            $sql = "DELETE FROM faq WHERE id=$id";
            // Execute the SQL query
            $con->query($sql);
    }
    // Redirect to the page displaying all FAQs after deleting the FAQ
    header("location: read_faq.php");
    
?>

