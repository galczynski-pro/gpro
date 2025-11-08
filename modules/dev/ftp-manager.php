<?php
/**
 * FTP Manager with KCFinder Integration
 * Deploy code directly from workspace to remote servers via FTP
 */

require_once(__DIR__ . '/../../inc/includes.php');

// Admin-only access
if (!isset($_SESSION['admin_id'])) {
    header("Location: /admin");
    exit;
}

define('META_TITLE', 'FTP Manager - Deploy Code');
define('META_DESCRIPTION', 'Deploy your code to remote servers via FTP');
require_once(__DIR__ . '/../../inc/header.php');

// Handle file operations
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'save_ftp_profile':
                // Save FTP profile to database or config file
                $profile = [
                    'name' => $_POST['profile_name'] ?? '',
                    'host' => $_POST['ftp_host'] ?? '',
                    'port' => $_POST['ftp_port'] ?? 21,
                    'username' => $_POST['ftp_username'] ?? '',
                    'password' => base64_encode($_POST['ftp_password'] ?? ''),
                    'remote_path' => $_POST['remote_path'] ?? '/',
                    'passive' => isset($_POST['ftp_passive'])
                ];

                // Save to session for now (you can save to DB later)
                if (!isset($_SESSION['ftp_profiles'])) {
                    $_SESSION['ftp_profiles'] = [];
                }
                $_SESSION['ftp_profiles'][$profile['name']] = $profile;

                $message = 'FTP profile saved successfully!';
                $messageType = 'success';
                break;

            case 'deploy_file':
                // Deploy file via FTP
                $profileName = $_POST['profile_name'] ?? '';
                $localFile = $_POST['local_file'] ?? '';
                $remoteFile = $_POST['remote_file'] ?? '';

                if (isset($_SESSION['ftp_profiles'][$profileName])) {
                    $profile = $_SESSION['ftp_profiles'][$profileName];

                    $result = deployFileViaFTP(
                        $profile,
                        $localFile,
                        $remoteFile
                    );

                    if ($result['success']) {
                        $message = 'File deployed successfully!';
                        $messageType = 'success';
                    } else {
                        $message = 'Deployment failed: ' . $result['error'];
                        $messageType = 'danger';
                    }
                }
                break;
        }
    }
}

// Get saved FTP profiles
$ftpProfiles = $_SESSION['ftp_profiles'] ?? [];

function deployFileViaFTP($profile, $localFile, $remoteFile) {
    try {
        $conn = ftp_connect($profile['host'], $profile['port'], 30);

        if (!$conn) {
            return ['success' => false, 'error' => 'Could not connect to FTP server'];
        }

        $login = ftp_login($conn, $profile['username'], base64_decode($profile['password']));

        if (!$login) {
            ftp_close($conn);
            return ['success' => false, 'error' => 'FTP login failed'];
        }

        if ($profile['passive']) {
            ftp_pasv($conn, true);
        }

        // Change to remote directory
        if (!empty($profile['remote_path']) && $profile['remote_path'] !== '/') {
            ftp_chdir($conn, $profile['remote_path']);
        }

        // Upload file
        $upload = ftp_put($conn, $remoteFile, $localFile, FTP_BINARY);

        ftp_close($conn);

        if ($upload) {
            return ['success' => true];
        } else {
            return ['success' => false, 'error' => 'File upload failed'];
        }

    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
?>

<style>
.ftp-manager {
    max-width: 1400px;
    margin: 0 auto;
}

.ftp-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.split-view {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.file-browser {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 1rem;
    min-height: 400px;
    background: #fafafa;
}

.file-item {
    padding: 0.75rem;
    margin: 0.5rem 0;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.file-item:hover {
    background: #e3f2fd;
    transform: translateX(5px);
}

.file-item.selected {
    background: #2196f3;
    color: white;
}

.ftp-profile-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    cursor: pointer;
    transition: transform 0.2s;
}

.ftp-profile-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.ftp-profile-card.active {
    border: 3px solid #ffd700;
}

.code-preview {
    background: #282c34;
    color: #abb2bf;
    padding: 1.5rem;
    border-radius: 8px;
    max-height: 400px;
    overflow-y: auto;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    line-height: 1.6;
}

.deploy-button {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 8px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s;
    width: 100%;
    margin-top: 1rem;
}

.deploy-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(17, 153, 142, 0.4);
}

.deploy-button:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-badge.connected {
    background: #4caf50;
    color: white;
}

.status-badge.disconnected {
    background: #f44336;
    color: white;
}
</style>

<section class="section-spacing">
    <div class="container-fluid ftp-manager">

        <div class="mb-4">
            <h1><i class="bi bi-cloud-upload"></i> FTP Manager</h1>
            <p class="lead">Deploy your code to remote servers via FTP</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- FTP Profiles Section -->
        <div class="ftp-section">
            <h3><i class="bi bi-server"></i> FTP Profiles</h3>

            <div class="row mt-3">
                <div class="col-md-8">
                    <div class="row">
                        <?php if (empty($ftpProfiles)): ?>
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> No FTP profiles yet. Create one to get started!
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($ftpProfiles as $name => $profile): ?>
                                <div class="col-md-6">
                                    <div class="ftp-profile-card" onclick="selectProfile('<?php echo $name; ?>')">
                                        <h5><i class="bi bi-hdd-network"></i> <?php echo htmlspecialchars($name); ?></h5>
                                        <p class="mb-1"><strong>Host:</strong> <?php echo htmlspecialchars($profile['host']); ?>:<?php echo $profile['port']; ?></p>
                                        <p class="mb-0"><strong>User:</strong> <?php echo htmlspecialchars($profile['username']); ?></p>
                                        <span class="status-badge disconnected mt-2">Not Connected</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#newProfileModal">
                        <i class="bi bi-plus-circle"></i> New FTP Profile
                    </button>
                </div>
            </div>
        </div>

        <!-- File Deployment Section -->
        <div class="ftp-section">
            <h3><i class="bi bi-arrow-up-circle"></i> Deploy Files</h3>

            <div class="split-view">
                <!-- Local Files (KCFinder Integration) -->
                <div>
                    <h5>Local Files</h5>
                    <div class="file-browser" id="localFiles">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="searchLocal" placeholder="Search files...">
                        </div>
                        <div id="fileList">
                            <!-- Files will be loaded here via JavaScript/KCFinder -->
                            <div class="file-item" onclick="selectFile('/home/user/gpro/index.html')">
                                <i class="bi bi-file-code"></i>
                                <span>index.html</span>
                            </div>
                            <div class="file-item" onclick="selectFile('/home/user/gpro/style/main.css')">
                                <i class="bi bi-file-code"></i>
                                <span>style/main.css</span>
                            </div>
                            <div class="file-item" onclick="selectFile('/home/user/gpro/js/app.js')">
                                <i class="bi bi-file-code"></i>
                                <span>js/app.js</span>
                            </div>
                        </div>
                        <button class="btn btn-secondary w-100 mt-3" onclick="openFileBrowser()">
                            <i class="bi bi-folder2-open"></i> Browse with KCFinder
                        </button>
                    </div>
                </div>

                <!-- Remote Path & Deploy -->
                <div>
                    <h5>Deployment Configuration</h5>
                    <form method="POST" id="deployForm">
                        <input type="hidden" name="action" value="deploy_file">

                        <div class="mb-3">
                            <label class="form-label">FTP Profile</label>
                            <select name="profile_name" class="form-select" required id="profileSelect">
                                <option value="">Select profile...</option>
                                <?php foreach ($ftpProfiles as $name => $profile): ?>
                                    <option value="<?php echo htmlspecialchars($name); ?>">
                                        <?php echo htmlspecialchars($name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Local File</label>
                            <input type="text" name="local_file" class="form-control" id="localFileInput" readonly required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Remote Path</label>
                            <input type="text" name="remote_file" class="form-control" placeholder="/public_html/index.html" required>
                        </div>

                        <div class="code-preview" id="codePreview">
                            <em>Select a file to preview...</em>
                        </div>

                        <button type="submit" class="deploy-button" id="deployBtn" disabled>
                            <i class="bi bi-rocket-takeoff"></i> Deploy to Server
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="ftp-section">
            <h3><i class="bi bi-lightning"></i> Quick Actions</h3>
            <div class="row">
                <div class="col-md-3">
                    <button class="btn btn-outline-primary w-100" onclick="deployFromAI()">
                        <i class="bi bi-robot"></i> Deploy from AI Chat
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-success w-100" onclick="backupSite()">
                        <i class="bi bi-download"></i> Backup Remote Site
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-info w-100" onclick="syncDirectory()">
                        <i class="bi bi-arrow-repeat"></i> Sync Directory
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-warning w-100" onclick="viewLogs()">
                        <i class="bi bi-file-text"></i> Deployment Logs
                    </button>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- New Profile Modal -->
<div class="modal fade" id="newProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle"></i> New FTP Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="save_ftp_profile">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Profile Name</label>
                            <input type="text" name="profile_name" class="form-control" placeholder="My Website" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">FTP Host</label>
                            <input type="text" name="ftp_host" class="form-control" placeholder="ftp.example.com" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Port</label>
                            <input type="number" name="ftp_port" class="form-control" value="21" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="ftp_username" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="ftp_password" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remote Path (optional)</label>
                        <input type="text" name="remote_path" class="form-control" placeholder="/public_html" value="/">
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ftp_passive" id="ftpPassive" checked>
                        <label class="form-check-label" for="ftpPassive">
                            Use Passive Mode (recommended)
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let selectedFile = null;
let selectedProfile = null;

function selectFile(filePath) {
    selectedFile = filePath;
    document.getElementById('localFileInput').value = filePath;
    document.getElementById('deployBtn').disabled = false;

    // Highlight selected file
    document.querySelectorAll('.file-item').forEach(item => {
        item.classList.remove('selected');
    });
    event.target.closest('.file-item').classList.add('selected');

    // Load and preview file content (simplified)
    loadFilePreview(filePath);
}

function selectProfile(profileName) {
    selectedProfile = profileName;
    document.getElementById('profileSelect').value = profileName;
}

function loadFilePreview(filePath) {
    // In production, load actual file content via AJAX
    document.getElementById('codePreview').innerHTML = '<em>Loading preview...</em>';

    // Simulate file preview (replace with actual AJAX call)
    setTimeout(() => {
        document.getElementById('codePreview').innerHTML = `
            <strong>File:</strong> ${filePath}<br>
            <strong>Size:</strong> 2.4 KB<br>
            <strong>Modified:</strong> ${new Date().toLocaleString()}<br><br>
            <em>Content preview would appear here...</em>
        `;
    }, 500);
}

function openFileBrowser() {
    // Open KCFinder in a new window/modal
    window.open('/admin/js/plugins/kcfinder/browse.php', 'kcfinder', 'width=800,height=600');
}

function deployFromAI() {
    window.location.href = '/dev/ai-employee/fullstack-dev';
}

function backupSite() {
    alert('Backup functionality coming soon!');
}

function syncDirectory() {
    alert('Directory sync functionality coming soon!');
}

function viewLogs() {
    alert('Deployment logs functionality coming soon!');
}

// Search functionality
document.getElementById('searchLocal')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.file-item').forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(searchTerm) ? 'flex' : 'none';
    });
});
</script>

<?php require_once(__DIR__ . '/../../inc/footer.php'); ?>
