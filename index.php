<?php 
	require_once "config.php";
	require_once './core/init.php';
	
	include 'includes/head.php'; 

	//navbar  
	include 'includes/navigation.php';

	//header
	include 'includes/headerfull.php';

	//left sidebar
	include 'includes/leftsidebar.php';

	$sql = "SELECT * FROM products WHERE featured = 1";
	$fquery = $db->query($sql);
?>
		  
	<div class="col-md-8">
		<div class="row">
			<h2 class="text-center">
				Featured Products
			</h2>
			<?php while($product = mysqli_fetch_assoc($fquery)) : ?>

			<?php 
				$brand_id = $product['brand'];
				$id = $product['id'];
				$sql2 = "SELECT COUNT(Distinct(size_id)) FROM `individual_products` WHERE product_id = '$id'";
				$sql3 = "SELECT * FROM brands WHERE id = '$brand_id' LIMIT 1";
				$count = $db->query($sql2);
				$brand = $db->query($sql3);
				$brand_data = $brand->fetch_assoc();
			?>

			<div class="col-md-3">
				<h4><?php echo $product['title'] ?></h4>
				<p class="brand">(<?= $brand_data['brand'] ?>)</p>
				<img src="<?php echo $product['image'] ?>" alt="" class="img-thumb"  />
				<?php while ($row = $count->fetch_assoc()) : ?>
				<p class="our-price text-success">Sizes Available: <b><?php echo $row['COUNT(Distinct(size_id))'] ?></b></p>
    			<?php endwhile; ?>
<!-- 				<p class="list-price text-danger">List Price <b><s>$69.99</s></b></p>
				 -->
				<button type="button" class="btn btn-sm btn-success" onclick="detailsModal(<?php echo $product['id']; ?>)">Details</button>
			</div>
			<?php endwhile; ?>
		</div>
	</div>


<?php 
	//modal


	//right sidebar
	include 'includes/rightsidebar.php';

	//footer
	include 'includes/footer.php';
?>
