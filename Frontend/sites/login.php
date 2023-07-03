<!DOCTYPE html>

<html lang="EN">

<head>

    <?php include_once "components/head.php";?>
    <?php include_once "../../Backend/logic/sessionShoppingCart.php";?>


    <title>Booktopia | Login</title>

</head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
        <?php include_once "components/navBar.php";?>
    </nav>

    <main>
        <div class="content">

            <!-- Login Form with Bootstrap-->
            <div class="container">

                <h1 class="headline">Login</h1>

                <div class="row col-md-6">

                    <form class="data-form" id="loginForm" action="login.php" method="post" autocomplete="on">
                        <!-- <form class="data-form" id="loginForm" action="../../Backend/logic/services/userService.php"
                            method="post" autocomplete="on"> -->


                        <!-- Show msg via JavaScript with append or prepend-->


                        <div class="mb-4">
                            <label for="username" class="form-label">Benutzername:</label>
                            <input type="text" id="usernameLogin" name="username" autocomplete="username"
                                placeholder="Benutzername" class="form-control">
                            <!--required-->
                            <!--error message if username is empty after clicking on the submit-->
                            <!--add error message via JavaScript with append-->
                        </div>

                        <div class="mb-4">
                            <label for="current-password" class="form-label">Passwort:</label>
                            <input type="password" id="passwordLogin" name="password" placeholder="Passwort"
                                class="form-control">
                            <!--required-->
                            <!--error message if password is empty after clicking on the submit-->
                            <!--add error message via JavaScript with append-->
                        </div>

                        <div class="mb-4">
                            <input type="checkbox" id="rememberMeLogin" name="rememberMeLogin">
                            <label for="rememberMeLogin">Login merken</label>
                        </div>

                        <div class="mb-4 errors" id="errorLogin"></div>

                        <div class="mb-5">
                            <button type="button" name="btnSubmitLogin" class="btn btn-primary"
                                id="btnSubmitLogin">Bestätigen</button>
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