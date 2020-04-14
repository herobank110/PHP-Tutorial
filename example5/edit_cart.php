<?php 
$productID = $_GET["id"];
$editType = $_GET["type"];
$redirectUrl = $_GET["redirect_url"] ?? "cart.php";
$cart = json_decode($_COOKIE["cart"] ?? "{}", true);

switch ($editType) {
case "add":
    // Increment quantity of already added product.
    if (isset($cart[$productID])) $cart[$productID]++;
    // Otherwise put 1 quantity of product in cart.
    else $cart[$productID] = 1;
    break;
case "sub":
    if (!isset($cart[$productID])) break;
    // Decrement quantity of already added product
    $cart[$productID]--;
    if ($cart[$productID] <= 0)
        // If quantity is now 0, remove from cart.
        unset($cart[$productID]);
    break;
case "rem":
    unset($cart[$productID]);
    break;
}

setcookie("cart", json_encode($cart), time() + 3600 * 24 * 10, "/");
header("Location: $redirectUrl");

?>
