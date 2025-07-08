<?php
require_once('clavebasededatos.php');

// Función para logging de errores (opcional)
function logImgBBError($error) {
    $log_file = __DIR__ . '/../logs/imgbb_errors.log';
    $timestamp = date('Y-m-d H:i:s');
    $message = "[$timestamp] ImgBB Error: $error" . PHP_EOL;
    
    // Crear directorio de logs si no existe
    $log_dir = dirname($log_file);
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    // Escribir al log solo si es posible
    if (is_writable($log_dir)) {
        file_put_contents($log_file, $message, FILE_APPEND | LOCK_EX);
    }
}

function subirImagenImgBB($archivo_temporal, $nombre_archivo) {
    global $imgbb_api_key;
    
    // Verificar que tengamos la API key
    if (empty($imgbb_api_key)) {
        $error = 'API key de ImgBB no configurada';
        logImgBBError($error);
        return ['success' => false, 'error' => $error];
    }
    
    // Verificar que el archivo temporal existe
    if (!file_exists($archivo_temporal)) {
        $error = 'Archivo temporal no encontrado';
        logImgBBError($error);
        return ['success' => false, 'error' => $error];
    }
    
    // Verificar que el archivo sea una imagen válida
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $tipo_archivo = mime_content_type($archivo_temporal);
    
    if (!in_array($tipo_archivo, $tipos_permitidos)) {
        return ['success' => false, 'error' => 'Tipo de archivo no permitido. Solo se permiten: JPG, PNG, GIF, WEBP'];
    }
    
    // Verificar tamaño (máximo 10MB)
    if (filesize($archivo_temporal) > 10 * 1024 * 1024) {
        return ['success' => false, 'error' => 'El archivo es demasiado grande. Máximo 10MB'];
    }
    
    // Codificar la imagen en base64
    $imagen_base64 = base64_encode(file_get_contents($archivo_temporal));
    
    // Preparar datos para la API de ImgBB
    $datos = [
        'key' => $imgbb_api_key,
        'image' => $imagen_base64,
        'name' => pathinfo($nombre_archivo, PATHINFO_FILENAME)
    ];
    
    // Configurar cURL
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.imgbb.com/1/upload',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $datos,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    
    $respuesta = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error_curl = curl_error($curl);
    curl_close($curl);
    
    if ($error_curl) {
        return ['success' => false, 'error' => 'Error de conexión: ' . $error_curl];
    }
    
    if ($http_code !== 200) {
        return ['success' => false, 'error' => 'Error HTTP: ' . $http_code];
    }
    
    $resultado = json_decode($respuesta, true);
    
    if (isset($resultado['success']) && $resultado['success']) {
        return [
            'success' => true,
            'url' => $resultado['data']['url'],
            'delete_url' => $resultado['data']['delete_url'],
            'display_url' => $resultado['data']['display_url']
        ];
    } else {
        $mensaje_error = isset($resultado['error']['message']) ? $resultado['error']['message'] : 'Error desconocido';
        return ['success' => false, 'error' => 'Error de ImgBB: ' . $mensaje_error];
    }
}
?>
