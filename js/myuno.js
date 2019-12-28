$(function()
{
	$('#draw_button').click( draw_card);
});

function print_card(card_number,card_color,place)
{
	var card = document.createElement("div");
	card.classList.add(place);
	card.style.background=card_color;
	card.innerHTML=card_number;
	document.getElementById('hand').appendChild(card);
}

function draw_card()
{
	print_card();
}

//function play_card()

//function remove_card_from_hand()

//function get_turn()

//function give_turn()