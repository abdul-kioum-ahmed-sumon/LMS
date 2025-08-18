<?php

function ensureSettingsTable(mysqli $conn): void
{
    $conn->query("CREATE TABLE IF NOT EXISTS settings (
        `key` VARCHAR(100) PRIMARY KEY,
        `value` VARCHAR(255) NOT NULL,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");
}

function getSetting(mysqli $conn, string $key, $default = null)
{
    ensureSettingsTable($conn);
    $keyEsc = $conn->real_escape_string($key);
    $res = $conn->query("SELECT `value` FROM settings WHERE `key` = '$keyEsc' LIMIT 1");
    if ($res && $res->num_rows === 1) {
        $row = $res->fetch_assoc();
        return $row['value'];
    }
    return $default;
}

function setSetting(mysqli $conn, string $key, string $value): bool
{
    ensureSettingsTable($conn);
    $stmt = $conn->prepare("INSERT INTO settings(`key`, `value`) VALUES(?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)");
    $stmt->bind_param('ss', $key, $value);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

function getIntSetting(mysqli $conn, string $key, int $default): int
{
    $val = getSetting($conn, $key, (string)$default);
    if ($val === null) return $default;
    if (is_numeric($val)) {
        return (int)$val;
    }
    return $default;
}
