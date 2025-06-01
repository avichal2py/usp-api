<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\LoginLog;

class LoginLoggerService
{
    public function log($type, $identifier, $status, $ip, $action)
    {
        LoginLog::create([
            'type' => $type,
            'identifier' => $identifier,
            'status' => $status,
            'ip_address' => $ip,
            'action' =>$action,
        ]);
    }
}
