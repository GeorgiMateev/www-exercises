<!DOCTYPE html>
<html>
<head>
<meta charset="windows-1251">
<title>Calendar</title>

<style>
	table, td {
		border: 1px solid #000;
		text-align: center;
		border-collapse: collapse;
	}
 </style>
</head>

<body>
	<?php 
		if (isset($_GET['month'])) {
			$month = $_GET['month'];
		}else{
			$month = date("m");
		}
		
		$currentMonth = mktime(0, 0, 0, $month, date("d"), date("Y"));
		$lastMonth = mktime(0, 0, 0, $month - 1, date("d"), date("Y"));
		$nextMonth = mktime(0, 0, 0, $month + 1, date("d"), date("Y")); 
	?>

	<table>
	  <tr>
		<td colspan="2">
			<a href="?<?php echo 'month='.($month - 1); ?>">
				<?php echo date('F Y', $lastMonth); ?>
			</a></td>
		<td colspan="3">
			<?php echo date('F Y', $currentMonth) ?>
		</td>
		<td colspan="2">
			<a href="?<?php echo 'month='.($month + 1); ?>">
				<?php echo date('F Y', $nextMonth); ?>
			</a>
		</td>
	  </tr>
	  <tr>
		<td>Понеделник</td>
		<td>Вторник</td>
		<td>Сряда</td>
		<td>Четвъртък</td>
		<td>Петък</td>
		<td>Събота</td>
		<td>Неделя</td>
	  </tr>
	  
	  <?php
		echo "<tr>";
		
		$firstDay = mktime(0, 0, 0, $month, 1, date("Y"));
		$weekFirstDay = date('N', $firstDay);
		
		$lastDay = date('t', $currentMonth);
		$weekLastDay = date('N', mktime(0, 0, 0, $month, $lastDay, date("Y")));
		
		for($i = 0; $i < $weekFirstDay - 1; $i++)
		{
			echo "<td></td>";
		}		
		
		for($d=1; $d<=$lastDay; $d++)
		{
			$currentDate = mktime(0, 0, 0, $month, $d, date("Y"));
			$weekDay = date('N', $currentDate);
			
			echo "<td>$d</td>";
			
			if($weekDay == 7)
			{
				echo "</tr>";
			}
		}
		
		for($j = 0; $j < 7 - $weekLastDay; $j++)
		{
			echo "<td></td>";
		}
		
		if($weekLastDay < 7)
		{
			echo "</tr>";
		}		
	  ?>
	</table>
</body>

</html>