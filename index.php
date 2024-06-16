<?php require_once('header.php'); ?>

<section class="content-header">
	<h1>Dashboard</h1>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_top_category");
$statement->execute();
$total_top_category = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_mid_category");
$statement->execute();
$total_mid_category = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_end_category");
$statement->execute();
$total_end_category = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_product");
$statement->execute();
$total_product = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=? AND shipping_status=?");
$statement->execute(array('Completed','Completed'));
$total_order_completed = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE shipping_status=?");
$statement->execute(array('Completed'));
$total_shipping_completed = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=?");
$statement->execute(array('Pending'));
$total_order_pending = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE shipping_status=?");
$statement->execute(array('Pending'));
$total_order_complete_shipping_pending = $statement->rowCount();

$statement = $pdo->prepare("SELECT sum(paid_amount)as amount FROM tbl_payment WHERE payment_status=?");
$statement->execute(array('Completed'));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
$total_cash_collected = 0;
foreach ($result as $row){
		$total_cash_collected = $row['amount'] + $total_cash_collected;
}

$month=array();
for($i=1;$i<=12;$i++){
$statement = $pdo->prepare("SELECT sum(paid_amount)as amount FROM tbl_payment WHERE month(payment_date)=? AND year(payment_date)=? AND payment_status=?");
$statement->execute(array($i,'2021','Completed'));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);	
	foreach ($result as $row){
		array_push($month,$row['amount']);
	}
}

		$jan = $month[0];	
        $feb = $month[1];
        $march = $month[2];
        $april = $month[3];
        $may = $month[4];
        $june = $month[5];
        $july = $month[6];
		$aug = $month[7];	
        $sep = $month[8];
        $oct = $month[9];
        $nov = $month[10];
        $dec = $month[11];

echo "<input type='hidden' id= 'jan' value = '$jan' >";
echo "<input type='hidden' id= 'feb' value = '$feb' >";
echo "<input type='hidden' id= 'march' value = '$march' >";
echo "<input type='hidden' id= 'april' value = '$april' >";
echo "<input type='hidden' id= 'may' value = '$may' >";
echo "<input type='hidden' id= 'june' value = '$june' >";
echo "<input type='hidden' id= 'july' value = '$july' >";
echo "<input type='hidden' id= 'aug' value = '$aug' >";
echo "<input type='hidden' id= 'sep' value = '$sep' >";
echo "<input type='hidden' id= 'oct' value = '$oct' >";
echo "<input type='hidden' id= 'nov' value = '$nov' >";
echo "<input type='hidden' id= 'dec' value = '$dec' >";

?>

<canvas id="myChart" style="height: auto; width: 500px;"></canvas>

<section class="content">
	<div class="row">
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-aqua"><i class="fa fa-list-alt"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Top Categories</span>
					<span class="info-box-number"><?php echo $total_top_category; ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-aqua"><i class="fa fa-list-alt"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Mid Categories</span>
					<span class="info-box-number"><?php echo $total_mid_category; ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-aqua"><i class="fa fa-list-alt"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">End Categories</span>
					<span class="info-box-number"><?php echo $total_end_category; ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-aqua"><i class="fa fa-laptop"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Products</span>
					<span class="info-box-number"><?php echo $total_product; ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Completed Orders</span>
					<span class="info-box-number"><?php echo $total_order_completed; ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-green"><i class="fa fa-truck"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Completed Shipping</span>
					<span class="info-box-number"><?php echo $total_shipping_completed; ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Total Sales </span>
					<span class="info-box-number"><?php echo 'Rs. ',$total_cash_collected; ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-red"><i class="fa fa-shopping-cart"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Pending Payments</span>
					<span class="info-box-number"><?php echo $total_order_pending; ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-red"><i class="fa fa-truck"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Pending Shipping </span>
					<span class="info-box-number"><?php echo $total_order_complete_shipping_pending; ?></span>
				</div>
			</div>
		</div>
		
		
	</div>
</section>

<?php require_once('footer.php'); ?>