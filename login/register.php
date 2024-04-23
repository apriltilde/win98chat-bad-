<?php
date_default_timezone_set("Europe/London");
session_start();
$session_id = session_id(); // Get the session ID
$ipAddress = $_SERVER['REMOTE_ADDR'];

try {
    $db = new SQLite3('users.db');
} catch (Exception $e) {
    die("Error: Unable to connect to the database");
}

$postData = json_decode(file_get_contents("php://input"), true);

$username = $postData['username'];
$password = $postData['password'];

// Check if the user with the same password exists in the database
$stmt = $db->prepare("SELECT * FROM users WHERE username = :username AND password_hash = :password_hash");
$stmt->bindValue(':username', $username, SQLITE3_TEXT);
$stmt->bindValue(':password_hash', $password, SQLITE3_TEXT);
$result = $stmt->execute();
$userExists = $result->fetchArray(SQLITE3_ASSOC);

if ($userExists) {
    // User with the same password exists, update last_active_time and cookie
    $stmt = $db->prepare("UPDATE users SET last_active_time = CURRENT_TIMESTAMP, cookie = :cookie WHERE username = :username AND password_hash = :password_hash");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':password_hash', $password, SQLITE3_TEXT);
    $stmt->bindValue(':cookie', $session_id, SQLITE3_TEXT);
} else {
    // User does not exist, insert new user
    function generateRandomString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    // Generate discriminator
    $unique = false;
    while (!$unique) {
        $discriminator = generateRandomString();
        $stmt = $db->prepare("SELECT * FROM users WHERE discriminator = :discriminator");
        $stmt->bindValue(':discriminator', $discriminator, SQLITE3_TEXT);
        $result = $stmt->execute();
        if (!$result->fetchArray()) {
            $unique = true;
        }
    }

    // Insert new user
    $stmt = $db->prepare("INSERT INTO users (username, password_hash, ip_address, discriminator, last_active_time, cookie) VALUES (:username, :password_hash, :ip_address, :discriminator, CURRENT_TIMESTAMP, :cookie)");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':password_hash', $password, SQLITE3_TEXT);
    $stmt->bindValue(':ip_address', $ipAddress, SQLITE3_TEXT);
    $stmt->bindValue(':discriminator', $discriminator, SQLITE3_TEXT);
    $stmt->bindValue(':cookie', $session_id, SQLITE3_TEXT);
}

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(array("message" => "User updated/inserted successfully"));
    $_SESSION['username'] = $username;

    setcookie('session_id', $session_id, strtotime('+10 years'), '/', '', true, true);
    exit();
} else {
    echo json_encode(array("message" => "Error updating/inserting user: " . $db->lastErrorMsg()));
}

// Close the database connection
$db->close();
