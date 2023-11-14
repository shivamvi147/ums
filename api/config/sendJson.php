<?php
function sendJson(int $status, string $message, array $extra = []): void
{
    $response = ['status' => $status];
    if ($message) $response['message'] = $message;
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode(array_merge($response, $extra));
    exit;
}