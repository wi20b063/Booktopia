<!DOCTYPE html>

<html lang="EN">

<head>

    <?php include "components/head.php";?>

    <title>Booktopia | Rechnung</title>

</head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
        <?php include "components/navBar.php";?>
    </nav>

    <main>
        <div class="content">
            <div class="container">

                <div id="invoiceCustomerData">
                    <p id="invoiceCustomerSalutation"></p>
                    <p id="invoiceCustomerName"></p>
                    <p id="invoiceCustomerAddress"></p>
                    <p id="invoiceCustomerZIPLocation"></p>
                </div>


                <div class="invoiceDetails">
                    <p id="invoiceDate"></p>
                    <p id="invoiceNumber"></p>
                    <p id="invoiceCustomerId"></p>
                    <p id="invoiceOrderId"></p>
                    <p id="invoiceDeliveryDate"></p>
                </div>

                <h1>Rechung</h1>

                <div>

                    <table class="table tableInvoiceDetails">
                        <thead>
                            <tr>
                                <th scope='col'>Pos</th>
                                <th scope='col'>ISBN</th>
                                <th scope='col'>Titel</th>
                                <th scope='col'>Autor</th>
                                <th scope='col'>Einzelpreis</th>
                                <th scope='col'>Menge</th>
                                <th scope='col'>Zeilensumme</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceTableBody">
                        </tbody>
                    </table>

                    <table class="table tableInvoiceCalculation">
                        <tbody id="tableInvoiceCalculationBody">
                            <tr>
                                <th scope="row">Zwischensumme</th>
                                <td id="subtotal"></td>
                            </tr>
                            <tr>
                                <th scope="row">10% MwSt.</th>
                                <td id="tax"></td>
                            </tr>
                            <tr>
                                <th scope="row">Gesamtsumme</th>
                                <td id="total"></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </main>

    <footer class="py-3 my-4 fixed-bottom">
        <?php include "components/footer.php";?>
    </footer>

</body>

</html>