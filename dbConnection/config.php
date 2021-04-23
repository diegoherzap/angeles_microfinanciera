<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "amf";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
if(mysqli_select_db($conn, $dbname)!=null)
    {
        echo "<script>console.log('Connected to DB')</script>";
    }
?>