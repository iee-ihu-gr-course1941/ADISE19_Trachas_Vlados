<?php 


function playable_card($hand_c, $down_c)
{
	require "deck.php";
	$current_color="";
	if($deck[$down_c]->get_color() == "black")
	{

	}else
	{
		$current_color=$deck[$down_c]->get_color();
	}

	if($deck[$hand_c]->get_color()=="black")
	{
		change_color();
		return true;
	}else if(($deck[$down_c]->get_number() == $deck[$hand_c]->get_number()) || ($deck[$down_c]->get_color() == $deck[$hand_c]->get_color()))
	{
		return true;
	}
	return false;
}

function change_color()
{

}

?>