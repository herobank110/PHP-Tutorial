<?php

define("ERROR_TOO_LONG", "Must be shorter than ");
define("ERROR_TOO_SHORT", "Must be longer than ");
define("ERROR_EMPTY", "Must not be empty");

/** Returns the input with potentially dangerous parts removed. */
function sanitize(string $input, mysqli $databaseLink): string
{
    $input = strip_tags($input); // Remove HTML tags, preventing abuse
    $input = $databaseLink->real_escape_string($input); // Prevent SQL injections
    return $input;
}

/** Returns whether the input is empty or not. */
function isPresent(string $input): bool
{
    return !empty($input);
}

/** Returns whether the input is longer that maxLength. */
function isLongerThan(string $input, int $maxLength): bool
{
    return strlen($input) > $maxLength;
}

/** Returns whether the input is shorter than minLength. */
function isShorterThan(string $input, int $minLength): bool
{
    return strlen($input) < $minLength;
}

/**
 * Process the registration form.
 *
 * @param array $inputArray Either $_GET or $_POST, depending on what
 * the form method was.
 */
function processRegisterForm(array $inputArray)
{
    $databaseLink = new mysqli("localhost", "root", "", "NeatTreats");
    $firstName = sanitize($inputArray["first_name"], $databaseLink);
    $lastName = sanitize($inputArray["last_name"], $databaseLink);
    $databaseLink->close();

    $formErrors = [];
    if (isPresent($firstName)) { // optional field - only check if specified
        if (isLongerThan($firstName, 20)) { // length check
            $formErrors["first_name"] = ERROR_TOO_LONG . "20";
            // error message will specify it was longer than 20
        }
    }
    if (!isPresent($lastName)) { // presence check
        $formErrors["last_name"] = ERROR_EMPTY;
    }

    $addedSuccessfully = false;
    if (empty($formErrors)) {
        $databaseLink = new mysqli("localhost", "root", "", "NeatTreats");
        $databaseLink->query(
            "INSERT INTO Customer(FirstName, LastName)" .
            "VALUES ($firstName, $lastName);"
        );
        // No error means added succesfully!
        if (empty($databaseLink->error)) $addedSuccessfully = true;
        $databaseLink->close(); // Always remember to close the database.
    }

    if ($addedSuccessfully) {
        /* I have provided 3 methods of automatic redirection below.
        Also provide a link just in case the automatic redirection
        fails. */
        echo "<p>You were added to our database!</p>";
        echo "<script>window.location='on_success.php';</script>"; // Javascript redirection
        // header("Location: on_success.php"); // Header redirection
        // echo "<meta http-equiv='refresh' content='0;url=on_success.php'>"; // HTML redirection
        echo "<a href='on_success.php'>Continue</a>";
    } else {
        // Redirect back to form page.
        echo "<p>Could not add record due to invalid input</p>";
        echo "<script>window.location='index.php';</script>";
        echo "<a href='index.php'>Try again</a>";

        /* Set form errors and last inputs by a cookie.
        json_encode is so the cookie sent is a string. It should be
        decoded at the other end.
        time() + 3600 makes the cookie expire in 3600 seconds (1 hour)
        from now.
        Set the cookie path to "/" so that it can be accessed from
        the form on the other page. */
        setcookie("formErrors", json_encode($formErrors), time() + 3600, "/");
        setcookie("lastInput", json_encode($inputArray), time() + 3600, "/");
    }
}
?>

<html><body><?php processRegisterForm($_POST); ?></body></html>
