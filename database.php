<?php

function getDB()
{
    static $db = null;

    if ($db === null) {
        $db = new PDO('sqlite:' . __DIR__ . '/tic_tac_toe.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Crear tabla board (si no existe)
        $db->exec("CREATE TABLE IF NOT EXISTS board (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            board TEXT NOT NULL,
            turn TEXT NOT NULL,
            winner TEXT
        )");

        // Crear tabla status (si no existe)
        $db->exec("CREATE TABLE IF NOT EXISTS status (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            label TEXT NOT NULL UNIQUE
        )");

        // Insertar estados iniciales si no existen
        $statuses = ['waiting', 'active'];
        foreach ($statuses as $label) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM status WHERE label = ?");
            $stmt->execute([$label]);
            if ($stmt->fetchColumn() == 0) {
                $insert = $db->prepare("INSERT INTO status (label) VALUES (?)");
                $insert->execute([$label]);
            }
        }

        // Crear tabla games (si no existe)
        $db->exec("CREATE TABLE IF NOT EXISTS games (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            board_id INTEGER NOT NULL,
            status_id INTEGER NOT NULL,
            FOREIGN KEY (board_id) REFERENCES board(id),
            FOREIGN KEY (status_id) REFERENCES status(id)
        )");
    }

    return $db;
}
