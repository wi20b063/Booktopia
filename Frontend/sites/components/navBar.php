<div class="container-fluid">
    <a class="navbar-brand " href="index.php">
        <img id="logo-nav" src="../res/img/logo-bookstopia.png" alt="Logo Bookstopia">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse " id="navbarSupportedContent">

        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item" id="navHome">
                <a class="nav-link active" aria-current="page" href="index.php">Home</a>
            </li>
            <li class="nav-item" id="navProducts">
                <a class="nav-link" href="#">Produkte</a>
            </li>
            <li class="nav-item" id="navAboutUs">
                <a class="nav-link" href="#">Über uns</a>
            </li>
            <li class="nav-item" id="navRegister">
                <a class="nav-link" href="../sites/registration.php">Registrierung</a>
            </li>
            
            <li class="nav-item dropdown" id="navAdminDropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarAdminDropdown" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">Admin Bereich</a>
                <ul class="dropdown-menu dropdown-menu-end" id="navbar-dropdown-menu" aria-labelledby="navbarAdminDropdown">
                    <li><a class="dropdown-item" id="navProductAdm" href=" ../sites/order_admin.php">Bestellungen verwalten</a></li>
                    <li><a class="dropdown-item" href="../sites/user_admin.php">Benutzer verwalten</a></li>
                    <li><a class="dropdown-item" href="../sites/product_admin.php">Produkte verwalten</a></li>
                    <li><a class="dropdown-item" href="../sites/voucher_admin.php">Gutscheine verwalten</a></li>

                </ul>
            </li>
<!--             <li class="nav-item" id="navManageProducts">
                <a class="nav-link" href="#">Produkte verwalten</a>
            </li>
            <li class="nav-item" id="navManageCustomers">
                <a class="nav-link" href="../sites/user_admin.php">Kunden verwalten</a>
            </li>
            <li class="nav-item" id="navManageVouchers">
                <a class="nav-link" href="#">Gutscheine verwalten</a>
            </li> -->
            <!-- <li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
            </li> -->
        </ul>


        <ul class="nav navbar-nav navbar-right">
            <form class="d-flex" role="search" id="navSearch">
                <input class=" form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            <li class="nav-item" id="navShoppingCart">
                <a class="nav-link" href="#">Warenkorb</a>
            </li>
            <!-- <li class="nav-item" id="navProfile">
                <a class="nav-link" href="#">Mein Konto</a>
            </li> -->
            <li class="nav-item dropdown" id="navMyAccountDropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">Mein Konto</a>
                <ul class="dropdown-menu dropdown-menu-end" id="navbar-dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" id="navProfile" href=" ../sites/profile.php">Profil</a></li>
                    <li><a class="dropdown-item" id="navOrders"
                            href="../sites/orderOverviewCustomer.php">Bestellungen</a></li>
                    <!-- <li><a class="dropdown-item" href="../sites/invoiceOverviewCustomer.php">Rechnungen</a></li> -->
                </ul>
            </li>
            <li class="nav-item" id="navLogin">
                <a class="nav-link" href="../sites/login.php">Login</a>
            </li>
            <li class="nav-item" id="navLogout">
                <a class="nav-link" href="#">Logout</a>
            </li>
        </ul>

    </div>
</div>
</div>
</div>