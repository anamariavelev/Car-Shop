<?php 
require_once("includes/config.php");
if(!empty($_POST["clientid"])) {
  $clientid= strtoupper($_POST["clientid"]);
 
    $sql ="SELECT FullName,Status,EmailId,MobileNumber FROM tblclients WHERE clientId=:clientid";
$query= $dbh -> prepare($sql);
$query-> bindParam(':clientid', $clientid, PDO::PARAM_STR);
$query-> execute();
$results = $query -> fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query -> rowCount() > 0)
{
foreach ($results as $result) {
if($result->Status==0)
{
echo "<span style='color:red'> client ID Blocked </span>"."<br />";
echo "<b>client Name-</b>" .$result->FullName;
 echo "<script>$('#submit').prop('disabled',true);</script>";
} else {
?>


<?php  
echo htmlentities($result->FullName)."<br />";
echo htmlentities($result->EmailId)."<br />";
echo htmlentities($result->MobileNumber);
 echo "<script>$('#submit').prop('disabled',false);</script>";
}
}
}
 else{
  
  echo "<span style='color:red'> Invaid client Id. Please Enter Valid client id .</span>";
 echo "<script>$('#submit').prop('disabled',true);</script>";
}
}



?>
