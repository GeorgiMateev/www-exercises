<?php
	require 'config.php';
	
	function getStudentsData() {
		try {
			$host = DB_SERVER;
			$dbName = DB_NAME;
			$conn = new PDO("mysql:host=$host;dbname=$dbName", DB_USERNAME, DB_PASSWORD);
			
			$studentsCountSql = 'SELECT COUNT(*) FROM students';
			
			$query   = $conn->query($studentsCountSql)or die("Failed to get students' count.");
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
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Students</title>
</head>

<body>
<?php
	getStudentsData();
?>
</body>

</html>
