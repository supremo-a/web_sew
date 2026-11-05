<?php
require_once __DIR__ . '/includes/header.php';

$profile = getProfile($pdo);
$education = getEducation($pdo);
$skills = getSkills($pdo);
$certifications = getCertifications($pdo);
?>
    <main>
        <!-- ABOUT -->
        <section id="about" class="section about-section">
            <div class="container">
                <div class="section-header">
                    <h2><i class="bi bi-person-circle"></i> About Me</h2>
                    <p class="section-sub">Get to know me</p>
                </div>
                <div class="about-card">
                    <div class="profile-image-wrapper">
                        <img src="<?= h($profile['profile_image'] ?? 'assets/images/profile.jpg') ?>" 
                             alt="Profile" class="profile-img" 
                             onerror="this.src='assets/images/profile-placeholder.svg'">
                        <div class="profile-glow"></div>
                    </div>
                    <div class="profile-info">
                        <h1 class="profile-name"><?= h($profile['full_name'] ?? 'Laurence Andrew B. Perlas') ?></h1>
                        <p class="profile-position"><?= h($profile['position'] ?? 'Web Developer & IT Student') ?></p>
                        <div class="contact-grid">
                            <a href="mailto:<?= h($profile['email'] ?? '') ?>" class="contact-item">
                                <i class="bi bi-envelope"></i>
                                <span><?= h($profile['email'] ?? 'xlaurence02@example.com') ?></span>
                            </a>
                            <a href="tel:<?= h($profile['phone'] ?? '') ?>" class="contact-item">
                                <i class="bi bi-telephone"></i>
                                <span><?= h($profile['phone'] ?? '+63 962 251 8929') ?></span>
                            </a>
                            <a href="<?= h($profile['github_url'] ?? '#') ?>" target="_blank" class="contact-item">
                                <i class="bi bi-github"></i>
                                <span><?= h(parse_url($profile['github_url'] ?? '', PHP_URL_HOST) ?? 'GitHub') ?></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- EDUCATION -->
        <section id="education" class="section education-section">
            <div class="container">
                <div class="section-header">
                    <h2><i class="bi bi-mortarboard"></i> Education</h2>
                    <p class="section-sub">Academic background</p>
                </div>
                <div class="education-grid">
                    <?php foreach ($education as $edu): ?>
                    <div class="edu-card">
                        <div class="edu-badge"><?= h($edu['category']) ?></div>
                        <div class="edu-icon"><i class="bi bi-award"></i></div>
                        <h3 class="edu-school"><?= h($edu['school']) ?></h3>
                        <p class="edu-course"><?= h($edu['course']) ?></p>
                        <span class="edu-status"><?= h($edu['status']) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- SKILLS -->
        <section id="skills" class="section skills-section">
            <div class="container">
                <div class="section-header">
                    <h2><i class="bi bi-code-square"></i> Skills</h2>
                    <p class="section-sub">Technical skills</p>
                </div>
<div class="skills-grid">
                        <?php
                        $skillLogos = [
                            'PHP' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg',
                            'Laravel' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/laravel/laravel-original.svg',
                            'MySQL' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg',
                            'VB.NET' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/dot-net/dot-net-original.svg',
                            'Tailwind CSS' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tailwindcss/tailwindcss-original.svg',
                            'Git & GitHub' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/github/github-original.svg',
                        ];
                        ?>
                        <?php foreach ($skills as $skill): ?>
                        <div class="skill-card">
                            <img src="<?= $skillLogos[$skill['name']] ?? '' ?>" alt="<?= h($skill['name']) ?>" class="skill-logo" style="width:32px;height:32px;margin-right:8px;vertical-align:middle;">
                            <span class="skill-name"><?= h($skill['name']) ?></span>
                        </div>
                        <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- CERTIFICATIONS -->
        <section id="certifications" class="section certifications-section">
            <div class="container">
                <div class="section-header">
                    <h2><i class="bi bi-diploma"></i> Certifications & Seminars</h2>
                    <p class="section-sub">Verified achievements</p>
                </div>
                <div class="certifications-grid">
                    <?php foreach ($certifications as $cert): ?>
                    <div class="cert-card">
                        <div class="cert-image-wrapper">
                            <?php if (str_ends_with($cert['image_path'] ?? '', '.pdf')): ?>
                            <embed src="<?= h($cert['image_path']) ?>" type="application/pdf" class="cert-img" style="width:100%;height:200px;">
                            <?php else: ?>
                            <img src="<?= h($cert['image_path'] ?? 'assets/images/certificate-sample.jpg') ?>" 
                                 alt="Certificate" class="cert-img" 
                                 onerror="this.src='assets/images/certificate-placeholder.svg'">
                            <?php endif; ?>
                            <div class="cert-icon-overlay"><i class="bi bi-file-earmark-medal"></i></div>
                        </div>
                        <div class="cert-content">
                            <h3 class="cert-title"><?= h($cert['title']) ?></h3>
                            <p class="cert-desc"><?= h($cert['description']) ?></p>
                            <span class="cert-date"><?= date('F j, Y', strtotime($cert['date'])) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <!-- PROJECTS -->
    <section id="projects" class="section projects-section">
        <div class="container">
            <div class="section-header">
                <h2><i class="bi bi-kanban"></i> Projects</h2>
                <p class="section-sub">Featured work</p>
            </div>
            <div class="project-card">
                <img src="assets/images/project1.png" alt="BDRPIS System" class="project-img">
                <img src="assets/images/project2.png" alt="BDRPIS System" class="project-img">
                <h3 class="project-title">BDRPIS System</h3>
                <p class="project-subtitle">Barangay Directory &amp; Document Request and Public Information System</p>
                <a href="http://barangay-request.infinityfree.io/" target="_blank" class="project-link">http://barangay-request.infinityfree.io/</a>
            </div>
        </div>
    </section>
    </main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
