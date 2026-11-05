<?php
require_once __DIR__ . '/header.php';
$recent = $pdo->query("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 8")->fetchAll();
?>
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-mortarboard"></i></div>
            <div>
                <div class="stat-value"><?= (int)$totalEdu ?></div>
                <div class="stat-label">Education Records</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-code-square"></i></div>
            <div>
                <div class="stat-value"><?= (int)$totalSkills ?></div>
                <div class="stat-label">Skills</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-diploma"></i></div>
            <div>
                <div class="stat-value"><?= (int)$totalCerts ?></div>
                <div class="stat-label">Certifications</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-cloud-arrow-up"></i></div>
            <div>
                <div class="stat-value"><?= (int)$totalUploads ?></div>
                <div class="stat-label">Uploaded Certificates</div>
            </div>
        </div>
    </div>

    <div class="quick-grid">
        <div class="quick-card">
            <h3>About Management</h3>
            <p>Edit profile name, position, email, phone, GitHub, and profile picture.</p>
            <a href="about.php" class="btn">Manage About</a>
        </div>
        <div class="quick-card">
            <h3>Education Management</h3>
            <p>Add, edit, or delete education records.</p>
            <a href="education.php" class="btn">Manage Education</a>
        </div>
        <div class="quick-card">
            <h3>Skills Management</h3>
            <p>Add, edit, or delete technical skills.</p>
            <a href="skills.php" class="btn">Manage Skills</a>
        </div>
        <div class="quick-card">
            <h3>Certifications</h3>
            <p>Manage certifications and upload certificate files.</p>
            <a href="certifications.php" class="btn">Manage Certifications</a>
        </div>
        <div class="quick-card">
            <h3>Website Settings</h3>
            <p>Update logo text, footer text, and site appearance.</p>
            <a href="settings.php" class="btn">Manage Settings</a>
        </div>
        <div class="quick-card">
            <h3>View Live Site</h3>
            <p>Open the public portfolio to review changes.</p>
            <a href="../index.php" class="btn btn-outline" target="_blank">Open Site</a>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Admin</th>
                    <th>Action</th>
                    <th>Table</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($recent): foreach ($recent as $log): ?>
                <tr>
                    <td><?= date('M j, Y g:i A', strtotime($log['created_at'])) ?></td>
                    <td><?= h($log['admin_id'] ? $pdo->query("SELECT full_name FROM admins WHERE id=".$log['admin_id'])->fetchColumn() ?? 'Unknown' : 'System') ?></td>
                    <td><span class="badge badge-primary"><?= h($log['action']) ?></span></td>
                    <td><?= h($log['table_name']) ?></td>
                    <td><?= h($log['description']) ?></td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5" style="text-align:center; color:var(--text-secondary); padding:2rem;">No recent activities.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php require_once __DIR__ . '/footer.php'; ?>
