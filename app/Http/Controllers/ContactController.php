<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __invoke(): View
    {
        $admin = User::where('is_admin', true)->first();
        $digits = preg_replace('/\D+/', '', (string) $admin?->phone);
        $digits = ltrim($digits, '0');
        $whatsAppNumber = strlen($digits) === 10 ? '91'.$digits : $digits;
        $configured = config('services.contact', []);

        return view('contact', [
            'admin' => $admin,
            'socials' => [
                'whatsapp' => ($configured['whatsapp'] ?? null) ?: 'https://wa.me/916383842171',
                'instagram' => ($configured['instagram'] ?? null) ?: 'https://www.instagram.com/pandiyanstoreapk?igsh=MTlzYzV4eHRzY2w4dQ==',
                'facebook' => ($configured['facebook'] ?? null) ?: '#',
                'youtube' => ($configured['youtube'] ?? null) ?: 'https://youtube.com/@pandiyanstoreapk?si=8pdU2IWji2rPGA5H',
            ],
        ]);
    }
}
