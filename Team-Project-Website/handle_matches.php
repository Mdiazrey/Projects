<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (empty($_SESSION['overseer_authenticated'])
        || empty($_SESSION['csrf_token'])
        || !hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        http_response_code(403);
        exit('You must sign in through the overseer dashboard to create a match.');
    }
}

require_once __DIR__ . '/db.php';
$con = db_connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = intval($_POST['request_id']);
    $tutor_id = intval($_POST['tutor_id']);
    $match_status = $con->real_escape_string($_POST['match_status']);
    $payment_status = $con->real_escape_string($_POST['payment_status']);
    $notes = $con->real_escape_string($_POST['notes']);

    $overseer_id = null; 

    // Insert into tutor_match
    $stmt = $con->prepare("
        INSERT INTO tutor_match (request_id, tutor_id, match_status, payment_status, notes, overseer_id)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iisssi", $request_id, $tutor_id, $match_status, $payment_status, $notes, $overseer_id);
    $stmt->execute();
    $stmt->close();
}

$sql = "SELECT 
tm.match_id,
s.first_name AS student_first, 
s.last_name AS student_last,
t.first_name AS tutor_first, 
t.last_name AS tutor_last,
c.course_number,
c.course_name,
tm.match_status,
tm.payment_status,
tm.notes
FROM tutor_match AS tm
JOIN request AS r ON tm.request_id = r.request_id
JOIN student AS s ON r.student_id = s.student_id
JOIN tutor AS t ON tm.tutor_id = t.tutor_id
JOIN course AS c ON r.course_id = c.course_id
ORDER BY tm.match_id DESC";

// Run query
$result = mysqli_query($con, $sql);

if (!$result) {
    echo "Query error: " . mysqli_error($con);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Matches</title>
  <style>
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, Helvetica, sans-serif;
    }

    body {
      background: linear-gradient(135deg, #6b73ff, #000dff);
      color: #f1f1f1;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    
    header {
      width: 100%;
      background: rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(5px);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      position: sticky;
      top: 0;
      z-index: 10;
    }

    header h1 {
      text-align: center;
      font-size: 1.8rem;
      padding: 1rem;
      color: #fff;
    }

    
    nav ul {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      list-style: none;
      background: rgba(255, 255, 255, 0.05);
    }

    nav li {
      margin: 0.5rem;
    }

    nav a {
      text-decoration: none;
      color: #f1f1f1;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    nav a:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    
    main {
      max-width: 95%;
      background: rgba(255, 255, 255, 0.1);
      margin: 3rem 1rem;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
      text-align: center;
    }

    main h2 {
      font-size: 1.6rem;
      margin-bottom: 1.5rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.3);
      padding-bottom: 0.5rem;
    }

    
    table {
      width: 100%;
      border-collapse: collapse;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    th, td {
      padding: 0.8rem 1rem;
      text-align: left;
      color: #e5e7ff;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    th {
      background: rgba(0, 0, 0, 0.3);
      color: #fff;
      text-align: center;
      font-size: 0.9rem;
    }

    tr:last-child td {
      border-bottom: none;
    }

    tr:hover {
      background: rgba(255, 255, 255, 0.08);
      transition: 0.2s;
    }

    .no-matches {
      padding: 2rem;
      font-size: 1.1rem;
      color: #e5e7ff;
    }

  
    @media (max-width: 600px) {
      header h1 {
        font-size: 1.5rem;
      }

      main {
        padding: 1.5rem;
      }

      table, th, td {
        font-size: 0.85rem;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1>Student Tutoring Help</h1>
    <nav>
      <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="student-form.html">Student Form</a></li>
        <li><a href="tutor-form.html">Tutor Form</a></li>
        <li><a href="handle_tutors.php">Tutors</a></li>
        <li><a href="handle_students.php">Students</a></li>
        <li><a href="handle_matches.php">Matches</a></li>
        <li><a href="payment-form.php">Payment Status Report</a></li>
        <li><a href="faq.html">FAQ</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <h2>Current Matches</h2>
    <?php if (mysqli_num_rows($result) > 0): ?>
    <table>
      <tr>
        <th>Match ID</th>
        <th>Student</th>
        <th>Tutor</th>
        <th>Course Number</th>
        <th>Course Name</th>
        <th>Match Status</th>
        <th>Payment Status</th>
        <th>Notes</th>
      </tr>

      <?php
      // Display each row
      while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>" . htmlspecialchars($row['match_id']) . "</td>";
          echo "<td>" . htmlspecialchars($row['student_first'] . " " . $row['student_last']) . "</td>";
          echo "<td>" . htmlspecialchars($row['tutor_first'] . " " . $row['tutor_last']) . "</td>";
          echo "<td>" . htmlspecialchars($row['course_number']) . "</td>";
          echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
          echo "<td>" . htmlspecialchars($row['match_status'] ?? 'N/A') . "</td>";
          echo "<td>" . htmlspecialchars($row['payment_status'] ?? 'N/A') . "</td>";
          echo "<td>" . htmlspecialchars($row['notes'] ?? 'N/A') . "</td>";
          echo "</tr>";
      }
      ?>

    </table>
    <?php else: ?>
    <div class="no-matches">
      <p>No matches found yet. Check back later!</p>
    </div>
    <?php endif; ?>
  </main>
</body>
</html>

<?php
mysqli_close($con);
?>
