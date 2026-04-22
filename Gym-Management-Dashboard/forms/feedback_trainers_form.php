

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
        <h5><?= isset($record) ? 'Edit' : 'Add' ?> Trainer Feedback</h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <?php if (isset($record)): ?>
                <input type="hidden" name="feedback_id" value="<?= $record['feedback_id'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="member_id" class="form-label">Member</label>
                <select class="form-control" id="member_id" name="member_id" required>
                    <option value="">Select a member</option>
                    <?= getOptions($conn, 'members', 'member_id', 'CONCAT(first_name, " ", last_name)', isset($record) ? $record['member_id'] : '') ?>
                </select>
            </div>
            <div class="form-group">
                <label for="trainer_id" class="form-label">Trainer</label>
                <select class="form-control" id="trainer_id" name="trainer_id" required>
                    <?= getOptions($conn, 'trainers', 'trainer_id', 'trainer_name', isset($record) ? $record['trainer_id'] : '') ?>
                </select>
            </div>
            <div class="form-group">
                <label for="rating" class="form-label">Rating (1-5)</label>
                <select class="form-control" id="rating" name="rating" required>
                    <option value="1" <?= (isset($record) && $record['rating'] == 1) ? 'selected' : '' ?>>1 - Poor</option>
                    <option value="2" <?= (isset($record) && $record['rating'] == 2) ? 'selected' : '' ?>>2 - Fair</option>
                    <option value="3" <?= (isset($record) && $record['rating'] == 3) ? 'selected' : '' ?>>3 - Good</option>
                    <option value="4" <?= (isset($record) && $record['rating'] == 4) ? 'selected' : '' ?>>4 - Very Good</option>
                    <option value="5" <?= (isset($record) && $record['rating'] == 5) ? 'selected' : '' ?>>5 - Excellent</option>
                </select>
            </div>
            <div class="form-group">
                <label for="comments" class="form-label">Comments</label>
                <textarea class="form-control" id="comments" name="comments" rows="3"><?= isset($record) ? test_input($record['comments']) : '' ?></textarea>
            </div>
            <button type="submit" name="<?= isset($record) ? 'update_feedback_trainer' : 'add_feedback_trainer' ?>" class="btn btn-primary">
                <?= isset($record) ? 'Update' : 'Submit' ?> Feedback
            </button>
        </form>
    </div>
</div>