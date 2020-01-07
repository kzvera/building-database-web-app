<?php include('includes/header.php'); ?>


<?php

//Include functions
include 'includes/functions.php';

?>



<?php 

/****************Get  customer info to ajax *******************/

///require database class files
require('includes/pdocon.php');

//instatiating our database objects
$db = new Pdocon;


//write a stametment that will check if a field name coming in from the ajax post is set and then Create a query to update user // You must bind the id coming in from the ajax data
if (isset($_POST['c_id'])) {

    //Get the id and keep it in a variable from the ajax
    $id = $_POST['c_id'];

    $raw_amount = cleandata($_POST['salary']);
    $c_amount = validate_int($raw_amount);
    
    $db->query('UPDATE users SET Spending_Amt=:spending_amt WHERE id=:id');
    

    //Bind your id
    $db->bindvalue(':spending_amt', $c_amount, PDO::PARAM_INT);
    $db->bindvalue(':id', $id, PDO::PARAM_INT);

    //Execute and keep the execution result in a row variable
    $row = $db->execute();

    //send echo message to ajax
    if($row){

            echo "<p class='bg-success text-center' style='font-weight:bold;'>User Updated </p>";

        }
}

?>