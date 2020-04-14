<html>
    <head> <link rel="stylesheet" href="style.css"> </head>
    <body>
        <?php include("_header.php"); ?>
        <main>
            <h2 class="typography subhead">Cart Page</h2>

            <div class="list-grid cart-list-grid">
                <?php
                $cart = json_decode($_COOKIE["cart"] ?? "{}", true);

                $databaseLink = new mysqli("localhost", "root", "", "NeatTreats");
                foreach ($cart as $productID => $quantity) {
                    $result = $databaseLink->query(
                        "SELECT Name FROM Product WHERE ProductID=$productID;"
                    );
                    if (empty($databaseLink->error) && $result->num_rows > 0) {
                        $row = $result->fetch_object();
                        $productName = $row->Name;
                    } else {
                        $productName = "INVALID ID";
                    }
                    echo "<span class='typography body'>{$row->Name}</span>";
                    echo "<span class='typography body'> x$quantity</span>";
                    $editUrl = "edit_cart.php?id=$productID";
                    echo "<a class='typography body' href='$editUrl&type=add'>Add</a>";
                    echo "<a class='typography body' href='$editUrl&type=sub'>Sub</a>";
                    echo "<a class='typography body' href='$editUrl&type=rem'>Remove</a>";
                }
                $databaseLink->close();
                ?>
            </div>

            <form action="on_checkout.php" method="post">
                <div class="list-grid checkout-list-grid">
                    <label class='typography body'>Email Address:</label> <input name="email">
                    <label class='typography body'>Card Number:</label> <input name="card_num">
                    <label class='typography body'>Expiry Month:</label>
                    <select name="expiry_month">
                        <option selected disabled></option>
                        <?php
                        for ($i=1; $i < 13; $i++)
                            echo "<option value=$i>". date("F", 3600 * 24 * 28 * $i) ."</option>";
                        ?>
                    </select>
                    <label class='typography body'>Expiry Year:</label> <input name="expiry_year">
                    <div> <button>Checkout</button> </div>
                    <span class="error">
                        <?php
                        if (isset($_COOKIE["checkout_error"])) {
                            // Output and expire the checkout error cookie.
                            echo $_COOKIE["checkout_error"];
                            setcookie("checkout_error", "", time() - 3600);
                        }
                        ?>
                    </span>
                </div>
            </form>
        </main>
    </body>
</html>
