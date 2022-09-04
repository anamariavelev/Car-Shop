<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 

if(isset($_POST['return']))
{
$rid=intval($_GET['rid']);
$fine=$_POST['fine'];
$rstatus=1;
$carid=$_POST['carid'];
$sql="update tblissuedcardetails set fine=:fine,RetrunStatus=:rstatus where id=:rid;
update tblcars set isIssued=0 where id=:carid";
$query = $dbh->prepare($sql);
$query->bindParam(':rid',$rid,PDO::PARAM_STR);
$query->bindParam(':fine',$fine,PDO::PARAM_STR);
$query->bindParam(':rstatus',$rstatus,PDO::PARAM_STR);
$query->bindParam(':carid',$carid,PDO::PARAM_STR);
$query->execute();

$_SESSION['msg']="Car Returned successfully";
header('location:manage-issued-cars.php');



}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Cars Management System | Issued Car Details</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
<script>
// function for get client name
function getclient() {
$("#loaderIcon").show();
jQuery.ajax({
url: "get_client.php",
data:'clientid='+$("#clientid").val(),
type: "POST",
success:function(data){
$("#get_client_name").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}

//function for car details
function getcar() {
$("#loaderIcon").show();
jQuery.ajax({
url: "get_car.php",
data:'carid='+$("#carid").val(),
type: "POST",
success:function(data){
$("#get_car_name").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}

</script> 
<style type="text/css">
  .others{
    color:red;
}

</style>


</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">Issued Car Details</h4>
                
                            </div>

</div>
<div class="row">
<div class="col-md-10 col-sm-6 col-xs-12 col-md-offset-1">
<div class="panel panel-info">
<div class="panel-heading">
Issued Car Details
</div>
<div class="panel-body">
<form role="form" method="post">
<?php 
$rid=intval($_GET['rid']);
$sql = "SELECT tblclients.clientId ,tblclients.FullName,tblclients.EmailId,tblclients.MobileNumber,tblcars.carName,tblcars.ISBNNumber,tblissuedcardetails.IssuesDate,tblissuedcardetails.ReturnDate,tblissuedcardetails.id as rid,tblissuedcardetails.fine,tblissuedcardetails.RetrunStatus,tblcars.id as bid,tblcars.carImage from  tblissuedcardetails join tblclients on tblclients.clientId=tblissuedcardetails.clientId join tblcars on tblcars.id=tblissuedcardetails.carId where tblissuedcardetails.id=:rid";
$query = $dbh -> prepare($sql);
$query->bindParam(':rid',$rid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>                                      
                   


<input type="hidden" name="carid" value="<?php echo htmlentities($result->bid);?>">
<h4>Client Details</h4>
<hr />
<div class="col-md-6"> 
<div class="form-group">
<label>Client ID :</label>
<?php echo htmlentities($result->clientId);?>
</div></div>

<div class="col-md-6"> 
<div class="form-group">
<label>Client Name :</label>
<?php echo htmlentities($result->FullName);?>
</div></div>

<div class="col-md-6"> 
<div class="form-group">
<label>Client Email Id :</label>
<?php echo htmlentities($result->EmailId);?>
</div></div>

<div class="col-md-6"> 
<div class="form-group">
<label>Client Contact No :</label>
<?php echo htmlentities($result->MobileNumber);?>
</div></div>



<h4>Car Details</h4>
<hr />

<div class="col-md-6"> 
<div class="form-group">
<label>Car Image :</label>
<img src="carimg/<?php echo htmlentities($result->carImage); ?>" width="120">
</div></div>


<div class="col-md-6"> 
<div class="form-group">
<label>Car Name :</label>
<?php echo htmlentities($result->carName);?>
</div>
</div>
<div class="col-md-6"> 
<div class="form-group">
<label>ISBN :</label>
<?php echo htmlentities($result->ISBNNumber);?>
</div>
</div>

<div class="col-md-6"> 
<div class="form-group">
<label>Car Issued Date :</label>
<?php echo htmlentities($result->IssuesDate);?>
</div></div>

<div class="col-md-6"> 
<div class="form-group">
<label>Car Returned Date :</label>
<?php if($result->ReturnDate=="")
                                            {
                                                echo htmlentities("Not Return Yet");
                                            } else {


                                            echo htmlentities($result->ReturnDate);
}
                                            ?>
</div>
</div>

<div class="col-md-12"> 
<div class="form-group">
<label>Fine (in USD) :</label>
<?php 
if($result->fine=="")
{?>
<input class="form-control" type="text" name="fine" id="fine"  required />

<?php }else {
echo htmlentities($result->fine);
}
?>
</div>
</div>
 <?php if($result->RetrunStatus==0){?>

<button type="submit" name="return" id="submit" class="btn btn-info">Return car </button>

 </div>

<?php }}} ?>
                                    </form>
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
