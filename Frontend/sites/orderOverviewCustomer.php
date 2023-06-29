<!DOCTYPE html>

<html lang="EN">

<head>

    <?php include "components/head.php";?>

    <title>Booktopia | Kunden Bestellübersicht</title>

</head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
        <?php include "components/navBar.php";?>
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

    <footer class=" py-3 my-4 fixed-bottom">
        <?php include "components/footer.php";?>
    </footer>

</body>

</html>