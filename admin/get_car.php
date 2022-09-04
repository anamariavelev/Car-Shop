<?php 
require_once("includes/config.php");
if(!empty($_POST["carid"])) {
  $carid=$_POST["carid"];
 
    $sql ="SELECT distinct tblcars.carName,tblcars.id,tblautodealers.dealerName,tblcars.carImage,tblcars.isIssued FROM tblcars
join tbldealers on tbldealers.id=tblcars.dealerId
     WHERE (ISBNNumber=:carid || carName like '%$carid%')";
$query= $dbh -> prepare($sql);
$query-> bindParam(':carid', $carid, PDO::PARAM_STR);
$query-> execute();
$results = $query -> fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query -> rowCount() > 0){
?>
<table border="1">

  <tr>
<?php foreach ($results as $result) {?>
    <th style="padding-left:5%; width: 10%;">
<img src="carimg/<?php echo htmlentities($result->carImage); ?>" width="120"><br />
      <?php echo htmlentities($result->carName); ?><br />
    <?php echo htmlentities($result->dealerName); ?><br />
    <?php if($result->isIssued=='1'): ?>
<p style="color:red;">Car Already issued</p>
<?php else:?>
<input type="radio" name="carid" value="<?php echo htmlentities($result->id); ?>" required>
<?php endif;?>
  </th>
    <?php  echo "<script>$('#submit').prop('disabled',false);</script>";
}
?>
  </tr>

</table>
</div>
</div>

<?php  
}else{?>
<p>Record not found. Please try again.</p>
<?php
 echo "<script>$('#submit').prop('disabled',true);</script>";
}
}
?>
