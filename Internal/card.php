<?php 
	class Card
	{
		public $card_id;
		public $color;
		public $number;

		function get_card_id(){return $this->card_id;}
		function get_color(){return $this->color;}
		function get_number(){return $this->number;}

		function set_card($card_id,$color,$number)
		{
			$this->card_id=$card_id;
			$this->color=$color;
			$this->number=$number;
		}
	}
	
	
?>