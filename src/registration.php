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
    || !isset($_GET['preoccupation']) || !isset($_GET['placeOfWork'])){
    $register["success"] = false;
    $register["message"] = "Parameter fehlen.";
    echo json_encode($register);
    return false;
}

$surname = $_GET['surname'];
$firstname = $_GET['firstname'];
$birthDate = $_GET['birthdate'];
$nickname = $_GET['nickname'];
$email = $_GET['email'];
$participantFunction = $_GET['participantFunction'];
$status = $_GET['status'];
$preoccupation = $_GET['preoccupation'];
$placeOfWork = $_GET['placeOfWork'];


$registration = $database->register_user($firstname, $surname, $email, $birthDate, $nickname, $participantFunction, $status, $preoccupation, $placeOfWork);

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
