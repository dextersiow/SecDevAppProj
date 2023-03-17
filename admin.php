<?php 
session_start();
require_once "workingconnection.php";
require_once "functions.php";

//check if role isset = is logged in and its admin
if(!isset($_SESSION["role"]) && $_SESSION['role'] != 'admin'){
    header("location: login.php");
    exit;
}

//check timeout
if (check_timeout()){
    messagebox('Session Timeout. Please Login Again.');
    logout();
    exit;
}

//update session time on new request
update_session();

//get event log data
$eventlog = geteventlog($conn);

?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
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
	<h1>Event Log</h1>
	<table>
		<thead>
			<tr>
				<th>id</th>
				<th>username</th>
				<th>Session ID</th>
				<th>IP Address</th>
				<th>Usesr Agent</th>
				<th>Action</th>
				<th>Description</th>
				<th>Time</th>
			</tr>
		</thead>
		<tbody>
			<?php while($row = $eventlog->fetch_assoc()) { 
				echo '<tr>';
				echo '<td>'.$row['id'].'</td>';
				echo '<td>'.filter($row['username']).'</td>';
				echo '<td>'.filter($row['sess_id']).'</td>';
				echo '<td>'.filter($row['ip_address']).'</td>';
				echo '<td>'.filter($row['user_agent']).'</td>';
				echo '<td>'.$row['action'].'</td>';
				echo '<td>'.$row['description'].'</td>';
				echo '<td>'.$row['timestamp'].'</td>';
				echo '</tr>';

			}?>
		</tbody>
	</table>
</body>
</html>
