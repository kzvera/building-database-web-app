<?php include('includes/header.php'); ?>

<?php

//Include functions
include 'includes/functions.php';

?>

<?php 
/************** Fetching data from database using id ******************/
//Get the id and keep it in a variable.
if (isset($_GET['cus_id'])) {
  $cus_id = $_GET['cus_id'];
}

 //require database class files
 require('includes/pdocon.php');

 //instatiating our database objects
 $db = new Pdocon;


 //Create a query to display customer inf // You must bind the id coming in from the url
 $db->query('SELECT * FROM users WHERE id=:id');

 //Bind your id

 $db->bindvalue(':id', $cus_id, PDO::PARAM_INT);

//Fetching the data
 $row = $db->fetchSingle();

?>



  <div class="well">
   
  <small class="pull-right"><a href="customers.php"> View Customers</a> </small>
 
  <?php //Collect the admin's name and put it in there using the session super global
    
    echo '<small class="pull-left" style="color:#337ab7;"> Admin Name here | Editing Customer</small>';

?>
    
    <h2 class="text-center">My Customers</h2> <hr>
    <br>
   </div> <hr>
   
    
   <div class="rows">
    <?php // call your display function to display session message on top page ?>
    <?php showmsg(); ?>
     <div class="col-md-6 col-md-offset-3">
          <?php  if ($row): // Display result in the form values : ?>
          <br>
           <form class="form-horizontal" role="form" method="post" action="edit.php?cus_id=<?php echo $cus_id; ?>">
            <div class="form-group">
            <label class="control-label col-sm-2" for="name" style="color:#f3f3f3;">Fullname:</label>
            <div class="col-sm-10">
              <input type="name" name="name" class="form-control" id="name" value="<?php echo $row['full_name']; ?>" required>
            </div>
          </div>
            <div class="form-group">
            <label class="control-label col-sm-2" for="country" style="color:#f3f3f3;">Amount:</label>
            <div class="col-sm-10">
              <input type="country" name="amount" class="form-control" id="country" value="<?php echo $row['Spending_Amt']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="email" style="color:#f3f3f3;">Email:</label>
            <div class="col-sm-10">
              <input type="email" name="email" class="form-control" id="email" value="<?php echo $row['email']; ?>" required>
            </div>
          </div>
          <div class="form-group ">
            <label class="control-label col-sm-2" for="pwd" style="color:#f3f3f3;">Password:</label>
            <div class="col-sm-10">
             <fieldset disabled> 
              <input type="password" name="password" class="form-control disabled" id="pwd" placeholder="Cannot Change Password" value="<?php echo $row['password']; ?>" required>
             </fieldset> 
            </div>
          </div>

          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
              <input type="submit" class="btn btn-primary" name="update_customer" value="Update">
              <button type="submit" class="btn btn-danger pull-right" name="delete_customer">Delete</button>
            </div>
          </div>
          
          <?php endif; //end  ?>
          
        </form>
          
  </div>
</div>  

<?php 
/************** Update data to database using id ******************/  
      
//Get field names from from and validate   
if (isset($_POST['update_customer'])) {

  $raw_name = cleandata($_POST['name']);
  $raw_amount = cleandata($_POST['amount']);
  $raw_email = cleandata($_POST['email']);

  $c_name = sanitize($raw_name);
  $c_amount = validate_int($raw_amount);
  $c_email = validate_email($raw_email);

  //Write your query
  $db->query('UPDATE users SET full_name=:fullname, email=:email, Spending_Amt=:spending_amt WHERE id=:id');

  //binding values with your variable
  $db->bindvalue(':fullname', $c_name, PDO::PARAM_STR);
  $db->bindvalue(':email', $c_email, PDO::PARAM_STR);
  $db->bindvalue(':spending_amt', $c_amount, PDO::PARAM_STR);
  $db->bindvalue(':id', $cus_id, PDO::PARAM_INT);

  //Execute query statement to send it into the database
  $run_update = $db->execute();

  //Confirm execution and set your messages to display as well has redirection and errors
  if ($run_update) {
    redirect('customers.php');

    keepmsg('<div class="alert alert-success text-center">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Success!</strong> Customer updated successful.
            </div>');
    
  } else {
    redirect('customers.php');

    keepmsg('<div class="alert alert-danger text-center">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Sorry!</strong> Customer could not be updated.
            </div>');
  }
}
 
    
/************** Delete data from database using id ******************/  
      
//Setting a confirmation message when the delete button is clicked // the result must be closable div that has a form with two buttons. one for no and one for yes. The no shoule close the closable div but the yes should proceed to deleting the customer, must delete the customer with the customer id         
if (isset($_POST['delete_customer'])) {

  keepmsg('<div class="alert alert-danger text-center">
            <strong>Confirm!</strong> Are you sure you want to delete your account? <br>
            <a href="#" class="btn btn-default" data-dismiss="alert" aria-label="close">No, thanks!</a>
            <br>
            <form method="post" action="edit.php">
            <input type="hidden" value="'. $cus_id .'" name="id">
            <input type="submit" name="delete_user" value="Yes, Delete" class="btn btn-danger">
            </form>
          </div>');
}



//If the Yes Delete (confim delete) button is click from the closable div proceed to delete
if (isset($_POST['delete_user'])) {
  // get the id from the url
  $id = $_POST['id'];

  //write your query
  $db->query('DELETE FROM users WHERE id=:id');

  //binding values with your  url id variable
  $db->bindvalue(':id', $id, PDO::PARAM_INT);

  //Execute query statement to send it into the database
  $run = $db->execute();

  //Confirm execution and display a delete success message and redirect admin to index page
  if ($run) {
    redirect('customers.php');

    keepmsg('<div class="alert alert-success text-center">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Sorry!</strong> Customer successfully deleted.
            </div>');
  } else {
    keepmsg('<div class="alert alert-danger text-center">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Sorry!</strong> Customer with ID of ' . $id . ' could not be deleted.
            </div>');
  }
}   
  
?>


</div>
 
</div>
  
</div>
<?php include('includes/footer.php'); ?>