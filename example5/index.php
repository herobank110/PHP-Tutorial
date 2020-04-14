<html>
    <head> <link rel="stylesheet" href="style.css"> </head>
    <body>
        <?php include("_header.php"); ?>
        <main>
            <h2 class="typography subhead">Products Page</h2>
            
            <div class="list-grid product-list-grid">
                <?php 
                    $databaseLink = new mysqli("localhost", "root", "", "NeatTreats");
                    $result = $databaseLink->query("SELECT * FROM Product LIMIT 200;");
                    if (empty($databaseLink->error)) {
                        while ($row = $result->fetch_object()) {
                            echo "<span class='typography body'>#{$row->ProductID}</span>";
                            echo "<img class='product-thumbnail' width='50' height='50' ";
                            echo "src='cake_images/cake_{$row->ProductID}.png' alt='cake image'>";
                            echo "<div>";
                            echo "<span class='typography body'>{$row->Name}</span>";
                            echo "<br>";
                            echo "<a class='typography body' href='edit_cart.php";
                            echo "?id={$row->ProductID}&type=add&redirect_url=index.php'>";
                            echo "Add to cart</a>";
                            echo "</div>";
                        }
                    }
                    $databaseLink->close();
                ?>
            </div>
        </main>
    </body>
</html>