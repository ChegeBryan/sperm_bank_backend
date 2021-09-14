<?php ob_start();?>

<?php
require_once 'connection.php';

$name = $location = "";

$name_err = "";

$error = "";

$response = array();

$name = trim($_POST["name"]);
$location = trim($_POST["location"]);

if (empty(trim($_POST["name"]))) {
    $name_err = "Please enter name address.";
} else {
    $sql = "SELECT id FROM sperm_banks WHERE name = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $param_name);
        $param_name = trim($_POST["name"]);

        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $name_err = "This name already exists.";
            } else {
                $name = trim($_POST["name"]);
            }
        } else {
            $error = "Something went wrong.";
        }
        $stmt->close();
    }
}

if (empty($name_err) && empty($error)) {

    $sql = "INSERT INTO sperm_banks (name, location) VALUES (?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $param_name, $param_location);

        $param_name = $name;
        $param_location = $location;

        if ($stmt->execute()) {
            $response['error'] = false;
            $response['message'] = 'Registered successfully.';
        } else {
            $response['error'] = true;
            $response['message'] = 'Something went wrong.';
        }
        $stmt->close();
    }
} else {
    $response['error'] = true;
    $response['data'] = array(
        'name_error' => $name_err,
    );
    $response['message'] = "One or more fields have an error.";

}
$conn->close();

echo json_encode($response);
