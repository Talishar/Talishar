<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Libraries/HandInstanceIDs.php';

class HandInstanceIDsTest extends TestCase
{
    public function testNewHandGetsFreshIDs(): void
    {
        $counter = 1;
        $ids = ReconcileHandInstanceIDs([], [], ["a", "b", "a"], $counter);
        $this->assertSame(["h1", "h2", "h3"], $ids);
        $this->assertSame(4, $counter);
    }

    public function testUnchangedHandKeepsIDs(): void
    {
        $counter = 9;
        $ids = ReconcileHandInstanceIDs(["a", "b"], ["h3", "h7"], ["a", "b"], $counter);
        $this->assertSame(["h3", "h7"], $ids);
        $this->assertSame(9, $counter);
    }

    public function testDrawAppendsNewID(): void
    {
        $counter = 5;
        $ids = ReconcileHandInstanceIDs(["a", "b"], ["h1", "h2"], ["a", "b", "c"], $counter);
        $this->assertSame(["h1", "h2", "h5"], $ids);
    }

    public function testRemovedIndexHintPicksTheRightDuplicate(): void
    {
        $counter = 5;
        $ids = ReconcileHandInstanceIDs(["a", "a", "b"], ["h1", "h2", "h3"], ["a", "b"], $counter, 0);
        $this->assertSame(["h2", "h3"], $ids);
    }

    public function testRemovalWithoutHintKeepsOrder(): void
    {
        $counter = 5;
        $ids = ReconcileHandInstanceIDs(["a", "b", "c"], ["h1", "h2", "h3"], ["a", "c"], $counter);
        $this->assertSame(["h1", "h3"], $ids);
    }

    public function testReorderedCardKeepsItsID(): void
    {
        $counter = 5;
        $ids = ReconcileHandInstanceIDs(["a", "b", "c"], ["h1", "h2", "h3"], ["c", "a", "b"], $counter);
        $this->assertSame(["h3", "h1", "h2"], $ids);
        $this->assertSame(5, $counter);
    }

    public function testMismatchedStoredStateStartsOver(): void
    {
        $counter = 2;
        $ids = ReconcileHandInstanceIDs(["a", "b"], ["h1"], ["a"], $counter);
        $this->assertSame(["h2"], $ids);
    }

    public function testDecodeHandlesMissingLine(): void
    {
        $state = DecodeHandInstanceIDs("");
        $this->assertSame(1, $state["n"]);
        $this->assertSame(["h" => [], "i" => []], $state[1]);
        $this->assertSame(["h" => [], "i" => []], $state[2]);
    }

    public function testUpdateUsesHintAndRoundTrips(): void
    {
        $GLOBALS["p1Hand"] = ["a", "a", "b"];
        $GLOBALS["p2Hand"] = ["x"];
        $GLOBALS["handInstanceIDs"] = DecodeHandInstanceIDs("");
        $first = UpdateHandInstanceIDs();
        $this->assertSame(["h1", "h2", "h3"], $first[1]["i"]);
        $this->assertSame(["h4"], $first[2]["i"]);

        $GLOBALS["handInstanceIDs"] = DecodeHandInstanceIDs(json_encode($first));
        $GLOBALS["p1Hand"] = ["a", "b"];
        NoteHandCardRemoved(1, 0);
        $second = UpdateHandInstanceIDs();
        $this->assertSame(["h2", "h3"], $second[1]["i"]);
        $this->assertSame(["h2", "h3"], HandInstanceIDsFor(1));
        $this->assertSame(["h4"], HandInstanceIDsFor(2));

        $GLOBALS["p1Hand"] = ["b"];
        $this->assertSame([], HandInstanceIDsFor(1));
    }

    public function testHintIgnoredWhenCardAtIndexDiffers(): void
    {
        $counter = 5;
        $ids = ReconcileHandInstanceIDs(["a", "b", "c"], ["h1", "h2", "h3"], ["a", "c"], $counter, 0, "b");
        $this->assertSame(["h1", "h3"], $ids);
        $this->assertSame(5, $counter);
    }

    public function testHintClearedWhenCardReturnedToHand(): void
    {
        $GLOBALS["handInstanceRemovedIndex"] = [];
        $GLOBALS["p1Hand"] = ["a", "b", "c"];
        $GLOBALS["p2Hand"] = [];
        $GLOBALS["handInstanceIDs"] = DecodeHandInstanceIDs("");
        $first = UpdateHandInstanceIDs();
        $this->assertSame(["h1", "h2", "h3"], $first[1]["i"]);

        $GLOBALS["handInstanceIDs"] = DecodeHandInstanceIDs(json_encode($first));
        NoteHandCardRemoved(1, 1, "b");
        ClearHandCardRemovedNote(1, "x");
        $this->assertSame(["index" => 1, "card" => "b"], $GLOBALS["handInstanceRemovedIndex"][1]);
        ClearHandCardRemovedNote(1, "b");
        $this->assertArrayNotHasKey(1, $GLOBALS["handInstanceRemovedIndex"]);
        $second = UpdateHandInstanceIDs();
        $this->assertSame(["h1", "h2", "h3"], $second[1]["i"]);
        $this->assertSame($first["n"], $second["n"]);
    }

    public function testHintAppliedWhenSameCardDrawnAfterPlay(): void
    {
        $counter = 5;
        $ids = ReconcileHandInstanceIDs(["a", "a", "b"], ["h1", "h2", "h3"], ["a", "b", "a"], $counter, 0, "a");
        $this->assertSame(["h2", "h3", "h5"], $ids);
        $this->assertSame(6, $counter);
    }

    public function testPitchedDuplicateFromIndexZeroKeepsSecondCopyID(): void
    {
        $counter = 5;
        $ids = ReconcileHandInstanceIDs(["a", "a", "b"], ["h1", "h2", "h3"], ["a", "b"], $counter, 0, "a");
        $this->assertSame(["h2", "h3"], $ids);
        $this->assertSame(5, $counter);
    }

    public function testUpdateRoundTripWithCardHint(): void
    {
        $GLOBALS["handInstanceRemovedIndex"] = [];
        $GLOBALS["p1Hand"] = ["a", "b", "a"];
        $GLOBALS["p2Hand"] = ["x", "x"];
        $GLOBALS["handInstanceIDs"] = DecodeHandInstanceIDs("");
        $first = UpdateHandInstanceIDs();
        $this->assertSame(["h1", "h2", "h3"], $first[1]["i"]);
        $this->assertSame(["h4", "h5"], $first[2]["i"]);

        $GLOBALS["handInstanceIDs"] = DecodeHandInstanceIDs(json_encode($first));
        $GLOBALS["p1Hand"] = ["b", "a"];
        $GLOBALS["p2Hand"] = ["x"];
        NoteHandCardRemoved(1, 2, "a");
        NoteHandCardRemoved(1, 0, "a");
        NoteHandCardRemoved(2, 1, "x");
        $this->assertSame(["index" => 2, "card" => "a"], $GLOBALS["handInstanceRemovedIndex"][1]);
        $second = UpdateHandInstanceIDs();
        $this->assertSame(["h2", "h1"], $second[1]["i"]);
        $this->assertSame(["h4"], $second[2]["i"]);
        $this->assertSame([], $GLOBALS["handInstanceRemovedIndex"]);

        $GLOBALS["handInstanceIDs"] = DecodeHandInstanceIDs(json_encode($second));
        $GLOBALS["p1Hand"] = ["a"];
        NoteHandCardRemoved(1, 0, "zzz");
        $third = UpdateHandInstanceIDs();
        $this->assertSame(["h1"], $third[1]["i"]);
        $this->assertSame(6, $third["n"]);
    }
}
