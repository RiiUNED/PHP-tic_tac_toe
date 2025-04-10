<?php

function makeMove($game, $position, $player)
{
    $board = $game["board"];
    $turn = $game["turn"];
    $winner = $game["winner"];

    // Validaciones básicas
    if ($winner !== null && $winner !== '') {
        return "La partida ya ha terminado.";
    }

    if ($player !== $turn) {
        return "No es el turno del jugador $player.";
    }

    if (!is_numeric($position) || $position < 0 || $position > 8) {
        return "Posición inválida.";
    }

    if ($board[$position] !== '-') {
        return "La celda ya está ocupada.";
    }

    // Realizar el movimiento
    $board[$position] = $player;

    // Comprobar si alguien ha ganado
    $winningCombos = [
        [0,1,2], [3,4,5], [6,7,8], // filas
        [0,3,6], [1,4,7], [2,5,8], // columnas
        [0,4,8], [2,4,6]           // diagonales
    ];

    foreach ($winningCombos as $combo) {
        if ($board[$combo[0]] === $player &&
            $board[$combo[1]] === $player &&
            $board[$combo[2]] === $player) {
            $winner = $player;
            break;
        }
    }

    // Comprobar empate
    if ($winner === null && strpos($board, '-') === false) {
        $winner = "draw";
    }

    // Cambiar el turno si la partida sigue
    $nextTurn = ($player === 'X') ? 'O' : 'X';

    return [
        "board" => $board,
        "turn" => ($winner === null) ? $nextTurn : $turn,
        "winner" => $winner
    ];
}
