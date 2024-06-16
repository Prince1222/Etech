<?php require_once('../../header.php'); ?>
<?php 
    $payment_id = $_SESSION['payment_id'];
    $payment_date = date('Y-m-d H:i:s');

    $statement = $pdo->prepare("INSERT INTO tbl_payment (   
                                        customer_id,
                                        customer_name,
                                        customer_email,
                                        payment_date,
                                        paid_amount,
                                        payment_method,
                                        payment_status,
                                        shipping_status,
                                        payment_id
                                        ) VALUES (?,?,?,?,?,?,?,?,?)");
    $statement->execute(array(
                        $_SESSION['customer']['cust_id'],
                        $_SESSION['customer']['cust_name'],
                        $_SESSION['customer']['cust_email'],
                        $payment_date,
                        $_REQUEST['amt'],
                        'Esewa',
                        'Pending',
                        'Pending',
                        $payment_id
                        ));
                                        ?>
<?php

if( isset($_REQUEST['oid']) &&
	isset( $_REQUEST['amt']) &&
	isset( $_REQUEST['refId'])
	)
{
	$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_id =?");
	$statement->execute(array($_REQUEST['oid']));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC); 

	if(  $result )
	{
		$pmt_id;
		foreach ($result as $row)
		{
			$url = "https://uat.esewa.com.np/epay/transrec";
		
			$data =[
			'amt'=> $row['paid_amount'],
			'rid'=>  $_REQUEST['refId'],
			'pid'=>  $row['payment_id'],
			'scd'=> 'EPAYTEST'
			];

			$pmt_id = $row['payment_id'];

			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($curl);
			$response_code = get_xml_node_value('response_code',$response  );

			if ( trim($response_code)  == 'Success')
			{
				$i=0;
				foreach($_SESSION['cart_p_id'] as $key => $value) 
				{
					$i++;
					$arr_cart_p_id[$i] = $value;
				}

				$i=0;
				foreach($_SESSION['cart_p_name'] as $key => $value) 
				{
					$i++;
					$arr_cart_p_name[$i] = $value;
				}

				$i=0;
				foreach($_SESSION['cart_ssd_capacity'] as $key => $value) 
				{
					$i++;
					$arr_cart_ssd_capacity[$i] = $value;
				}

				$i=0;
				foreach($_SESSION['cart_ram_capacity'] as $key => $value) 
				{
					$i++;
					$arr_cart_ram_capacity[$i] = $value;
				}

				$i=0;
				foreach($_SESSION['cart_p_qty'] as $key => $value) 
				{
					$i++;
					$arr_cart_p_qty[$i] = $value;
				}

				$i=0;
				foreach($_SESSION['cart_p_current_price'] as $key => $value) 
				{
					$i++;
					$arr_cart_p_current_price[$i] = $value;
				}


				$i=0;
				$statement = $pdo->prepare("SELECT * FROM tbl_product");
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
				foreach ($result as $row) {
					$i++;
					$arr_p_id[$i] = $row['p_id'];
					$arr_p_qty[$i] = $row['p_qty'];
				}

				for($i=1;$i<=count($arr_cart_p_name);$i++) {
					$statement = $pdo->prepare("INSERT INTO tbl_order (
									product_id,
									product_name,
									ssd, 
									ram,
									quantity, 
									unit_price, 
									payment_id,
									cust_id
									) 
									VALUES (?,?,?,?,?,?,?,?)");
					$sql = $statement->execute(array(
									$arr_cart_p_id[$i],
									$arr_cart_p_name[$i],
									$arr_cart_ssd_capacity[$i],
									$arr_cart_ram_capacity[$i],
									$arr_cart_p_qty[$i],
									$arr_cart_p_current_price[$i],
									$pmt_id,
									$_SESSION['customer']['cust_id']
								));

					// Update the stock
					for($j=1;$j<=count($arr_p_id);$j++)
					{
						if($arr_p_id[$j] == $arr_cart_p_id[$i]) 
						{
							$current_qty = $arr_p_qty[$j];
							break;
						}
					}
					$final_quantity = $current_qty - $arr_cart_p_qty[$i];
					$statement = $pdo->prepare("UPDATE tbl_product SET p_qty=? WHERE p_id=?");
					$statement->execute(array($final_quantity,$arr_cart_p_id[$i]));
					$statement = $pdo->prepare("UPDATE tbl_payment SET payment_status=? WHERE payment_id=?");
					$statement->execute(array('Completed',$pmt_id));
					
				}
				unset($_SESSION['cart_p_id']);
				unset($_SESSION['cart_ssd_id']);
				unset($_SESSION['cart_ssd_capacity']);
				unset($_SESSION['cart_ram_id']);
				unset($_SESSION['cart_ram_capacity']);
				unset($_SESSION['cart_p_qty']);
				unset($_SESSION['cart_p_current_price']);
				unset($_SESSION['cart_p_name']);
				unset($_SESSION['cart_p_featured_photo']);
				unset($_SESSION['payment_id']);
				header('location: ../../payment_success.php');
			}
	
	
		}
	}
}


function get_xml_node_value($node, $xml) {
    if ($xml == false) {
        return false;
    }
    $found = preg_match('#<'.$node.'(?:\s+[^>]+)?>(.*?)'.
            '</'.$node.'>#s', $xml, $matches);
    if ($found != false) {
        
            return $matches[1]; 
         
    }	  

   return false;
}