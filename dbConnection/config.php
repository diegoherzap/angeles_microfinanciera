<?php
$servername = "localhost:3306";
$username = "examenexoal2021";
$password = "examenexoal2021";

// Create connection
$conn = mysqli_connect($servername, $username, $password) or die("Connection failed: " . mysqli_connect_error());
if(mysqli_select_db($conn, "amf")!=null)
    echo "<script>console.log('Connected to DB')</script>";
?>