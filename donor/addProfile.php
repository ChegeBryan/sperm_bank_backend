<?php

ob_start();

require_once '../connection.php';

$name = $height = $birth_date = $weight = $ethnicity = $eye_color = $hair_color = $complexion = $education = $bloodtype = $interests = $hobbies = "";

$name_err = "";

$error = "";

$response = array();

$donor_id = trim($_POST["donor_id"]);
$sperm_bank = intval($_POST['sperm_bank_id']);
$name = trim($_POST["name"]);
$height = doubleval($_POST["height"]);
$birth_date = $_POST['birth_date'];
$weight = doubleval($_POST["weight"]);
$ethnicity = trim($_POST['ethnicity']);
$eye_color = trim($_POST['eye_color']);
$hair_color = trim($_POST['hair_color']);
$complexion = trim($_POST['complexion']);
$education = trim($_POST['education']);
$bloodtype = trim($_POST['blood_type']);
$interests = trim($_POST['interests']);
$hobbies = trim($_POST['hobbies']);

$sql = "INSERT INTO profiles (donor_id, sperm_bank_id, name, height, birth_date, ethnicity, weight, eye_color, hair_color, complexion, education, bloodtype, interests, hobbies) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("iisdsssdssssss", $param_id, $sperm_bank, $param_name, $param_height, $param_birth, $param_ethnicity, $param_weight, $param_eye_color, $param_hair_color, $param_complexion, $param_education, $param_bloodtype, $param_interests, $param_hobbies);

    $param_id = $donor_id;
    $param_bank = $sperm_bank;
    $param_name = $name;
    $param_height = $height;
    $param_weight = $weight;
    $param_birth = $birth_date;
    $param_ethnicity = $ethnicity;
    $param_weight = $weight;
    $param_eye_color = $eye_color;
    $param_hair_color = $hair_color;
    $param_complexion = $complexion;
    $param_education = $education;
    $param_bloodtype = $bloodtype;
    $param_interests = $interests;
    $param_hobbies = $hobbies;

    if ($stmt->execute()) {
        $response['error'] = false;
        $response['message'] = 'Registered successfully.';
    } else {
        $response['error'] = true;
        $response['message'] = 'Something went wrong.';
    }
    $stmt->close();
}

$conn->close();

echo json_encode($response);
