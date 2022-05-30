<?php  
    $servername = "localhost:8080";
    $username = "root";
    $password = "Amit@2001";
    $database = "groco";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    
    // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
    // echo "Connected successfully";
?>