<?php


$bniconn = ocilogon("LABOR", "LABOR", "BNI");

if ($bniconn < 1) {
	printf("Error logging on to the LCS Oracle Server.");
	exit;
}

function convertToArray($ids)
{
	$ids = preg_replace('/[\s,;&?.]+/', ',', $ids); //Convert separators to commas
	$ids = preg_replace('/^[\s,;&?.]+/', '', $ids); //Strip leading
	$ids = preg_replace('/[\s,;&?.]+$/', '', $ids); //Strip trailing
	$all = array_filter(explode(",", $ids));
	sort($all);
	
	return $all;
}

function getEmployees($id)
{
	global $bniconn;
	$allIds = "'" . implode("', '", $id) . "'";
	
	$sql = "SELECT
				e.employee_name,
				e.employee_id,
				d.training_type,
				TO_CHAR(d.date_added, 'YYYY-MM-DD HH24:MI') as date_added,
				d.added_by
			FROM employee e
			LEFT JOIN dspc_worker_eligibility d
				ON d.employee_id = e.employee_id
			WHERE
				e.employee_id IN ($allIds)";
	$stmt = ociparse($bniconn, $sql);
	ociexecute($stmt);
	
	$employees = Array();
	while (ocifetch($stmt)) {
		$employeeId = ociresult($stmt, 'EMPLOYEE_ID');
		unset($id[$employeeId]);
		$employees[$employeeId]['name'] = ociresult($stmt, 'EMPLOYEE_NAME');
		
		$type = ociresult($stmt, 'TRAINING_TYPE');
		if ($type) {
			$employees[$employeeId]['trainingTypes'][$type] = Array(
				'dateAdded' => ociresult($stmt, 'DATE_ADDED'),
				'addedBy' => ociresult($stmt, 'ADDED_BY')
			);
		} else {
			$employees[$employeeId]['trainingTypes'] = Array();
		}
	}
	
	$empties = Array();
	foreach ($id as $anId) {
		$empties[$anId] = false;
	}
	
	$employees = $employees + $empties;
	ksort($employees);
			
	return $employees;
}

function getTrainingTypes()
{
	global $bniconn;
	$sql = "SELECT training_type, name
			FROM training_types
			ORDER BY training_type";
	$stmt = ociparse($bniconn, $sql);
	ociexecute($stmt);
	
	$types = Array();
	while (ocifetch($stmt)) {
		$types[ociresult($stmt, 'TRAINING_TYPE')] = ociresult($stmt, 'NAME');
	}
	
	if (empty($types)) {
		return false;
	}
	return $types;
}

function saveEmployeeTrainingTypes($id, $addedBy, $newTypes)
{
	global $bniconn;
	
	$trainingTypes = getTrainingTypes();
	
	$employee = getEmployee($id);
	$oldTypes = array_keys($employee['trainingTypes']);
	
	foreach ($trainingTypes as $type => $name) {
		if (!in_array($type, $newTypes)) {
			deleteTraining($id, $type);
		}
		else {
			if (!in_array($type, $oldTypes)) {
				$sql = "INSERT INTO dspc_worker_eligibility
							(employee_id, training_type, date_added, added_by)
						VALUES ('$id', '$type', SYSDATE, '$addedBy')";
				$stmt = ociparse($bniconn, $sql);
				ociexecute($stmt);
			}
		}
	}
}

function saveEmployees($employeeTraining, $addedBy)
{
	global $bniconn;
	
	$trainingTypes = getTrainingTypes();
	$oldEmployees = getEmployees(array_keys($employeeTraining));
	
	//For each employee in the batch
	foreach ($employeeTraining as $id => $newTraining) {
		$oldTraining = array_keys($oldEmployees[$id]['trainingTypes']);
		print_r($oldTypes);
		
		//For each possible type of training
		foreach ($trainingTypes as $type => $name) {
			
			//If it was removed, delete it
			if (!in_array($type, $newTraining)) {
				deleteTraining($id, $type);
			}
			//If it's new, add it
			elseif (!in_array($type, $oldTraining)) {
				$sql = "INSERT INTO dspc_worker_eligibility
							(employee_id, training_type, date_added, added_by)
						VALUES ('$id', '$type', SYSDATE, '$addedBy')";
				$stmt = ociparse($bniconn, $sql);
				ociexecute($stmt);
			}
			//Otherwise, do nothing
		}
	}
}

function deleteTraining($id, $type)
{
	global $bniconn;
	$sql = "DELETE FROM dspc_worker_eligibility
			WHERE
				employee_id = '$id'
				AND training_type = '$type'";
	$stmt = ociparse($bniconn, $sql);
	ociexecute($stmt);
}

function saveTrainingTypes($allTypes)
{
	global $bniconn;
	
	$sql = "DELETE FROM training_types";
	$stmt = ociparse($bniconn, $sql);
	ociexecute($stmt);
	
	foreach ($allTypes as $type => $name) {
		$sql = "INSERT INTO training_types
					(training_type, name)
				VALUES ('$type', '$name')";
		$stmt = ociparse($bniconn, $sql);
		ociexecute($stmt);
	}
}