<?php
namespace ChartComponent\Api;

use ChartComponent\Charts\BarChart;
use ChartComponent\Security\JwtAuth;

class ChartApi {
    public function getResponse(array $data, array $options, JwtAuth $auth, string $token): string {
        if ($auth->validateToken($token)) {
            $chart = new BarChart($data, $options);
            return $chart->toJson();
        } else {
            http_response_code(403);
            return json_encode(['error' => 'Unauthorized']);
        }
    }
}