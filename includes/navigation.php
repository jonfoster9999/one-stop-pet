<?php 
	//Grab all categories from db and then query the db object
	$sql = "SELECT * FROM categories WHERE parent = 0";
	$pquery = $db->query($sql)
?>


<nav class="navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<a href="index.php" class="navbar-brand">One Stop Pet Shop</a>
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>				
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
			 <?php while($parent = mysqli_fetch_assoc($pquery)) :  ?>
				<?php $parent_id = $parent['id'];
					$sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
					$cquery = $db->query($sql2);
				?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo ucwords($parent['name']) ?><span class="caret"></span></a>
				<ul class="dropdown-menu" role="menu">
					<?php while($child = mysqli_fetch_assoc($cquery)) : ?>
						<li><a href="#"><?php echo $child['name']; ?></a></li>
					<?php endwhile; ?>
					</ul>
				</li>
			<?php endwhile; ?>
			</ul>
			

			<ul class="nav navbar-nav navbar-right">
				<li>
			        <div class="span12">
			            <form id="custom-search-form" class="form-search form-horizontal pull-right">
			                <div class="input-append span12">
			                    <input type="text" class="search-query" placeholder="Search">
			                    <button type="submit" class="btn"><i class=" search-button fa fa-search"></i></button>
			                </div>
			            </form>
			        </div>
			    </li>
			</ul>
		</div>
	</div>
</nav>