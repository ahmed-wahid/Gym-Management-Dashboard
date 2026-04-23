


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
        <h5><?= isset($record) ? 'Edit' : 'Add New' ?> Membership Plan</h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <?php if (isset($record)): ?>
                <input type="hidden" name="id_plan" value="<?= $record['id_plan'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="plan_name" class="form-label">Plan Name</label>
                <input type="text" class="form-control" id="plan_name" name="plan_name" 
                        value="<?= isset($record) ? test_input($record['plan_name']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" 
                        value="<?= isset($record) ? test_input($record['price']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="duration_months" class="form-label">Duration (Months)</label>
                <input type="number" class="form-control" id="duration_months" name="duration_months" 
                        value="<?= isset($record) ? test_input($record['duration_months']) : '' ?>" required>
            </div>
            <button type="submit" name="<?= isset($record) ? 'update_membership' : 'add_membership' ?>" 
                    class="btn btn-primary">
                <?= isset($record) ? 'Update' : 'Add' ?> Plan
            </button>
            <?php if (isset($record)): ?>
                <a href="?action=view&table=memberships" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </form>
    </div>
</div>