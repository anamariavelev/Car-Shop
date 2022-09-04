<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 

if(isset($_POST['update']))
{
$carname=$_POST['carname'];
$category=$_POST['company'];
$dealer=$_POST['autodealer'];
$isbn=$_POST['isbn'];
$price=$_POST['price'];
$carid=intval($_GET['carid']);
$sql="update  tblcars set carName=:carname,CatId=:company,AutodealerId=:dealer,carPrice=:price where id=:carid";
$query = $dbh->prepare($sql);
$query->bindParam(':carname',$carname,PDO::PARAM_STR);
$query->bindParam(':company',$category,PDO::PARAM_STR);
$query->bindParam(':dealer',$dealer,PDO::PARAM_STR);
$query->bindParam(':price',$price,PDO::PARAM_STR);
$query->bindParam(':carid',$carid,PDO::PARAM_STR);
$query->execute();
echo "<script>alert('Car info updated successfully');</script>";
echo "<script>window.location.href='manage-cars.php'</script>";


}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="dealer" content="" />
    <title>Online Cars Management System | Edit car</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

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
<div class="col-md12 col-sm-12 col-xs-12">
<div class="panel panel-info">
<div class="panel-heading">
Car Info
</div>
<div class="panel-body">
<form role="form" method="post">
<?php 
$carid=intval($_GET['carid']);
$sql = "SELECT tblcars.carName,tblcompany.CompanyName,tblcompany.id as cid,tbldealers.dealerName,tbldealers.id as athrid,tblcars.ISBNNumber,tblcars.carPrice,tblcars.id as carid,tblcars.carImage from  tblcars join tblcategory on tblcategory.id=tblcars.CatId join tbldealers on tbldealers.id=tblcars.dealerId where tblcars.id=:carid";
$query = $dbh -> prepare($sql);
$query->bindParam(':carid',$carid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>  

<div class="col-md-6">
<div class="form-group">
<label>Car Image</label>
<img src="carimg/<?php echo htmlentities($result->carImage);?>" width="100">
<a href="change-carimg.php?carid=<?php echo htmlentities($result->carid);?>">Change Car Image</a>
</div></div>

<div class="col-md-6">
<div class="form-group">
<label>Car Name<span style="color:red;">*</span></label>
<input class="form-control" type="text" name="carname" value="<?php echo htmlentities($result->carName);?>" required />
</div></div>

<div class="col-md-6">
<div class="form-group">
<label> Company<span style="color:red;">*</span></label>
<select class="form-control" name="category" required="required">
<option value="<?php echo htmlentities($result->cid);?>"> <?php echo htmlentities($catname=$result->CompanyName);?></option>
<?php 
$status=1;
$sql1 = "SELECT * from  tblcompany where Status=:status";
$query1 = $dbh -> prepare($sql1);
$query1-> bindParam(':status',$status, PDO::PARAM_STR);
$query1->execute();
$resultss=$query1->fetchAll(PDO::FETCH_OBJ);
if($query1->rowCount() > 0)
{
foreach($resultss as $row)
{           
if($catname==$row->CompanyName)
{
continue;
}
else
{
    ?>  
<option value="<?php echo htmlentities($row->id);?>"><?php echo htmlentities($row->CompanyName);?></option>
 <?php }}} ?> 
</select>
</div></div>

<div class="col-md-6">
<div class="form-group">
<label> Dealer<span style="color:red;">*</span></label>
<select class="form-control" name="dealer" required="required">
<option value="<?php echo htmlentities($result->athrid);?>"> <?php echo htmlentities($athrname=$result->dealerName);?></option>
<?php 

$sql2 = "SELECT * from  tbldealers ";
$query2 = $dbh -> prepare($sql2);
$query2->execute();
$result2=$query2->fetchAll(PDO::FETCH_OBJ);
if($query2->rowCount() > 0)
{
foreach($result2 as $ret)
{           
if($dlrname==$ret->dealerName)
{
continue;
} else{

    ?>  
<option value="<?php echo htmlentities($ret->id);?>"><?php echo htmlentities($ret->dealerName);?></option>
 <?php }}} ?> 
</select>
</div></div>


<div class="col-md-6">
<div class="form-group">
<label>ISBN Number<span style="color:red;">*</span></label>
<input class="form-control" type="text" name="isbn" value="<?php echo htmlentities($result->ISBNNumber);?>"  readonly />
<p class="help-block">An ISBN is an International Standard car Number.ISBN Must be unique</p>
</div></div>


<div class="col-md-6">
 <div class="form-group">
 <label>Price in USD<span style="color:red;">*</span></label>
 <input class="form-control" type="text" name="price" value="<?php echo htmlentities($result->carPrice);?>"   required="required" />
 </div></div>
 <?php }} ?><div class="col-md-12">
<button type="submit" name="update" class="btn btn-info">Update </button></div>

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
