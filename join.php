<?php

function handleJoinRequest($db)
{
    header('Content-Type: application/json');

    // Obtener IDs de estado
    $waitingId = getStatusId($db, 'waiting');
    $activeId = getStatusId($db, 'active');

    // Buscar una partida en estado 'waiting'
    $stmt = $db->prepare("
        SELECT games.id, games.board_id
        FROM games
        WHERE status_id = ?
        ORDER BY games.id ASC
        LIMIT 1
    ");
    $stmt->execute([$waitingId]);
    $waitingGame = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($waitingGame) {
        // Cambiar el estado a 'active'
        $update = $db->prepare("UPDATE games SET status_id = ? WHERE id = ?");
        $update->execute([$activeId, $waitingGame["id"]]);

        // Obtener los datos del tablero
        $stmt = $db->prepare("SELECT board, turn, winner FROM board WHERE id = ?");
        $stmt->execute([$waitingGame["board_id"]]);
        $board = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            "game_id" => (int)$waitingGame["id"],
            "board" => $board["board"],
            "turn" => $board["turn"],
            "winner" => $board["winner"]
        ]);
        exit;
    }

    // No hay partida esperando → crear una nueva
    $insertBoard = $db->prepare("INSERT INTO board (board, turn, winner) VALUES ('---------', 'X', NULL)");
    $insertBoard->execute();
    $boardId = $db->lastInsertId();

    $insertGame = $db->prepare("INSERT INTO games (board_id, status_id) VALUES (?, ?)");
    $insertGame->execute([$boardId, $waitingId]);
    $gameId = $db->lastInsertId();

    echo json_encode([
        "status" => "waiting",
        "game_id" => (int)$gameId
    ]);
    exit;
}

function getStatusId($db, $label)
{
    $stmt = $db->prepare("SELECT id FROM status WHERE label = ?");
    $stmt->execute([$label]);
    return (int)$stmt->fetchColumn();
}
