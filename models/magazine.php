<?php
// Magazine model functions

/**
 * Fetch all magazines
 */
function getMagazines(mysqli $conn)
{
    $sql = "SELECT magazine_id, title, publisher, publication_date, category, language, ISSN_or_ISBN, description FROM magazine ORDER BY magazine_id DESC";
    return $conn->query($sql);
}

/**
 * Fetch single magazine by id
 */
function getMagazineById(mysqli $conn, int $magazineId)
{
    $magazineId = (int)$magazineId;
    $sql = "SELECT magazine_id, title, publisher, publication_date, category, language, ISSN_or_ISBN, description FROM magazine WHERE magazine_id = {$magazineId} LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    return null;
}

/**
 * Create magazine record
 */
function createMagazine(mysqli $conn, array $data, array $file = [])
{
    $title = $conn->real_escape_string(trim($data['title'] ?? ''));
    $publisher = $conn->real_escape_string(trim($data['publisher'] ?? ''));
    $publicationDate = $conn->real_escape_string(trim($data['publication_date'] ?? ''));
    $category = $conn->real_escape_string(trim($data['category'] ?? ''));
    $language = $conn->real_escape_string(trim($data['language'] ?? ''));
    $issnIsbn = $conn->real_escape_string(trim($data['issn_isbn'] ?? ''));

    $descriptionPath = $conn->real_escape_string(trim($data['description'] ?? ''));

    // Optional file upload (PDF)
    if (!empty($file) && isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
        $uploadDir = rtrim(DIR_URL, '/\\') . 'Magazine/mag_ass/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }
        $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', basename($file['name']));
        $fullPath = $uploadDir . $safeName;
        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            $descriptionPath = 'Magazine/mag_ass/' . $safeName; // store relative path
        }
    }

    $sql = "INSERT INTO magazine (title, publisher, publication_date, category, language, ISSN_or_ISBN, description)
            VALUES ('{$title}', '{$publisher}', '{$publicationDate}', '{$category}', '{$language}', '{$issnIsbn}', '{$descriptionPath}')";
    $ok = $conn->query($sql);
    return $ok ? ["success" => true, "id" => $conn->insert_id] : ["error" => $conn->error];
}

/**
 * Update magazine record
 */
function updateMagazine(mysqli $conn, int $magazineId, array $data, array $file = [])
{
    $magazineId = (int)$magazineId;
    $title = $conn->real_escape_string(trim($data['title'] ?? ''));
    $publisher = $conn->real_escape_string(trim($data['publisher'] ?? ''));
    $publicationDate = $conn->real_escape_string(trim($data['publication_date'] ?? ''));
    $category = $conn->real_escape_string(trim($data['category'] ?? ''));
    $language = $conn->real_escape_string(trim($data['language'] ?? ''));
    $issnIsbn = $conn->real_escape_string(trim($data['issn_isbn'] ?? ''));

    // Get current to preserve description if no new file provided
    $current = getMagazineById($conn, $magazineId);
    if (!$current) {
        return ["error" => "Magazine not found"];
    }
    $descriptionPath = $conn->real_escape_string(trim($data['description'] ?? $current['description']));

    if (!empty($file) && isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
        $uploadDir = rtrim(DIR_URL, '/\\') . 'Magazine/mag_ass/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }
        $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', basename($file['name']));
        $fullPath = $uploadDir . $safeName;
        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            $descriptionPath = 'Magazine/mag_ass/' . $safeName; // store relative path
        }
    }

    $sql = "UPDATE magazine SET
                title = '{$title}',
                publisher = '{$publisher}',
                publication_date = '{$publicationDate}',
                category = '{$category}',
                language = '{$language}',
                ISSN_or_ISBN = '{$issnIsbn}',
                description = '{$descriptionPath}'
            WHERE magazine_id = {$magazineId}";
    $ok = $conn->query($sql);
    return $ok ? ["success" => true] : ["error" => $conn->error];
}

/**
 * Delete magazine
 */
function deleteMagazine(mysqli $conn, int $magazineId)
{
    $magazineId = (int)$magazineId;
    $sql = "DELETE FROM magazine WHERE magazine_id = {$magazineId} LIMIT 1";
    return $conn->query($sql);
}
