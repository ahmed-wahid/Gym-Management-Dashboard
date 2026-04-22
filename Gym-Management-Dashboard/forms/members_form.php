
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
        <h5><?= isset($record) ? 'Edit' : 'Add New' ?> Member</h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <?php if (isset($record)): ?>
                <input type="hidden" name="member_id" value="<?= $record['member_id'] ?>">
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" 
                                value="<?= isset($record) ? test_input($record['first_name']) : '' ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" 
                                value="<?= isset($record) ? test_input($record['last_name']) : '' ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="weight" class="form-label">Weight (kg)</label>
                        <input type="number" step="0.01" class="form-control" id="weight" name="weight" 
                                value="<?= isset($record) ? test_input($record['weight']) : '' ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="height" class="form-label">Height (cm)</label>
                        <input type="number" step="0.01" class="form-control" id="height" name="height" 
                                value="<?= isset($record) ? test_input($record['height']) : '' ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="Male" <?= (isset($record) && $record['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= (isset($record) && $record['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone_no" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone_no" name="phone_no" 
                                value="<?= isset($record) ? htmlspecialchars($record['phone_no']) : '' ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email_id" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email_id" name="email_id" 
                                value="<?= isset($record) ? htmlspecialchars($record['email_id']) : '' ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="branch_id" class="form-label">Branch</label>
                        <select class="form-control" id="branch_id" name="branch_id" required>
                            <?= getOptions($conn, 'branches', 'branch_id', 'branch_name', isset($record) ? $record['branch_id'] : null) ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="trainer_id" class="form-label">Trainer (Optional)</label>
                        <select class="form-control" id="trainer_id" name="trainer_id">
                            <option value="">No Trainer</option>
                            <?= getOptions($conn, 'trainers', 'trainer_id', 'trainer_name', isset($record) ? $record['trainer_id'] : null) ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="membership_id" class="form-label">Membership Plan</label>
                        <select class="form-control" id="membership_id" name="membership_id" required>
                            <?= getOptions($conn, 'memberships', 'id_plan', 'plan_name', isset($record) ? $record['membership_id'] : null) ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="date_of_birth" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                        value="<?= isset($record) ? htmlspecialchars($record['date_of_birth']) : '' ?>" required>
            </div>
            
            <button type="submit" name="<?= isset($record) ? 'update_member' : 'add_member' ?>" 
                    class="btn btn-primary">
                <?= isset($record) ? 'Update' : 'Add' ?> Member
            </button>
            <?php if (isset($record)): ?>
                <a href="?action=view&table=members" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </form>
    </div>
</div>