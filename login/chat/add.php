<?php
try {
    // Connect to the SQLite database
    $db = new SQLite3('chats.db');
} catch (Exception $e) {
    // If connection fails, return error message as JSON
    die(json_encode(array("message" => "Error: Unable to connect to the database")));
}

// Function to generate a random alphanumeric string
function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

// Generate a random chat ID and check if it already exists in the database
$chatId = generateRandomString();
$stmt = $db->prepare("SELECT COUNT(*) AS count FROM chats WHERE chat_id = :chatId");
$stmt->bindValue(':chatId', $chatId, SQLITE3_TEXT);
$result = $stmt->execute();
$row = $result->fetchArray(SQLITE3_ASSOC);
$count = $row['count'];

// Ensure the generated chat ID is unique
while ($count > 0) {
    $chatId = generateRandomString();
    $stmt->bindValue(':chatId', $chatId, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $count = $row['count'];
}

// Retrieve the discriminator from the request body
$postData = json_decode(file_get_contents("php://input"), true);
$discriminator = $postData['discriminator'] ?? '';
$name = $postData['name'] ?? '';

// Prepare the SQL statement to insert the new entry with discriminator
$stmt = $db->prepare("INSERT INTO chats (chat_id, discriminator, dm , chat_name) VALUES (:chatId, :discriminator, 1, :name)");
$stmt->bindValue(':chatId', $chatId, SQLITE3_TEXT);
$stmt->bindValue(':name', $name, SQLITE3_TEXT);
$stmt->bindValue(':discriminator', $discriminator, SQLITE3_TEXT);

// Execute the statement
$result = $stmt->execute();

// Check if the statement executed successfully
if ($result) {
    // Success message
    echo json_encode(array("message" => "Chat with discriminator added successfully"));
} else {
    // Error adding chat
    echo json_encode(array("message" => "Error adding chat with discriminator: " . $db->lastErrorMsg()));
}

// Retrieve the friend from the request body
$friend = $postData['friend'] ?? '';

// Prepare the SQL statement to insert the new entry with friend
$stmt = $db->prepare("INSERT INTO chats (chat_id, discriminator, dm, chat_name) VALUES (:chatIdFriend, :friend, 1, :name)");
$stmt->bindValue(':chatIdFriend', $chatId, SQLITE3_TEXT);
$stmt->bindValue(':name', $name, SQLITE3_TEXT);
$stmt->bindValue(':friend', $friend, SQLITE3_TEXT);

// Execute the statement
$result = $stmt->execute();

// Check if the statement executed successfully
if ($result) {
    // Success message
    echo json_encode(array("message" => "Chat with friend added successfully"));
} else {
    // Error adding chat
    echo json_encode(array("message" => "Error adding chat with friend: " . $db->lastErrorMsg()));
}

// Close the database connection
$db->close();
?>
