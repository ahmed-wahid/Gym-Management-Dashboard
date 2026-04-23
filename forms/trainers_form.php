

<?php
function test_input($data) {
    $data = trim($data); // يشيل المسافات اللي في الأول والآخر
    $data = stripslashes($data); // يشيل الباك سلاش  
    $data = htmlspecialchars($data); //<> يحول الرموز الخاصة لحاجات آمنة
    return $data;
}
?>



<div class="card mt-2">
    <div class="card-header">
        <h5><?= isset($record) ? 'Edit' : 'Add New' ?> Trainer</h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <?php if (isset($record)): ?>
                <input type="hidden" name="trainer_id" value="<?= $record['trainer_id'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="trainer_name" class="form-label">Trainer Name</label>
                <input type="text" class="form-control" id="trainer_name" name="trainer_name" 
                        value="<?= isset($record) ? test_input($record['trainer_name']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="email_id" class="form-label">Email</label>
                <input type="email" class="form-control" id="email_id" name="email_id" 
                        value="<?= isset($record) ? test_input($record['email_id']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="join_date" class="form-label">Join Date</label>
                <input type="date" class="form-control" id="join_date" name="join_date" 
                        value="<?= isset($record) ? test_input($record['join_date']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="salary" class="form-label">Salary</label>
                <input type="number" step="0.01" class="form-control" id="salary" name="salary" 
                        value="<?= isset($record) ? test_input($record['salary']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="branch_id" class="form-label">Branch</label>
                <select class="form-control" id="branch_id" name="branch_id">
                    <option value="">Select Branch</option>
                    <?= getOptions($conn, 'branches', 'branch_id', 'branch_name', isset($record) ? $record['branch_id'] : null) ?>
                </select>
            </div>
            <button type="submit" name="<?= isset($record) ? 'update_trainer' : 'add_trainer' ?>" 
                    class="btn btn-primary">
                <?= isset($record) ? 'Update' : 'Add' ?> Trainer
            </button>
            <?php if (isset($record)): ?>
                <a href="?action=view&table=trainers" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </form>
    </div>
</div>
