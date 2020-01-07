<?php include('includes/header.php'); ?>


<?php

//Include functions
include 'includes/functions.php';

?>



<?php 

/****************Get  customer info to ajax *******************/

//Collect ID from ajax url
$id = $_GET['cid'];

//require database class files
require('includes/pdocon.php');

//instatiating our database objects
$db = new Pdocon;


//Create a query to select all users to display in the table
$db->query('SELECT * FROM users WHERE id=:id');

//write a statement to check if the customer id is coming in from the ajax request and write a function to send back the below report menu to ajax, you must bind using the customer id
$db->bindvalue(':id', $id, PDO::PARAM_INT);

//Fetch the result and keep in a rows variable
$row = $db->fetchSingle();


//Display this result to ajax
    if($row){
        
        echo '  <div  class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr >
                                <th class="text-center">Customer Name</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                           <tr class="text-center">
                            <td>' . $row['full_name'] . '</td>
                            <td>$ ' . $row['Spending_Amt'] . '</td>
                            <td>' . $row['email'] . '</td>
                          </tr>

                        </tbody>
                    </table>
                </div>';
    }



?>

