<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['district_id'])) {
        $valid = 0;
        $error_message .= "You must have to select a district<br>";
    } else {
		// Duplicate district checking
    	// current district name that is in the database
    	$statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost WHERE shipping_cost_id=?");
		$statement->execute(array($_REQUEST['id']));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			$current_district = $row['district_id'];
		}

		$statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost WHERE district_id=? and district_id!=?");
    	$statement->execute(array($_POST['district_id'],$current_district));
    	$total = $statement->rowCount();							
    	if($total) {
    		$valid = 0;
        	$error_message .= 'district already exists<br>';
    	}
    }

    if($valid == 1) {    	
		// updating into the database
		$statement = $pdo->prepare("UPDATE tbl_shipping_cost SET district_id=?,amount=? WHERE shipping_cost_id=?");
		$statement->execute(array($_POST['district_id'],$_POST['amount'],$_REQUEST['id']));

    	$success_message = 'Shipping Cost is updated successfully.';
    }
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost WHERE shipping_cost_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Edit Shipping Cost</h1>
	</div>
	<div class="content-header-right">
		<a href="shipping-cost.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>


<?php
foreach ($result as $row) {
	$district_id = $row['district_id'];
    $amount = $row['amount'];
}
?>

<section class="content">

  <div class="row">
    <div class="col-md-12">

		<?php if($error_message): ?>
		<div class="callout callout-danger">
		
		<p>
		<?php echo $error_message; ?>
		</p>
		</div>
		<?php endif; ?>

		<?php if($success_message): ?>
		<div class="callout callout-success">
		
		<p><?php echo $success_message; ?></p>
		</div>
		<?php endif; ?>

        <form class="form-horizontal" action="" method="post">
            <div class="box box-info">
                <div class="box-body">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Select district <span>*</span></label>
                        <div class="col-sm-4">
                            <select name="district_id" class="form-control select2">
                                <option value="">Select a district</option>
                                <?php
                                $statement = $pdo->prepare("SELECT * FROM tbl_district ORDER BY district_name ASC");
                                $statement->execute();
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    ?>
                                    <option value="<?php echo $row['district_id']; ?>" <?php if($row['district_id'] == $district_id) {echo 'selected';} ?>><?php echo $row['district_name']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Amount <span>*</span></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="amount" value="<?php echo $amount; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                    	<label for="" class="col-sm-2 control-label"></label>
                        <div class="col-sm-6">
                          <button type="submit" class="btn btn-success pull-left" name="form1">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>



    </div>
  </div>

</section>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>