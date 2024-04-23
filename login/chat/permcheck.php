<?php

// Check if the chatid is provided in the URL
if (isset($_GET['chatid'])) {
    // Check if the discriminator is provided in the POST request
    try {
        // Connect to the SQLite database
        $db = new SQLite3('chats.db');

        // Sanitize the input to prevent SQL injection
        $chatid = $_GET['chatid'];
        $chatid = preg_replace('/[^a-zA-Z0-9]/', '', $chatid);
        $postData = json_decode(file_get_contents("php://input"), true);
        $discriminator = $postData['discriminator'] ?? '';

        // Prepare the SQL query to select data from the chats table for the specified chatid and discriminator
        $query = "SELECT * FROM chats WHERE chat_id = :chatid AND discriminator = :discriminator";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':chatid', $chatid, SQLITE3_TEXT);
        $stmt->bindValue(':discriminator', $discriminator, SQLITE3_TEXT);

        // Execute the query
        $result = $stmt->execute();

        // Check if any data is found
        if ($result->fetchArray(SQLITE3_ASSOC)) {
            echo "auth";
        } else {
            // No data found
            echo json_encode(array("message" => "No data found for the provided chat ID and discriminator"));
        }

        // Close the database connection
        $db->close();
    } catch (Exception $e) {
        // Handle connection error
        die(json_encode(array("message" => "Error: Unable to connect to the database")));
    }
} else {
    // Chatid is not provided in the URL data
    die(json_encode(array("message" => "Error: Chat ID not provided")));
}
?>
