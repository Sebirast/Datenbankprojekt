<?php

require_once("database.php");

$database = new Database();
$login = [];

if (!$database->prepare_login()) {
    $login["success"] = false;
    $login["message"] = "Login nicht bereit.";
    echo json_encode($login);
    return false;
}

if (!isset($_GET['username']) || !isset($_GET['password'])) {
    $login["success"] = false;
    $login["message"] = "Parameter fehlen.";
    echo json_encode($login);
    return false;
}

$username = $_GET['username'];
$password = $_GET['password'];

$logged_in = $database->login_user($username, $password);

if ($logged_in) {
    $login["success"] = true;
    $login["message"] = "login erfolgreich.";
    echo json_encode($login);
    return false;
}

$login["success"] = false;
$login["message"] = "login nicht erfolgreich. Username oder Passwort sind falsch.";
echo json_encode($login);
return false;
