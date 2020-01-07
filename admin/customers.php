<?php include('includes/header.php'); ?>


<?php

//Include functions
include 'includes/functions.php';

//check to see if user if logged in else redirect to index page


?>

<?php 
/************** Fetching all data from database ******************/


//require database class files
require('includes/pdocon.php');

//instatiating our database objects
$db = new Pdocon;


//Create a query to select all users to display in the table
$db->query('SELECT * FROM users');
    

//Fetch all data and keep in a result set
$results = $db->fetchMultiple();
 


?>



  <div class="container">

   <?php // call your display function to display session message on top page ?>
   <?php showmsg(); ?>
   
  <div class="jumbotron">
  
  <small class="pull-right"><a href="register_user.php"> Add Customer </a> </small>
 
  <?php //Collect session name and write a welcome message with the user session's name ?>
  <?php echo $_SESSION['user_data']['fullname']; ?> | Admin
    
    <h2 class="text-center">Customers</h2> <hr>
    <br>
     <table class="table table-bordered table-hover text-center">
        <thead >
          <tr>
            <th class="text-center">User ID</th>
            <th class="text-center">Full Name</th>
            <th class="text-center">Spending</th>
            <th class="text-center">Email</th>
            <th class="text-center">Password</th>
            <th class="text-center">Report</th>
          </tr>
        </thead>
        <tbody>
    <?php  foreach($results as $result): // loop through your result set and fill in the table : ?>
          <tr>
            <td><?php echo $result['id']; ?></td>
            <td><?php echo $result['full_name']; ?></td>
            <td><?php echo $result['Spending_Amt']; ?></td>
            <td><?php echo $result['email']; ?></td>
            <td><?php echo $result['password']; ?></td>
            <td><a href="reports.php?cus_id=<?php echo $result['id']; ?>" class='btn btn-primary'>View Report</a></td>
            <td><a href="edit.php?cus_id=<?php echo $result['id']; ?>" class='btn btn-danger'>Edit</a></td>
            
          </tr>
          
    <?php endforeach; //end your loop ?>
        </tbody>
     </table>
</div>
  </div>
  
</div>
  
  
<?php include('includes/footer.php'); ?>