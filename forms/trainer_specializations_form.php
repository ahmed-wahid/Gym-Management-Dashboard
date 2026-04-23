



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
        <h5><?= isset($record) ? 'Edit' : 'Assign' ?> Specialization to Trainer</h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <?php if (isset($record)): ?>
                <input type="hidden" name="original_trainer_id" value="<?= $record['trainer_id'] ?>">
                <input type="hidden" name="original_specialization_id" value="<?= $record['specialization_id'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="trainer_id" class="form-label">Trainer</label>
                <select class="form-control" id="trainer_id" name="trainer_id" required>
                    <?php
                    $trainers_result = $conn->query("SELECT trainer_id, trainer_name FROM trainers ORDER BY trainer_name");
                    while ($trainer = $trainers_result->fetch_assoc()) {
                        $selected = (isset($record) && $record['trainer_id'] == $trainer['trainer_id']) ? 'selected' : '';
                        echo "<option value='" . $trainer['trainer_id'] . "' $selected>" . test_input($trainer['trainer_name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="specialization_id" class="form-label">Specialization</label>
                <select class="form-control" id="specialization_id" name="specialization_id" required>
                    <?php
                    $specializations_result = $conn->query("SELECT specialization_id, specialization_name FROM specializations ORDER BY specialization_name");
                    while ($specialization = $specializations_result->fetch_assoc()) {
                        $selected = (isset($record) && $record['specialization_id'] == $specialization['specialization_id']) ? 'selected' : '';
                        echo "<option value='" . $specialization['specialization_id'] . "' $selected>" . test_input($specialization['specialization_name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="<?= isset($record) ? 'update_trainer_specialization' : 'add_trainer_specialization' ?>" class="btn btn-primary">
                <?= isset($record) ? 'Update' : 'Assign' ?> Specialization
            </button>
            <?php if (isset($record)): ?>
                <a href="?action=view&table=trainer_specializations" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </form>
    </div>
</div>