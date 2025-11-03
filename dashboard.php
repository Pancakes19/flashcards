<?php
include 'config.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch user info
$stmt = $pdo->prepare("SELECT firstname, lastname FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Fetch stacks for current user
$stmt2 = $pdo->prepare("SELECT * FROM stacks WHERE user_id = ?");
$stmt2->execute([$_SESSION['user_id']]);
$stacks = $stmt2->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <style>
    body { margin: 0; background: #f9f9fb; font-family: sans-serif; }
    .tab-header { display: flex; border-bottom: 1.5px solid #e3e5ea; background: #fff; border-radius: 16px 16px 0 0; box-shadow: 0 2px 10px rgba(30,42,79,0.08); padding: 0 2em; }
    .tab-link { padding: 1.1em 2em 1em 0; font-weight: 500; font-size: 1.08em; color: #222; cursor: pointer; border: none; background: none; outline: none; border-bottom: 3px solid transparent; transition: color 0.15s, border-bottom 0.2s; }
    .tab-link.active { color: #0074d9; border-bottom: 3px solid #0074d9; }
    .tab-content { background: #fff; border-radius: 0 0 16px 16px; box-shadow: 0 2px 10px rgba(30,42,79,0.08); padding: 2em; }
    .sidebar-card { width:215px; background:#fff; padding:2em 1em 2em 1.5em; box-shadow:0 4px 16px rgba(30,42,79,0.09); border-radius:18px; min-height:80vh; display:flex; flex-direction:column; position: relative; left: 0; top: 0; bottom: 0; z-index:1000; transition:left 0.19s,box-shadow 0.15s; }
    .sidebar-card ul { list-style:none; padding-left:0; margin:0; }
    .sidebar-card li { margin-bottom: 0.5em; }
    .sidebar-card a { display:block; padding:.7em 0; color:#111; font-weight:500; text-decoration:none; }
    .sidebar-card a[style*='e05252'] { color: #e05252 !important; }
    #sidebar-toggle { position:fixed;top:28px;left:38px;z-index:1101;cursor:pointer;display:none;background:#fff;padding:8px 14px;border-radius:8px;box-shadow:0 1px 6px rgba(30,42,79,0.05); }
    @media (max-width: 900px) {
      .sidebar-card { width: 180px; padding: 1em 0.5em; }
    }
    @media (max-width: 800px) {
      .sidebar-card { position:fixed; left:-270px; top:0; bottom:0; min-height:100vh; box-shadow:none; transition:left 0.19s,box-shadow 0.15s; }
      .sidebar-card.visible { left:0; box-shadow:0 4px 16px rgba(30,42,79,0.13); }
      #sidebar-toggle { display:block; }
      main { padding-left: 0 !important; }
    }
  </style>
</head>
<body>
<div style="display:flex;min-height:100vh;">
  <div style="margin:2.5em 1.5em 2.5em 2.5em;">
    <aside id="sidebar" class="sidebar-card">
      <div style="font-weight:bold;font-size:1.3em;margin-bottom:2em;letter-spacing:1px;">Flashcards</div>
      <ul>
        <li><a href="#" style="color:#111;font-weight:600;"><span style='font-size:1.1em;'>🏠</span> Dashboard</a></li>
        <li><a href="#">📚 Collections</a></li>
        <li><a href="#">⚙️ Settings</a></li>
        <li><a href="logout.php" style="color:#e05252;">🚪 Logout</a></li>
      </ul>
    </aside>
  </div>

  <!-- Hamburger icon for toggling sidebar -->
  <div id="sidebar-toggle" onclick="toggleSidebar()">
    <div style="width:28px;height:3px;background:#222;margin:4px 0;border-radius:2px;"></div>
    <div style="width:28px;height:3px;background:#222;margin:4px 0;border-radius:2px;"></div>
    <div style="width:28px;height:3px;background:#222;margin:4px 0;border-radius:2px;"></div>
  </div>

  <main style="flex:1;padding:2.5em 2em 2em 0;">
    <h1 style="margin-top:2rem;">Welcome, <?php echo htmlspecialchars($user['firstname']) . ' ' . htmlspecialchars($user['lastname']); ?>!</h1>
    <!-- Tabbed navbar -->
    <div class="tab-header">
      <div class="tab-link active" onclick="openTab(event, 'tab1')">My Stacks</div>
      <div class="tab-link" onclick="openTab(event, 'tab2')">Favorites</div>
      <div class="tab-link" onclick="openTab(event, 'tab3')">Create Stack</div>
    </div>

    <div class="tab-content" id="tab1" style="display:block;">
      <?php if (count($stacks) === 0): ?>
        <div style="background:#f4f8fe;border-radius:12px;padding:2em;box-shadow:0 2px 8px rgba(30,42,79,0.06);display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:150px;">
          <div style="font-size:1.24em;color:#265eb8;font-weight:600;margin-bottom:0.5em;">No flashcard stacks yet</div>
          <p style="color:#555;margin-bottom:1.2em;">Ready to begin? Create your first flashcard stack now.</p>
          <a href="create_stack.php" style="background:#0074d9;color:#fff;text-decoration:none;padding:0.7em 2em;border-radius:22px;font-size:1.05em;font-weight:500;box-shadow:0 2px 7px rgba(30,42,79,0.07);">+ Create Flashcard Stack</a>
        </div>
      <?php else: ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(270px,1fr));gap:2em;">
          <?php foreach ($stacks as $stack): ?>
            <div style="background:#fff;border-radius:12px;padding:1.5em;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
              <div style="font-size:1.15em;font-weight:600;margin-bottom:1em;">
                <?php echo htmlspecialchars($stack['title']); ?>
              </div>
              <div style="color:#777;font-size:1em;margin-bottom:1em;">
                <?php echo htmlspecialchars($stack['description'] ?? 'No description'); ?>
              </div>
              <a href="#" style="color:#0074d9;text-decoration:none;font-weight:500;">View Stack</a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="tab-content" id="tab2" style="display:none;">
      <div style="color:#333;text-align:center;font-size:1.12em;">Your favorited stacks will appear here.</div>
    </div>
    <div class="tab-content" id="tab3" style="display:none;">
      <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:160px;">
        <div style="font-size:1.24em;color:#265eb8;font-weight:600;margin-bottom:0.5em;">Create a new flashcard stack</div>
        <a href="create_stack.php" style="background:#0074d9;color:#fff;text-decoration:none;padding:0.7em 2em;border-radius:22px;font-size:1.05em;font-weight:500;box-shadow:0 2px 7px rgba(30,42,79,0.07);">Go to Create Stack</a>
      </div>
    </div>

    <script>
    function openTab(evt, tabID) {
      document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
      document.querySelectorAll('.tab-link').forEach(el => el.classList.remove('active'));
      document.getElementById(tabID).style.display = 'block';
      evt.currentTarget.classList.add('active');
    }
    function toggleSidebar() {
      var sidebar = document.getElementById('sidebar');
      if (sidebar.classList.contains('visible')) {
        sidebar.classList.remove('visible');
      } else {
        sidebar.classList.add('visible');
      }
    }
    // Optional: close sidebar when clicking outside (mobile only)
    window.addEventListener('click', function(e) {
      var sidebar = document.getElementById('sidebar');
      var toggle = document.getElementById('sidebar-toggle');
      if (sidebar.classList.contains('visible') && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
        sidebar.classList.remove('visible');
      }
    });
    </script>
  </main>
</div>
</body>
</html>




