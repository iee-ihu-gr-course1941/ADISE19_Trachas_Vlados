<?php 
require 'deck.php';
function playable_card($down_c, $hand_c)
{
	if($deck[$down_c].get_number() == $deck[$hand_c].get_number())
	{
		//playable
	}else if($deck[$hand_c].get_color()=="black")
	{
		//playable
	}else if($deck[$down_c].get_color() == $deck[$hand_c].get_color() )
	{
		//playable
	}
}

?>