<?php

	function LoadDatabaseCards($set="")
	{
		$cards = [];
		$conn = GetDBConnection();
		$setLike = $set . "%";
		if($set != "") $sql = "SELECT * FROM carddefinition WHERE cardID LIKE ?";
		else $sql = "SELECT * FROM carddefinition";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			return $cards;
		}

		if($set != "") mysqli_stmt_bind_param($stmt, "s", $setLike);
		mysqli_stmt_execute($stmt);
		$resultData = mysqli_stmt_get_result($stmt);
		while($row = mysqli_fetch_assoc($resultData)) {
			$card = new stdClass();
			$card->cardID = $row['cardID'];
			$card->hasGoAgain = $row['hasGoAgain'];
			$card->playAbility = $row['playAbility'];
			$card->hitEffect = $row['hitEffect'];
			array_push($cards, $card);
		}

		mysqli_stmt_close($stmt);
		mysqli_close($conn);
		return $cards;
	}

	function CreateEditCard($id, $hasGoAgain)
	{
		// Validate and sanitize input
		if (empty($id) || !is_string($id)) {
			return false;
		}
		
		// Ensure hasGoAgain is a valid boolean/integer
		$hasGoAgain = intval($hasGoAgain);
		
		$conn = GetDBConnection();
		$sql = "INSERT INTO carddefinition (cardID, hasGoAgain)
		        VALUES (?, ?)
		        ON DUPLICATE KEY UPDATE
		        hasGoAgain = ?";

		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			return false;
		}
		mysqli_stmt_bind_param($stmt, "sii", $id, $hasGoAgain, $hasGoAgain);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
		return true;
	}

?>
