<?php

ob_start();

require_once '../connection.php';

$response = array();

$donor_id = intval($_POST["donor_id"]);
$recipient_id = intval($_POST['recipient_id']);

$sql = "INSERT INTO recipients_choice (donor_id, recipient_id) VALUES (?, ?)";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ii", $param_donor, $param_recipient);

    $param_donor = $donor_id;
    $param_recipient = $recipient_id;

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
