<?php 


function playable_card($hand_c, $down_c, $color)
{
	require "deck.php";

	if($deck[$hand_c]->get_color()=="black" || $color == "black")
	{
		return true;
	}else if(($color == $deck[$hand_c]->get_color()) ||  ($deck[$down_c]->get_number() == $deck[$hand_c]->get_number()))
	{
		return true;
	}
	return false;
}

?>