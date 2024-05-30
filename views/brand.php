<link rel="stylesheet" href="assets/css/user/brand.css">

<?php
	$numbers_per_page = 15;
	if (isset($_GET['page-number']) && is_numeric($_GET['page-number'])) {
		$current_page = (int) $_GET['page-number'];
	} else {
		$current_page = 1;
	}

	$offset = ($current_page - 1) * $numbers_per_page;

	$total_records_sql = "SELECT COUNT(*) FROM brand";
	$total_records_result = mysqli_query($connect, $total_records_sql);
	$total_records = mysqli_fetch_array($total_records_result)[0];

	$total_pages = ceil($total_records / $numbers_per_page);

	$sql_brand = "SELECT * FROM brand ORDER BY name ASC LIMIT $offset, $numbers_per_page";
	$query_brand = mysqli_query($connect, $sql_brand);
?>
<div class="wp-brands">
	<div class="container">
		<div class="brands-nav">
			<a class="home-page-link" href="index.php">
				<i class="fa-solid fa-house-chimney"></i>
				<div>Trang chủ</div>
			</a>
			<div class="slash">/</div>
			<a href="?page=thuong-hieu">Thương hiệu</a>
		</div>
	</div>
	<div class="container">
		<hr class="brands-hr">
	</div>
	<div class="container">
		<div class="brands">
			<div class="brands-title">Thương hiệu</div>
			<div class="list-brands">
				<?php
				while ($row2 = mysqli_fetch_array($query_brand)) {
				?>
					<div class="wp-brand-item">
						<a style="text-decoration: none; " href="">
							<img class="brand-img" src=<?= $row2['image'] ?>>
							<div class="brand-name"><?= $row2['name'] ?></div>
						</a>
					</div>
				<?php
				}
				?>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="pagination">
			<?php if ($current_page > 1): ?>
				<a href="?page=thuong-hieu&page-number=<?= $current_page - 1 ?>"><i class="fa-solid fa-arrow-left"></i></a>
			<?php endif; ?>
			<?php for ($page = 1; $page <= $total_pages; $page++): ?>
				<a href="?page=thuong-hieu&page-number=<?= $page ?>" class="<?= ($current_page == $page) ? 'active' : '' ?>"><?= $page ?></a>
			<?php endfor; ?>
			<?php if ($current_page < $total_pages): ?>
				<a href="?page=thuong-hieu&page-number=<?= $current_page + 1 ?>"><i class="fa-solid fa-arrow-right"></i></a>
			<?php endif; ?>
		</div>
	</div>
</div>