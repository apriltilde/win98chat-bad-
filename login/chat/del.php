<?php
try {
    // Connect to the SQLite database
    $db = new SQLite3('chats.db');
} catch (Exception $e) {
    // If connection fails, return error message as JSON
    die(json_encode(array("message" => "Error: Unable to connect to the database")));
}

// Retrieve the chat name from the request data
$postData = json_decode(file_get_contents("php://input"), true);
$chatName = $postData['chat_name'] ?? '';

if (!empty($chatName)) {
    // Prepare the SQL statement to delete the chat based on chat name
    $stmt = $db->prepare("DELETE FROM chats WHERE chat_name = :chatName");
    $stmt->bindValue(':chatName', $chatName, SQLITE3_TEXT);

    // Execute the statement
    if ($stmt->execute()) {
        // Success message
        echo json_encode(array("message" => "Chat '$chatName' deleted successfully"));
    } else {
        // Error deleting chat
        echo json_encode(array("message" => "Error deleting chat: " . $db->lastErrorMsg()));
    }
} else {
    // Invalid data provided
    echo json_encode(array("message" => "Invalid data provided"));
}

// Close the database connection
$db->close();
?>
