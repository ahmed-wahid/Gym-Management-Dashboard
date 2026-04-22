

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
        <h5><?= isset($record) ? 'Edit' : 'Add New' ?> Branch</h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <?php if (isset($record)): ?>
                <input type="hidden" name="branch_id" value="<?= $record['branch_id'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="branch_name" class="form-label">Branch Name</label>
                <input type="text" class="form-control" id="branch_name" name="branch_name" 
                        value="<?= isset($record) ? test_input($record['branch_name']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3" required><?= 
                    isset($record) ? test_input($record['address']) : '' ?></textarea>
            </div>
            <button type="submit" name="<?= isset($record) ? 'update_branch' : 'add_branch' ?>" 
                    class="btn btn-primary">
                <?= isset($record) ? 'Update' : 'Add' ?> Branch
            </button>
            <?php if (isset($record)): ?>
                <a href="?action=view&table=branches" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </form>
    </div>
</div>