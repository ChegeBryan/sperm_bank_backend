<?php ob_start();?>

<?php
require_once '../connection.php';

$email = $password = "";

$email_err = "";

$error = "";

$response = array();

$email = trim($_POST["email"]);
$role = $_POST["role"];
$password = $_POST["password"];

if (empty(trim($_POST["email"]))) {
    $email_err = "Please enter email address.";
} else {
    $sql = "SELECT id FROM users WHERE email = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $param_email);
        $param_email = trim($_POST["email"]);

        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $email_err = "This email already exists.";
            } else {
                $email = trim($_POST["email"]);
            }
        } else {
            $error = "Something went wrong.";
        }
        $stmt->close();
    }
}

if (empty($email_err) && empty($error)) {

    $sql = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssi", $param_email, $param_password, $param_role);

        $param_email = $email;
        $param_password = password_hash($password, PASSWORD_DEFAULT);
        $param_role = intval($role); // admin

        if ($stmt->execute()) {
            $response['error'] = false;
            $response['data'] = array(
                'email' => $email,
                'role' => 'admin',
                'user_id', $stmt->insert_id,
            );
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
        'email_error' => $email_err,
    );
    $response['message'] = "One or more fields have an error.";

}
$conn->close();

echo json_encode($response);
