<?php
/**
 * Developer Projects Dashboard
 * Overview of all your development projects
 */

require_once(__DIR__ . '/../../inc/includes.php');

// Admin-only access
if (!isset($_SESSION['admin_id'])) {
    header("Location: /admin");
    exit;
}

define('META_TITLE', 'Developer Dashboard');
define('META_DESCRIPTION', 'Your personal AI-powered development workspace');
require_once(__DIR__ . '/../../inc/header.php');
?>

<style>
.dashboard-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 3rem 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.quick-access-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s;
    cursor: pointer;
    text-align: center;
    border: 2px solid transparent;
}

.quick-access-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    border-color: #667eea;
}

.card-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: #667eea;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    color: #667eea;
}
</style>

<section class="section-spacing">
    <div class="container-fluid">

        <div class="dashboard-hero">
            <h1><i class="bi bi-rocket-takeoff"></i> Welcome to Your Developer Workspace</h1>
            <p class="lead">AI-powered development environment at your fingertips</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">4</div>
                <div>AI Employees</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo count($_SESSION['ftp_profiles'] ?? []); ?></div>
                <div>FTP Profiles</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">∞</div>
                <div>Code Generation</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">24/7</div>
                <div>Available</div>
            </div>
        </div>

        <h2 class="mb-4"><i class="bi bi-grid"></i> Quick Access</h2>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="quick-access-card" onclick="window.location.href='/dev/ai-employee/html5-dev'">
                    <div class="card-icon"><i class="bi bi-filetype-html"></i></div>
                    <h4>HTML5 Developer</h4>
                    <p>Generate semantic HTML5 markup</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="quick-access-card" onclick="window.location.href='/dev/ai-employee/css-dev'">
                    <div class="card-icon"><i class="bi bi-palette"></i></div>
                    <h4>CSS Developer</h4>
                    <p>Create modern, responsive styles</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="quick-access-card" onclick="window.location.href='/dev/ai-employee/js-dev'">
                    <div class="card-icon"><i class="bi bi-braces"></i></div>
                    <h4>JavaScript Developer</h4>
                    <p>Build interactive functionality</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="quick-access-card" onclick="window.location.href='/dev/ai-employee/fullstack-dev'">
                    <div class="card-icon"><i class="bi bi-layers"></i></div>
                    <h4>Fullstack Developer</h4>
                    <p>Complete solutions (HTML/CSS/JS/PHP)</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="quick-access-card" onclick="window.location.href='/dev/code-editor'">
                    <div class="card-icon"><i class="bi bi-code-square"></i></div>
                    <h4>Live Code Editor</h4>
                    <p>Write and preview code instantly</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="quick-access-card" onclick="window.location.href='/dev/ftp-manager'">
                    <div class="card-icon"><i class="bi bi-cloud-upload"></i></div>
                    <h4>FTP Manager</h4>
                    <p>Deploy code to remote servers</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="quick-access-card" onclick="window.open('/admin/js/plugins/kcfinder/browse.php', 'kcfinder', 'width=800,height=600')">
                    <div class="card-icon"><i class="bi bi-folder2-open"></i></div>
                    <h4>File Browser</h4>
                    <p>Browse and manage files</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="quick-access-card" onclick="window.location.href='/admin'">
                    <div class="card-icon"><i class="bi bi-gear"></i></div>
                    <h4>Settings</h4>
                    <p>Configure your workspace</p>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h2><i class="bi bi-lightbulb"></i> Quick Tips</h2>
            <div class="row g-3 mt-2">
                <div class="col-md-4">
                    <div class="alert alert-info">
                        <strong><i class="bi bi-info-circle"></i> Tip:</strong> Ask AI employees specific questions for better results
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-success">
                        <strong><i class="bi bi-lightning"></i> Pro Tip:</strong> Use the Code Editor's "Ask AI" button for quick help
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-warning">
                        <strong><i class="bi bi-shield"></i> Remember:</strong> Test code before deploying to production
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<?php require_once(__DIR__ . '/../../inc/footer.php'); ?>
