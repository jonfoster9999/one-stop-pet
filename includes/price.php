<?php 
	require_once '../core/init.php';
	$size_id = $_POST['sizeId'];
	$product_id = $_POST['productId'];
	$sql = "SELECT o.price AS 'price', o.list_price AS 'list_price', ip.quantity AS 'quantity' FROM offers o 
			INNER JOIN individual_products ip ON ip.offer_id = o.id 
			INNER JOIN products p ON ip.product_id = p.id 
			INNER JOIN size s ON ip.size_id = s.id 
			WHERE s.id = '$size_id' AND p.id = '$product_id'
	";
	$pquery = $db->query($sql);
	$price = mysqli_fetch_assoc($pquery);
?>

<?= $price['price'] ?>, <?= $price['list_price'] ?>, <?= $price['quantity'] ?>