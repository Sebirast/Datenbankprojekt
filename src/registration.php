<?php

include("database.php");

$database = new Database();
$register = [];

if (!$database->prepare_registration()) {
    $register["success"] = false;
    $register["message"] = "Registrierung nicht bereit.";
    echo json_encode($register);
    return false;
}

if (!isset($_GET['surname']) || !isset($_GET['firstname']) 
    || !isset($_GET['email']) || !isset($_GET['birthdate']) 
    || !isset($_GET['participantFunction']) || !isset($_GET['status']) 
    || !isset($_GET['preoccupation'])){
    $register["success"] = false;
    $register["message"] = "Parameter fehlen.";
    echo json_encode($register);
    return false;
}

$username = $_GET['username'];
$password = $_GET['password'];

$registration = $database->register_user($username, $password);

if ($registration) {
    $register["success"] = true;
    $register["message"] = "registration erfolgreich.";
    echo json_encode($register);
    return true;
}

$register["success"] = false;
$register["message"] = "registration nicht erfolgreich.";
echo json_encode($register);
return false;
