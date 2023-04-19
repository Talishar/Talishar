<?php

	function LoadCardsForSet($set)
	{
		$cards = [];
		$conn = GetDBConnection();
		$setLike = $set . "%";
		$sql = "SELECT * FROM carddefinition WHERE cardID LIKE ?";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			return $cards;
		}

		mysqli_stmt_bind_param($stmt, "s", $setLike);
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

?>
