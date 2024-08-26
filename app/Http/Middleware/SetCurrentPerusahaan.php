<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentPerusahaan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if (auth()->check()) {
        //     $user = auth()->user();
        //     $currentPerusahaan = $user->currentPerusahaan();

        //     if (!$currentPerusahaan && $user->perusahaan()->exists()) {
        //         $firstPerusahaan = $user->perusahaan()->first();
        //         $user->switchPerusahaan($firstPerusahaan);
        //         $currentPerusahaan = $firstPerusahaan;
        //     }

        //     if ($currentPerusahaan) {
        //         session(['current_perusahaan_id' => $currentPerusahaan->id]);
        //     }
        // }

        if (auth()->check()) {
            $user = auth()->user();
            // $currentPerusahaan = $user->currentPerusahaan();
            // if (!$currentPerusahaan && $user->perusahaan()->exists()) {
                $firstPerusahaan = Filament::getTenant();

                $user->switchPerusahaan($firstPerusahaan);
                $currentPerusahaan = $firstPerusahaan;
            // }

            if ($currentPerusahaan) {
                session(['current_perusahaan_id' => $currentPerusahaan->id]);
                // Tambahkan baris berikut untuk mengatur team_id
                $request->merge(['team_id' => $currentPerusahaan->id]);
            }
        }

        return $next($request);
    }
}
