<!DOCTYPE html>

<html lang="EN">

<head>

    <?php include_once "components/head.php";?>
    <?php include_once "../../Backend/logic/sessionShoppingCart.php";?>

    <title>Booktopia | Kunden Bestellübersicht</title>

</head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
        <?php include_once "components/navBar.php";?>
    </nav>

    <main>
        <div class="content">
            <div class="container">
                <h1 class="headline">Bestellübersicht</h1>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <!-- Bucherbestellung -->
                            <th scope="col">Bestellnummer</th>
                            <th scope="col">Bestelldatum</th>
                            <th scope="col">Lieferdatum</th>
                            <th scope="col">Lieferadresse</th>
                            <th scope="col">Lieferstatus</th>
                            <th scope="col">Bestellmenge</th>
                            <th scope="col">Gesamtpreis</th>
                            <th scope="col">Details</th>
                            <th scope="col">Rechnung</th>
                        </tr>
                    </thead>
                    <tbody id="orderOverviewCustomerTableBody">
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <footer class="py-3 my-4">
        <?php include_once "components/footer.php";?>
    </footer>

</body>

</html>