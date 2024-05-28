<link rel="stylesheet" href="assets/css/user/list_brand.css">

<?php
	$sql_show_brand = "SELECT * FROM brand LIMIT 8";
    $query_show_brand = mysqli_query($connect, $sql_show_brand);
?>
<div class="container">
	<div class="wp-brands">
		<div class="d-flex justify-content-between">
			<h3 class="title">THƯƠNG HIỆU NỔI BẬT</h3>
			<a class="view-all" href="?page=thuong-hieu">XEM TẤT CẢ</a>
		</div>
		<div class="list-brands">
			<?php
			while ($row2 = mysqli_fetch_array($query_show_brand)) {
			?>
				<div class="wp-brand-item">
					<a style="text-decoration: none;">
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