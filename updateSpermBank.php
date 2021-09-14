<?php
ob_start();

require_once 'connection.php';

$error = "";

$name_err = "";

$response = array();

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

if (empty($name_err) && empty($error)) {

    $sql = "UPDATE sperm_banks SET name = ?, location = ? WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssi", $param_name, $param_location, $param_id);

        $param_id = intval($_POST["id"]);
        $param_name = trim($_POST["name"]);
        $param_location = trim($_POST["location"]);

        if ($stmt->execute()) {
            $response['error'] = false;
            $response['message'] = 'Sperm bank updated.';

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
    $response['message'] = 'An error occurred.';
}
$conn->close();

echo json_encode($response);
