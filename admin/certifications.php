<?php
require_once __DIR__ . '/header.php';

$message = ''; $messageType = 'success'; $editId = null; $editRow = null;

if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM certifications WHERE id=?");
    $stmt->execute([$editId]);
    $editRow = $stmt->fetch();
    if (!$editRow) $editId = null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? '')) { $message = 'Invalid security token.'; $messageType = 'error'; }
    else {
        $action = $_POST['action'] ?? '';
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $date = $_POST['date'] ?? '';
        $id = (int)($_POST['id'] ?? 0);
        $imagePath = $editRow['image_path'] ?? '';

        if ($action === 'add' || $action === 'update') {
            if (!$title || !$date) { $message = 'Title and date are required.'; $messageType = 'error'; }
            else {
                if (!empty($_FILES['certificate_file']['tmp_name'])) {
                    $upload = uploadFile($_FILES['certificate_file'], 'certificates', array_merge(ALLOWED_IMAGE_TYPES, ALLOWED_PDF_TYPES));
                    if ($upload['success']) {
                        if ($imagePath && strpos($imagePath, 'certificate-sample') === false) deleteFile($imagePath);
                        $imagePath = $upload['path'];
                        logActivity($pdo, $_SESSION['admin_id'], 'UPDATE', 'certifications', $id ?: $pdo->lastInsertId(), "Uploaded certificate file");
                    } else {
                        $message = $upload['error']; $messageType = 'error';
                    }
                }
                if ($messageType !== 'error') {
                    if ($action === 'add') {
                        $maxOrder = (int)$pdo->query("SELECT COALESCE(MAX(sort_order),0) FROM certifications")->fetchColumn();
                    $pdo->prepare("INSERT INTO certifications (title, description, date, image_path, sort_order) VALUES (?, ?, ?, ?, ?)")->execute([$title, $description ?: 'Verified Completion Certificate', $date, $imagePath, $maxOrder + 1]);
                        logActivity($pdo, $_SESSION['admin_id'], 'INSERT', 'certifications', $pdo->lastInsertId(), "Added certification: $title");
                        $message = 'Certification added.'; $messageType = 'success';
                    } else {
                        $pdo->prepare("UPDATE certifications SET title=?, description=?, date=?, image_path=?, updated_at=NOW() WHERE id=?")->execute([$title, $description ?: 'Verified Completion Certificate', $date, $imagePath, $id]);
                        logActivity($pdo, $_SESSION['admin_id'], 'UPDATE', 'certifications', $id, "Updated certification: $title");
                        $message = 'Certification updated.'; $messageType = 'success';
                        redirect('certifications.php');
                    }
                }
            }
        } elseif ($action === 'delete' && $id) {
            $stmt = $pdo->prepare("SELECT title, image_path FROM certifications WHERE id=?"); $stmt->execute([$id]); $row = $stmt->fetch();
            if ($row['image_path'] && strpos($row['image_path'], 'certificate-sample') === false) deleteFile($row['image_path']);
            $pdo->prepare("DELETE FROM certifications WHERE id=?")->execute([$id]);
            logActivity($pdo, $_SESSION['admin_id'], 'DELETE', 'certifications', $id, "Deleted certification: ".($row['title'] ?? ''));
            $message = 'Certification deleted.'; $messageType = 'success';
            redirect('certifications.php');
        }
    }
}

$certifications = $pdo->query("SELECT * FROM certifications ORDER BY sort_order ASC, id DESC")->fetchAll();
?>
    <div class="table-wrap">
        <?php if ($message): ?>
            <div class="alert" style="background:rgba(119,117,255,0.1); border:1px solid var(--primary); color:var(--primary); padding:0.75rem 1rem; margin-bottom:1rem;"><?= h($message) ?></div>
        <?php endif; ?>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:1rem;">
            <h3 style="font-size:1.1rem; font-weight:800; color:var(--text-main);">Certifications & Seminars</h3>
            <a href="certifications.php<?= $editId ? '' : '?add=1' ?>" class="btn btn-sm" style="<?= $editId ? 'display:none' : '' ?>"><?= $editId ? '' : '+ Add Certification' ?></a>
            <?= $editId ? '<a href="certifications.php" class="btn btn-sm btn-outline">Cancel</a>' : '' ?>
        </div>

        <div class="form-card" style="margin-bottom:1.5rem; max-width:700px;">
            <form method="POST" enctype="multipart/form-data" action="">
                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                <input type="hidden" name="action" value="<?= $editId ? 'update' : 'add' ?>">
                <input type="hidden" name="id" value="<?= h($editId ?? 0) ?>">
                <div class="form-group">
                    <label>Certification Title</label>
                    <input type="text" name="title" value="<?= h($editRow['title'] ?? '') ?>" required placeholder="e.g. Online Safety Through Netiquette">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" name="description" value="<?= h($editRow['description'] ?? '') ?>" placeholder="Verified Completion Certificate">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" value="<?= h($editRow['date'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Certificate File (Image or PDF)</label>
                        <input type="file" name="certificate_file" accept="image/*,.pdf">
                        <div class="file-hint">JPG, PNG, WEBP, GIF or PDF. Max 5MB. Leave blank to keep existing file.</div>
                        <?php if (!empty($editRow['image_path'])): ?>
                            <div style="margin-top:0.5rem;">
                                <?php if (strtolower(pathinfo($editRow['image_path'], PATHINFO_EXTENSION)) === 'pdf'): ?>
                                    <a href="<?= h($editRow['image_path']) ?>" target="_blank" class="btn btn-sm btn-outline" style="padding:0.4rem 0.8rem; font-size:0.8rem;">View PDF</a>
                                <?php else: ?>
                                    <img src="<?= h($editRow['image_path']) ?>" class="img-preview" alt="Preview" onerror="this.style.display='none'">
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <button type="submit" class="btn"><?= $editId ? 'Update Certification' : 'Add Certification' ?></button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>File</th>
                    <th class="actions-cell">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($certifications as $c): ?>
                <tr>
                    <td><?= h($c['title']) ?></td>
                    <td><?= h($c['description']) ?></td>
                    <td><?= date('M j, Y', strtotime($c['date'])) ?></td>
                    <td><?= basename($c['image_path']) ?></td>
                    <td class="actions-cell">
                        <div class="btn-group">
                            <a href="certifications.php?edit=<?= (int)$c['id'] ?>" class="btn btn-sm btn-outline" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button onclick="return confirmDeleteCert(this, 'Delete this certification?')" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            <form method="POST" style="display:none;" id="delForm-<?= (int)$c['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (!$certifications): ?>
                <tr><td colspan="5" style="text-align:center; color:var(--text-secondary); padding:2rem;">No certifications found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<script>
function confirmDeleteCert(btn, msg) {
    if (!confirm(msg)) return false;
    const id = btn.closest('.btn-group').querySelector('input[name="id"]').value;
    document.getElementById('delForm-' + id).submit();
    return false;
}
</script>
<?php require_once __DIR__ . '/footer.php'; ?>
