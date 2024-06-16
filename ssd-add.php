<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['ssd_capacity'])) {
        $valid = 0;
        $error_message .= "SSD Capacity can not be empty<br>";
    } else {
    	// Duplicate Category checking
    	$statement = $pdo->prepare("SELECT * FROM tbl_ssd WHERE ssd_capacity=?");
    	$statement->execute(array($_POST['ssd_capacity']));
    	$total = $statement->rowCount();
    	if($total)
    	{
    		$valid = 0;
        	$error_message .= "SSD Capacity already exists<br>";
    	}
    }

    if($valid == 1) {

		// Saving data into the main table tbl_ssd
		$statement = $pdo->prepare("INSERT INTO tbl_ssd (ssd_capacity) VALUES (?)");
		$statement->execute(array($_POST['ssd_capacity']));
	
    	$success_message = 'SSD is added successfully.';
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Add SSD</h1>
	</div>
	<div class="content-header-right">
		<a href="ssd.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>


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
							<label for="" class="col-sm-2 control-label">SSd Capacity <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="ssd_capacity">
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

<?php require_once('footer.php'); ?>