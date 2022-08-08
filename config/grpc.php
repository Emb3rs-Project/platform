<?php

return [
    "grpc_manager_host" =>  env('GRPC_MANAGER_HOST', 'ms_manager'),
    "grpc_manager_port" => env('GRPC_MANAGER_PORT', '50041'),
    "grpc_cf_host" => env('GRPC_CF_HOST', 'm_cf'),
    "grpc_cf_port" => env('GRPC_CF_PORT', '50051'),
];
