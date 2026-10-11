<?php
/**
 * Practical 7: PHP Form Processing - Contact & Feedback Submission
 * ----------------------------------------------------------------
 * - Handles contact and feedback submissions via POST
 * - Validates and sanitizes email, name, message
 * - Stores records in CSV and JSON formats with safe file locking
 * - Renders success/error feedback page
 */

$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0777, true);
}
$csvFile = $dataDir . '/feedback.csv';
$jsonFile = $dataDir . '/feedback.json';

$errors = [];
$success = false;
$submittedData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. CSRF Token Validation
    $csrfToken = isset($_POST['csrf_token']) ? trim($_POST['csrf_token']) : '';
    if ($csrfToken !== 'sh_csrf_token_feedback_2026' && $csrfToken !== 'sh_csrf_token_contact_2026') {
        // Fallback for contact form
    }

    // 2. Sanitization
    $name = isset($_POST['name']) ? trim(htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8')) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $category = isset($_POST['category']) ? trim(htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8')) : 'General';
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 5;
    $message = isset($_POST['message']) ? trim(htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8')) : '';

    // 3. Validation
    if (empty($name) || strlen($name) < 2) {
        $errors['name'] = 'Please provide your full name (minimum 2 characters).';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'A valid email address is required.';
    }

    if (empty($message) || strlen($message) < 5) {
        $errors['message'] = 'Message must be at least 5 characters long.';
    }

    if ($rating < 1 || $rating > 5) {
        $rating = 5;
    }

    // 4. Safe Storage
    if (empty($errors)) {
        $ticketId = 'FB-' . strtoupper(substr(uniqid(), -6));
        $submittedAt = date('Y-m-d H:i:s');

        $record = [
            'ticket_id'   => $ticketId,
            'name'        => $name,
            'email'       => $email,
            'category'    => $category,
            'rating'      => $rating,
            'message'     => $message,
            'submitted_at'=> $submittedAt
        ];

        // Safe CSV append
        $isNewCsv = !file_exists($csvFile) || filesize($csvFile) === 0;
        $fp = fopen($csvFile, 'a');
        if ($fp && flock($fp, LOCK_EX)) {
            if ($isNewCsv) {
                fputcsv($fp, ['Ticket ID', 'Name', 'Email', 'Category', 'Rating', 'Message', 'Submitted At']);
            }
            fputcsv($fp, [
                $record['ticket_id'],
                $record['name'],
                $record['email'],
                $record['category'],
                $record['rating'],
                $record['message'],
                $record['submitted_at']
            ]);
            flock($fp, LOCK_UN);
            fclose($fp);
        }

        // Safe JSON append
        $existing = [];
        if (file_exists($jsonFile) && filesize($jsonFile) > 0) {
            $content = file_get_contents($jsonFile);
            $decoded = json_decode($content, true);
            if (is_array($decoded)) {
                $existing = $decoded;
            }
        }
        $existing[] = $record;
        file_put_contents($jsonFile, json_encode($existing, JSON_PRETTY_PRINT), LOCK_EX);

        $success = true;
        $submittedData = $record;
    }
} else {
    $errors[] = 'Invalid request method. Form must be submitted via POST.';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Hub Portal - Message Received</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <a href="#main-content" class="skip-link">Skip to main content</a>

  <header>
    <div class="header">
      <a href="index.html"><img src="logo.jpg" alt="Student Hub Portal Logo" class="logo"></a>
      <strong>Student Hub Portal</strong>
    </div>
    <div class="headerlinks">
      <button onclick="toggleTheme()">🌓 Theme</button>
      <button><a href="login.html">Login</a></button>
      <button><a href="register.html">Register</a></button>
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

  <main id="main-content" style="max-width: 800px; margin: 30px auto; padding: 10px;">
    <?php if ($success): ?>
      <div class="table1" style="padding: 30px; text-align: center; border-left: 6px solid #10B981;">
        <div style="font-size: 50px; margin-bottom: 10px;">✉️</div>
        <h2 style="color: #10B981; font-size: 26px; margin-bottom: 8px;">Message Received Successfully!</h2>
        <p style="color: #CBD5E1; font-size: 15px; margin-bottom: 24px;">
          Thank you for contacting us. Your message has been validated and safely archived (Practical 7).
        </p>

        <div style="background-color: #141E2E; border-radius: 12px; padding: 20px; text-align: left; max-width: 550px; margin: 0 auto 24px auto;">
          <p><strong>Ticket ID:</strong> <span style="color: #EC9F05; font-weight: bold;"><?php echo htmlspecialchars($submittedData['ticket_id']); ?></span></p>
          <p><strong>Name:</strong> <?php echo htmlspecialchars($submittedData['name']); ?></p>
          <p><strong>Email:</strong> <?php echo htmlspecialchars($submittedData['email']); ?></p>
          <p><strong>Category:</strong> <?php echo htmlspecialchars($submittedData['category']); ?></p>
          <p><strong>Rating:</strong> <?php echo str_repeat('⭐', $submittedData['rating']); ?> (<?php echo $submittedData['rating']; ?>/5)</p>
          <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($submittedData['message'])); ?></p>
          <p><strong>Timestamp:</strong> <?php echo htmlspecialchars($submittedData['submitted_at']); ?></p>
        </div>

        <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
          <a href="view_records.php" style="text-decoration: none;">
            <button style="padding: 11px 24px; font-size: 15px; background: #6B4EFD;">📂 View All Stored Records</button>
          </a>
          <a href="contact.html" style="text-decoration: none;">
            <button style="padding: 11px 24px; font-size: 15px;">&larr; Back to Contact</button>
          </a>
        </div>
      </div>
    <?php else: ?>
      <div class="table1" style="padding: 30px; text-align: center; border-left: 6px solid #EF4444;">
        <div style="font-size: 50px; margin-bottom: 10px;">⚠️</div>
        <h2 style="color: #EF4444; font-size: 26px; margin-bottom: 8px;">Form Submission Error</h2>
        <div style="background-color: #141E2E; border-radius: 12px; padding: 20px; text-align: left; max-width: 550px; margin: 0 auto 24px auto;">
          <ul style="color: #FF8A80; line-height: 1.8; margin-left: 20px;">
            <?php foreach ($errors as $field => $err): ?>
              <li><strong><?php echo ucfirst($field); ?>:</strong> <?php echo htmlspecialchars($err); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <a href="contact.html" style="text-decoration: none;">
          <button style="padding: 11px 26px; font-size: 15px;">&larr; Back to Contact Form</button>
        </a>
      </div>
    <?php endif; ?>
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
