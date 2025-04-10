<?php
header('Content-Type: application/json');
require_once 'database.php';

if (!isset($_GET['game_id'])) {
    echo json_encode(["error" => "Missing game_id"]);
    exit;
}

$gameId = (int)$_GET['game_id'];

$db = getDB();

// Obtener juego, su tablero y su estado
$stmt = $db->prepare("
    SELECT g.id AS game_id, b.board, b.turn, b.winner, s.label AS status
    FROM games g
    JOIN board b ON g.board_id = b.id
    JOIN status s ON g.status_id = s.id
    WHERE g.id = ?
");
$stmt->execute([$gameId]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$game) {
    echo json_encode(["error" => "Game not found"]);
    exit;
}

if ($game["status"] === "waiting") {
    echo json_encode([
        "status" => "waiting",
        "game_id" => (int)$game["game_id"]
    ]);
    exit;
}

if ($game["status"] === "active") {
    echo json_encode([
        "game_id" => (int)$game["game_id"],
        "board" => $game["board"],
        "turn" => $game["turn"],
        "winner" => $game["winner"]
    ]);
    exit;
}

// Opción futura: manejar más estados como 'expired', 'canceled', etc.
echo json_encode([
    "error" => "Unknown game status"
]);
