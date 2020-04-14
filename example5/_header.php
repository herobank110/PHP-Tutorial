<header>
<?php
function getCartCount()
{
    $cart = json_decode($_COOKIE["cart"] ?? "{}", true);
    $cartCount = 0;
    foreach ($cart as $productID => $quantity)
        $cartCount += $quantity;
    return $cartCount;
}
?>

    <a class="typography headline" href="index.php">Neat Treats</a>
    <a class="typography headline" href="index.php">Products</a>
    <a class="typography headline" href="cart.php">Cart (<?=getCartCount()?>)</a>
</header>
