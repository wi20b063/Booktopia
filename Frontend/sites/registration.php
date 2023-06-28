<!DOCTYPE html>

<html lang="EN">

<head>

    <?php include "components/head.php";?>

    <title>Booktopia | Registrierung</title>

</head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
        <?php include "components/navBar.php";?>
    </nav>

    <main>
        <div class="content">

            <!-- Registration Form with Bootstrap-->
            <div class="container">

                <h1 class="headline">Registrierung</h1>

                <div class="row col-md-6">

                    <form class="data-form" id="registrationForm" action="registration.php" method="post"
                        autocomplete="on">

                        <div class="mb-4">
                            <label for="salutation">Anrede: *</label><br>
                            <select name="salutation" id="salutationRegistration" style=" margin-bottom: 25px;"
                                class="form-control">
                                <option disabled selected value> -- Anrede auswählen -- </option>
                                <option value="Frau">Frau</option>
                                <option value="Herr">Herr</option>
                                <option value="Divers">Divers</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="firstName">Vorname:</label>
                            <input type="text" name="firstName" id="firstNameRegistration" placeholder="Vorname"
                                class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label for="lastName">Nachname:</label>
                            <input type="text" name="lastName" id="lastNameRegistration" placeholder="Nachname"
                                class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label for="address">Adresse:</label><br>
                            <input type="text" name="address" id="addressRegistration" placeholder="Musterstraße 1/1/1"
                                class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label for="postcode">PLZ:</label><br>
                            <input type="int" name="postcode" id="postcodeRegistration" placeholder="1010"
                                class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label for="location">Ort:</label><br>
                            <input type="text" name="location" id="locationRegistration" placeholder="Wien"
                                class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label for="email">E-Mail-Adresse:</label><br>
                            <input type="email" name="email" id="emailRegistration" placeholder="musteremail@icloud.com"
                                class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label for="username">Benutzername:</label><br>
                            <input type="text" name="username" id="usernameRegistration" placeholder="Benutzername"
                                class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label for="password">Passwort:</label><br>
                            <input type="password" name="password" id="passwordRegistration" minlength="8"
                                class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label for="passwordConfirmed">Passwort bestätigen:</label><br>
                            <input type="password" name="passwordConfirmed" id="passwordConfirmedRegistration"
                                minlength="8" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label for="creditCard">Kreditkartennummer:</label><br>
                            <input type="int" name="creditCard" id="creditCardRegistration" placeholder="12345678"
                                class="form-control">
                        </div>

                        <div class="mb-4 errors" id="errorRegistration"></div>

                        <div class="mb-5">
                            <button type="button" name="btnSubmitRegistration" class="btn btn-primary"
                                id="btnSubmitRegistration">Registrieren</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </main>

    <footer class=" py-3 my-4">
        <?php include "components/footer.php";?>
    </footer>



</body>

</html>

</html>