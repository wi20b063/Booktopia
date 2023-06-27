<?php
require(dirname(__FILE__, 4) . "\logic\session.php");

$error = []; //declaring an array to store the error messages
$error["username"] = false; //making sure the error message is not shown at the beginning (in case of a previous error - defensive programming)
$error["current"] = false;
$error["save"] = false;

$msg = "";

//the following code is executed when the user clicks on the submit button
if ((isset($_POST['submit']))) { //&& ($_SERVER["REQUEST_METHOD"] == "POST")
    if (empty($_POST["username"])) {
        $error["username"] = true;
    }
    if (empty($_POST["password"])) {
        $error["password"] = true;
    }
    if (isset($_POST["save"])) {
        $error["save"] = true;
    }
    if ((!empty($_POST["username"])) && (!empty($_POST["password"]))) {
        $cookie_name = "username";
        $cookie_value = $_POST["username"];
        setcookie($cookie_name, $cookie_value, time() + (86400 * 30)); // 86400 = 1 day / secure, http only

        // connect to database
        if (!$con) {
            die('Error connecting to login database: ' . mysqli_error($con));
        }
        //verify login data with database
        //first strip and clean the user input (maybe not needed with perpared statement)
        $username = mysqli_real_escape_string($con, $_REQUEST['username']);
        $password = mysqli_real_escape_string($con, $_REQUEST['password']);

        //run  the SQL query (in import) but already hash the password

        $hashedPassword = hash("sha512", $password);
        query_UserData($username, $hashedPassword);

        header('Refresh: 0; URL = index.php');
        /* echo "<div class='row col-8'>
        <H1> SUCCESS....</H1>
        </div>"; */

        //}
    }
}

//function to query the database for user data & write it into SESSION variables
function query_UserData($username, $hashedPassword) {

    //global variables needed for DB access - they are defined already but we need to 
    //declare them again as global variables to be able to use them in this function
    global $tbl_user, $con;
    //building the s SQL query with prepared sqli statement
    $sqlLogin = "SELECT * FROM $tbl_user WHERE username= ? AND password= ?";
    $stmt = $con->prepare($sqlLogin);
    $stmt->bind_param("ss", $username, $hashedPassword);
    $stmt->execute();

    $result = mysqli_stmt_get_result($stmt); // returns a result-Set

    if ($result->num_rows == 1) { // if we have 1 row as result, the user login was successful
        // getting data into SESSION variable for later. 
        $row = mysqli_fetch_assoc($result);
        //check if the user is active or not --> inactiv users can not login
        if ($row["active"] != "1") {
            echo "<script>alert('Ihr Account wurde gesperrt. Bitte wenden Sie sich an den Administrator');</script>";
        } else {
            $_SESSION["admin"] = $row["admin"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["password"] = $row["password"];
            $_SESSION["userid"] = $row["userid"];
        }

    } else {
        //the user login was not successful, the username was not found in the database or the password was wrong
        echo "<script>alert('Username oder Password ungültig');</script>";
    }
}
?>

<!DOCTYPE html>

<html lang="EN">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="description" content="Booktopia - Der online Buchstore für vielfältige Leser. ....">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow" />

    <!-- Bootstrap 5.3 Stylesheet CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <!-- Local Stylesheets -->
    <link rel="stylesheet" href="./Frontend/res/css/myStyles.css">

    <!-- jQuery Core CDN -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

    <!-- jQuery UI CDN -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"
        integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>

    <!-- Local JavaScripts -->
    <script src="./Frontend/js/myFunctions.js" async defer></script>

    <!-- Bootstrap 5.3 JavaScript Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>

    <title>Booktopia</title>

</head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
    </nav>

    <main>
        <!-- Login Form with Bootstrap-->
        <div class="container">
            <h1 class="headline">Login</h1>
            <div class="row col-md-6">
                <form class="data-form" action="login.php" method="post" autocomplete="on">

                    <!-- <?php if ($msg != "") { ?>
                            <div class="row g-3">
                                <div class="col-md-6 mb-4" style="background-color:lightgrey"><?php echo $msg; ?></div>
                            </div>
                        <?php } ?> -->

                    <div class="mb-3">
                        <label for="username" class="form-label">Username (E-Mail-Adresse):</label>
                        <input type="text" id="username" name="username" autocomplete="username" placeholder="Username"
                            class="form-control">
                        <!--required-->
                        <!--error message if username is empty after clicking on the submit-->
                        <!--the text in the following echo is inserted into the html code rihgt after checking if 
                        the username empty is true-->
                        <!-- <?php if ($error["username"])
                                echo "<div>Please enter Username!</div>" ?> -->
                    </div>

                    <div class="mb-3">
                        <label for="current-password" class="form-label">Passwort:</label>
                        <input type="password" id="password" name="password" placeholder="Passwort"
                            class="form-control">
                        <!--required-->
                        <!-- <?php if ($error["current-password"])
                            echo "<div>Please enter Password!</div>" ?> -->
                    </div>

                    <div class="mb-3">
                        <input type="checkbox" id="save" name="save">
                        <label for="save">Login merken</label>
                        <!-- <?php if ($error["save"])
                            echo "<div>Error</div>" ?> -->
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">Bestätigen</button>

                </form>

            </div>
        </div>

    </main>

    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
        <div id="footer"></div>
    </footer>



</body>

</html>