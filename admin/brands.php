<?php 
	require_once '../core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
	$sql = "SELECT * FROM brands ORDER BY brand";
	$bquery = $db->query($sql);
	$errors = array();


	//Edit Brand 
	if(isset($_GET['edit']) && !empty($_GET['edit'])) {
		$edit_id = sanitize((int)$_GET['edit']);
		$sql2 = "SELECT * FROM brands WHERE id = '$edit_id'";
		$result2 = mysqli_fetch_assoc($db->query($sql2));
	}

	//Delete Brand
	if(isset($_GET['delete']) && !empty($_GET['delete'])) {
		$delete_id = sanitize((int)$_GET['delete']);
		$sql = "DELETE FROM brands WHERE id = '$delete_id'";
		$db->query($sql);
		header('Location: brands.php');
	}

	//If add form is submitted 

	if(isset($_POST['add_submit'])) {
		$brand = sanitize($_POST['brand']);
		//check if brand is blank
		if($_POST['brand'] == '') {
			//add to the empty errors array
			$errors[] .= 'You must enter a brand!';
		}
		// check if brand exists already in database
		$sql = "SELECT * FROM brands WHERE brand = '$brand'";
		if(isset($_GET['edit'])) {

			//If we're in edit mode, check if there exists a brand with the same name as the one we submit for edit with a DIFFERENT id...if that exists we want to throw an error. If it exists and it's the same ID, we don't want to throw an error because we're just updating it. 

			$sql = "SELECT * FROM brands WHERE brand = '$brand' AND id != '$edit_id'";
		}
		$result = $db->query($sql);
		$count = mysqli_num_rows($result);
		
		if ($count > 0) {
			$errors[].= $brand.' brand already exists!';
		}
		//display the errors only if errors exists, i.e. it failed the above tests.
		if (!empty($errors)) {
			echo display_errors($errors);
		} else {
			//check if we're updating or adding
			
			//Add brand to database
			$sql = "INSERT INTO brands (brand) VALUES ('$brand')";
			if (isset($_GET['edit'])) {
				$sql = "UPDATE brands 
						SET brand = '$brand'
				        WHERE id = $edit_id";
			}
			$db->query($sql);
			//refresh the page
			header('Location: brands.php');
		}
	}

?>
<h2 class="text-center">Brands</h2>
<hr>

<!-- Brand Form --> 

<div id="brand-form">
	<!-- submit form to the same page we're on -->
	<form class="form-inline" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id : '')?>" method="post">
		<div class="form-group">

			<?php 
			    $brand_value = '';
				if(isset($_GET['edit'])) {
					$brand_value = $result2['brand'];
				} else {
					if(isset($_POST['brand'])) {
						$brand_value = sanitize($_POST['brand']);
					}
				}
			?>
			<label for="brand"><?= ((isset($_GET['edit'])) ? 'Edit brand: ' : 'Add A Brand: ')?></label>
			<input type="text" name="brand" id="brand" class="form-control" value="<?= $brand_value ?>"/>
			<?php if(isset($_GET['edit'])): ?>
				<a href="brands.php" class="btn btn-danger">Cancel</a>

			<?php endif; ?>
			<input type="submit" value="<?= ((isset($_GET['edit'])) ? 'Update' : 'Add') ?> Brand" name="add_submit" class="btn btn-success">
		</div>
	</form>
</div>
<hr>
<table class="table table-bordered table-striped table-auto table-condensed">
	<thead>
		<th></th>
		<th>Brand</th>
		<th></th>
	</thead>
	<tbody>
		<?php while($brand = mysqli_fetch_assoc($bquery)) : ?>
		<tr>
			<td><a href="brands.php?edit=<?= $brand['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
			<td><?= $brand['brand'] ?></td>
			<td><a href="brands.php?delete=<?= $brand['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
		</tr>
		<?php endwhile; ?>
	</tbody>
</table>
<?php include 'includes/footer.php' ?>