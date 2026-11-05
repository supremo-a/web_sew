<?php
require_once __DIR__ . '/header.php';

$message = '';
$messageType = 'success';
$editId = null;
$editRow = null;

if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM education WHERE id=?");
    $stmt->execute([$editId]);
    $editRow = $stmt->fetch();
    if (!$editRow) { $editId = null; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log('EDU POST: ' . print_r($_POST, true) . "\nCSRF_SESS: " . var_export($_SESSION['csrf_token'] ?? null, true), 3, 'C:\xampp\htdocs\sew_web\edu_debug.log');
    if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
        $message = 'Invalid security token.'; $messageType = 'error';
    } else {
        $action = $_POST['action'] ?? '';
        $category = trim($_POST['category'] ?? '');
        $school = trim($_POST['school'] ?? '');
        $course = trim($_POST['course'] ?? '');
        $status = trim($_POST['status'] ?? '');
        $id = (int)($_POST['id'] ?? 0);

        if ($action === 'add') {
            if (!$category || !$school || !$course || !$status) {
                $message = 'All fields are required.'; $messageType = 'error';
            } else {
                $maxOrder = (int)$pdo->query("SELECT COALESCE(MAX(sort_order),0) FROM education")->fetchColumn();
                $stmt = $pdo->prepare("INSERT INTO education (category, school, course, status, sort_order) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$category, $school, $course, $status, $maxOrder + 1]);
                logActivity($pdo, $_SESSION['admin_id'], 'INSERT', 'education', $pdo->lastInsertId(), "Added education: $school");
                $message = 'Education record added.'; $messageType = 'success';
            }
        } elseif ($action === 'update' && $id) {
            if (!$category || !$school || !$course || !$status) {
                $message = 'All fields are required.'; $messageType = 'error';
            } else {
                $stmt = $pdo->prepare("UPDATE education SET category=?, school=?, course=?, status=?, updated_at=NOW() WHERE id=?");
                $stmt->execute([$category, $school, $course, $status, $id]);
                logActivity($pdo, $_SESSION['admin_id'], 'UPDATE', 'education', $id, "Updated education: $school");
                $message = 'Education record updated.'; $messageType = 'success';
                redirect('education.php');
            }
        } elseif ($action === 'delete' && $id) {
            $stmt = $pdo->prepare("SELECT school FROM education WHERE id=?");
            $stmt->execute([$id]); $row = $stmt->fetch();
            $pdo->prepare("DELETE FROM education WHERE id=?")->execute([$id]);
            logActivity($pdo, $_SESSION['admin_id'], 'DELETE', 'education', $id, "Deleted education: ".($row['school'] ?? ''));
            $message = 'Education record deleted.'; $messageType = 'success';
            redirect('education.php');
        }
    }
}

$education = $pdo->query("SELECT * FROM education ORDER BY sort_order ASC, id ASC")->fetchAll();
?>
    <div class="table-wrap">
        <?php if ($message): ?>
            <div class="alert" style="background:rgba(119,117,255,0.1); border:1px solid var(--primary); color:var(--primary); padding:0.75rem 1rem; margin-bottom:1rem;"><?= h($message) ?></div>
        <?php endif; ?>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:1rem;">
            <h3 style="font-size:1.1rem; font-weight:800; color:var(--text-main);">Education Records</h3>
            <a href="education.php<?= $editId ? '' : '?add=1' ?>" class="btn btn-sm" style="<?= $editId ? 'display:none' : '' ?>"><?= $editId ? '' : '+ Add Education' ?></a>
            <?= $editId ? '<a href="education.php" class="btn btn-sm btn-outline">Cancel</a>' : '' ?>
        </div>

        <!-- Add / Edit Form -->
        <div class="form-card" style="margin-bottom:1.5rem; max-width:700px;">
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                <input type="hidden" name="action" value="<?= $editId ? 'update' : 'add' ?>">
                <input type="hidden" name="id" value="<?= h($editId ?? 0) ?>">
                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap:1rem; margin-bottom:1rem;">
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" name="category" value="<?= h($editRow['category'] ?? '') ?>" required placeholder="e.g. COLLEGE">
                    </div>
                    <div class="form-group">
                        <label>School</label>
                        <input type="text" name="school" value="<?= h($editRow['school'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Course</label>
                    <input type="text" name="course" value="<?= h($editRow['course'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Status / Graduation</label>
                    <input type="text" name="status" value="<?= h($editRow['status'] ?? '') ?>" required placeholder="e.g. Expected Graduation - 2027">
                </div>
                <button type="submit" class="btn"><?= $editId ? 'Update Record' : 'Add Record' ?></button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>School</th>
                    <th>Course</th>
                    <th>Status</th>
                    <th>Order</th>
                    <th class="actions-cell">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($education as $edu): ?>
                <tr>
                    <td><span class="badge badge-primary"><?= h($edu['category']) ?></span></td>
                    <td><?= h($edu['school']) ?></td>
                    <td><?= h($edu['course']) ?></td>
                    <td><?= h($edu['status']) ?></td>
                    <td><?= (int)$edu['sort_order'] ?></td>
                    <td class="actions-cell">
                        <div class="btn-group">
                            <a href="education.php?edit=<?= (int)$edu['id'] ?>" class="btn btn-sm btn-outline" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button onclick="return confirmDelete('Delete this education record?')" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            <form method="POST" style="display:none;" id="delForm-<?= (int)$edu['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int)$edu['id'] ?>">
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (!$education): ?>
                <tr><td colspan="6" style="text-align:center; color:var(--text-secondary); padding:2rem;">No education records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<script>
function confirmDelete(msg) {
    if (confirm(msg)) {
        const id = event.currentTarget.closest('.btn-group').querySelector('form').querySelector('input[name="id"]').value;
        document.getElementById('delForm-' + id).submit();
    }
    return false;
}
</script>
<?php require_once __DIR__ . '/footer.php'; ?>
