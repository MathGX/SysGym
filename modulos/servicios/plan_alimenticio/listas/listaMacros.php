<?php
header('Content-Type: application/json; charset=utf-8'); // Devuelve JSON

$comida = $_GET['comida'] ?? '';

if (!$comida) {
	echo json_encode(["error" => "No se recibió el nombre de la comida"]);
	exit;
}

// Configura la API key y endpoint correspondientes (ejemplo para Gemini)
$url = "https://generativelanguage.googleapis.com/v1beta2/models/gemini-2.5-flash:generateContent";

$prompt = "Dime en formato JSON solo los campos proteina, calorias y carbohidratos para una porción estándar de la comida '$comida'.";

$data = [
	"prompt" => [
		"text" => $prompt
	],
	"max_tokens" => 150
];

$headers = [
	"Content-Type: application/json",
	"Authorization: Bearer $api_key"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
curl_close($ch);

if (!$response) {
	echo json_encode(["error" => "No se pudo conectar con la API"]);
exit;
}

$jsonResponse = json_decode($response, true);
$contenido = $jsonResponse['candidates'][0]['content'] ?? '{}';

// Intentar decodificar el JSON contenido para devolver directamente estructura usable en JS
$macrosJson = json_decode($contenido, true);

if (json_last_error() === JSON_ERROR_NONE) {
	echo json_encode($macrosJson);
} else {
	// Si no es JSON válido, devuelve texto bruto en error para debug
	echo json_encode(["error" => "Respuesta no JSON", "raw" => $contenido]);
}
exit;