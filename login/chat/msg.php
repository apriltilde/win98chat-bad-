<?php

try {
    $db = new SQLite3('msg.db');
} catch (Exception $e) {
    die(json_encode(array("message" => "Error: Unable to connect to the database")));
}

// Retrieve chat ID from the URL
$chatid = $_GET['chatid'] ?? '';
$chatid = preg_replace('/[^a-zA-Z0-9]/', '', $chatid);

// Check if chat ID is provided
if (!empty($chatid)) {
    $postData = json_decode(file_get_contents("php://input"), true);

    $username = $postData['username'] ?? '';
    $message = $postData['message'] ?? '';

    if (!empty($username) && !empty($message)) {
        // Prepare and bind parameters for the SQL statement
        $stmt = $db->prepare("INSERT INTO msg (username, message, chatid) VALUES (:username, :message, :chatid)");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->bindValue(':message', $message, SQLITE3_TEXT);
        $stmt->bindValue(':chatid', $chatid, SQLITE3_TEXT);

        // Execute the statement
        if ($stmt->execute()) {
            // Message inserted successfully
            $message_id = $db->lastInsertRowID();
            echo json_encode(array("message" => "Message inserted successfully", "message_id" => $message_id));
        } else {
            // Error inserting message
            echo json_encode(array("message" => "Error inserting message: " . $db->lastErrorMsg()));
        }
    } else {
        // Invalid data provided
        echo json_encode(array("message" => "Invalid data provided"));
    }
} else {
    // Chat ID not provided
    echo json_encode(array("message" => "Chat ID not provided"));
}

// Close the database connection
$db->close();

?>
