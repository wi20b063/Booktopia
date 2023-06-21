<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once ($path . '/backend/logic/session.php');
require_once ($path . '/Backend/logic/services/userService.php');


class adminUser {
    private $con;
    private $tbl_user;

    public function __construct($con, $tbl_user) {
        $this->con = $con;
        $this->tbl_user = $tbl_user;
    }

    // Add a new user with administrative privileges
    public function addUser($user) {
        // Set the admin flag to 1
        $user->admin= 1;
        $user->active=1;

        // Insert the user into the database
        $sql = "INSERT INTO {$this->tbl_user} (salutation, firstName, lastName, address, postcode, location, creditCard, email, username, password, active, admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("ssssssssssii", $user->salutation, $user->firstName, $user->lastName, $user->address, $user->postalCode, $user->location, $user->creditCard, $user->email, $user->username, $user->password, $user->active, $user->admin);
        $stmt->execute();
        $result = $stmt->affected_rows;

        if ($result == 1) {
            echo "User created successfully.";
        } else {
            echo "Error creating user.";
        }

        $stmt->close();
        return($result);
    }

    // Update an existing user with administrative privileges
    public function updateUser($userId, $user) {
        // Set admin flag to 1 for admin privileges
        //$user->admin = 1;
        //$user->active=$active;
        $sql = "UPDATE {$this->tbl_user} SET salutation = ?, firstName = ?, lastName = ?, address = ?, postcode = ?, location = ?, creditCard = ?, email = ?, username = ?, password = ?, active = ?, admin = ? WHERE userid = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("ssssssssssiii", $user->salutation, $user->firstName, $user->lastName, $user->address, $user->postcode, $user->location, $user->creditCard, $user->email, $user->username, $user->password, $user->active, $user->admin, $userId);
        $stmt->execute();
        $result = $stmt->affected_rows;

        if ($result == 1) {
            echo "User updated successfully.";
        } else {
            echo "Error updating user.";
        }
        $stmt->close();
        return($result);
    }

    // Delete a user from the system
    public function deleteUser($userId) {
        // Delete the user from the database
        $sql = "DELETE FROM {$this->tbl_user} WHERE userid = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->affected_rows;
        $stmt->close();

        if ($result == 1) {
            echo "User deleted successfully.";

            // Perform additional operations
            // Example: Update activity logs, trigger events

            return true;
        } else {
            echo "Error deleting user.";
            return false;
        }
    }


    public function getUserPayMethods(string $userID){
        $sql = "Select * from paymentItems WHERE userId = ?";
        $query = $this->con->prepare($sql);
        $query->bind_param("s", $userID);
        $query->execute();
        $result= $query->get_result();
        $i=0;
        $userPayMethods=[];
        while($resSet = mysqli_fetch_assoc($result)){
            $userPayMethods[$i++]= new PaymenMetType($resSet['userId'], $resSet['userPayMethodId'],$resSet['paymentMethod'],$resSet['payMethodDetail']);
        }
        return $userPayMethods;
    }
    // return all user info as array of user-Object)
    public function getUsers(){

        $sql = "Select * from {$this->tbl_user}";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $result= $stmt->get_result();
        $userObjArr=[];
        $i=0;
        while ($resSet=mysqli_fetch_assoc($result)){
            //get each user's payment methods adn details
            $payArrObj=$this->getUserPayMethods($resSet['userid']);

            $userObjArr[$i++]= new User($resSet['userid'],$resSet['salutation'],$resSet['firstName'],$resSet['lastName'],$resSet['address'],$resSet['postcode'],$resSet['location'],$payArrObj, $resSet['email'],$resSet['username'],$resSet['password'], $resSet['active'], $resSet['admin']);
        }
        
        $stmt->close();
        return($userObjArr);
    }

    


}
