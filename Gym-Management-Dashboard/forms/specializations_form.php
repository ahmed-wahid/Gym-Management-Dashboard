

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
        <h5><?= isset($record) ? 'Edit' : 'Add New' ?> Specialization</h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <?php if (isset($record)): ?>
                <input type="hidden" name="specialization_id" value="<?= $record['specialization_id'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="specialization_name" class="form-label">Specialization Name</label>
                <input type="text" class="form-control" id="specialization_name" name="specialization_name" 
                      value="<?= isset($record) ? test_input($record['specialization_name']) : '' ?>" required>
            </div>
            <button type="submit" name="<?= isset($record) ? 'update_specialization' : 'add_specialization' ?>" 
                    class="btn btn-primary">
                <?= isset($record) ? 'Update' : 'Add' ?> Specialization
            </button>
            <?php if (isset($record)): ?>
                <a href="?action=view&table=specializations" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </form>
    </div>
</div>