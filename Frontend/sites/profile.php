<!DOCTYPE html>

<html lang="EN">

<head>

    <?php include_once "components/head.php";?>
    <?php include_once "../../Backend/logic/sessionShoppingCart.php";?>

    <title>Booktopia - Mein Profil</title>

</head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
        <?php include_once "components/navBar.php";?>
    </nav>

    <main>
        <div class="content">
            <div class="container">
                <h1 class="headline">Mein Profil</h1>

                <div class="row col-md-6">

                    <form class="data-form" id="userProfileForm" action="#" method="post" autocomplete="on">

                        <div class="mb-4">
                            <label for="salutation">Anrede: *</label><br>
                            <select name="salutation" id="salutationProfile" style=" margin-bottom: 25px;"
                                class="form-control editableProfile" disabled>
                                <option disabled selected value> -- Anrede auswählen -- </option>
                                <option value="Frau">Frau</option>
                                <option value="Herr">Herr</option>
                                <option value="Divers">Divers</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="firstName">Vorname:</label>
                            <input type="text" name="firstName" id="firstNameProfile" placeholder="Vorname"
                                class="form-control editableProfile" disabled>
                        </div>

                        <div class="mb-4">
                            <label for="lastName">Nachname:</label>
                            <input type="text" name="lastName" id="lastNameProfile" placeholder="Nachname"
                                class="form-control editableProfile" disabled>
                        </div>

                        <div class="mb-4">
                            <label for="address">Adresse:</label><br>
                            <input type="text" name="address" id="addressProfile" placeholder="Musterstraße 1/1/1"
                                class="form-control editableProfile" disabled>
                        </div>

                        <div class="mb-4">
                            <label for="postcode">PLZ:</label><br>
                            <input type="int" name="postcode" id="postcodeProfile" placeholder="1010"
                                class="form-control editableProfile" disabled>
                        </div>

                        <div class="mb-4">
                            <label for="location">Ort:</label><br>
                            <input type="text" name="location" id="locationProfile" placeholder="Wien"
                                class="form-control editableProfile" disabled>
                        </div>

                        <div class="mb-4">
                            <label for="email">E-Mail-Adresse:</label><br>
                            <input type="email" name="email" id="emailProfile" placeholder="musteremail@icloud.com"
                                class="form-control editableProfile" disabled>
                        </div>

                        <div class="mb-4">
                            <label for="username">Benutzername:</label><br>
                            <input type="text" name="username" id="usernameProfile" placeholder="Benutzername"
                                class="form-control" disabled>
                        </div>

                        <div class="mb-4">
                            <label for="password">Passwort:</label><br>
                            <input type="password" name="password" id="passwordProfile" minlength="8"
                                class="form-control" disabled>
                        </div>

                        <!-- <div class="mb-4">
                            <label for="creditCard">Kreditkartennummer:</label><br>
                            <input type="password" name="creditCard" id="creditCardProfile" placeholder="12345678"
                                class="form-control editableProfile" disabled>
                        </div> -->

                        <div class="mb-4 errors" id="errorProfile"></div>

                        <div class="mb-5" id="buttonDivProfile">
                            <button type="button" name="btnEditProfile" class="btn btn-primary"
                                id="btnEditProfile">Bearbeiten</button>
                        </div>

                    </form>

                </div>


            </div>
        </div>

    </main>

    <footer class="py-3 my-4">
        <?php include_once "components/footer.php";?>
    </footer>

</body>

</html>