<?php 

$server = "localhost"; //hostname
$user = "machadokd"; //username da bd
$pass = "machado35"; //password da bd
$database = "projeto4"; //nome da bd

$conn = mysqli_connect($server, $user, $pass, $database);

if (!$conn) {
    die("<script>alert('Connection Failed.')</script>");
}

?>