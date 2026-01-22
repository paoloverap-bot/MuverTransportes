<?php
/**
 * Configuración de conexión a la base de datos MariaDB
 * Muver - Plataforma de cotización de mudanzas
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'muver');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Obtiene conexión PDO a la base de datos
 * @return PDO
 */
function getConnection(): PDO {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            throw new Exception("Error de conexión: " . $e->getMessage());
        }
    }
    
    return $pdo;
}

/**
 * Obtiene los tipos de servicio activos
 * @return array
 */
function getTiposServicio(): array {
    $pdo = getConnection();
    $stmt = $pdo->query("SELECT id_tipo_servicio, descripcion FROM tipo_servicio WHERE estado = 1 ORDER BY descripcion");
    return $stmt->fetchAll();
}

/**
 * Obtiene las regiones activas
 * @return array
 */
function getRegiones(): array {
    $pdo = getConnection();
    $stmt = $pdo->query("SELECT id_region, nombre_region, codigo_region FROM region WHERE estado = 1 ORDER BY nombre_region");
    return $stmt->fetchAll();
}

/**
 * Detecta el tipo de dispositivo
 * @return string
 */
function detectarDispositivo(): string {
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    if (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
        return 'tablet';
    }
    
    if (preg_match('/mobile|android|iphone|ipod|blackberry|opera mini|iemobile/i', $userAgent)) {
        return 'mobile';
    }
    
    return 'desktop';
}

/**
 * Obtiene la IP real del cliente
 * @return string
 */
function getClientIP(): string {
    $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
    
    foreach ($ipKeys as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = explode(',', $_SERVER[$key])[0];
            if (filter_var(trim($ip), FILTER_VALIDATE_IP)) {
                return trim($ip);
            }
        }
    }
    
    return '0.0.0.0';
}
?>
