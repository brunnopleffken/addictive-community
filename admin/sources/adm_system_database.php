<?php

## ---------------------------------------------------
#  ADDICTIVE COMMUNITY
## ---------------------------------------------------
#  Developed by Brunno Pleffken Hosti
#  File: adm_system_database.php
#  License: GPLv2
#  Copyright: (c) 2016 - Addictive Community
## ---------------------------------------------------

use \AC\Kernel\Html;
use \AC\Kernel\Http;

// Execute queries, if defined

$execute = (Http::Request("execute")) ? Http::Request("execute") : false;

if($execute) {
	switch($execute) {
		case "optimize":

			$tables = "";
			$Db->Query("SHOW TABLES;");

			while($table = $sql->Fetch()) {
				foreach($table as $db => $name) {
					$tables .= $name . ", ";
				}
			}

			$tables = substr($tables, 0, -2);

			$Db->Query("OPTIMIZE TABLE {$tables};");
			$Admin->RegisterLog("Database optimization.");

			echo Html::Notification(
				"Tables have been successfully optimized.<br><span style='font-family: monospace'>OPTIMIZE TABLE {$tables};</span>",
				"success"
			);

			break;

		case "repair":

			$tables = "";
			$Db->Query("SHOW TABLES;");

			while($table = $Db->Fetch()) {
				foreach($table as $db => $name) {
					$tables .= $name . ", ";
				}
			}

			$tables = substr($tables, 0, -2);

			$Db->Query("REPAIR TABLE {$tables};");
			$Admin->RegisterLog("Database repairing.");

			echo Html::Notification(
				"Tables have been successfully repaired.<br><span style='font-family: monospace'>REPAIR TABLE {$tables};</span>",
				"success"
			);

			break;
	}
}

// Get administration/moderation logs

$html = "";

$Db->Query("SHOW TABLE STATUS;");

while($table = $Db->Fetch()) {
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
		if(confirm('CAUTION\nIt is best to make a backup of a table before performing a table repair operation; under some circumstances the operation might cause data loss. Possible causes include but are not limited to file system errors. Proceed?')) {
			window.location.href = 'main.php?act=system&p=database&execute=repair';
		}
		else {
			return false;
		}
	}

</script>

<h1>Database Toolbox</h1>

<div id="content">
	<div class="grid-row">
		<form action="process.php?do=optimize" method="post">
			<div style="text-align: right; margin-bottom: 15px">
				<input type="button" value="Optimize Tables" onclick="Optimize()">
				<input type="button" value="Repair Corrupted Tables" onclick="Repair()">
			</div>
			<table class="table-list">
				<tr>
					<th colspan="4">Tables in Database</th>
				</tr>
				<tr class="subtitle">
					<td>Table Name</td>
					<td>Entries</td>
					<td>Engine</td>
					<td>Collation</td>
				</tr>
				<?php echo $html ?>
			</table>
		</form>
	</div>
</div>
