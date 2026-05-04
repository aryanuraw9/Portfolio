<?php
/**
 * Admin Panel — Aryan Uraw Portfolio
 * Simple dashboard to manage projects & view messages
 */

session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
require_once '../config/database.php';

// ── Auth ────────────────────────────────────────────────
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user = trim(htmlspecialchars($_POST['username'] ?? ''));
    $pass = $_POST['password'] ?? '';
    $conn = getDBConnection();
    if ($conn) {
        $stmt = $conn->prepare("SELECT id, password FROM admin_users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row && password_verify($pass, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            header("Location: index.php");
            exit;
        }
        $error = 'Invalid credentials.';
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$loggedIn = isset($_SESSION['admin_id']);

function check_csrf() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token mismatch');
    }
}

// ── Handle project add/delete ───────────────────────────
if ($loggedIn) {
    $conn = getDBConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_project']) && $conn) {
        $title    = trim(htmlspecialchars($_POST['title'] ?? ''));
        $desc     = trim(htmlspecialchars($_POST['description'] ?? ''));
        $image    = trim(htmlspecialchars($_POST['image'] ?? 'assets/images/proj1.jpg'));
        if (!preg_match('/^assets\/images\/[a-zA-Z0-9_.-]+\.(jpg|png|gif|webp)$/', $image)) {
            $image = 'assets/images/proj1.jpg'; // default if invalid
        }
        $link     = trim(htmlspecialchars($_POST['link'] ?? '#'));
        $category = in_array($_POST['category'] ?? '', ['Web','Design','Other']) ? $_POST['category'] : 'Web';
        $featured = isset($_POST['featured']) ? 1 : 0;

        $stmt = $conn->prepare("INSERT INTO projects (title,description,image,link,category,featured) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("sssssi", $title, $desc, $image, $link, $category, $featured);
        $stmt->execute();
        $message = 'Project added successfully!';
    }

    if (isset($_POST['delete']) && $conn) {
        check_csrf();
        $id = (int)$_POST['delete'];
        $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: index.php");
        exit;
    }

    if (isset($_POST['mark_read']) && $conn) {
        check_csrf();
        $id = (int)$_POST['mark_read'];
        $stmt = $conn->prepare("UPDATE contacts SET read_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: index.php#messages");
        exit;
    }

    // Fetch data
    $projects = $contacts = [];
    if ($conn) {
        $r = $conn->query("SELECT * FROM projects ORDER BY sort_order ASC, id DESC");
        while ($row = $r->fetch_assoc()) $projects[] = $row;

        $r = $conn->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 50");
        while ($row = $r->fetch_assoc()) $contacts[] = $row;
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel — Aryan Uraw Portfolio</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --bg: #080810; --bg2: #0d0d1a; --card: rgba(255,255,255,0.04);
      --border: rgba(255,255,255,0.07); --accent: #6c63ff; --accent2: #a855f7;
      --text: #f0f0ff; --text2: rgba(240,240,255,0.6); --text3: rgba(240,240,255,0.3);
      --radius: 12px; --success: #10b981; --error: #ef4444;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }
    a { color: inherit; text-decoration: none; }

    /* Login */
    .login-wrap {
      min-height: 100vh;
      display: flex; align-items: center; justify-content: center;
      padding: 2rem;
    }
    .login-card {
      width: 100%; max-width: 420px;
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 2.5rem;
    }
    .login-card h1 {
      font-size: 1.6rem; font-weight: 600; margin-bottom: 0.25rem;
    }
    .login-card p { color: var(--text2); font-size: 0.875rem; margin-bottom: 2rem; }
    .error-msg { color: var(--error); font-size: 0.85rem; margin-bottom: 1rem; background: rgba(239,68,68,0.1); padding: 0.7rem 1rem; border-radius: 8px; }
    .form-field { margin-bottom: 1.25rem; }
    .form-field label { display: block; font-size: 0.8rem; color: var(--text2); margin-bottom: 0.4rem; }
    .form-field input {
      width: 100%; padding: 0.8rem 1rem; background: rgba(255,255,255,0.05);
      border: 1px solid var(--border); border-radius: 8px;
      color: var(--text); font-size: 0.9rem; font-family: inherit;
      outline: none;
    }
    .form-field input:focus { border-color: var(--accent); }
    .btn-login {
      width: 100%; padding: 0.85rem; background: var(--accent);
      color: #fff; border: none; border-radius: 8px; font-size: 0.9rem;
      font-weight: 600; cursor: pointer; font-family: inherit;
      transition: background 0.2s;
    }
    .btn-login:hover { background: var(--accent2); }

    /* Dashboard */
    .dashboard { display: flex; min-height: 100vh; }
    .sidebar {
      width: 240px; flex-shrink: 0;
      background: var(--bg2);
      border-right: 1px solid var(--border);
      padding: 2rem 1.25rem;
      display: flex; flex-direction: column;
    }
    .sidebar-logo {
      font-size: 1.4rem; font-weight: 600; color: var(--accent);
      margin-bottom: 2rem; padding-bottom: 1.5rem;
      border-bottom: 1px solid var(--border);
    }
    .sidebar nav a {
      display: flex; align-items: center; gap: 0.75rem;
      padding: 0.65rem 0.9rem; border-radius: 8px;
      font-size: 0.875rem; color: var(--text2);
      margin-bottom: 0.25rem; transition: all 0.2s;
    }
    .sidebar nav a:hover, .sidebar nav a.active {
      background: rgba(108,99,255,0.1); color: var(--text);
    }
    .sidebar nav a i { width: 16px; color: var(--accent); }
    .sidebar-footer { margin-top: auto; }
    .btn-logout {
      width: 100%; padding: 0.65rem 0.9rem;
      background: rgba(239,68,68,0.1); border: none;
      border-radius: 8px; color: var(--error); font-size: 0.875rem;
      cursor: pointer; font-family: inherit;
      display: flex; align-items: center; gap: 0.75rem;
      transition: background 0.2s;
    }
    .btn-logout:hover { background: rgba(239,68,68,0.2); }

    .main { flex: 1; padding: 2.5rem; overflow-y: auto; }
    .page-header { margin-bottom: 2rem; }
    .page-header h2 { font-size: 1.6rem; font-weight: 600; }
    .page-header p { color: var(--text2); font-size: 0.875rem; }

    /* Cards */
    .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; margin-bottom: 2.5rem; }
    .stat-card {
      background: var(--card); border: 1px solid var(--border);
      border-radius: var(--radius); padding: 1.5rem;
    }
    .stat-card .num { font-size: 2rem; font-weight: 700; color: var(--accent); }
    .stat-card .lbl { font-size: 0.8rem; color: var(--text2); margin-top: 0.25rem; }

    /* Table */
    .table-card {
      background: var(--card); border: 1px solid var(--border);
      border-radius: var(--radius); margin-bottom: 2.5rem; overflow: hidden;
    }
    .table-card-header {
      padding: 1.25rem 1.5rem;
      display: flex; align-items: center; justify-content: space-between;
      border-bottom: 1px solid var(--border);
    }
    .table-card-header h3 { font-size: 1rem; font-weight: 600; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 0.85rem 1.25rem; text-align: left; border-bottom: 1px solid var(--border); font-size: 0.875rem; }
    th { color: var(--text3); font-weight: 500; font-size: 0.75rem; letter-spacing: 0.05em; text-transform: uppercase; }
    td { color: var(--text2); }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: rgba(255,255,255,0.02); }
    .badge {
      display: inline-block; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600;
    }
    .badge-web    { background: rgba(108,99,255,0.15); color: var(--accent); }
    .badge-design { background: rgba(168,85,247,0.15); color: #a855f7; }
    .badge-other  { background: rgba(16,185,129,0.15); color: var(--success); }
    .badge-unread { background: rgba(245,158,11,0.15); color: #f59e0b; }
    .badge-read   { background: rgba(255,255,255,0.05); color: var(--text3); }

    .btn-danger { color: var(--error); background: rgba(239,68,68,0.1); border: none; border-radius: 6px; padding: 0.3rem 0.7rem; font-size: 0.8rem; cursor: pointer; font-family: inherit; transition: background 0.2s; }
    .btn-danger:hover { background: rgba(239,68,68,0.2); }
    .btn-sm { color: var(--accent); background: var(--accent-soft,rgba(108,99,255,0.1)); border: none; border-radius: 6px; padding: 0.3rem 0.7rem; font-size: 0.8rem; cursor: pointer; font-family: inherit; transition: background 0.2s; }

    /* Add project form */
    .add-form {
      background: var(--card); border: 1px solid var(--border);
      border-radius: var(--radius); padding: 2rem; margin-bottom: 2.5rem;
    }
    .add-form h3 { font-size: 1rem; font-weight: 600; margin-bottom: 1.5rem; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .add-form .form-field input, .add-form .form-field select, .add-form .form-field textarea {
      width: 100%; padding: 0.75rem 1rem; background: rgba(255,255,255,0.05);
      border: 1px solid var(--border); border-radius: 8px;
      color: var(--text); font-size: 0.875rem; font-family: inherit;
      outline: none;
    }
    .add-form .form-field input:focus,
    .add-form .form-field select:focus,
    .add-form .form-field textarea:focus { border-color: var(--accent); }
    .add-form .form-field textarea { resize: vertical; min-height: 90px; }
    .add-form .form-field select option { background: #1a1a2e; }
    .full-col { grid-column: span 2; }
    .btn-add {
      padding: 0.75rem 1.75rem; background: var(--accent);
      color: #fff; border: none; border-radius: 8px;
      font-size: 0.875rem; font-weight: 600; cursor: pointer;
      font-family: inherit; transition: background 0.2s;
    }
    .btn-add:hover { background: var(--accent2); }
    .success-msg { color: var(--success); font-size: 0.85rem; margin-bottom: 1rem; background: rgba(16,185,129,0.1); padding: 0.7rem 1rem; border-radius: 8px; }
    .checkbox-row { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text2); }
    .checkbox-row input[type=checkbox] { width: auto; }

    @media (max-width: 768px) {
      .dashboard { flex-direction: column; }
      .sidebar { width: 100%; }
      .stats-row { grid-template-columns: 1fr 1fr; }
      .form-grid { grid-template-columns: 1fr; }
      .full-col { grid-column: auto; }
    }
  </style>
</head>
<body>

<?php if (!$loggedIn): ?>
<!-- ── Login ──────────────────────────────────────────── -->
<div class="login-wrap">
  <div class="login-card">
    <h1>Admin Panel</h1>
    <p>Aryan Uraw Portfolio Dashboard</p>
    <?php if ($error): ?><div class="error-msg"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <form method="POST">
      <div class="form-field">
        <label>Username</label>
        <input type="text" name="username" autocomplete="username" required>
      </div>
      <div class="form-field">
        <label>Password</label>
        <input type="password" name="password" autocomplete="current-password" required>
      </div>
      <button type="submit" name="login" class="btn-login">Sign In</button>
    </form>
  </div>
</div>

<?php else: ?>
<!-- ── Dashboard ──────────────────────────────────────── -->
<div class="dashboard">
  <aside class="sidebar">
    <div class="sidebar-logo">Aryan Admin</div>
    <nav>
      <a href="#projects"  class="active"><i class="fas fa-folder"></i> Projects</a>
      <a href="#messages"><i class="fas fa-envelope"></i> Messages</a>
      <a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Site</a>
    </nav>
    <div class="sidebar-footer">
      <form method="POST">
        <button type="submit" name="logout" class="btn-logout">
          <i class="fas fa-sign-out-alt"></i> Sign Out
        </button>
      </form>
    </div>
  </aside>

  <main class="main">
    <div class="page-header">
      <h2>Dashboard</h2>
      <p>Manage your portfolio content</p>
    </div>

    <!-- Stats -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="num"><?php echo count($projects); ?></div>
        <div class="lbl">Total Projects</div>
      </div>
      <div class="stat-card">
        <div class="num"><?php echo count(array_filter($contacts, fn($c) => !$c['read_at'])); ?></div>
        <div class="lbl">Unread Messages</div>
      </div>
      <div class="stat-card">
        <div class="num"><?php echo count($contacts); ?></div>
        <div class="lbl">Total Messages</div>
      </div>
    </div>

    <!-- Add Project -->
    <div class="add-form" id="projects">
      <h3>Add New Project</h3>
      <?php if (!empty($message)): ?><div class="success-msg"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
      <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="form-grid">
          <div class="form-field">
            <label>Title *</label>
            <input type="text" name="title" required placeholder="Project Title">
          </div>
          <div class="form-field">
            <label>Category</label>
            <select name="category">
              <option value="Web">Web</option>
              <option value="Design">Design</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div class="form-field full-col">
            <label>Description *</label>
            <textarea name="description" required placeholder="Brief project description..."></textarea>
          </div>
          <div class="form-field">
            <label>Image Path</label>
            <input type="text" name="image" placeholder="assets/images/proj1.jpg">
          </div>
          <div class="form-field">
            <label>Project URL</label>
            <input type="text" name="link" placeholder="https://example.com">
          </div>
          <div class="form-field full-col">
            <label class="checkbox-row">
              <input type="checkbox" name="featured" value="1">
              Mark as Featured
            </label>
          </div>
        </div>
        <button type="submit" name="add_project" class="btn-add">Add Project</button>
      </form>
    </div>

    <!-- Projects Table -->
    <div class="table-card">
      <div class="table-card-header">
        <h3>Projects (<?php echo count($projects); ?>)</h3>
      </div>
      <table>
        <thead>
          <tr><th>#</th><th>Title</th><th>Category</th><th>Featured</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach ($projects as $p): ?>
            <tr>
              <td><?php echo $p['id']; ?></td>
              <td><?php echo htmlspecialchars($p['title']); ?></td>
              <td><span class="badge badge-<?php echo strtolower($p['category']); ?>"><?php echo $p['category']; ?></span></td>
              <td><?php echo $p['featured'] ? '⭐' : '—'; ?></td>
              <td>
                <form method="post" onsubmit="return confirm('Delete this project?')">
                  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                  <input type="hidden" name="delete" value="<?php echo $p['id']; ?>">
                  <button type="submit" class="btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Messages -->
    <div class="table-card" id="messages">
      <div class="table-card-header">
        <h3>Contact Messages</h3>
      </div>
      <table>
        <thead>
          <tr><th>Name</th><th>Email</th><th>Message</th><th>Date</th><th>Status</th></tr>
        </thead>
        <tbody>
          <?php foreach ($contacts as $c): ?>
            <tr>
              <td><?php echo htmlspecialchars($c['name']); ?></td>
              <td><?php echo htmlspecialchars($c['email']); ?></td>
              <td style="max-width:300px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?php echo htmlspecialchars($c['message']); ?></td>
              <td><?php echo date('M d, Y', strtotime($c['created_at'])); ?></td>
              <td>
                <?php if (!$c['read_at']): ?>
                  <form method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="mark_read" value="<?php echo $c['id']; ?>">
                    <button type="submit" class="badge badge-unread">Unread</button>
                  </form>
                <?php else: ?>
                  <span class="badge badge-read">Read</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($contacts)): ?>
            <tr><td colspan="5" style="text-align:center;color:var(--text3);padding:2rem;">No messages yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
<?php endif; ?>

</body>
</html>
