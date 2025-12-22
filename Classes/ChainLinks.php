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
}

class ChainLink {

  // Properties
  private $link = [];
	private $linkNum;

  // Constructor
  function __construct($linkNum) {
    global $chainLinks;
    $this->linkNum = $linkNum;
    $this->link = &$chainLinks[$linkNum];
  }

	function GetLinkCard($index, $cardNumber=false) {
		if ($cardNumber) $index = $index * ChainLinksPieces();
		return new LinkCard($this->linkNum, $index);
	}
}

class LinkCard {
	private $link = [];
	private $index;
	private $linkSummary = [];

	// Constructor
	function __construct($linkNum, $index) {
		global $chainLinks, $chainLinkSummary;
		$this->link = &$chainLinks[$linkNum];
		$this->index = $index;
		$summaryIndex = intdiv($index, ChainLinksPieces()) * ChainLinkSummaryPieces();
		$this->linkSummary = array_slice($chainLinkSummary, $summaryIndex, ChainLinkSummaryPieces());
	}

	function Index() {
		return $this->index;
	}

	function ID() {
		if (count($this->link) == 0) return "";
		return $this->link[$this->index];
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

	function OriginUID() {
		return $this->link[$this->index+8] ?? "";
	}

	function NumTimesUsed() {
		return $this->link[$this->index+9] ?? 0;
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
}