<?php
require_once __DIR__ . '/header.php';

$message = ''; $messageType = 'success'; $editId = null; $editRow = null;

if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM skills WHERE id=?");
    $stmt->execute([$editId]);
    $editRow = $stmt->fetch();
    if (!$editRow) $editId = null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? '')) { $message = 'Invalid security token.'; $messageType = 'error'; }
    else {
        $action = $_POST['action'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $id = (int)($_POST['id'] ?? 0);
        if ($action === 'add') {
            if (!$name) { $message = 'Skill name is required.'; $messageType = 'error'; }
            else {
                $maxOrder = (int)$pdo->query("SELECT COALESCE(MAX(sort_order),0) FROM skills")->fetchColumn();
                $pdo->prepare("INSERT INTO skills (name, sort_order) VALUES (?, ?)")->execute([$name, $maxOrder + 1]);
                logActivity($pdo, $_SESSION['admin_id'], 'INSERT', 'skills', $pdo->lastInsertId(), "Added skill: $name");
                $message = 'Skill added.'; $messageType = 'success';
            }
        } elseif ($action === 'update' && $id) {
            if (!$name) { $message = 'Skill name is required.'; $messageType = 'error'; }
            else {
                $pdo->prepare("UPDATE skills SET name=?, updated_at=NOW() WHERE id=?")->execute([$name, $id]);
                logActivity($pdo, $_SESSION['admin_id'], 'UPDATE', 'skills', $id, "Updated skill: $name");
                $message = 'Skill updated.'; $messageType = 'success';
                redirect('skills.php');
            }
        } elseif ($action === 'delete' && $id) {
            $stmt = $pdo->prepare("SELECT name FROM skills WHERE id=?"); $stmt->execute([$id]); $row = $stmt->fetch();
            $pdo->prepare("DELETE FROM skills WHERE id=?")->execute([$id]);
            logActivity($pdo, $_SESSION['admin_id'], 'DELETE', 'skills', $id, "Deleted skill: ".($row['name'] ?? ''));
            $message = 'Skill deleted.'; $messageType = 'success';
            redirect('skills.php');
        }
    }
}

$skills = $pdo->query("SELECT * FROM skills ORDER BY sort_order ASC, id ASC")->fetchAll();
?>
    <div class="table-wrap">
        <?php if ($message): ?>
            <div class="alert" style="background:rgba(119,117,255,0.1); border:1px solid var(--primary); color:var(--primary); padding:0.75rem 1rem; margin-bottom:1rem;"><?= h($message) ?></div>
        <?php endif; ?>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:1rem;">
            <h3 style="font-size:1.1rem; font-weight:800; color:var(--text-main);">Skills</h3>
            <a href="skills.php<?= $editId ? '' : '?add=1' ?>" class="btn btn-sm" style="<?= $editId ? 'display:none' : '' ?>"><?= $editId ? '' : '+ Add Skill' ?></a>
            <?= $editId ? '<a href="skills.php" class="btn btn-sm btn-outline">Cancel</a>' : '' ?>
        </div>

        <div class="form-card" style="margin-bottom:1.5rem; max-width:500px;">
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                <input type="hidden" name="action" value="<?= $editId ? 'update' : 'add' ?>">
                <input type="hidden" name="id" value="<?= h($editId ?? 0) ?>">
                <div class="form-group">
                    <label>Skill Name</label>
                    <input type="text" name="name" value="<?= h($editRow['name'] ?? '') ?>" required placeholder="e.g. PHP">
                </div>
                <button type="submit" class="btn"><?= $editId ? 'Update Skill' : 'Add Skill' ?></button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Skill Name</th>
                    <th class="actions-cell">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($skills as $s): ?>
                <tr>
                    <td><?= (int)$s['id'] ?></td>
                    <td><?= h($s['name']) ?></td>
                    <td class="actions-cell">
                        <div class="btn-group">
                            <a href="skills.php?edit=<?= (int)$s['id'] ?>" class="btn btn-sm btn-outline" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button onclick="return confirmDeleteSkill(this, 'Delete this skill?')" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            <form method="POST" style="display:none;" id="delForm-<?= (int)$s['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (!$skills): ?>
                <tr><td colspan="3" style="text-align:center; color:var(--text-secondary); padding:2rem;">No skills found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<script>
function confirmDeleteSkill(btn, msg) {
    if (!confirm(msg)) return false;
    const id = btn.closest('.btn-group').querySelector('input[name="id"]').value;
    document.getElementById('delForm-' + id).submit();
    return false;
}
</script>
<?php require_once __DIR__ . '/footer.php'; ?>
