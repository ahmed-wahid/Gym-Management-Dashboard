

<?php
function test_input($data) {
    $data = trim($data); // يشيل المسافات اللي في الأول والآخر
    $data = stripslashes($data); // يشيل الباك سلاش 
    $data = htmlspecialchars($data); //<> يحول الرموز الخاصة لحاجات آمنة
    return $data;
}
?>

<div class="card mt-4">
    <div class="card-header">
        <h5><?= isset($record) ? 'Edit' : 'Add New' ?> Product</h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <?php if (isset($record)): ?>
                <input type="hidden" name="product_id" value="<?= $record['product_id'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" 
                        value="<?= isset($record) ? test_input($record['product_name']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="category" class="form-label">Category</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="Supplement" <?= (isset($record) && $record['category'] == 'Supplement') ? 'selected' : '' ?>>Supplement</option>
                    <option value="Equipment" <?= (isset($record) && $record['category'] == 'Equipment') ? 'selected' : '' ?>>Equipment</option>
                    <option value="Clothing" <?= (isset($record) && $record['category'] == 'Clothing') ? 'selected' : '' ?>>Clothing</option>
                    <option value="Other" <?= (isset($record) && $record['category'] == 'Other') ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" min="1" class="form-control" id="price" name="price" 
                        value="<?= isset($record) ? test_input($record['price']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="stock_quantity" class="form-label">Stock Quantity</label>
                <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" 
                        value="<?= isset($record) ? test_input($record['stock_quantity']) : '0' ?>" required>
            </div>
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= 
                    isset($record) ? test_input($record['description']) : '' ?></textarea>
            </div>
            <button type="submit" name="<?= isset($record) ? 'update_product' : 'add_product' ?>" 
                    class="btn btn-primary">
                <?= isset($record) ? 'Update' : 'Add' ?> Product
            </button>
            <?php if (isset($record)): ?>
                <a href="?action=view&table=products" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </form>
    </div>
</div>