<?php ob_start();

require_once '../connection.php';

$error = "";

$response = array();

$sql = "SELECT sperm_banks.name AS sb_name, profiles.name AS donor_name, donor_id
FROM profiles
INNER JOIN sperm_banks
ON profiles.sperm_bank_id = sperm_banks.id";

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
