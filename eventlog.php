<?php 
session_start();
require_once "workingconnection.php";
require_once "functions.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Table with Headings</title>
	<style>
		table {
			border-collapse: collapse;
			width: 100%;
		}
		th, td {
			text-align: left;
			padding: 8px;
			border: 1px solid #ddd;
		}
		th {
			background-color: #f2f2f2;
			color: #000;
		}
		tr:nth-child(even) {
			background-color: #f2f2f2;
		}
	</style>
</head>
<body>
	<table>
		<thead>
			<tr>
				<th>id</th>
				<th>username</th>
				<th>sess_id</th>
				<th>ip_address</th>
				<th>user_agent</th>
				<th>action</th>
				<th>description</th>
				<th>timestamp</th>
			</tr>
		</thead>
		<tbody>
			<?php for ($i = 1; $i <= 8; $i++) { ?>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</body>
</html>
