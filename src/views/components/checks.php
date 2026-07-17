<?php if (isset($this->errors) && !empty($this->errors)): ?>
    <div class="checks errors">
        <?php foreach ($this->errors as $e): ?>
            <?= $e ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (isset($this->success) && $this->success !== ""): ?>
    <div class="checks success">
        <?= $this->success ?>
    </div>
<?php endif; ?>