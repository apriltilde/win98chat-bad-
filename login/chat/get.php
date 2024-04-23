<?php
usleep(200000);

// Check if the chatid is provided in the URL
if(isset($_GET['chatid'])) {
    try {
        // Connect to the SQLite database
        $db = new SQLite3('msg.db');
        
        // Sanitize the input to prevent SQL injection
        $chatid = $_GET['chatid'];
        // Prepare the SQL query to select username, message, and datetime from the msg table for the specified chatid
        $query = "SELECT username, message, datetime FROM msg WHERE chatid = " . $chatid;
        
        // Execute the query
        $result = $db->query($query);
        
        // Initialize an array to store the fetched data
        $data = array();
        
        // Fetch data from the result set
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            // Add each row to the data array
            $data[] = $row;
        }
        
        // Close the database connection
        $db->close();
        
        // Return the fetched data as JSON
        echo json_encode($data);
    } catch (Exception $e) {
        // Handle connection error
        die(json_encode(array("message" => "Error: Unable to connect to the database")));
    }
} else {
    // Chatid is not provided in the URL data
    die(json_encode(array("message" => "Error: Chat ID not provided")));
}
?>
