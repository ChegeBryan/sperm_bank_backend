<?php
ob_start();

require_once 'connection.php';

$error = "";

$response = array();

$sql = "DELETE FROM sperm_banks WHERE id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $param_id);

    $param_id = $_POST["id"];

    if ($stmt->execute()) {
        $response['error'] = false;
        $response['message'] = 'Sperm bank deleted.';

    } else {
        $response['error'] = true;
        $response['message'] = 'Something went wrong.';
    }
    $stmt->close();
}
$conn->close();

echo json_encode($response);
