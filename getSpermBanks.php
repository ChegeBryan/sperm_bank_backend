<?php ob_start();?>

<?php
require_once 'connection.php';

$error = "";

$response = array();

$sql = "SELECT id, name, location FROM sperm_banks";

if ($stmt = $conn->prepare($sql)) {
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_all(MYSQLI_ASSOC);

            $response['error'] = false;
            $response['data'] = $data;
            $response['message'] = 'Fetch successful.';
            $result->free();
        } else {
            $response['error'] = false;
            $response['data'] = array();
            $response['message'] = 'No sperm banks registered.';
        }
    }
    $stmt->close();
} else {
    $response['error'] = true;
    $response['message'] = "Could not able to process request.";
}

$conn->close();

echo json_encode($response);
