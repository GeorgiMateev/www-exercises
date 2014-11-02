<?php
	require 'config.php';
	
	function getStudentsData() {
		try {
			$host = DB_SERVER;
			$dbName = DB_NAME;
			$conn = new PDO("mysql:host=$host;dbname=$dbName", DB_USERNAME, DB_PASSWORD);
			
			$studentsCountSql = 'SELECT COUNT(*) FROM students';
			
			$query = $conn->query($studentsCountSql)or die("Failed to get students' count.");
			$row = $query->fetch(PDO::FETCH_ASSOC);
			if($row['COUNT(*)'] == 0) {
				echo 'There are no students.';
			}
			else {
				$studentsSql = 'SELECT * FROM students ORDER BY grade DESC LIMIT 10';
				
				$query2  = $conn->query($studentsSql)or die("Failed to get first ten students wiht the highest grade.");
				while ($row = $query2->fetch(PDO::FETCH_ASSOC)) {
					$fName = $row['first_name'];
					$lName = $row['last_name'];
					$grade = $row['grade'];
					$updated = date('d-m-Y',  strtotime($row['last_updated']));
					echo "$fName $lName grade: $grade Last updated: $updated<br/>";
				}
			}
			
			// and now we're done; close it
			$conn = null;
		}
		catch (Exception $e) {
            echo 'Something went wrong with the connection to the database.';
        }
	}
	
	function insertNewStudent($student) {
		try {
			$host = DB_SERVER;
			$dbName = DB_NAME;
			$conn = new PDO("mysql:host=$host;dbname=$dbName", DB_USERNAME, DB_PASSWORD);
		
			$fn = $student['fn'];
			$studentQuery = "SELECT * FROM students WHERE fn=$fn";
			
			$query = $conn->query($studentQuery)or die("Failed to get student's info.");
			$row = $query->fetch(PDO::FETCH_ASSOC);
			if($row) {
				// Update student
				$updateStmt = $conn->prepare("
					  UPDATE students
					  SET first_name=:firstName, last_name=:lastName, major=:major, grade=:grade, last_updated=:lastUpdated
					  WHERE fn=:fn");
					  
				$updateStmt->bindParam(':first_name', $student['first_name']);
				$updateStmt->bindParam(':last_name', $student['last_name']);
				$updateStmt->bindParam(':major', $student['major']);
				$updateStmt->bindParam(':grade', $student['grade']);
				$updateStmt->bindParam(':last_updated', date('Y-m-d'));
				
				$updateStmt->execute();
			}
			else {
				// insert student
				$insertStmt = $conn->prepare("
					  INSERT INTO students (fn, first_name, last_name, major, grade, last_updated)
					  VALUES (:fn, :firstName, :lastName, :major, :grade, :lastUpdated");
					  
				$insertStmt->bindParam(':fn', $student['fn']);
				$insertStmt->bindParam(':firstName', $student['first_name']);
				$insertStmt->bindParam(':lastName', $student['last_name']);
				$insertStmt->bindParam(':major', $student['major']);
				$insertStmt->bindParam(':grade', $student['grade']);
				$insertStmt->bindParam(':lastUpdated', date('Y-m-d'));
								
				$insertStmt->execute();
			}
			
			// and now we're done; close it
			$conn = null; 
		}
		catch (Exception $e) {
            echo 'Something went wrong with the connection to the database.';
        }
	}
	
	function insertStudents($students) {
		foreach($students as $value) {
			insertNewStudent($value);
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Students</title>
</head>

<body>
<?php
	$students = array(
	  array('fn' => '61556', 'first_name' => 'Ivan','last_name' => 'Ivanov', 
			  'major' => 'Computer science', 'grade' => 5.45),
	  array('fn' => '811546', 'first_name' => 'Peter', 'last_name' => 'Petrov',
				  'major' => 'Computer science', 'grade' => 3.45),
	  array('fn' => '61556', 'first_name' => 'Ivan', 'last_name' => 'Ivanov',
				  'major' => 'Computer science', 'grade' => 4.80)
	);
	
	insertStudents($students);

	getStudentsData();
?>
</body>

</html>
