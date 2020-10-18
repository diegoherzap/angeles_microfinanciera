<?php
$servername = "localhost";
$username = "admin";
$password = "exoalisoftdahz2020";
$dbname = "amf";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$idname = $_POST["username"];
$pwd = $_POST["password"];

$sql = "SELECT * FROM users WHERE username = '"+ $idname +"'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    if($pwd = $result["username"])
        header("http://localhost/html/inicio.html");
    else echo   "Contraseña incorrecta";
}
else {
    echo "El nombre de usuario no existe";
}
$conn->close();
?>