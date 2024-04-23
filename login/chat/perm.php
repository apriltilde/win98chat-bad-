<?php
// Wrap the code in a try-catch block to handle exceptions
try {
    // Connect to the SQLite database
    $db = new SQLite3('chats.db');
} catch (Exception $e) {
    // If connection fails, return error message as JSON
    die(json_encode(array("message" => "Error: Unable to connect to the database")));
}

$chatid = $_GET['chatid'] ?? '';
$chatid = preg_replace('/[^a-zA-Z0-9]/', '', $chatid);
// Retrieve discriminator from the request data
$postData = json_decode(file_get_contents("php://input"), true);
$discriminator = $postData['discriminator'] ?? '';

if (!empty($chatid) && !empty($discriminator)) {
    if ($chatid === 'main' || $chatid === 'new') {
        // Prepare and bind parameters for the SQL statement
        $stmt = $db->prepare("INSERT OR IGNORE INTO chats (chat_id, discriminator) VALUES (:chatid, :discriminator)");
        $stmt->bindValue(':chatid', $chatid, SQLITE3_TEXT);
        $stmt->bindValue(':discriminator', $discriminator, SQLITE3_TEXT);

        // Execute the statement
        $result = $stmt->execute();

        // Check if the statement executed successfully
        if ($result) {
            // Success message
            echo json_encode(array("message" => "Chat ID and discriminator added successfully"));
        } else {
            // Error adding chat ID and discriminator
            echo json_encode(array("message" => "Error adding chat ID and discriminator: " . $db->lastErrorMsg()));
        }
    } else {
        // Invalid chat id provided
        echo json_encode(array("message" => "Invalid chat ID. Allowed values are 'main' or 'new'"));
    }
} else {
    // Invalid data provided
    echo json_encode(array("message" => "Invalid data provided"));
}

// Close the database connection
$db->close();
?>
