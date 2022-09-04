<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 

if(isset($_POST['add']))
{
$carname=$_POST['carname'];
$category=$_POST['company'];
$author=$_POST['dealer'];
$isbn=$_POST['isbn'];
$price=$_POST['price'];
$carimg=$_FILES["carpic"]["name"];
// get the image extension
$extension = substr($carimg,strlen($carimg)-4,strlen($carimg));
// allowed extensions
$allowed_extensions = array(".jpg","jpeg",".png",".gif");
// Validation for allowed extensions .in_array() function searches an array for a specific value.
//rename the image file
$imgnewname=md5($carimg.time()).$extension;
// Code for move image into directory

if(!in_array($extension,$allowed_extensions))
{
echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
}
else
{
move_uploaded_file($_FILES["carpic"]["tmp_name"],"carimg/".$imgnewname);
$sql="INSERT INTO  tblcars(carName,CatId,DealerId,ISBNNumber,carPrice,carImage) VALUES(:carname,:company,:dealer,:isbn,:price,:imgnewname)";
$query = $dbh->prepare($sql);
$query->bindParam(':carname',$carname,PDO::PARAM_STR);
$query->bindParam(':company',$company,PDO::PARAM_STR);
$query->bindParam(':dealer',$dealer,PDO::PARAM_STR);
$query->bindParam(':isbn',$isbn,PDO::PARAM_STR);
$query->bindParam(':price',$price,PDO::PARAM_STR);
$query->bindParam(':imgnewname',$imgnewname,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
echo "<script>alert('Car Listed successfully');</script>";
echo "<script>window.location.href='manage-cars.php'</script>";
}
else 
{
echo "<script>alert('Something went wrong. Please try again');</script>";    
echo "<script>window.location.href='manage-cars.php'</script>";
}}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online cars Management System | Add car</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
<script type="text/javascript">
    function checkisbnAvailability() {
$("#loaderIcon").show();
jQuery.ajax({
url: "check_availability.php",
data:'isbn='+$("#isbn").val(),
type: "POST",
success:function(data){
$("#isbn-availability-status").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}

</script>
</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">Add car</h4>
                
                            </div>

</div>
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
<div class="panel panel-info">
<div class="panel-heading">
Car Info
</div>
<div class="panel-body">
<form role="form" method="post" enctype="multipart/form-data">

<div class="col-md-6">   
<div class="form-group">
<label>Car Name<span style="color:red;">*</span></label>
<input class="form-control" type="text" name="carname" autocomplete="off"  required />
</div>
</div>

<div class="col-md-6">  
<div class="form-group">
<label> Company<span style="color:red;">*</span></label>
<select class="form-control" name="category" required="required">
<option value=""> Select Company</option>
<?php 
$status=1;
$sql = "SELECT * from  tblcompany where Status=:status";
$query = $dbh -> prepare($sql);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>  
<option value="<?php echo htmlentities($result->id);?>"><?php echo htmlentities($result->CompanyName);?></option>
 <?php }} ?> 
</select>
</div></div>

<div class="col-md-6">  
<div class="form-group">
<label> Dealer<span style="color:red;">*</span></label>
<select class="form-control" name="dealer" required="required">
<option value=""> Select Dealer</option>
<?php 

$sql = "SELECT * from  tblautodealers ";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>  
<option value="<?php echo htmlentities($result->id);?>"><?php echo htmlentities($result->DealerName);?></option>
 <?php }} ?> 
</select>
</div></div>

<div class="col-md-6">  
<div class="form-group">
<label>ISBN Number<span style="color:red;">*</span></label>
<input class="form-control" type="text" name="isbn" id="isbn" required="required" autocomplete="off" onBlur="checkisbnAvailability()"  />
<p class="help-block">An ISBN is an International Standard car Number.ISBN Must be unique</p>
         <span id="isbn-availability-status" style="font-size:12px;"></span>
</div></div>

<div class="col-md-6">  
 <div class="form-group">
 <label>Price<span style="color:red;">*</span></label>
 <input class="form-control" type="text" name="price" autocomplete="off"   required="required" />
 </div>
</div>

<div class="col-md-6">  
 <div class="form-group">
 <label>car Picture<span style="color:red;">*</span></label>
 <input class="form-control" type="file" name="carpic" autocomplete="off"   required="required" />
 </div>
    </div>
<button type="submit" name="add" id="add" class="btn btn-info">Submit </button>
 </div>
</div>
                            </div>

        </div>
   
    </div>
    </div>
     <!-- CONTENT-WRAPPER SECTION END-->
  <?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>
