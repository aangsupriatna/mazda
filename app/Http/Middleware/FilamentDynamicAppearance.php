<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tampilan;
use App\Enums\Setting\Font;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use App\Enums\Setting\PrimaryColor;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Facades\FilamentColor;
use Symfony\Component\HttpFoundation\Response;

class FilamentDynamicAppearance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user) {
            return $next($request);
        }

        $currentPerusahaan = $user->currentPerusahaan();
        if (!$currentPerusahaan) {
            return $next($request);
        }

        $tampilan = Tampilan::where(['perusahaan_id' => $currentPerusahaan->id, 'user_id' => $user->id])->first();

        $defaultPrimaryColor = $tampilan->primary_color ?? PrimaryColor::from(PrimaryColor::DEFAULT);
        $defaultFont = $tampilan->font->value ?? Font::DEFAULT;

        FilamentColor::register([
            'primary' => $defaultPrimaryColor->getColor(),
        ]);

        Filament::getPanel('admin')
            ->font($defaultFont);

        return $next($request);
    }
}
