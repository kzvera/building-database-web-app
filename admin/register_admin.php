<?php 
include('../includes/header.php'); 


//Include functions
include 'includes/functions.php';

//check to see if user if logged in else redirect to index page 


?>

 
<?php
/************** Register new Admin ******************/


//require database class files
require 'includes/pdocon.php';


//instatiating our database objects
$db = new Pdocon;


//Collect and clean values from the form // Collect image and move image to upload_image folder
if (isset($_POST['submit_login'])) {
  $raw_name = cleandata($_POST['name']);
  $raw_sex = cleandata($_POST['sex']);
  $raw_email = cleandata($_POST['username']);
  $raw_password = cleandata($_POST['password']);

  $c_name = sanitize($raw_name);
  $c_sex = sanitize($raw_sex);
  $c_email = validate_email($raw_email);
  $c_password = sanitize($raw_password);

  //Hash password using our md5 function
  $hashed_pass = hashpassword($c_password);

  // Verify File Exention
  $allowed_ext = array('jpg', 'jpeg', 'png');
  $filename = $_FILES['image']['name'];
  $file_ext = pathinfo($filename, PATHINFO_EXTENSION);

  if (!in_array($file_ext, $allowed_ext)) {
    echo '<div class="alert alert-danger text-center">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Sorry!</strong> The file you uploaded is not allowed. Please try again.
            </div>';
  } else {
    // Collect image
    $c_img = $_FILES['image']['name'];
    $c_img_tmp = $_FILES['image']['tmp_name'];
  
    // move image to uploaded images folder
    move_uploaded_file($c_img_tmp, "uploaded_image/$c_img");
  
    //Check and see if user already exist in database using email so write query and bind email
    $db->query("SELECT * FROM admin WHERE email = :email");
  
    $db->bindvalue(':email', $c_email, PDO::PARAM_STR);
  
    //Call function to count row
    $row = $db->fetchSingle();
  
    if ($row) {
      //Display error if admin exist 
      echo '<div class="alert alert-danger text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Sorry!</strong> User already Exist. Register or Try Again
              </div>';
    } else {     
      //Write query to insert values, bind values
      $db->query("INSERT INTO admin(id, fullname, email, pass, sex, image) VALUES(NULL, :fullname, :email, :password, :sex, :image)");
  
      $db->bindvalue(':fullname', $c_name, PDO::PARAM_STR);
      $db->bindvalue(':email', $c_email, PDO::PARAM_STR);
      $db->bindvalue(':password', $hashed_pass, PDO::PARAM_STR);
      $db->bindvalue(':sex', $c_sex, PDO::PARAM_STR);
      $db->bindvalue(':image', $c_img, PDO::PARAM_STR);
  
      //Execute and assign a varaible to the execution result // remember it returns true of false
      $run = $db->execute();
  
      //Comfirm execute and display error or success message
      if ($run) {
        echo '<div class="alert alert-success text-center">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Success!</strong> Admin registered successfully.  Please Login
                    </div>';
      } else {
        echo '<div class="alert alert-danger text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Sorry!</strong> Admin user could not be registered. Please try again later
              </div>';
      }
    }
  }


}

?>  
    <div class="row">
      <div class="col-md-4 col-md-offset-4">
          <p class=""><a class="pull-right" href="../index.php"> Login</a></p><br>
      </div>
      <div class="col-md-4 col-md-offset-4">
        <form class="form-horizontal" role="form" method="post" action="register_admin.php" enctype="multipart/form-data">
          <div class="form-group">
            <label class="control-label col-sm-2" for="name"></label>
            <div class="col-sm-10">
              <input type="name" name="name" class="form-control" id="name" placeholder="Enter Full Name" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="sex"></label>
            <div class="col-sm-10">
              <select type="" name="sex" class="form-control" id="sex" >
                  <option value="">Select Sex</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="Secret">N/A</option>
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <label class="control-label col-sm-2" for="email"></label>
            <div class="col-sm-10">
              <input type="email" name="username" class="form-control" id="email" placeholder="Enter Email" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="pwd"></label>
            <div class="col-sm-10"> 
              <input type="password" name="password" class="form-control" id="pwd" placeholder="Enter Password" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="image"></label>
            <div class="col-sm-10">
              <input type="file" name="image" id="image" placeholder="Choose Image" required>
            </div>
          </div>

          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
              <div class="checkbox">
                <label><input type="checkbox" required> Accept Agreement</label>
              </div>
            </div>
          </div>

          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10 text-center">
              <button type="submit" class="btn btn-primary pull-right" name="submit_login">Register</button>
              <a class="pull-left btn btn-danger" href="../index.php"> Cancel</a>
            </div>
          </div>
</form>
          
  </div>
</div>
          
  </div>
</div>
  
<?php include('includes/footer.php'); ?>  