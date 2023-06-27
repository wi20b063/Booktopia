<?php include (dirname(__FILE__, 3) . "\Backend\logic\session.php");?>


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

                <div class="invoiceCustomerData">
                    <p id="invoiceCustomerSalutation"></p>
                    <p id="invoiceCustomerName"></p>
                    <p id="invoiceCustomerAddress"></p>
                    <p id="invoiceCustomerZIPLocation"></p>
                </div>


                <div class="invoiceDetails">
                    <p id="invoiceDate"></p>
                    <p id="invoiceNumber"></p>
                    <p id="customerId"></p>
                </div>

                <h1>Rechung</h1>

                <div>

                    <table class="table tableInvoiceDetails">
                        <thead>
                            <tr>
                                <th scope="col">Pos.</th>
                                <th scope="col">Artikel-Nr.</th>
                                <th scope="col">Bezeichnung</th>
                                <th scope="col">Einzelpreis</th>
                                <th scope="col">Menge</th>
                                <th scope="col">Gesamtpreis</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceTableBody">
                            <tr>
                                <th scope=" row">1</th>
                                <td>1234</td>
                                <td>Der mit dem Wolf tanzt</td>
                                <td>16 EUR</td>
                                <td>1</td>
                                <td>16 EUR</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>1111</td>
                                <td>Winnie Puuh</td>
                                <td>5 EUR</td>
                                <td>2</td>
                                <td>10 EUR</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>3214</td>
                                <td>Das Haus am See</td>
                                <td>12 EUR</td>
                                <td>1</td>
                                <td>12 EUR</td>
                            </tr>
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