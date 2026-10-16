<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require_once __DIR__ . '/db.php';
$con = db_connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['first_name'])) {
    // Student Table
    $sql1 = $con->prepare("INSERT INTO student (first_name, last_name, email, phone, school_affiliation) VALUES (?, ?, ?, ?, ?)");
    $sql1->bind_param("sssss", $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone'], $_POST['school_affiliation']);
    $sql1->execute();
    $student_id = $con->insert_id;
    $sql1->close();
    
    // Course Table
    $sql2 = $con->prepare("INSERT INTO course (course_number, course_name) VALUES (?, ?)");
    $sql2->bind_param("ss", $_POST['course_number'], $_POST['course_name']);
    $sql2->execute();
    $course_id = $con->insert_id;
    $sql2->close();
    
    // Request Table
    $sql3 = $con->prepare("INSERT INTO request (student_id, course_id, topics, format) VALUES (?, ?, ?, ?)");
    $sql3->bind_param("iiss", $student_id, $course_id, $_POST['topics'], $_POST['format']);
    $sql3->execute();
    $sql3->close();
}

// Query to display student requests
$sql = "SELECT r.request_id, s.first_name AS student_first, s.last_name AS student_last, s.email AS student_email,
s.phone, s.school_affiliation, c.course_number, c.course_name, r.topics, r.format, tm.match_status
FROM request AS r
JOIN student AS s 
ON r.student_id = s.student_id
JOIN course AS c 
ON r.course_id = c.course_id
LEFT JOIN tutor_match AS tm 
ON r.request_id = tm.request_id
ORDER BY r.request_id DESC";

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
  <title>Students</title>
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
  <?php if (!isset($dashboard_mode)) { ?>
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
    <h2>Student Requests</h2>
    <?php } ?>
    <table>
      <tr>
        <th>Request ID</th>
        <th>Student Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>School</th>
        <th>Course Number</th>
        <th>Course Name</th>
        <th>Topics</th>
        <th>Format</th>
        <th>Match Status</th>
      </tr>

      <?php
      // display each row
      while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>" . htmlspecialchars($row['request_id']) . "</td>";
          echo "<td>" . htmlspecialchars($row['student_first'] . " " . $row['student_last']) . "</td>";
          echo "<td>" . htmlspecialchars($row['student_email']) . "</td>";
          echo "<td>" . htmlspecialchars($row['phone'] ?? 'N/A') . "</td>";
          echo "<td>" . htmlspecialchars($row['school_affiliation'] ?? 'N/A') . "</td>";
          echo "<td>" . htmlspecialchars($row['course_number']) . "</td>";
          echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
          echo "<td>" . htmlspecialchars($row['topics']) . "</td>";
          echo "<td>" . htmlspecialchars($row['format']) . "</td>";
          
          // this is to handle NULL match status
          $status = $row['match_status'] ?? "Not Matched";
          echo "<td>" . htmlspecialchars($status) . "</td>";
          
          echo "</tr>";
      }
      ?>

    </table>
    <?php if (!isset($dashboard_mode)) { ?>
  </main>
</body>
</html>
    <?php } ?>

<?php
mysqli_close($con);
?>
