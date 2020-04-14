<?php

/**
 * Returns the welcome message associated with a message ID.
 * 
 * This message is shown to the user when they first access the index
 * page. It is meant as a welcoming message and can be personalised for
 * each user.
 *
 * When writing documentation comments, start with a single line brief
 * description, and if necessary provide additional details below.
 * You should always document each parameter and the return value of
 * a function unless it is an extremely simple function.
 *
 * @param integer $messageId ID of the message to select.
 * @param mysqli $databaseLink Connection to database to query with.
 * Must have SELECT access to the WelcomeMessage table.
 * @return string Welcome message from the database. Empty string is
 * returned if $messageId wasn't found in the database.
 */
function getWelcomeMessage(int $messageId, mysqli $databaseLink): string
{
    $result = $databaseLink->query(
        "SELECT Message FROM WelcomeMessage WHERE MessageID = $messageId;"
    );
 
    if (!empty($databaseLink->error) || $result->num_rows() == 0) {
        // An error occurred during the query or there were no matching records.
        // Return empty string.
        return "";
    }
 
    return $result->fetch_assoc()["Message"];
}
?>
 
<html><body><?php
// Output the message to the user as HTML output.
$databaseLink = new mysqli("localhost", "root", "", "NeatTreats");
echo getWelcomeMessage(1, $databaseLink);
$databaseLink->close();
?></body></html>
