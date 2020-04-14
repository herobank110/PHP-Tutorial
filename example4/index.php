<?php
function makeMessage($name, $age)
{
    // Make the name have a capital first letter
    $first = strtoupper($name[0]);
    // Make the rest have small letters.
    $end = strtolower(substr($name, 1));
    // Save the two parts in the name variable.
    $name = $first . $end;

    // Ensure the age is in the valid range.
    assert(0 < $age && $age < 1000, "age must be between 1 and 999");

    // Return the final message.
    return "$name is $age years old!";
}

$message = makeMessage("david", 99);
echo $message;

?>
