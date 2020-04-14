<?php
// Function definitions

/** Sanitizes and validates checkout form input
 * 
 * @param array $inputArray $_POST or $_GET array. Will be sanitized in place.
 * @param mysqli $databaseLink Link to sanitize SQL injections. If null, SQL
 * injections will not be protected.
 * @return boolean Whether all input is valid.
 */
function isCheckoutInputValid(&$inputArray, mysqli $databaseLink=null): bool
{
    foreach ($inputArray as $name => $input) {
        $input = strip_tags($input);
        if ($databaseLink !== null) $input = $databaseLink->real_escape_string($input);
        // Propagate changes back into array.
        $inputArray[$name] = $input;
    }

    $email = $inputArray["email"] ?? "";
    $cardNum = $inputArray["card_num"] ?? "";
    $expiryMonth = $inputArray["expiry_month"] ?? "";
    $expiryYear = $inputArray["expiry_year"] ?? "";

    // This validation example will not save each individual error. Refer
    // to example 3 for making specific error messages.
    return (
        !empty($email) // presence check
        && filter_var($email, FILTER_VALIDATE_EMAIL) // format: x@y.z
        && !empty($cardNum) // presence
        && (int)$cardNum != null // type: int (Also fails for $cardNum = "0")
        && (int)$cardNum > 0 // range: greater than 0
        && strlen($cardNum) >= 12 // length: between 12 and 16
        && strlen($cardNum) <= 16
        && !empty($expiryMonth) // presence
        && (int)$expiryMonth != null // type: int
        && (int)$expiryMonth >= 1 // range: between 1 and 12
        && (int)$expiryMonth <= 12
        && !empty($expiryYear) // presence
        && (int)$expiryYear != null // type: int
        && (int)$expiryYear >= 2020 // range: between 2020 and 3000
        && (int)$expiryYear <= 3000
    );
}

/** Save the cart into the database via the given link. */
function saveCartToDatabase(array $cart, mysqli $databaseLink): bool
{
    // Can't checkout with an empty cart!
    if (count($cart) == 0) return false;

    // Get the next valid auto increment CartID
    $databaseLink->query("INSERT INTO Cart (ProductID, Quantity) VALUES (1, 0);");
    if ($databaseLink->errno != 0) return false;
    $nextCartID = $databaseLink->insert_id;
    $databaseLink->query("DELETE FROM Cart WHERE CartID=$nextCartID;");
    if ($databaseLink->errno != 0) return false;

    // Add the cart rows to the database.
    $saveCartQuery = "INSERT INTO Cart (CartID, ProductID, Quantity) VALUES ";
    $isFirst = true;
    foreach ($cart as $productID => $quantity) {
        if ($isFirst) $isFirst = false;
        else $saveCartQuery .= ", ";
        $saveCartQuery .= "($nextCartID, $productID, $quantity)";
    }
    $saveCartQuery .= ";";

    $databaseLink->query($saveCartQuery);
    if ($databaseLink->errno != 0) return false;
    return true;
}

/** Send an invoice to the customer about the specified order. */
function sendInvoice(array $cart, array $inputArray): bool
{
    // Extract inputs from input array. Should have already been validated.
    $customerEmail = $inputArray["email"];
    $cardNum = $inputArray["card_num"];
    $expiryMonth = $inputArray["expiry_month"];
    $expiryYear = $inputArray["expiry_year"];

    $emailSubject = "Invoice of your order from Neat Treats";
    $emailHeaders = (
        "From: neattreats.sender@gmail.com\r\n" .
        "Content-Type: text/html; charset=ISO-8859-1\r\n"
    );

    $productsGrid = "";
    foreach($cart as $productID => $quantity)
        $productsGrid .= "<p>Product #$productID x$quantity</p>";

    $emailBody = (
        "<html>" .
            "<body>" .
                "<h2>Order Confirmation</h2>" .
                "<p>Thanks for your order! We hope you enjoy it a lot.</p>" .
                
                "<h4>Products:</h4>" .
                "<div style='padding-left:10px'>" .
                    $productsGrid .
                "</div>" .
                
                "<h4>Payment Method:</h4>" .
                "<div style='padding-left:10px'>" .
                    "<p>Card Number: $cardNum</p>" .
                    "<p>Expires: $expiryMonth / $expiryYear</p>" .
                "</div>" .
            "</body>" .
        "</html>"
    );
    return mail($customerEmail, $emailSubject, $emailBody, $emailHeaders);
}

// Performs actual script when page is opened:
(function () {
    $cart = json_decode($_COOKIE["cart"] ?? "{}", true);
    $databaseLink = new mysqli("localhost", "root", "", "NeatTreats");

    $allSuccess = false;
    $error = "";
    if (count($cart) > 0)
        if (isCheckoutInputValid($_POST))
            if (saveCartToDatabase($cart, $databaseLink))
                if (sendInvoice($cart, $_POST))
                    $allSuccess = true;
                else $error = "Couldn't send invoice";
            else $error = "Couldn't save to database";
        else $error = "Invalid checkout input";
    else $error = "Cart is empty";

    $databaseLink->close();

    if ($allSuccess) {
        setcookie("cart", "", time() - 3600, "/");
        header("Location: order_confirm.php");
    } else {
        setcookie("checkout_error", $error, time() + 3600, "cart.php");
        header("Location: cart.php");
    }
})();
?>
