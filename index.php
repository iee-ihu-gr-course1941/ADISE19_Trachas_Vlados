<!doctype html>
<html lang="en">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Uno Card Game</title>

    <!-- calls -->
    <link href="css/myUno.css?t=122" type="text/css" rel="stylesheet">
    <script src="bootstrap/jquery-3.2.1.min.js"></script>
    <script src="bootstrap/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/myuno.js?t=120"></script>
  </head>
<body style="background-color:#ff751a;">

<div id='opponent_hand'>

    <p id='opponent_cards'></p>
</div>

<div id='board'>
    <input id="username">
    </br>
    <button id='game_login'>ΕΙΣΟΔΟΣ ΣΤΟ ΠΑΙΧΝΙΔΙ</button>
    <div id='board_center'>
    </div>

    <div id='deck'>
        <img src="Internal/unoBack.png">        
    </div>
    <div class="buttons">
            <button id='draw_button'>Draw</button>
            <button id='pass_button'>Pass</button>
    </div>
</div>

<div id='player_hand'>
    <div id='hand'>
    </div>
</div>

</body>
</html>