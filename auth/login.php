<?php ob_start();

require_once "../connection.php";

$email = $password = "";
$email_err = $password_err = "";

$response = array();

if (empty(trim($_POST["email"]))) {
    $email_err = "Please enter email.";
} else {
    $email = trim($_POST["email"]);
}

if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter your password.";
} else {
    $password = trim($_POST["password"]);
}

if (empty($email_err) && empty($password_err) && empty('error')) {
    $sql = "SELECT id, email, password FROM users WHERE email = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $param_email);
        $param_email = $email;
        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $email, $hashed_password);

                if ($stmt->fetch()) {
                    if (password_verify($password, $hashed_password)) {
                        $response['error'] = false;
                        $response['data'] = array(
                            'user_id' => $id,
                            'email' => $email,
                        );
                        $response["message"] = 'Login successful.';
                    } else {
                        $password_err = "The password you entered was not valid.";

                        $response["error"] = true;
                        $response['data'] = array(
                            'password_error' => $password_err,
                        );
                        $response["message"] = 'Login failed.';
                    }
                }
            } else {
                $email_err = "No account found with that email.";

                $response["error"] = true;
                $response['data'] = array(
                    'email_error' => $email_err,
                );
                $response["message"] = 'Login failed.';

            }
        } else {
            $response['error'] = true;
            $response['message'] = "Something went wrong.";
        }
        $stmt->close();
    }
} else {
    $response["error"] = true;
    $response['data'] = array(
        'email_error' => $email_err,
        'password_error' => $password_err,
    );
    $data["message"] = "One or more fields have an error/";
}
$conn->close();

echo json_encode($response);
