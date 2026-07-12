<?php

namespace App\Http\Middleware;

use App\Services\AttackDetector;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectAttack
{
    public function handle(Request $request, Closure $next): Response
    {
        $detector = app(AttackDetector::class);

        $clientIp = $request->ip();

        if ($detector->isBlockedIp($clientIp)) {
            abort(403, 'Akses ditolak: IP Anda telah diblokir oleh sistem IDS.');
        }

        $attackData = $detector->inspect($request);
        if ($attackData) {
            $detector->logAttack($attackData);
        }

        return $next($request);
    }
}
