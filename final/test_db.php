<?php
/**
 * Practical 8: Database Connection Test & Prepared Statement Demo
 * ---------------------------------------------------------------
 * Tests PDO database connectivity, validates database schema,
 * executes prepared statements, and presents test results for lab submission.
 */

require_once __DIR__ . '/db.php';

$testResults = [
    'connection'    => false,
    'server_version'=> 'Unknown',
    'student_count' => 0,
    'event_count'   => 0,
    'reg_count'     => 0,
    'sample_data'   => [],
    'error_message' => null
];

if ($dbConnected && $pdo instanceof PDO) {
    $testResults['connection'] = true;
    try {
        // 1. Fetch Server Version
        $stmt = $pdo->query("SELECT VERSION() AS ver");
        $verRow = $stmt->fetch();
        $testResults['server_version'] = $verRow['ver'] ?? 'MySQL 8.x';

        // 2. Prepared Statement 1: Count Students
        $stmtStudents = $pdo->prepare("SELECT COUNT(*) AS total FROM `students`");
        $stmtStudents->execute();
        $testResults['student_count'] = $stmtStudents->fetchColumn();

        // 3. Prepared Statement 2: Count Events
        $stmtEvents = $pdo->prepare("SELECT COUNT(*) AS total FROM `events`");
        $stmtEvents->execute();
        $testResults['event_count'] = $stmtEvents->fetchColumn();

        // 4. Prepared Statement 3: Count Registrations
        $stmtReg = $pdo->prepare("SELECT COUNT(*) AS total FROM `registrations`");
        $stmtReg->execute();
        $testResults['reg_count'] = $stmtReg->fetchColumn();

        // 5. Prepared Statement 4: Parameterized JOIN query (Prepared Statement Demo)
        $limit = 5;
        $joinSql = "SELECT 
                        s.student_id, 
                        s.name AS student_name, 
                        e.title AS event_title, 
                        e.category, 
                        r.status AS reg_status, 
                        r.registered_at
                    FROM `registrations` r
                    INNER JOIN `students` s ON r.student_id = s.id
                    INNER JOIN `events` e ON r.event_id = e.id
                    ORDER BY r.registered_at DESC
                    LIMIT :limit";
        $joinStmt = $pdo->prepare($joinSql);
        $joinStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $joinStmt->execute();
        $testResults['sample_data'] = $joinStmt->fetchAll();

    } catch (PDOException $ex) {
        $testResults['error_message'] = $ex->getMessage();
    }
} else {
    $testResults['error_message'] = $dbError;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Hub Portal - Database Connection Test (Practical 8)</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .status-card {
      padding: 24px;
      border-radius: 14px;
      margin-bottom: 24px;
      border-left: 6px solid;
    }
    .status-connected {
      background: rgba(16, 185, 129, 0.12);
      border-color: #10B981;
    }
    .status-disconnected {
      background: rgba(239, 68, 68, 0.12);
      border-color: #EF4444;
    }
  </style>
</head>

<body>
  <a href="#main-content" class="skip-link">Skip to main content</a>

  <header>
    <div class="header">
      <a href="index.html"><img src="logo.jpg" alt="Student Hub Portal Logo" class="logo"></a>
      <strong>Student Hub Portal</strong>
    </div>
    <div class="headerlinks">
      <button onclick="toggleTheme()" aria-label="Toggle Theme">🌓 Theme</button>
      <button><a href="dashboard.html">Dashboard</a></button>
      <button><a href="view_records.php">Records</a></button>
    </div>
  </header>

  <div class="navlinks">
    <button class="hamburger" onclick="toggleMenu()">☰ Menu</button>
    <nav id="nav-menu" align="center">
      <a href="index.html">Home</a>
      <a href="about.html">About</a>
      <a href="events.html">Events</a>
      <a href="dashboard.html">Dashboard</a>
      <a href="faq.html">FAQ</a>
      <a href="contact.html">Contact</a>
      <a href="feedback.html">Feedback</a>
    </nav>
  </div>

  <main id="main-content" style="max-width: 1150px; width: 100%; margin: 20px auto; padding: 10px;">
    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" style="font-size: 13px; color: #CBD5E1; margin-bottom: 12px;">
      <a href="index.html" style="color: #EC9F05; text-decoration: none;">Home</a> &raquo;
      <span>Database Connection Test (Practical 8)</span>
    </nav>

    <h1 style="font-size: 32px; text-align: center; margin-bottom: 6px;">MySQL &amp; PDO Connectivity Diagnostic</h1>
    <p style="text-align: center; color: #CBD5E1; margin-bottom: 24px; font-size: 15px;">
      Practical 8 Submission: Verification of MySQL Schema, PDO Connection, and Prepared Statement Queries
    </p>

    <!-- CONNECTION STATUS BANNER -->
    <?php if ($testResults['connection']): ?>
      <div class="status-card status-connected table1">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
          <div>
            <h2 style="color: #10B981; font-size: 22px; margin-bottom: 4px;">✅ Database Connected Successfully!</h2>
            <p style="color: #CBD5E1; font-size: 14px;">
              PHP PDO established connection to MySQL database <code><?php echo htmlspecialchars($dbName); ?></code> on <code><?php echo htmlspecialchars($dbHost . ':' . $dbPort); ?></code>.
            </p>
          </div>
          <div>
            <span class="badge badge-green" style="font-size: 14px; padding: 6px 14px;">PDO Status: ACTIVE</span>
          </div>
        </div>
      </div>

      <!-- METRICS GRID -->
      <div style="display: flex; gap: 16px; margin-bottom: 24px; flex-wrap: wrap;">
        <div class="table1" style="flex: 1; min-width: 180px; padding: 18px; text-align: center;">
          <h3 style="color: #EC9F05; font-size: 15px; margin-bottom: 6px;">Students Table</h3>
          <h2 style="font-size: 34px;"><?php echo $testResults['student_count']; ?></h2>
          <p style="font-size: 12px; color: #CBD5E1;">Normalized Records</p>
        </div>
        <div class="table1" style="flex: 1; min-width: 180px; padding: 18px; text-align: center;">
          <h3 style="color: #EC9F05; font-size: 15px; margin-bottom: 6px;">Events Table</h3>
          <h2 style="font-size: 34px;"><?php echo $testResults['event_count']; ?></h2>
          <p style="font-size: 12px; color: #CBD5E1;">Campus Activities</p>
        </div>
        <div class="table1" style="flex: 1; min-width: 180px; padding: 18px; text-align: center;">
          <h3 style="color: #EC9F05; font-size: 15px; margin-bottom: 6px;">Registrations Table</h3>
          <h2 style="font-size: 34px;"><?php echo $testResults['reg_count']; ?></h2>
          <p style="font-size: 12px; color: #CBD5E1;">Foreign Key Links</p>
        </div>
        <div class="table1" style="flex: 1; min-width: 180px; padding: 18px; text-align: center;">
          <h3 style="color: #EC9F05; font-size: 15px; margin-bottom: 6px;">MySQL Version</h3>
          <h3 style="font-size: 20px; color: #DAF264; margin-top: 8px;"><?php echo htmlspecialchars($testResults['server_version']); ?></h3>
          <p style="font-size: 12px; color: #CBD5E1;">UTF8MB4 Unicode</p>
        </div>
      </div>

      <!-- PREPARED STATEMENT DEMO TABLE -->
      <div class="table1" style="padding: 24px; margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
          <h2 style="font-size: 20px; color: #EC9F05;">Prepared Statement Query Demonstration (Intermediate Extension)</h2>
          <span class="badge badge-amber">SQL Injection Safe</span>
        </div>
        <p style="color: #CBD5E1; font-size: 14px; margin-bottom: 16px;">
          Query executed via PDO prepared statement: <code>SELECT ... FROM registrations JOIN students JOIN events LIMIT :limit</code>
        </p>

        <div class="data-table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>Student Code</th>
                <th>Student Name</th>
                <th>Event Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Registered Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($testResults['sample_data'] as $row): ?>
                <tr>
                  <td><strong style="color: #EC9F05;"><?php echo htmlspecialchars($row['student_id']); ?></strong></td>
                  <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                  <td><?php echo htmlspecialchars($row['event_title']); ?></td>
                  <td><span class="badge badge-blue"><?php echo htmlspecialchars($row['category']); ?></span></td>
                  <td><span class="badge badge-green"><?php echo htmlspecialchars($row['reg_status']); ?></span></td>
                  <td style="color: #94A3B8;"><?php echo htmlspecialchars($row['registered_at']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    <?php else: ?>
      <!-- DISCONNECTED STATE & STEP-BY-STEP XAMPP SETUP INSTRUCTIONS -->
      <div class="status-card status-disconnected table1">
        <h2 style="color: #EF4444; font-size: 22px; margin-bottom: 8px;">⚠️ MySQL Server Not Connected Yet</h2>
        <p style="color: #CBD5E1; font-size: 14px; margin-bottom: 12px;">
          <strong>Error message:</strong> <code><?php echo htmlspecialchars($testResults['error_message'] ?? 'Connection refused'); ?></code>
        </p>
        <p style="color: #CBD5E1; font-size: 14px;">
          This occurs when MySQL service has not been started yet in XAMPP or the <code>studenthub_db</code> database has not yet been imported. Follow the quick steps below:
        </p>
      </div>

      <div class="table1" style="padding: 26px; margin-bottom: 24px;">
        <h2 style="color: #EC9F05; font-size: 22px; margin-bottom: 16px;">Quick 3-Step Setup Instructions for MySQL (Practical 8)</h2>
        
        <div style="display: flex; flex-direction: column; gap: 16px;">
          <div style="background-color: #141E2E; padding: 16px; border-radius: 10px; border-left: 4px solid #EC9F05;">
            <strong style="color: #DAF264; font-size: 16px;">Step 1: Start Apache &amp; MySQL in XAMPP Control Panel</strong>
            <p style="color: #CBD5E1; font-size: 14px; margin-top: 4px;">
              Open <strong>XAMPP Control Panel</strong> and click the <strong>Start</strong> button next to both <strong>Apache</strong> and <strong>MySQL</strong>.
            </p>
          </div>

          <div style="background-color: #141E2E; padding: 16px; border-radius: 10px; border-left: 4px solid #EC9F05;">
            <strong style="color: #DAF264; font-size: 16px;">Step 2: Import the Database Schema</strong>
            <p style="color: #CBD5E1; font-size: 14px; margin-top: 4px;">
              Open phpMyAdmin at <a href="http://localhost/phpmyadmin" target="_blank" style="color: #EC9F05; text-decoration: underline;">http://localhost/phpmyadmin</a>, click <strong>Import</strong>, select the file <code>schema.sql</code>, and click <strong>Go</strong>.
              <br><em>(Alternatively run: <code>mysql -u root &lt; schema.sql</code>)</em>
            </p>
          </div>

          <div style="background-color: #141E2E; padding: 16px; border-radius: 10px; border-left: 4px solid #EC9F05;">
            <strong style="color: #DAF264; font-size: 16px;">Step 3: Refresh this Test Page</strong>
            <p style="color: #CBD5E1; font-size: 14px; margin-top: 4px;">
              Reload this page (<code>test_db.php</code>). It will instantly connect and display green status with all tables and records! Take a screenshot for your Practical 8 lab submission.
            </p>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Schema Architecture Card -->
    <div class="table1" style="padding: 24px;">
      <h3 style="color: #EC9F05; font-size: 20px; margin-bottom: 12px;">Practical 8 Relational Schema Overview</h3>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
        <div style="background-color: #141E2E; padding: 14px; border-radius: 10px;">
          <h4 style="color: #DAF264; margin-bottom: 6px;">1. students Table</h4>
          <p style="color: #CBD5E1; font-size: 13px;">Stores student profiles. Primary key: <code>id</code>. Unique keys: <code>student_id</code>, <code>email</code>.</p>
        </div>
        <div style="background-color: #141E2E; padding: 14px; border-radius: 10px;">
          <h4 style="color: #DAF264; margin-bottom: 6px;">2. events Table</h4>
          <p style="color: #CBD5E1; font-size: 13px;">Stores campus events &amp; workshops. Primary key: <code>id</code>.</p>
        </div>
        <div style="background-color: #141E2E; padding: 14px; border-radius: 10px;">
          <h4 style="color: #DAF264; margin-bottom: 6px;">3. registrations Table</h4>
          <p style="color: #CBD5E1; font-size: 13px;">M:N Junction linking students &amp; events with Foreign Keys and <code>ON DELETE CASCADE</code>.</p>
        </div>
      </div>
    </div>
  </main>

  <div class="footer">
    <footer align="center">
      <p>
        <a href="about.html">About</a> &bull;
        <a href="contact.html">Contact</a> &bull;
        <a href="feedback.html">Feedback</a> &bull;
        Copyright &copy; Student Hub Portal
      </p>
    </footer>
  </div>

  <script src="script.js"></script>
</body>

</html>
