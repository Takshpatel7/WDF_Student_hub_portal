<?php
/**
 * Practical 7: Intermediate Extension - Display Stored CSV/JSON Records on a Webpage
 * ---------------------------------------------------------------------------------
 * Reads data safely from CSV and JSON file storage and renders them in formatted,
 * interactive HTML tables with record metrics and statistics.
 */

$dataDir = __DIR__ . '/data';
$studentsCsv = $dataDir . '/students.csv';
$studentsJson = $dataDir . '/students.json';
$feedbackCsv = $dataDir . '/feedback.csv';
$feedbackJson = $dataDir . '/feedback.json';

// Function to read CSV records safely
function readCsvRecords($filepath) {
    $records = [];
    if (file_exists($filepath) && ($handle = fopen($filepath, 'r')) !== false) {
        $header = fgetcsv($handle);
        if ($header) {
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) === count($header)) {
                    $records[] = array_combine($header, $row);
                } else {
                    $records[] = $row;
                }
            }
        }
        fclose($handle);
    }
    return $records;
}

// Function to read JSON records safely
function readJsonRecords($filepath) {
    if (file_exists($filepath) && filesize($filepath) > 0) {
        $content = file_get_contents($filepath);
        $decoded = json_decode($content, true);
        if (is_array($decoded)) {
            return $decoded;
        }
    }
    return [];
}

$students = readJsonRecords($studentsJson);
if (empty($students)) {
    $students = readCsvRecords($studentsCsv);
}

$feedbackList = readJsonRecords($feedbackJson);
if (empty($feedbackList)) {
    $feedbackList = readCsvRecords($feedbackCsv);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Hub Portal - Stored Records Viewer (Practical 7)</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .record-tabs {
      display: flex;
      gap: 12px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }
    .tab-btn {
      padding: 10px 20px;
      background: #141E2E;
      color: #CBD5E1;
      border: 1px solid #6B4EFD;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.2s ease;
    }
    .tab-btn.active, .tab-btn:hover {
      background: #6B4EFD;
      color: #FFFFFF;
    }
    .tab-pane {
      display: none;
    }
    .tab-pane.active {
      display: block;
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
      <button><a href="register.html">Register</a></button>
      <button><a href="dashboard.html">Dashboard</a></button>
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
      <span>Stored Records (Practical 7)</span>
    </nav>

    <h1 style="font-size: 32px; text-align: center; margin-bottom: 6px;">CSV &amp; JSON Storage Explorer</h1>
    <p style="text-align: center; color: #CBD5E1; margin-bottom: 24px; font-size: 15px;">
      Practical 7 Intermediate Extension: Live inspection of server-side validated records stored in CSV and JSON file formats
    </p>

    <!-- Top Summary Metrics Grid -->
    <div style="display: flex; gap: 16px; margin-bottom: 24px; flex-wrap: wrap;">
      <div class="table1" style="flex: 1; min-width: 200px; padding: 18px; text-align: center;">
        <h3 style="color: #EC9F05; font-size: 16px; margin-bottom: 6px;">Registered Students</h3>
        <h2 style="font-size: 34px; color: #FFFFFF;"><?php echo count($students); ?></h2>
        <span class="badge badge-green">CSV &amp; JSON Synced</span>
      </div>
      <div class="table1" style="flex: 1; min-width: 200px; padding: 18px; text-align: center;">
        <h3 style="color: #EC9F05; font-size: 16px; margin-bottom: 6px;">Feedback Messages</h3>
        <h2 style="font-size: 34px; color: #FFFFFF;"><?php echo count($feedbackList); ?></h2>
        <span class="badge badge-blue">Archived</span>
      </div>
      <div class="table1" style="flex: 1; min-width: 200px; padding: 18px; text-align: center;">
        <h3 style="color: #EC9F05; font-size: 16px; margin-bottom: 6px;">File Storage Engine</h3>
        <h2 style="font-size: 24px; color: #DAF264; margin-top: 6px;">CSV + JSON</h2>
        <span class="badge badge-amber">Atomic File Locks (LOCK_EX)</span>
      </div>
    </div>

    <!-- Tab Navigation -->
    <div class="record-tabs">
      <button class="tab-btn active" onclick="switchTab('tab-students', this)">👨‍🎓 Student Registrations (<?php echo count($students); ?>)</button>
      <button class="tab-btn" onclick="switchTab('tab-feedback', this)">💬 Feedback &amp; Inquiries (<?php echo count($feedbackList); ?>)</button>
      <button class="tab-btn" onclick="switchTab('tab-files', this)">📁 Storage File Stats</button>
    </div>

    <!-- TAB 1: Registered Students -->
    <div id="tab-students" class="tab-pane active table1" style="padding: 24px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
        <h2 style="font-size: 22px; color: #EC9F05;">Registered Student Records</h2>
        <a href="register.html"><button style="padding: 8px 18px; font-size: 13px;">+ New Registration</button></a>
      </div>

      <?php if (empty($students)): ?>
        <p style="text-align: center; color: #CBD5E1; padding: 30px;">No registered student records found yet. Submit the registration form to create records!</p>
      <?php else: ?>
        <div class="data-table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Course</th>
                <th>Year</th>
                <th>Gender</th>
                <th>Registered At</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($students as $row): ?>
                <tr>
                  <td><strong style="color: #EC9F05;"><?php echo htmlspecialchars($row['id'] ?? $row['Student ID'] ?? 'N/A'); ?></strong></td>
                  <td><?php echo htmlspecialchars($row['name'] ?? $row['Full Name'] ?? 'N/A'); ?></td>
                  <td><?php echo htmlspecialchars($row['email'] ?? $row['Email'] ?? 'N/A'); ?></td>
                  <td><?php echo htmlspecialchars($row['mobile'] ?? $row['Mobile'] ?? 'N/A'); ?></td>
                  <td><span class="badge badge-blue"><?php echo htmlspecialchars($row['course'] ?? $row['Course'] ?? 'N/A'); ?></span></td>
                  <td>Year <?php echo htmlspecialchars($row['year'] ?? $row['Year'] ?? 'N/A'); ?></td>
                  <td><?php echo htmlspecialchars($row['gender'] ?? $row['Gender'] ?? 'N/A'); ?></td>
                  <td style="color: #94A3B8; font-size: 13px;"><?php echo htmlspecialchars($row['registered_at'] ?? $row['Registered At'] ?? 'N/A'); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <!-- TAB 2: Feedback & Inquiries -->
    <div id="tab-feedback" class="tab-pane table1" style="padding: 24px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
        <h2 style="font-size: 22px; color: #EC9F05;">Feedback &amp; Contact Inquiries</h2>
        <a href="feedback.html"><button style="padding: 8px 18px; font-size: 13px;">+ Submit Feedback</button></a>
      </div>

      <?php if (empty($feedbackList)): ?>
        <p style="text-align: center; color: #CBD5E1; padding: 30px;">No feedback submissions yet. Fill out the contact or feedback form to add entries.</p>
      <?php else: ?>
        <div class="data-table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>Ticket ID</th>
                <th>Sender Name</th>
                <th>Email</th>
                <th>Category</th>
                <th>Rating</th>
                <th>Message</th>
                <th>Submitted At</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($feedbackList as $fb): ?>
                <tr>
                  <td><strong style="color: #EC9F05;"><?php echo htmlspecialchars($fb['ticket_id'] ?? $fb['Ticket ID'] ?? 'N/A'); ?></strong></td>
                  <td><?php echo htmlspecialchars($fb['name'] ?? $fb['Name'] ?? 'N/A'); ?></td>
                  <td><?php echo htmlspecialchars($fb['email'] ?? $fb['Email'] ?? 'N/A'); ?></td>
                  <td><span class="badge badge-amber"><?php echo htmlspecialchars($fb['category'] ?? $fb['Category'] ?? 'N/A'); ?></span></td>
                  <td><?php echo isset($fb['rating']) ? str_repeat('⭐', (int)$fb['rating']) : '⭐ 5/5'; ?></td>
                  <td><?php echo htmlspecialchars($fb['message'] ?? $fb['Message'] ?? 'N/A'); ?></td>
                  <td style="color: #94A3B8; font-size: 13px;"><?php echo htmlspecialchars($fb['submitted_at'] ?? $fb['Submitted At'] ?? 'N/A'); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <!-- TAB 3: File Storage Diagnostics -->
    <div id="tab-files" class="tab-pane table1" style="padding: 24px;">
      <h2 style="font-size: 22px; color: #EC9F05; margin-bottom: 14px;">Physical Storage Files Details</h2>
      <div class="data-table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>File Name</th>
              <th>Format</th>
              <th>Status</th>
              <th>File Size</th>
              <th>Last Modified</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $filesToCheck = [
                ['name' => 'data/students.csv', 'path' => $studentsCsv, 'format' => 'CSV'],
                ['name' => 'data/students.json', 'path' => $studentsJson, 'format' => 'JSON'],
                ['name' => 'data/feedback.csv', 'path' => $feedbackCsv, 'format' => 'CSV'],
                ['name' => 'data/feedback.json', 'path' => $feedbackJson, 'format' => 'JSON'],
            ];
            foreach ($filesToCheck as $f):
                $exists = file_exists($f['path']);
                $size = $exists ? round(filesize($f['path']) / 1024, 2) . ' KB' : '0 KB';
                $modTime = $exists ? date('Y-m-d H:i:s', filemtime($f['path'])) : 'Not created yet';
            ?>
              <tr>
                <td><code><?php echo htmlspecialchars($f['name']); ?></code></td>
                <td><span class="badge <?php echo $f['format'] === 'JSON' ? 'badge-amber' : 'badge-green'; ?>"><?php echo $f['format']; ?></span></td>
                <td><?php echo $exists ? '<span style="color:#10B981;">● Present</span>' : '<span style="color:#F59E0B;">Pending writes</span>'; ?></td>
                <td><?php echo $size; ?></td>
                <td style="color: #94A3B8;"><?php echo $modTime; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
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
  <script>
    function switchTab(tabId, btn) {
      document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
      document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
      document.getElementById(tabId).classList.add('active');
      btn.classList.add('active');
    }
  </script>
</body>

</html>
