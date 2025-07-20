<?php if ($total_pages > 1): ?>
  <div class="pagination">
    <?php if($page > 1): ?>
      <a href="?page=<?= $page - 1 ?>" class="btn-pagination sm"><< Préc.</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <a href="?page=<?= $i ?>" class="btn-pagination sm <?= $i === $page ? 'active' : '' ?>" ><?= $i ?></a>
    <?php endfor; ?>

    <?php if($page < $total_pages): ?>
      <a href="?page=<?= $page + 1 ?>" class="btn-pagination sm">>> Suiv.</a>
    <?php endif; ?>
  </div>
<?php endif; ?>