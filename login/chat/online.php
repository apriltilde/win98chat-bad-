<?php

try {
    // Connect to the SQLite database
    $db = new SQLite3('../users.db');
} catch (Exception $e) {
    // Handle connection error
    die(json_encode(array("message" => "Error: Unable to connect to the database")));
}

// Define the threshold for considering a user offline (in seconds)
$offlineThreshold = 5 * 60; // 5 minutes in seconds

// Get the current GMT timestamp
$currentTimestampGMT = gmdate('Y-m-d H:i:s');

// Prepare and execute a query to select all users
$stmt = $db->prepare("SELECT username, discriminator, last_active_time FROM users");
$result = $stmt->execute();

// Initialize arrays to store online and offline users
$onlineUsers = array();
$offlineUsers = array();

// Fetch data from the result set
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    // Get the username, discriminator, and last active timestamp
    $username = $row['username'];
    $discriminator = $row['discriminator'];
    $lastActiveTimestamp = $row['last_active_time'];

    // Calculate the time difference between the current GMT time and the last active time
    $timeDifference = strtotime($currentTimestampGMT) - strtotime($lastActiveTimestamp);

    // Determine if the user is online or offline based on the time difference
    if ($timeDifference < $offlineThreshold) {
        $onlineUsers[] = array("username" => $username, "discriminator" => $discriminator);
    } else {
        $offlineUsers[] = array("username" => $username, "discriminator" => $discriminator);
    }
}

// Close the database connection
$db->close();

// Return the online and offline users as JSON
echo json_encode(array("online_users" => $onlineUsers, "offline_users" => $offlineUsers));

?>
