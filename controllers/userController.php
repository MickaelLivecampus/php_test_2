<?php

use Random\RandomException;

require_once __DIR__ . '/../config/db.php';

// function createUser() {
//     $username = filter_input(INPUT_POST, "username");
//     $password = filter_input(INPUT_POST, "password");   
//     if($username && $password) {
//         try {
//             create("users", [
//                 "username" => $username,
//                 "password" => password_hash($password, PASSWORD_DEFAULT)
//             ]);
            
//         } catch(PDOException $e) {
//             http_response_code(500);
//             echo "Unable to register user";
//             exit();
//         }
//     }
// }

// function loginUser() {
//     $username = filter_input(INPUT_POST, "username");
//     $password = filter_input(INPUT_POST, "password");
//     if($username && $password) {

//         $user = getById("users", "*", null, [
//             "username" => $username
//         ]);
    
//         // if user exist
//         if($user) {
//             // check if the password is ok
//             if(password_verify($password, $user["password"])) {
//                 // init the connection
//                 $_SESSION["loggedin"] = true;
//                 $_SESSION["username"] = $username;
//                 header("Refresh:0");
//             } else {
//                 echo "Incorrect password or email address";
//             }
//         } else {
//             echo "Incorrect password or email address";
//         }
//     }
// }

function createUser($username, $password) {
    $conn = connectDB();

    if($username && $password) {
        $stmt = $conn?->prepare(
            'INSERT INTO administrateurs (username,password_hash) VALUES(?,?)'
        );
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
    }
}

function loginUser($username, $password) {
    $conn = connectDB();

    if($username && $password) {
        $query = "SELECT id, username, password_hash FROM administrateurs WHERE username = ?";
        $stmt = $conn?->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $adminId = $row['id'];
            $hashedPassword = $row['password_hash'];

            if (password_verify($password, $hashedPassword)) {
                $token = generateToken();

                $expirationDate = date('Y-m-d H:i:s', strtotime('+1 day'));
                $insertQuery = "INSERT INTO tokens (user_id, token, expiration_date) VALUES (?, ?, ?)";
                $insertStmt = $conn?->prepare($insertQuery);
                $insertStmt->bind_param("iss", $adminId, $token, $expirationDate);
                $insertStmt->execute();

                $_SESSION["loggedin"] = true;
                $_SESSION["username"] = $username;
                $_SESSION["token"] = $token;
            }
        }

        return false;
    }
}

/**
 * @throws RandomException
 */
function generateToken(): string
{
    return bin2hex(random_bytes(32));
}

function isTokenInDatabase($token): bool
{
    $conn = connectDB();

    $query = "SELECT COUNT(*) AS token_count FROM tokens WHERE token = ? AND expiration_date > NOW()";
    $stmt = $conn?->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $tokenCount = $row['token_count'];

        return $tokenCount > 0;
    }
    return false;
}

function isTokenValid($token): bool
{
    if (!empty($token)) {
        return isTokenInDatabase($token);
    }

    return false;
}

?>