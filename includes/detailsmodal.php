<?php  
require_once '../core/init.php';
$id = $_POST['id'];
//cast as integer just to make sure
$id = (int)$id;
$sql = "SELECT * FROM products WHERE id = '$id'";
$result = $db->query($sql);
$product = mysqli_fetch_assoc($result);
$product_id = $product['id'];
$brand_id = $product['brand'];
$brandsql = "SELECT * FROM brands WHERE id = '$brand_id'";
$brandquery = $db->query($brandsql);
$brand = mysqli_fetch_assoc($brandquery);
$size_sql = "SELECT DISTINCT(size) as 'size', s.id as 'id' FROM size s
			 INNER JOIN individual_products ip ON ip.size_id = s.id 
			 INNER JOIN products p ON p.id = ip.product_id 
			 WHERE p.id = '$product_id'";
$size_results = $db->query($size_sql);
?>

<?php ob_start(); 
?>
<div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" arialabelledby="details-1" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" type="button" aria-label="close" onclick="closeModal();">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title text-center" id="product-title" data-product="<?= $product['id'] ?>"><?php echo $product['title'] ?></h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-6">
							<div class="center-block">
								<img src="<?= $product['image'] ?>" alt="" class="details img-responsive">
							</div>	
						</div>
						<div class="col-sm-6">
							<h4>Description</h4>
							<p><?= $product['description'] ?></p>
							<hr>
							<p class="list-price text-danger" id="list-price">List Price <b><s><span id="lp">$69.99</span></s></b></p>
							<p class="our-price text-success">Our Price: <span id="price"><i>Select Size</i></span></p>
							<p>Brand: <?= $brand['brand'] ?></p>
							<form action="add_cart.php" method="POST">
								<div class="form-group">
								<div class="row">
									<div class="col-xs-2">
										<label for="quantity">Quantity</label>
										<input type="text" class="form-control" name="quantity" id="quantity">

									</div><div class="col-xs-10"></div>
								</div>
									<p id="show-quantity">Available: <span id="q"></span></p>
									<div class="form-group">
										<label for="size">Size</label>
										<select name="size" id="size" class="form-control">
											<option value="">-</option>
											<?php while($size = mysqli_fetch_assoc($size_results)) : ?>
												<option value="<?= $size['id'] ?>"><?= $size['size'] ?></option>
											<?php endwhile; ?>
										</select>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-default" onclick="closeModal()">Close</button>
				<button class="btn btn-warning" type="submit"><span class="glyphicon glyphicon-shopping-cart"></span>Add To Cart</button>
			</div>
		</div>
	</div>
</div>

<script>
	function closeModal() {
		$('#details-modal').modal('hide');
		setTimeout(()=> {
			$('#details-modal').remove();
		}, 500);
	}
	
	$('#size').change( ()=> {
		var sizeId = ($('#size option:selected').val());
		if (!sizeId) {
			$('#list-price').hide();
			$('#show-quantity').hide();
			$('#price').html("<i>Select Size</i>");
		} else {
		var productId = ($('#product-title').data('product'));	
		var data = {
			"sizeId" : sizeId,
			"productId" : productId
		}
		$.ajax({
			url: "/ecommerce/includes/price.php",
			data: data,
			method: 'post',
			success: function(data) {
				var split = data.split(",")
				var ourPrice = split[0];
				var listPrice = split[1];
				var quantity = split[2]
				$('#price').html(ourPrice);
				$('#lp').html(listPrice);
				$('#q').html(quantity);
				$('#list-price').show();
				$('#show-quantity').show();
			},
			failure: function() {
				alert("something went wrong")
			}
		})
	} })

</script>
<?php echo ob_get_clean(); ?>