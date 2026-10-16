<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require_once __DIR__ . '/db.php';
$con = db_connect();

$payment_status = $_POST['payment_status'] ?? '';

// Query #5 with filter
$sql = "SELECT 
            s.first_name AS student_first, 
            s.last_name AS student_last, 
            t.first_name AS tutor_first, 
            t.last_name AS tutor_last, 
            c.course_name, 
            tm.payment_status
        FROM tutor_match AS tm
        JOIN tutor AS t ON tm.tutor_id = t.tutor_id
        JOIN request AS r ON tm.request_id = r.request_id
        JOIN student AS s ON r.student_id = s.student_id
        JOIN course AS c ON r.course_id = c.course_id
        WHERE tm.payment_status = ?";

$statement = $con->prepare($sql);
$statement->bind_param('s', $payment_status);
$statement->execute();
$result = $statement->get_result();
?>
<html>
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
            padding: 2rem 1rem;
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

        .table-container {
            overflow-x: auto;
            background: rgba(255,255,255,0.1);
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 900px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            color: #fff;
        }

        thead {
            background: rgba(0,0,0,0.4);
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        tbody tr:nth-child(even) {
            background: rgba(255,255,255,0.05);
        }

        tbody tr:hover {
            background: rgba(255,255,255,0.15);
        }

        th:first-child { border-top-left-radius: 8px; }
        th:last-child { border-top-right-radius: 8px; }

        .back-link {
            display: inline-block;
            margin-top: 2rem;
            padding: 0.8rem 1.5rem;
            background: rgba(255,255,255,0.15);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            background: rgba(255,255,255,0.25);
            transform: scale(1.05);
        }

        @media (max-width: 600px) {
            h2 { font-size: 1.5rem; }
        }
    </style>
<body>
    <header>
        <h1>Matched Pairs with Payment Status: 
    <?php echo htmlspecialchars($payment_status); ?>
    </h1>
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

<table border="1" cellpadding="6">
<tr>
    <th>Student</th>
    <th>Tutor</th>
    <th>Course</th>
    <th>Payment Status</th>
</tr>

<?php
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['student_first'] . " " . $row['student_last']) . "</td>";
    echo "<td>" . htmlspecialchars($row['tutor_first'] . " " . $row['tutor_last']) . "</td>";
    echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['payment_status']) . "</td>";
    echo "</tr>";
}
?>
</table>

</body>
</html>

<?php
$statement->close();
mysqli_close($con);
?>
