<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "database.php";
require_once "game.php";     // Carga la lógica del juego

$db = getDB(); // Conexión a la base de datos

// Obtiene la ruta de la solicitud
$request = explode("/", trim(parse_url($_SERVER["PATH_INFO"], PHP_URL_PATH), "/"));

// Manejo de solicitudes
switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        if (isset($request[0]) && $request[0] === "create") {
            // Crear una nueva partida
            $stmt = $db->prepare("INSERT INTO games DEFAULT VALUES");
            $stmt->execute();
            echo json_encode(["game_id" => $db->lastInsertId(), "message" => "Partida creada"]);
            exit;
        } elseif (isset($request[0]) && $request[0] === "move") {
            // Recibir datos JSON del cliente
            $input = json_decode(file_get_contents("php://input"), true);
            if (!isset($input["game_id"], $input["position"], $input["player"])) {
                echo json_encode(["error" => "Faltan parámetros"]);
                exit;
            }
            
            // Buscar la partida en la base de datos
            $stmt = $db->prepare("SELECT * FROM games WHERE id = ?");
            $stmt->execute([$input["game_id"]]);
            $game = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$game) {
                echo json_encode(["error" => "Partida no encontrada"]);
                exit;
            }

            // Intentar hacer el movimiento
            $result = makeMove($game, $input["position"], $input["player"]);
            if (is_string($result)) {
                echo json_encode(["error" => $result]);
                echo "<li><strong>" . $result . "</strong></li>";
                exit;
            }

            // Actualizar la base de datos con el nuevo estado del juego
            $stmt = $db->prepare("UPDATE games SET board = ?, turn = ?, winner = ? WHERE id = ?");
            $stmt->execute([$result["board"], $result["turn"], $result["winner"], $input["game_id"]]);

            echo json_encode(["board" => $result["board"], "turn" => $result["turn"], "winner" => $result["winner"]]);
            exit;
        }
        break;

    case "GET":
        if (isset($request[0]) && $request[0] === "game" && isset($request[1])) {
            // Obtener el estado de una partida
            $stmt = $db->prepare("SELECT * FROM games WHERE id = ?");
            $stmt->execute([$request[1]]);
            $game = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$game) {
                echo json_encode(["error" => "Partida no encontrada"]);
                exit;
            }

            echo json_encode(["board" => $game["board"], "turn" => $game["turn"], "winner" => $game["winner"]]);
            exit;
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        exit;
}
?>
