<!DOCTYPE html>

<html lang="EN">

<head>

    <?php 
    
    //path var necessary to overcome include relative path of an included file from different folder
    $path = $_SERVER['DOCUMENT_ROOT'];
    require_once ($path."/Frontend/sites/components/head.php");
    //require_once ($path."/Frontend/sites/components/head2.php");
    require_once ($path. '/Backend/models/user.php');
    require_once ($path . '/Backend/logic/services/admin_manageUsers.php');
    require_once ($path . '/Backend/logic/services/userService.php');
    require_once ($path . '/Backend/logic/services/orderService.php');
    ?>
    <title>Booktopia | Userverwaltung</title>

</head>

<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
        <?php include "components/navBar.php";?>
    </nav>
    <main>
<body>
<!-- --------This block is inserted by the Ajax result -------- -->
    <div class="component" name="dynComponentDiv" id="dynComponentDiv" >
         
        
    </div> 
<!-- -------- End of  Ajax result insert block ---------->               
    </main>

    <footer class="py-3 my-4 fixed-bottom">
        <?php include "components/footer.php";?>
    </footer>
<script type="text/javascript">


       $(document).ready(function() {
        var users;
        getUserData("dynComponentDiv");

      }); 


function filterUserTable(filter, tableid, col1, col2){
    filter = filter.toUpperCase();
  table = document.getElementById(tableid);
  tr = table.getElementsByTagName("tr");
  len=tr.length;
  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td1 = tr[i].getElementsByTagName("td")[col1];
    td2 = tr[i].getElementsByTagName("td")[col2];
    if (td1 && td2) {
      txtValue1 = td1.textContent || td1.innerText;
      txtValue2 = td2.textContent || td2.innerText;
      if (txtValue1.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1 ) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
    </script>
</body>

</html>