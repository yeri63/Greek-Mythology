<?php
    # Program: mytho.php
    #    Date: 09-May-2023
    #  Author: gerry.walterscheid@gmail.com
    # Descrip: Randomly display one of the Olympian gods and some background info.

    session_start();
    header("Content-type: text/xml");

    $delay = 60;
	header("Refresh:$delay");

    $urlPath = "http://192.168.3.250/cisco/mytho.php";

	$mytho = array(
        "Zeus",
        "He was the king of Olympus. His temper affected the weather, and he threw thunderbolts when he was unhappy. He was married to Hera but had many other lovers. His symbols include the oak and the thunderbolt.",

        "Hera",
        "She was the queen of Olympus. She was Zeus's wife and sister; many myths tell of how she sought revenge when Zeus betrayed her with his lovers. Her symbols include the peacock and the cow.",
	
        "Poseidon",
        "He was the most powerful god except for his brother, Zeus. He lived in a beautiful palace under the sea and caused earthquakes when he was in a temper. His symbols include the horse and the trident.",
	
        "Hades",
        "He lived in the underworld, the heavily guarded land where he ruled over the dead. He was the brother of Zeus and the husband of Persephone, Demeter's daughter, whom he kidnapped.",
 
        "Aphrodite",
        "She was the protector of sailors. She may have been the daughter of Zeus and the Titan Dione, or she may have risen from the sea on a shell. Her symbols include the myrtle tree and the dove.",
	
        "Apollo",
        "He was an archer, and hunted with a silver bow. He was the son of Zeus and the Titan Leto, and the twin of Artemis. His symbols include the laurel tree, the crow, and the dolphin.",
	 
        "Ares",
        "He was both cruel and a coward. He was the son of Zeus and Hera, but neither of his parents liked him. His symbols include the vulture and the dog, and he often carried a bloody spear.",
	
        "Artemis",
        "She was the protector of women in childbirth. She hunted with silver arrows and loved all wild animals. This goddess was the daughter of Zeus and Leto, and the twin of Apollo. Her symbols include the cypress tree and the deer.",
		
        "Athena",
        "She was skilled in the art of war, and helped heroes such as Odysseus and Hercules. She sprang full-grown from the forehead of Zeus, and became his favorite child. Her symbols include the owl and the olive tree.",
		
        "Hephaestus",
        "He was the god of the forge (a furnace in which metal is heated). Although he made armor and weapons for the gods, he loved peace. He was the son of Zeus and Hera and married Aphrodite. His symbols include the anvil and the forge.",
		
        "Hestia",
        "The hearth is a fireplace at the center of the home. She was the most gentle of the gods, and does not play a role in many myths. Shw was the sister of Zeus and the oldest of the Olympians. Fire is among her symbols.",
		
        "Hermes",
        "He was a trickster, and a friend to thieves. He was said to have invented boxing and gymnastics. This god was the son of Zeus and the constellation Maia. The speediest of all, he wore winged sandals and a winged hat and carried a magic wand.",
		
        "Demeter",
        "She was the sister of Zeus. Her daughter, Persephone, was forced to live with Hades each winter; so this goddess prevented crops from growing. Her symbols include wheat.",
		
        "Dionysus",
        "He invented wine. In ancient Greece he was honored with springtime festivals that centered on theater. He was the son of Zeus and Semele, a mortal. His symbols include ivy, the snake, and grapes.");

    $count = 14;

    function FisherYatesIndex($n) {

        # Create array of n elements containing indexes.
        $arr = [];

    	for ($i = 0; $i < $n; $i++)
            $arr[$i] = $i;

        # Perform index shuffle.	
        for($i = $n - 1; $i > 0; $i--) {
            $r = rand(0, $i); $tmp = $arr[$i];
            $arr[$i] = $arr[$r];
            $arr[$r] = $tmp; }

        return $arr; }

    # Recall prior variable state
    $id = $_SESSION['id'] ?? "";            # App name.

    # Prevent data corruption to next application.
    if ($id == "mytho") {
        $lastCmd = $_SESSION['cmd'];        # User command.
        $mode = $_SESSION['mode'];          # Current mode.
        $ndx = $_SESSION['ndx'];            # Current index.
        $rndNdx = $_SESSION['rndNdx'];      # Shuffled index.
        $update = $_SESSION['update']; }    # Time from prior page update.
    else {
        $mode = 0;
        $ndx = 0; 
        $rndNdx = FisherYatesIndex($count); }

    # Get SoftKey selection.
    $cmd = $_GET['key'] ?? 0;

    # Check page refresh delay.
    if (time() - $update < $delay)

    	# Process user request based on the key pressed.	
        switch($cmd) {

        case 1 : # mode
            $mode = ++$mode % 2;
            break;

        case 2 : # previous
            $ndx = (--$ndx + $count) % $count;
            $mode = 0;
            break;

        case 3 : # next
            $ndx = ++$ndx % $count;
            $mode = 0;
            break;
        }

    else {
        # Select next record after page delay.
        $ndx = ++$ndx % $count;
        $mode = 0; }

    # Update the indexes on both fields.
    $i = $rndNdx[$ndx] * 2;
    $j = $i + 1;

    switch($mode) {

    case 0 : # Background
        echo "<CiscoIPPhoneText>\n";
        echo "  <Title>The Olympians - Who am I?</Title>\n";
        echo "  <Text>$mytho[$j]</Text>\n";

        $label = "Answer";
        $mesg = "";
        break;

    case 1 : # Background and name of God.
        echo "<CiscoIPPhoneText>\n";
        echo "  <Title>The Olympians - $mytho[$i]</Title>\n";
        echo "  <Text>$mytho[$j]</Text>\n";

        $label = "Back";
        $mesg = "Use arrows to view all text.";
        break;
    }

    echo "  <SoftKeyItem>\n";
    echo "    <Name>$label</Name>\n";
    echo "    <URL>$urlPath?key=1</URL>\n";
    echo "    <Position>1</Position>\n";
    echo "  </SoftKeyItem>\n";

    echo "  <SoftKeyItem>\n";
    echo "    <Name>Prev</Name>\n";
    echo "    <URL>$urlPath?key=2</URL>\n";
    echo "    <Position>2</Position>\n";
    echo "  </SoftKeyItem>\n";

    echo "  <SoftKeyItem>\n";
    echo "    <Name>Next</Name>\n";
    echo "    <URL>$urlPath?key=3</URL>\n";
    echo "    <Position>3</Position>\n";
    echo "  </SoftKeyItem>\n";

    echo "  <SoftKeyItem>\n";
    echo "    <Name>Exit</Name>\n";
    echo "    <URL>SoftKey:Exit</URL>\n";
    echo "    <Position>4</Position>\n";
    echo "  </SoftKeyItem>\n";

    echo "  <Prompt>$mesg</Prompt>\n";
    echo "</CiscoIPPhoneText>";

    # Save current variable state.
    $_SESSION['id'] = "mytho";
    $_SESSION['cmd'] = $cmd;
    $_SESSION['mode'] = $mode;
    $_SESSION['ndx'] = $ndx;
    $_SESSION['rndNdx'] = $rndNdx;
    $_SESSION['update'] = time();
?>
