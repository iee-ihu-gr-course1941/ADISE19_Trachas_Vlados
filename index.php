<!doctype html>
<html lang="en">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Uno Card Game</title>

    <!-- CSS -->
    <link href="./Css/myUno.css" type="text/css" rel="stylesheet">
  </head>
<body style="background-color:#ff751a;">

<div id='opponent_hand'>
    <p id='opponent_cards'>0</p>
</div>

<div id='board'>
    <div id='board_center'>
        <img src="https://opengameart.org/sites/default/files/UNO-Back_0.png">
    </div>

    <div id='deck'>
        <img src="https://opengameart.org/sites/default/files/UNO-Back_0.png">
        <br>
        <div style="text-align:center">
            <button id="draw_button">Draw</button>
            <button id="draw_button">Pass</button>
        </div>
    </div>
</div>

<div id='player_hand'>
    <div class='hand'>
        <div class='card' style="background-color: red;">8</div>
        <div class='card' style="background-color: green;">9</div>
        <div class='card' style="background-color: yellow;">5</div>
        <div class='card' style="background-color: blue;">+2</div>
        <div class='card' style="background-color: black;">+4</div>
    </div>
</div>

</body>
</html>