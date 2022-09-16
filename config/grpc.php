<?php

return [
    "grpc_manager_host" =>  env('GRPC_MANAGER_HOST', 'ms_manager'),
    "grpc_manager_port" => env('GRPC_MANAGER_PORT', '50041'),
    "grpc_cf_host" => env('GRPC_CF_HOST', 'm_cf'),
    "grpc_cf_port" => env('GRPC_CF_PORT', '50051'),
    "grpc_m_mm_host" => env('GRPC_M_MM_HOST', 'm_mm'),
    "grpc_m_mm_port" => env('GRPC_M_MM_PORT', '50054'),
];
