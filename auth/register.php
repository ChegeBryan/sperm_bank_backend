<?php ob_start();?>

<?php
require_once '../connection.php';

$email = $password = $confirm_password = "";

$email_err = $password_err = $confirm_password_err = "";

$error = "";

$response = array();

$email = trim($_POST["email"]);
$role = $_POST["role"];

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

if (empty(trim($_POST["psw"]))) {
    $password_err = "Please enter a password.";
} elseif (strlen(trim($_POST["psw"])) < 6) {
    $password_err = "Password must have at least 6 characters.";
} else {
    $password = trim($_POST["psw"]);
}

if (empty(trim($_POST["psw_rpt"]))) {
    $confirm_password_err = "Please confirm password.";
} else {
    $confirm_password = trim($_POST["psw_rpt"]);
    if (empty($password_err) && ($password != $confirm_password)) {
        $confirm_password_err = "Password did not match.";
    }
}

if (empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($error)) {

    $sql = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssi", $param_email, $param_password, $param_role);

        $param_email = trim($_POST["email"]);
        $param_password = password_hash($password, PASSWORD_DEFAULT);
        $param_role = intval($role); // admin

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
    $response['error'] = false;
    $response['data'] = array(
        'email_error' => $email_err,
        'password_error' => $password_err,
        'confirm_password_error' => $confirm_password,
    );
    $response['message'] = "One or more fields have an error.";

}
$conn->close();

echo json_encode($response);
