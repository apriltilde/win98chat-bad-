<?php
try {
    // Connect to the SQLite database
    $db = new SQLite3('chats.db');
} catch (Exception $e) {
    // If connection fails, return error message as JSON
    die(json_encode(array("message" => "Error: Unable to connect to the database")));
}

// Define the discriminator to search for
$postData = json_decode(file_get_contents("php://input"), true);
$discriminator = $postData['discriminator'] ?? '';

// Prepare the SQL statement to retrieve chats with the specified discriminator and dm = 1
$stmt = $db->prepare("SELECT chat_id, chat_name FROM chats WHERE discriminator = :discriminator AND dm = 1");
$stmt->bindValue(':discriminator', $discriminator, SQLITE3_TEXT);

// Execute the statement
$result = $stmt->execute();

// Check if there are any rows returned
if ($result) {
    $chats = array();
    // Fetch each row and store it in the $chats array
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        // Add chat_id and chat_name to the array
        $chats[] = array(
            "chat_id" => $row['chat_id'],
            "chat_name" => $row['chat_name']
        );
    }
    // Return the result as JSON
    echo json_encode(array("chats" => $chats));
} else {
    // No chats found or error occurred
    echo json_encode(array("message" => "No chats found or error occurred"));
}

// Close the database connection
$db->close();
?>
