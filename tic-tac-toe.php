<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Pragma" content="no-cache">
<title>まるばつゲーム</title>
<meta http-equiv="Content-Style-Type" content="text/css">
</head>

<body>
<style>
#a td {
	width: 100px;
	height: 100px;
	font-size: 80px;
	border: 1px solid #ccc;
	text-align:center;
}
input[type="text"] {
  font-size: 30pt; /* txtboxのフォントサイズ */
}
input[type="button"] {
  font-size: 30pt; /* buttonのフォントサイズ */
}
</style>

<?php
//

?>
<script>
function create_tic_tac_toe_ai_array()
{
	ai_array = new Array(9);

	for(var index = 0; index < ai_array.length; index++)
	{
		ai_array[index] = new Array(ai_array.length - index);
		for(var index2 = 0; index2 < ai_array[index].length; index2++)
			ai_array[index][index2] = index2;
	}

	return(ai_array);
}

function randomise_ai_array(tic_tac_toe_ai_array)
{
	for(var index = 0; index < tic_tac_toe_ai_array.length; index++)
		for(var index2 = 0; index2 < tic_tac_toe_ai_array[index].length; index2++)
		{
			temp = tic_tac_toe_ai_array[index][index2];
			rand = parseInt(Math.random() * tic_tac_toe_ai_array[index].length);
			tic_tac_toe_ai_array[index][index2] = tic_tac_toe_ai_array[index][rand];
			tic_tac_toe_ai_array[index][rand] = temp;
		}
}

function create_tic_tac_toe()
{
	this.Letter = '';
	this.Play = -1;
}

function create_tic_tac_toe_array()
{
	game_array = new Array(9);

	for(var index = 0; index < ai_array.length; index++)
		game_array[index] = new create_tic_tac_toe();

	return(game_array);
}

function next_available_square(tic_tac_toe_array, square)
{
	available_square = 0;

	for(index = 0; index <= square; index++)
	{
		while(tic_tac_toe_array[available_square].Letter != '')
		{
			available_square++;
		}

		if(index < square)
			available_square++;
	}

	if(available_square < 0 || available_square > 8)
		alert('oh my god available_square = ' + available_square);

	return(available_square);
}

function ai_play(tic_tac_toe_array, tic_tac_toe_ai_array, turn_number, trial_number)
{
	for(var index = turn_number + trial_number; index < tic_tac_toe_ai_array.length; index++)
	{
		//Each increment in this loop is a possible move.
		for(var index2 = 0; index2 < tic_tac_toe_ai_array[index].length; index2++)
		{
			take_back_moves(tic_tac_toe_array, turn_number + trial_number);

			index3 = next_available_square(tic_tac_toe_array, tic_tac_toe_ai_array[index][index2]);

			trial_move(tic_tac_toe_array, index3, next_letter(tic_tac_toe_array));

			if(win(tic_tac_toe_array, Player_Letter()) == true)
			{
				return(1);
			}

			if(turn_number + trial_number + 1 == tic_tac_toe_array.length)
			{
				return(0);
			}

			if(trial_number < 2)
			{
				return_value = ai_play(tic_tac_toe_array, tic_tac_toe_ai_array, turn_number, trial_number + 1);

				take_back_moves(tic_tac_toe_array, turn_number + trial_number + 1);

				if((return_value != 1 && next_letter(tic_tac_toe_array) == Player_Letter()) ||
					(index2 == tic_tac_toe_ai_array[index].length - 1))
					return(return_value);
			}
               }
	}
}

function Computer_Letter()
{
	return(computer_letter);
}

function Player_Letter()
{
	return(player_letter);
}

function play()
{
	if(turn == Computer_Letter())
		document.tic_tac_toe.message.value = '私の番です (' + Computer_Letter() + ')';
	else
		document.tic_tac_toe.message.value = 'あなたの番です (' + Player_Letter() + ')';

	if(win(tic_tac_toe, Computer_Letter()) == false &&
		win(tic_tac_toe, Player_Letter()) == false &&
		tied(tic_tac_toe) == false)
	{
		if(turn == Computer_Letter())
		{
			ai_play(tic_tac_toe, ai_array, move_number(tic_tac_toe), 0);
			turn = Player_Letter();
		}

		setTimeout('play();', 1000);
	}

	if(win(tic_tac_toe, Computer_Letter()))
	{
		document.tic_tac_toe.message.value = '私の勝ち';
	}

	if(win(tic_tac_toe, Player_Letter()))
	{
		document.tic_tac_toe.message.value = 'あなたの勝ち';
	}

	if(tied(tic_tac_toe))
	{
		document.tic_tac_toe.message.value = '引き分け';
	}
}

function move_number(tic_tac_toe_array)
{
	latest_move = 0;
	for(var index = 0; index < tic_tac_toe_array.length; index++)
	{
		if(tic_tac_toe_array[index].Play >= latest_move)
			latest_move = tic_tac_toe_array[index].Play + 1;
	}

	return(latest_move);
}

function next_letter(tic_tac_toe_array)
{
	last_position = 0;
	for(var index = 0; index < tic_tac_toe_array.length; index++)
	{
			if(tic_tac_toe_array[index].Play > tic_tac_toe_array[last_position].Play)
			last_position = index;
	}

	if(tic_tac_toe_array[last_position].Letter == Computer_Letter())
		return(Player_Letter());
	else
		return(Computer_Letter());
}

function user_move(square, Letter)
{
	if(tic_tac_toe[square].Letter == '' && turn == Player_Letter())
	{
		move(tic_tac_toe, square, Letter);
		turn = Computer_Letter();
		document.tic_tac_toe.message.value = '私の番です (' + Computer_Letter() + ')';
	}
}

function move(tic_tac_toe_array, square, Letter)
{
	tic_tac_toe_array[square].Letter = Letter;
	tic_tac_toe_array[square].Play = move_number(tic_tac_toe_array);
	eval('document.tic_tac_toe.square' + square + '.value = \'' + Letter + '\'');
}

function trial_move(tic_tac_toe_array, square, Letter)
{
	if(square < tic_tac_toe_array.length)
	{
		tic_tac_toe_array[square].Letter = Letter;
		tic_tac_toe_array[square].Play = move_number(tic_tac_toe_array);
		eval('document.tic_tac_toe.square' + square + '.value = \'' + Letter + '\'');
	}
}

function take_back_moves(tic_tac_toe_array, Play)
{
	for(var index = 0; index < tic_tac_toe_array.length; index++)
	{
		if(tic_tac_toe_array[index].Play >= Play)
		{
			tic_tac_toe_array[index].Letter = '';
			tic_tac_toe_array[index].Play = -1;
			eval('document.tic_tac_toe.square' + index + '.value = \'\'');
		}
	}
}

var ai_array;
var tic_tac_toe;
var turn;
var computer_letter;
var player_letter;

function new_game()
{
	if(Math.random() < 0.5)
	{
		computer_letter = '×';
		player_letter = '○';
	}
	else
	{
		computer_letter = '○';
		player_letter = '×';
	}

	if(Math.random() < 0.5)
		turn = Computer_Letter();
	else
		turn = Player_Letter();

	randomise_ai_array(ai_array);

	take_back_moves(tic_tac_toe, 0);
}

function init()
{
	ai_array = create_tic_tac_toe_ai_array();
	tic_tac_toe = create_tic_tac_toe_array();

	new_game();

	play();
}

function win(tic_tac_toe_array, Letter)
{
	//First row, first column, and diagonal.
	if(tic_tac_toe_array[0].Letter == Letter)
		{
		if(tic_tac_toe_array[1].Letter == Letter && tic_tac_toe_array[2].Letter == Letter)
			return(true);
		if(tic_tac_toe_array[3].Letter == Letter && tic_tac_toe_array[6].Letter == Letter)
			return(true);
		if(tic_tac_toe_array[4].Letter == Letter && tic_tac_toe_array[8].Letter == Letter)
			return(true);
	}

	//Second row, second column, and other diagonal.
	if(tic_tac_toe_array[4].Letter == Letter)
	{
		if(tic_tac_toe_array[3].Letter == Letter && tic_tac_toe_array[5].Letter == Letter)
			return(true);
		if(tic_tac_toe_array[1].Letter == Letter && tic_tac_toe_array[7].Letter == Letter)
			return(true);
		if(tic_tac_toe_array[2].Letter == Letter && tic_tac_toe_array[6].Letter == Letter)
			return(true);
	}

	//Third row and third column.
	if(tic_tac_toe_array[8].Letter == Letter)
	{
		if(tic_tac_toe_array[6].Letter == Letter && tic_tac_toe_array[7].Letter == Letter)
			return(true);
		if(tic_tac_toe_array[2].Letter == Letter && tic_tac_toe_array[5].Letter == Letter)
			return(true);
	}

	return(false);
}

function tied(tic_tac_toe_array)
{
	if(win(tic_tac_toe_array, Player_Letter()) == false &&
		win(tic_tac_toe_array, Computer_Letter()) == false)
	{
	for(var index = 0; index < tic_tac_toe_array.length; index++)
		if(tic_tac_toe_array[index].Letter == '')
		return(false);

		return(true);
	}

	return(false);
}

</script>

	<h1>まるばつゲーム</h1>

	<body onload="init();">

	<form name="tic_tac_toe">
	<table id="a">
		<tbody>
			<tr><td colspan="3" align="center">
				<input type="text" size="16" name="message" style="border:none; text-align:center;" />
			</td></tr>
			<tr>
				<td onclick="user_move(0, Player_Letter());">
					<input type="text" size="2" name="square0" style="border:none; text-align:center;" readonly
						 /></td>
				<td align="center" onclick="user_move(1, Player_Letter());">
					<input type="text" size="2" name="square1" style="border:none; text-align:center;" readonly
						 /></td>
				<td align="right" onclick="user_move(2, Player_Letter());">
					<input type="text" size="2" name="square2" style="border:none; text-align:center;" readonly
						 /></td>
				</tr>

			<tr>
				<td onclick="user_move(3, Player_Letter());">
					<input type="text" size="2" name="square3" style="border:none; text-align:center;" readonly
						 /></td>
				<td align="center" onclick="user_move(4, Player_Letter());">
					<input type="text" size="2" name="square4" style="border:none; text-align:center;" readonly
						 /></td>
				<td align="right" onclick="user_move(5, Player_Letter());">
					<input type="text" size="2" name="square5" style="border:none; text-align:center;" readonly
						 /></td>
			</tr>

			<tr>
					<td onclick="user_move(6, Player_Letter());">
					<input type="text" size="2" name="square6" style="border:none; text-align:center;" readonly
						 /></td>
					<td align="center" onclick="user_move(7, Player_Letter());" >
					<input type="text" size="2" name="square7" style="border:none; text-align:center;" readonly
						/></td>
					<td align="right" onclick="user_move(8, Player_Letter());">
					<input type="text" size="2" name="square8" style="border:none; text-align:center;" readonly
						  /></td>
			</tr>
			<tr><td colspan="3" align="center">
				<input type="button" value="new game"
					onclick="new_game();
							play();" />
			</td></tr>
		</tbody>
	</table>

<hr>
最新更新日 2020/12/8

</body>
</html>