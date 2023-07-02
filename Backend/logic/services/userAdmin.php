<?php
/* $path = $_SERVER['DOCUMENT_ROOT'];
require_once ($path . '/backend/logic/session.php');
require_once ($path . '/Backend/logic/services/userService.php');
require_once ($path . '/backend/models/paymentType.php'); */


class adminUser {
    private $con;
    private $tbl_user;
    private $tbl_payment_items;

    public function __construct($con, $tbl_user, $tbl_payment_items) {
        $this->con = $con;
        $this->tbl_user = $tbl_user;
        $this->tbl_payment_items = $tbl_payment_items;
    }
    

    // Add a new user with administrative privileges
    public function addUser($user) {
        // Set the admin flag to 1
        $user->admin= 1;
        $user->active=1;

        // Insert the user into the database
        $sql = "INSERT INTO {$this->tbl_user} (salutation, firstName, lastName, address, postcode, location, email, username, password, active, admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("ssssssssssii", $user->salutation, $user->firstName, $user->lastName, $user->address, $user->postcode, $user->location,  $user->email, $user->username, $user->password, $user->active, $user->admin);
        $stmt->execute();
        $result = $stmt->affected_rows;
        

        if ($result == 1) {
            echo "User created successfully.";
            $last_id = $stmt->lastInsertId();  //need DB issued ID for inserting payment Info

        } else {
            echo "Error creating user.";
            return($result);
        }
        $stmt->close();

        
        return($result);
    }

    // Update an existing user with administrative privileges
    public function updateUser($user,  $mode) {
        // Set admin flag to 1 for admin privileges
        $user=(object) $user;
        //$user->admin = 1;
        //$user->active=$active;
        if(($mode=="userSrv") && (strlen($user->password)>7)){ //userSrv called and new password was provided for update
            $sql = "UPDATE {$this->tbl_user} SET salutation = ?, firstName = ?, lastName = ?, address = ?, postcode = ?, location = ?,  email = ?, username = ?, password = ?, active = ?, admin = ? WHERE userid = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("sssssssssiii", $user->salutation, $user->firstName, $user->lastName, $user->address, $user->postcode, $user->location, $user->email, $user->username, $user->password, $user->active, $user->admin, $user->userId);
        }
        else { //adminSrv or no new password was provided by user
            $sql = "UPDATE {$this->tbl_user} SET salutation = ?, firstName = ?, lastName = ?, address = ?, postcode = ?, location = ?,  email = ?, username = ?,  active = ?, admin = ? WHERE userid = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("ssssssssiii", $user->salutation, $user->firstName, $user->lastName, $user->address, $user->postcode, $user->location, $user->email, $user->username,  $user->active, $user->admin, $user->userId);
        }
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
     // NOTE: DATABASE contraints take care of deleting payment-Options from a used when user is deleted. (-> ON DELETE CASCADE option for the foreign key)
   
    public function deleteUser($userId) {
        // Delete the user from the database
        $sql = "DELETE FROM {$this->tbl_user} WHERE userid = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->affected_rows;
        $stmt->close();

        if ($result >= 1) {
            echo "User deleted successfully.";


            return true;
        } else {
            echo "Error deleting user.";
            return false;
        }
    }

       
    public function deleteSinglePaymentMethodby($userId, $itemId){
        // Delete all payment info from  user
        $sql = "DELETE FROM {$this->tbl_payment_items} WHERE userid = ? and  userPayMethodId = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("ii", $userId, $itemId);
        $stmt->execute();
        $result = $stmt->affected_rows;
        $stmt->close();

        if ($result > 0) {
            echo $result . "Payment info successfully deleted.";
            return $result;
        } else {
            echo "No Payment info deleted";
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