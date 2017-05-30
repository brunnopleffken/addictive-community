<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_system_database.php
#  License: GPLv2
#  Copyright: (c) 2017 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Database;
use \AC\Kernel\Html;
use \AC\Kernel\Http;

// Execute queries, if defined

$execute = (Http::request("execute")) ? Http::request("execute") : false;

if($execute) {
	switch($execute) {
		case "optimize":

			$tables = "";
			Database::query("SHOW TABLES;");

			while($table = Database::fetch()) {
				foreach($table as $db => $name) {
					$tables .= $name . ", ";
				}
			}

			$tables = substr($tables, 0, -2);

			Database::query("OPTIMIZE TABLE {$tables};");
			$Admin->registerLog("Database optimization.");

			echo Html::notification(
				"Tables have been successfully optimized.<br><span style='font-family: monospace'>OPTIMIZE TABLE {$tables};</span>",
				"success"
			);

			break;

		case "repair":

			$tables = "";
			Database::query("SHOW TABLES;");

			while($table = Database::fetch()) {
				foreach($table as $db => $name) {
					$tables .= $name . ", ";
				}
			}

			$tables = substr($tables, 0, -2);

			Database::query("REPAIR TABLE {$tables};");
			$Admin->registerLog("Database repairing.");

			echo Html::notification(
				"Tables have been successfully repaired.<br><span style='font-family: monospace'>REPAIR TABLE {$tables};</span>",
				"success"
			);

			break;
	}
}

// Get tables from database

$html = "";
Database::query("SHOW TABLE STATUS;");

while($table = Database::fetch()) {
	$html .= "<tr>
			<td>{$table['Name']}</td>
			<td>{$table['Rows']}</td>
			<td>{$table['Engine']}</td>
			<td>{$table['Collation']}</td>
		</tr>";
}

?>

<script>

	function Optimize() {
		if(confirm('OPTIMIZE TABLE reorganizes the physical storage of table data and associated index data, to reduce storage space and improve I/O efficiency when accessing the table. Proceed?')) {
			window.location.href = 'main.php?act=system&p=database&execute=optimize';
		}
	}

	function Repair() {
		if(confirm('CAUTION!\nIt is best to make a backup of a table before performing a table repair operation; under some circumstances the operation might cause data loss. Possible causes include but are not limited to file system errors. Proceed?')) {
			window.location.href = 'main.php?act=system&p=database&execute=repair';
		}
		else {
			return false;
		}
	}

</script>

<div class="header">
	<h1>Database Toolbox</h1>
	<div class="header-buttons">
		<a class="btn btn-default font-w600" onclick="Optimize()">Optimize Tables</a>
		<a class="btn btn-default font-w600" onclick="Repair()">Repair Corrupted Tables</a>
	</div>
</div>

<div class="block">
	<form action="process.php?do=optimize" method="post">
		<table class="table">
			<thead>
				<tr>
					<th colspan="4">Tables in Database</th>
				</tr>
				<tr>
					<td>Table Name</td>
					<td>Entries</td>
					<td>Storage Engine</td>
					<td>Collation</td>
				</tr>
			</thead>
			<?php echo $html ?>
		</table>
	</form>
</div>
