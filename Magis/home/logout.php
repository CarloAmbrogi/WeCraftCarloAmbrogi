<?php

// session:
session_start();
unset($_SESSION["userinfo"]);


header("Location: login.php"); // rinvia alla pagina di login
?>
