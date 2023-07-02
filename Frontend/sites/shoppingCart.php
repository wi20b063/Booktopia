<!DOCTYPE html>
<html lang="EN">

<head>
    <?php include "components/head.php";?>
    <?php include "../../Backend/logic/sessionShoppingCart.php";?>

    <title>Warenkorb</title>

</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
        <?php include "components/navBar.php";?>
    </nav>

    <main>
        <div class="content">
            <div class="container">
                <h1 class="headline">Warenkorb</h1>

                <?php if (count($cart) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produkt</th>
                                <th>Preis</th>
                                <th>Menge</th>
                                <th>Gesamt</th>
                                <th>Aktion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart as $productId): ?>
                                <?php $product = getProductDetails($con, $productId); ?>
                                <?php if ($product): ?>
                                    <tr>
                                        <td><?php echo $product['titel']; ?></td>
                                        <td><?php echo $product['preis'] . "€"; ?></td>
                                        <td><?php echo $product['quantity']. " Stück"; ?></td>
                                        <td><?php echo $product['preis'] * $product['quantity'] . "€"; ?></td>
                                        
                                        <td>
                                            <form method="POST" action="../../Backend/logic/services/remove_from_Cart.php">
                                                <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                                <button type="submit" class="btn btn-danger" name="remove_from_Cart">Entfernen</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"><strong>Gesamtsumme</strong></td>

                                <td>
                                    <?php
                                    $totalquantity = 0;
                                    foreach ($cart as $productId) {
                                        $product = getProductDetails($con, $productId);
                                        if ($product) {
                                            $totalquantity += $product['quantity'];
                                        }
                                    }
                                    echo $totalquantity . " Stück";
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    $total = 0;
                                    foreach ($cart as $productId) {
                                        $product = getProductDetails($con, $productId);
                                        if ($product) {
                                            $total += $product['preis'] * $product['quantity'];
                                        }
                                    }
                                    echo $total . "€";
                                    ?>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="text-center">
                        <a href="" class="btn btn-secondary">Zur Kasse gehen</a>
                    </div>
                <?php else: ?>
                    <p>Der Warenkorb ist leer.</p>
                <?php endif; ?>

                <?php $con->close(); ?>
            </div>
        </div>
    </main>

    <footer class="py-3 my-4">
        <?php include "components/footer.php";?>
    </footer>
</body>

</html>