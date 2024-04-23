<?php
date_default_timezone_set("Europe/London");
// Connect to the SQLite database
try {
    $db = new SQLite3('../users.db');
} catch (Exception $e) {
    die("Error: Unable to connect to the database");
}

// Get the discriminator from the request or any other source
$discriminator = $_POST['discriminator'] ?? '';

// Check if the discriminator is provided
if (!empty($discriminator)) {
    // Prepare and execute a query to update the timestamp of the user with the specified discriminator
    $stmt = $db->prepare("UPDATE users SET last_active_time = CURRENT_TIMESTAMP WHERE discriminator = :discriminator");
    $stmt->bindValue(':discriminator', $discriminator, SQLITE3_TEXT);
    $result = $stmt->execute();

    // Check if the query was successful
    if ($result) {
        echo json_encode(array("message" => "Timestamp updated successfully"));
    } else {
        echo json_encode(array("message" => "Error updating timestamp"));
    }
} else {
    // If discriminator is not provided, return an error message
    echo json_encode(array("message" => "Discriminator not provided"));
}

// Close the database connection
$db->close();

?>
