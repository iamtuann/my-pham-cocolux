<?php include 'layouts/admin/header.php'; ?>
<div>
  <?php foreach ($products as $product): ?>
    <div>
      <h2><?php echo ($product['name']); ?></h2>
      <p><?php echo ($product['description']); ?></p>
    </div>
  <?php endforeach; ?>
</div>
<?php include 'layouts/admin/footer.php'; ?>