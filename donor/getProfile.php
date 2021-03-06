<?php

ob_start();

require_once '../connection.php';

$response = array();

$sql = "SELECT id, name FROM sperm_banks";

if ($stmt = $conn->prepare($sql)) {
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $response['sperm_banks'] = $data;
            $result->free();
        } else {
            $response['sperm_banks'] = array();
        }
    }
    $stmt->close();
}

$sql = "SELECT * FROM profiles WHERE donor_id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $param_id);
    $param_id = intval($_POST['id']);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $response['error'] = false;
            $response['data'] = $result->fetch_array(MYSQLI_ASSOC);
            $response['message'] = 'Fetch successful.';
            $result->free();
        } else {
            $response['error'] = false;
            $response['data'] = array();
            $response['message'] = 'No profile registered.';
        }
    }
    $stmt->close();
} else {
    $response['error'] = true;
    $response['message'] = "Could not able to process request.";
}
echo ($conn->info);

$conn->close();

echo json_encode($response);
