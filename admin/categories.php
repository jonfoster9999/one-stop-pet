<?php 
	//bring in the database and helpers and BASEURL 
	require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';

	$sql = "SELECT * FROM categories WHERE parent = 0";
	$results = $db->query($sql);
	$edit_index = 0;
	$category_name = '';
	$parent = 0;

	if(isset($_GET['delete'])) {
		$category_id = $_GET['delete'];
		$delete_sql = "DELETE FROM categories WHERE id = '$category_id' OR parent = $category_id";
		$db->query($delete_sql);
		header ('Location: categories.php');
	}

	if(isset($_GET['edit'])) {
		$category_id = sanitize((int)$_GET['edit']);
		$edit_sql = "SELECT * FROM categories WHERE id = '$category_id'";
		$c_results = $db->query($edit_sql);
		$edit_category = mysqli_fetch_assoc($c_results);
		$edit_category_id =  $edit_category['id'];
		$edit_action = 'categories.php?update=' . $edit_category_id;
		$edit_parent = $edit_category['parent'];	
	}

	//process form

	
	if(isset($_POST) && !empty($_POST)) {
		$errors = array();
		$parent = sanitize((int)$_POST['parent']);
		$category = sanitize($_POST['category']);
		


		$sql3 = "SELECT * FROM categories WHERE name = '$category' AND parent = '$parent'";
		$results3 = $db->query($sql3);
		$count = mysqli_num_rows($results3);
		$category_name = mysqli_fetch_assoc($results3)['name'];

		//If exists in database
		if($count > 0) {
			$errors[] .= "The category already exists!";
		}

		//If category is blank 
		if($category == '') {
			$errors[] .= "The category cannot be left blank!";
		}

		if(!empty($errors)) {
			$display =  display_errors($errors); ?>
		<script>
			$(function() {
				$('#errors').html('<?= $display ?>');
			})
		</script>
	<?php
		} else {
			// Create Category
			$create_sql = "INSERT INTO categories (name, parent) 
						   VALUES ('$category', '$parent');";

			if (isset($_GET['update'])) {
				$category_id = $_GET['update'];
				$create_sql = "UPDATE categories SET name = '$category', parent = '$parent' WHERE id = '$category_id'";
			}
			$db->query($create_sql);
			header('Location: categories.php');
		}
	}

	$category_value = '';

	if (isset($_GET['edit'])) {
		$category_value = $edit_category['name'];
		$edit_index = $edit_parent;
	} else if (isset($_POST)){
		$category_value = $category_name;
		$edit_index = $parent;
	}
?>
<h2 class="text-center">Categories</h2>
<hr>
<div class="row">
	<div class="col-md-6">
		<!-- Form -->

		<form action="<?= isset($_GET['edit']) ? $edit_action : 'categories.php' ?>" method=POST class="form">
			<div id="errors"></div>
			<legend><?= isset($_GET['edit']) ? 'Edit Category' : 'Add A Category' ?></legend>
			<div class="form-group">
				<label for="parent">Parent: </label>
				<select name="parent" id="parent" class="form-control">
					<option value="0">Parent</option>
					<?php while($parent_option = mysqli_fetch_assoc($results)) : ?>
						<option value="<?= $parent_option['id'] ?>" <?= $edit_index == $parent_option['id'] ? 'selected="selected"' : "" ?>><?= $parent_option['name'] ?></option>
					<?php endwhile; ?>
				</select>
			</div>
			<div class="form-group">
				<label for="category">Category: 
				</label>
				<input type="text" class="form-control" id="category" name="category" value="<?= $category_value ?>">
			</div>
			<div class="form-group">
				<input type="submit" value="<?= isset($_GET['edit']) ? 'Edit Category' : 'Add Category'?>" class="btn btn-success">
			</div>
		</form>
	</div>
	<div class="col-md-6">
		<table class="table table-bordered">
			<thead>

				<th>Category</th>
				<th>Parent</th>
				<th></th>

			</thead>
			<tbody>
			<?php $sql = "SELECT * FROM categories WHERE parent = 0";
			$results = $db->query($sql);
			?>
			<?php while($parent = mysqli_fetch_assoc($results)) : 
			    $parent_id = (int)$parent['id'];
				$sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
				$child_results = $db->query($sql2);
			?>
				<tr class="bg-primary">
					<td><?= $parent['name'] ?></td>
					<td> - </td>
					<td>
						<a href="categories.php?edit=<?= $parent['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
						<a href="categories.php?delete=<?= $parent['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
					</td>
				</tr>
			    <?php while($child = mysqli_fetch_assoc($child_results)): ?>
				<tr class="bg-info">
					<td><?= $child['name'] ?></td>
					<td><?= $parent['name'] ?></td>
					<td>
						<a href="categories.php?edit=<?= $child['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
						<a href="categories.php?delete=<?= $child['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
					</td>
				</tr>	 
			    <?php endwhile; ?>
			<? endwhile; ?>
			</tbody>
		</table>
	</div>
</div>

<?php include 'includes/footer.php';