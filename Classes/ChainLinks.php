<?php

global $ChainLinks;
$ChainLinks = new ChainLinks();

class ChainLinks {

  // Properties
  private $chain = [];

  // Constructor
  function __construct() {
    global $chainLinks;
    $this->chain = &$chainLinks;
  }

  function GetLink($linkNum) {
    return new ChainLink($linkNum);
  }

  function NumLinks() {
		return count($this->chain);
  }

	function LastLink() {
		return new ChainLink($this->NumLinks() - 1);
	}

  function SearchForType($type) {
		$found = [];
		for ($i = 0; $i < $this->NumLinks(); ++$i) {
			$Link = $this->GetLink($i);
			for ($j = 0; $j < $Link->NumCards(); ++$j) {
				$cardID = $Link->GetLinkCard($j, true)->ID();
				if (TypeContains($cardID, $type)) array_push($found, $cardID);
			}
		}
		return implode(",", $found);
  }

	function RemoveOriginUID($uid) {
		for ($i = 0; $i < $this->NumLinks(); ++$i) {
			$Link = $this->GetLink($i);
			for ($j = 0; $j < $Link->NumCards(); ++$j) {
				$Card = $Link->GetLinkCard($j, true);
				if ($Card->OriginUniqueID() == $uid) {
					$Card->Remove();
					return true;
				}
			}
		}
		return false;
	}
}

class ChainLink {

  // Properties
  private $link = [];
	private $linkSummary = [];
	private $linkNum;

  // Constructor
  function __construct($linkNum) {
    global $chainLinks, $chainLinkSummary;
    $this->linkNum = $linkNum;
    $this->link = &$chainLinks[$linkNum];
		$summaryIndex = $linkNum * ChainLinkSummaryPieces();
		$this->linkSummary = array_slice($chainLinkSummary, $summaryIndex, ChainLinkSummaryPieces());
  }

	function NumCards() {
		return intdiv(count($this->link), ChainLinksPieces());
	}

	function GetLinkCard($index, $cardNumber=false) {
		if ($cardNumber) $index = $index * ChainLinksPieces();
		return new LinkCard($this->linkNum, $index);
	}

	function AttackCard() {
		return $this->GetLinkCard(0);
	}

	function DamageDealt() {
		return $this->linkSummary[0] ?? 0;
	}

	function TotalAttack() {
		return $this->linkSummary[1] ?? 0;
	}

	function Talents() {
		return $this->linkSummary[2] ?? "-";
	}

	function AddTalent($tal) {
		if ($this->linkSummary[2] == "-") $this->linkSummary[2] = $tal;
		else $this->linkSummary[2] .= ",$tal";
	}

	function Class() {
		return $this->linkSummary[3] ?? "-";
	}

	function ListofNames() {
		return $this->linkSummary[4] ?? "";
	}

	function HitOnLink() {
		return $this->linkSummary[5] ?? 0;
	}

	function ModifiedBaseAttack() {
		return $this->linkSummary[6] ?? 0;
	}

	function ModalPlayAbility() {
		return $this->linkSummary[7] ?? "-";
	}

	function Colors() {
		return $this->linkSummary[8] ?? "-";
	}
}

class LinkCard {
	private $link = [];
	private $index;

	// Constructor
	function __construct($linkNum, $index) {
		global $chainLinks, $chainLinkSummary;
		$this->link = &$chainLinks[$linkNum];
		$this->index = $index;
	}

	function Index() {
		return $this->index;
	}

	function ID() {
		if (!isset($this->link) || count($this->link) == 0) return "-";
		return $this->link[$this->index] ?? "-";
	}

	function PlayerID() {
		return $this->link[$this->index+1] ?? "-";
	}

	function StillOnChain() {
		return $this->link[$this->index+2] ?? "-";
	}

	function From() {
		return $this->link[$this->index+3] ?? "-";
	}

	function PowerModifier() {
		return $this->link[$this->index+4] ?? 0;
	}

	function DefenseModifier() {
		return $this->link[$this->index+5] ?? 0;
	}

	function AddedOnHits() {
		return $this->link[$this->index+6] ?? "";
	}

	function OriginalCardID() {
		return $this->link[$this->index+7] ?? "";
	}

	function OriginUniqueID() {
		return $this->link[$this->index+8] ?? "";
	}

	function NumTimesUsed() {
		return $this->link[$this->index+9] ?? 0;
	}

	function Remove() {
		if (isset($this->link[$this->index+2]))
			$this->link[$this->index+2] = 0;
	}
}