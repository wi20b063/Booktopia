<?php include (dirname(__FILE__, 3) . "\Backend\logic\session.php");?>


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
                            <th scope="col">Lieferstatus</th>
                            <th scope="col">Lieferdatum</th>
                            <th scope="col">Lieferadresse</th>
                            <th scope="col">Gesamtpreis</th>
                            <th scope="col">Details</th>
                            <th scope="col">Rechnung</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>01.05.2023</td>
                            <td>Geliefert</td>
                            <td>10.05.2023</td>
                            <td>23 EUR</td>
                            <td><button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#showOrderDetails" name="showOrderDetails"
                                    id="showOrderDetails">Details</button>
                            </td>
                            <td><button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#showInvoiceCustomer" name="showInvoiceCustomer"
                                    id="showInvoiceCustomer">Rechnung
                                    drucken</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope=" row">2</th>
                            <td>26.05.2023</td>
                            <td>Storniert</td>
                            <td>02.06.2023</td>
                            <td>10 EUR</td>
                            <td><button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#showOrderDetails" name="showOrderDetails"
                                    id="showOrderDetails">Details</button>
                            </td>
                            <td><button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#showInvoiceCustomer" name="showInvoiceCustomer"
                                    id="showInvoiceCustomer">Rechnung
                                    drucken</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>20.06.2023</td>
                            <td>Offen</td>
                            <td>26.06.2023</td>
                            <td>13 EUR</td>
                            <td><button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#showOrderDetails" name="showOrderDetails"
                                    id="showOrderDetails">Details</button>
                            </td>
                            <td><button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#showInvoiceCustomer" name="showInvoiceCustomer"
                                    id="showInvoiceCustomer">Rechnung
                                    drucken</button>
                            </td>

                            <!-- Moda for order details -->
                            <div class="container" id="modalOrderDetails"></div>
                        </tr>
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