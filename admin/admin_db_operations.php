<?php
/**
 * Created by phpStorm
 * User: marcin
 * Date: 03/03/2017
 * Time: 22:28
 */
//Method to execute any queries on db
function executeQuery($query, $schema){
    // Create connection
    $conn = mysqli_connect('localhost', 'root', '', $schema);
    // Check connection
    if (!$conn) {
        die("<h2>DB Connection failed: " . mysqli_connect_error())."</h2>";
    }
    $result = mysqli_query($conn, $query);
    //if((!$result)){
    //    echo "<h2>SQL Error </h2></br>";
    //}
    return $result;
    mysqli_close($conn);
}
//******************************** Validate Login ********************************

//Page would call that method should validate that parameters are set:
// if(isset($_POST["login"]))
// if(isset($_POST["password"]))
function validateLogin($username, $password){
    $toReturn="";
    $sql = "SELECT * FROM `CarSales`.`Admins` WHERE login ='$username'";
    $result = executeQuery($sql, "CarSales");
    //Checks if there is exactly one record for a provided username
    if (mysqli_num_rows($result) <> 1) {
        $toReturn =  "incorrect username";
    } else {
        //Password validation
        while ($row = mysqli_fetch_assoc($result)) {
            if (md5($row['password']) != md5($password)) {
                $toReturn =  "incorrect password";
            } else {
                $toReturn =  "login successful";
            }
        }
    }
    return $toReturn;
}
//*************************** Checks if ID is already in use *************************
function isIdAlreadyUsed($id){
    //Converts id to uppercase
    $id = strtoupper($id);
    $checkId = "SELECT * FROM `CarSales`.`UsedCars` WHERE `id`='$id'";
    $result = executeQuery($checkId, "CarSales");
    //checks if the record with the provided id already exist in db
    if(mysqli_num_rows($result) != 0){
        return true;
    }
    else{
        return false;
    }
}
//******************************** Adds new car to DB ********************************
function addCar($id , $manufacturer, $model, $colour, $year, $type, $doors, $cc, $fuel, $email, $phone, $price, $desc){
    //Converts id to uppercase
    $id = strtoupper($id);
    //adds a record to db
    $addCar = "INSERT INTO `CarSales`.`UsedCars` (`id`, `manufacturer`, `model`, `colour`,
              `year`, `type`, `doors`, `cc`, `fuel`, `email`, `phone`, `price`, `description`) VALUES
               ('$id', '$manufacturer', '$model', '$colour', '$year', '$type',
                '$doors', '$cc', '$fuel', '$email', '$phone', '$price', '$desc')";
    executeQuery($addCar, "CarSales");
    echo "<li>
           Added Successfully -  '$id', '$manufacturer', '$model'
          </li>
        ";
}

//******************************** Delete a car from DB ********************************

function deleteCar($id){
    $deleteQuery = "Delete from `CarSales`.`UsedCars` WHERE `id`='$id'";
    $validateReturn = executeQuery($deleteQuery, "CarSales");
    if($validateReturn==1){
        return "Car was deleted successfully";
    }
    else{
        return "An error occurred, please try again later";
    }

}
//******************************** Update a record  ********************************

function update($oldID, $id , $manufacturer, $model, $colour, $year, $type, $doors, $cc, $fuel, $email, $phone, $price, $desc){
    $updateQuery = "UPDATE `CarSales`.`UsedCars` SET `id`='$id', `manufacturer`='$manufacturer', `model`='$model',
                    `colour`='$colour', `year`='$year', `type`='$type', `doors`='$doors', `cc`='$cc',
                    `fuel`='$fuel', `email`='$email', `phone`='$phone',`price`='$price', `description`='$desc'
                     WHERE `id`='$oldID'";

    $validateReturn =   executeQuery($updateQuery, "CarSales");
    if($validateReturn==1){
        return "Listing was updated";
    }
    else{
        return "An error occurred, please try again later";
    }
}

//******************************** Retrieves all cars from DB  ********************************

function getAllCarsInDB(){
    $getAllCars = "SELECT * FROM CarSales.UsedCars";
    displayListOfCars($getAllCars);
}

//******************************** Update a password  ********************************

function updatePassword($username, $newPass){
    $updateQuery = "UPDATE `CarSales`.`Admins` SET `password`='$newPass' WHERE `login`='$username'";
    //
    $validateReturn =  executeQuery($updateQuery, "CarSales");
    if($validateReturn==1){
        return "Password was updated";
    }
    else{
        return "An error occurred, please try again later";
    }
}


//********************** Displays list of cars to edit *********************
function displayListOfCars(){
    $getAllCars = "SELECT * FROM CarSales.UsedCars";
    $resultCars = executeQuery($getAllCars, "CarSales");
    if (mysqli_num_rows($resultCars) > 0) {
//TODO: form have to go to add_update_item.php first then to confirmation

        // output data of each row
        while ($row = mysqli_fetch_assoc($resultCars)) {

            //-------Cell element for edit_existing.php page ----------------------
            echo "<li>
                    <form name='type' action='add_update_item.php' method='POST'>
                          ".$row['id']."
                          $row[manufacturer]"." "."$row[model]
                          "." "."$row[colour]"." "."$row[year]"." "."$row[type].
                          <input name='id' value='".$row['id']."' hidden>
                          <input name='username' value=".$_POST['username']." hidden>
                          <input name='pass' value=".$_POST['pass']." hidden>
                          <input type='submit' name='updateDelete' value='update' class=\"primary\">
                          <form name='type' class ='feature equal center' action='confirmationScreen.php' method='POST'>
                                <input name='id' value='".$row['id']."' hidden>
                                <input name='username' value=".$_POST['username']." hidden>
                                <input name='pass' value=".$_POST['pass']." hidden>
                              <input type='submit' name='updateDelete' value='delete' class=\"danger\">
                          </form>
                        </form>
                      </li>";
            //-------------------------------------------------------------
        }

    }
    else {
        echo "0 results";
    }
}

//********************** Get a value of a given field for car with ID *********************
function getValue($id, $attribute){
    $getAttribute = "SELECT * FROM CarSales.UsedCars where id='$id'";
    $result = executeQuery($getAttribute, "CarSales");
    $value="";

        // output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            $value=$row[$attribute];
        }

    return $value;
}
