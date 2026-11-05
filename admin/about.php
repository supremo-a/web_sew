<?php
require_once __DIR__ . '/header.php';

$profile = $pdo->query("SELECT * FROM profile LIMIT 1")->fetch();
$message = '';
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $github = trim($_POST['github_url'] ?? '');

    if (!$fullName || !$position || !$email || !$phone) {
        $message = 'Please fill in all required fields.';
        $messageType = 'error';
    } else {
        $imagePath = $profile['profile_image'] ?? 'assets/images/profile.jpg';
        if (!empty($_FILES['profile_image']['tmp_name'])) {
            $upload = uploadFile($_FILES['profile_image'], 'profile', ALLOWED_IMAGE_TYPES);
            if ($upload['success']) {
                // delete old image if not default
                if ($imagePath && strpos($imagePath, 'profile.jpg') === false) {
                    deleteFile($imagePath);
                }
                $imagePath = $upload['path'];
                logActivity($pdo, $_SESSION['admin_id'], 'UPDATE', 'profile', $profile['id'], 'Updated profile picture');
            } else {
                $message = $upload['error'];
                $messageType = 'error';
            }
        }

        if ($messageType !== 'error') {
            $stmt = $pdo->prepare("UPDATE profile SET full_name=?, position=?, email=?, phone=?, github_url=?, profile_image=?, updated_at=NOW() WHERE id=?");
            $stmt->execute([$fullName, $position, $email, $phone, $github, $imagePath, $profile['id']]);
            logActivity($pdo, $_SESSION['admin_id'], 'UPDATE', 'profile', $profile['id'], 'Updated profile information');
            $message = 'Profile updated successfully.';
            $profile = $pdo->query("SELECT * FROM profile LIMIT 1")->fetch();
        }
    }
}
?>
    <div class="form-card">
        <?php if ($message): ?>
            <div class="alert" style="background:rgba(119,117,255,0.1); border:1px solid var(--primary); color:var(--primary); margin-bottom:1rem;"><?= h($message) ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data" id="aboutForm">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?= h($profile['full_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Position</label>
                <input type="text" name="position" value="<?= h($profile['position'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= h($profile['email'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" value="<?= h($profile['phone'] ?? '') ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>GitHub URL</label>
                <input type="url" name="github_url" value="<?= h($profile['github_url'] ?? '') ?>" placeholder="https://github.com/username">
            </div>
            <div class="form-group">
                <label>Profile Picture</label>
                <input type="file" name="profile_image" accept="image/*" onchange="previewImage(this, 'profilePreview')">
                <div class="file-hint">Upload a circular profile image (JPG, PNG, WEBP, GIF). Max 5MB.</div>
                <?php if (!empty($profile['profile_image'])): ?>
                    <img src="<?= h($profile['profile_image']) ?>" id="profilePreview" class="img-preview" alt="Preview" onerror="this.style.display='none'">
                <?php else: ?>
                    <img id="profilePreview" class="img-preview" alt="Preview" style="display:none;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn">Save Changes</button>
            <a href="index.php" class="btn btn-outline" style="margin-left:0.5rem;">Cancel</a>
        </form>
    </div>
<script>
function previewImage(input, previewId) {
    const file = input.files[0];
    const preview = document.getElementById(previewId);
    if (!file || !preview) return;
    const reader = new FileReader();
    reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
    reader.readAsDataURL(file);
}
</script>
<?php require_once __DIR__ . '/footer.php'; ?>
