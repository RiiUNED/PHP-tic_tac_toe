<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Función para verificar si hay un ganador en el tablero
function checkWinner($board) {
    $lines = [
        [0,1,2], [3,4,5], [6,7,8], // Filas
        [0,3,6], [1,4,7], [2,5,8], // Columnas
        [0,4,8], [2,4,6]           // Diagonales
    ];

    foreach ($lines as $line) {
        if ($board[$line[0]] != '-' && 
            $board[$line[0]] == $board[$line[1]] && 
            $board[$line[0]] == $board[$line[2]]) {
            return $board[$line[0]]; // Retorna 'X' o 'O' si hay ganador
        }
    }

    return (strpos($board, '-') === false) ? 'T' : null; // 'T' para empate, null si sigue el juego
}

// Función para realizar un movimiento en el juego
function makeMove($game, $position, $player) {
    if ($game['winner']) return "Partida terminada"; // Ya hay un ganador
    if ($game['turn'] !== $player) return "No es tu turno";
    if ($game['board'][$position] !== '-') return "Movimiento inválido";

    // Actualizar el tablero con el nuevo movimiento
    $board = $game['board'];
    $board[$position] = $player;

    // Verificar si hay un ganador
    $winner = checkWinner($board);
    $nextTurn = ($player === 'X') ? 'O' : 'X';

    return [
        'board' => $board,
        'winner' => $winner,
        'turn' => $winner ? null : $nextTurn
    ];
}
?>
