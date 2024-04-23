<?php

try {
    $db = new SQLite3('users.db');
} catch (Exception $e) {
    die("Error: Unable to connect to the database");
}

// Get the session ID (cookie) from the user's browser
$session_id = $_COOKIE['session_id'] ?? null;

if ($session_id) {
    // Session ID is found, proceed to check in the database
    $stmt = $db->prepare("SELECT username, discriminator FROM users WHERE cookie = :cookie");
    $stmt->bindValue(':cookie', $session_id, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user) {
        // User found in the database with matching session ID
        echo json_encode(array("message" => "Cookie matches user in the database","user" => $user));
    } else {
        // No user found with the provided session ID
        echo json_encode(array("message" => "No user found with the provided session ID"));
    }
} else {
    // Session ID not found in the user's browser
    echo json_encode(array("message" => "Session ID not found in the user's browser"));
}

// Close the database connection
$db->close();

?>
