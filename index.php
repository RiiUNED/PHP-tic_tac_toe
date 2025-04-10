<?php
header('Content-Type: application/json');

require_once 'database.php';
require_once 'game.php';
require_once 'join.php';

$db = getDB();

$request = explode("/", trim($_SERVER["PATH_INFO"] ?? "/", "/"));
$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {
    case "GET":
        if (isset($request[0]) && is_numeric($request[0])) {
            $stmt = $db->prepare("
                SELECT g.id AS game_id, b.board, b.turn, b.winner, s.label AS status
                FROM games g
                JOIN board b ON g.board_id = b.id
                JOIN status s ON g.status_id = s.id
                WHERE g.id = ?
            ");
            $stmt->execute([$request[0]]);
            $game = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($game) {
                echo json_encode($game);
            } else {
                echo json_encode(["error" => "Game not found"]);
            }
        }
        break;

    case "POST":
        if (isset($request[0]) && $request[0] === "create") {
            handleJoinRequest($db);
            exit;
        }

        if (isset($request[0]) && $request[0] === "move") {
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data["game_id"], $data["position"], $data["player"])) {
                echo json_encode(["error" => "Invalid input"]);
                exit;
            }

            // Obtener juego y tablero asociado
            $stmt = $db->prepare("
                SELECT g.board_id, b.board, b.turn, b.winner
                FROM games g
                JOIN board b ON g.board_id = b.id
                WHERE g.id = ?
            ");
            $stmt->execute([$data["game_id"]]);
            $game = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$game) {
                echo json_encode(["error" => "Game not found"]);
                exit;
            }

            $result = makeMove($game, $data["position"], $data["player"]);

            if (is_string($result)) {
                echo json_encode(["error" => $result]);
                exit;
            }

            // Actualizar la tabla board
            $stmt = $db->prepare("UPDATE board SET board = ?, turn = ?, winner = ? WHERE id = ?");
            $stmt->execute([
                $result["board"],
                $result["turn"],
                $result["winner"],
                $game["board_id"]
            ]);

            echo json_encode([
                "game_id" => $data["game_id"],
                "board" => $result["board"],
                "turn" => $result["turn"],
                "winner" => $result["winner"]
            ]);
            exit;
        }

        break;

    default:
        echo json_encode(["error" => "Unsupported method"]);
}
