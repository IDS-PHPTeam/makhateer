<?php 
session_start(); 
echo (isset($_SESSION['loggedIn']) ? $_SESSION['loggedIn'] : "false");
//if (!$_SESSION["loggedIn"]) 
//header("Location: http://localhost:8080/GithubProject/php_rest_myblog-master/Views/login.php");

?>