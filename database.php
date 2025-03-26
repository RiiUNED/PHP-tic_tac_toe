<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Función para obtener la conexión a la base de datos SQLite
function getDB() {
    try {
        $db = new PDO("sqlite:D:/proyecto/tres_en_raya/tic_tac_toe.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die(json_encode(["error" => "Error de conexión: " . $e->getMessage()]));
    }
}

// Crear la tabla si no existe
function setupDatabase() {
    $db = getDB();
    $db->exec("CREATE TABLE IF NOT EXISTS games (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        board TEXT DEFAULT '---------',
        turn CHAR(1) DEFAULT 'X',
        winner CHAR(1) DEFAULT NULL
    )");
}

// Ejecutar la configuración de la base de datos
setupDatabase();
?>
