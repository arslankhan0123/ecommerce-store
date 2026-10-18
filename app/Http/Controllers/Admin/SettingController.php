<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::allValues();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'mail_enabled'       => 'nullable|boolean',
            'mail_mailer'        => 'required|in:smtp,log',
            'mail_host'          => 'nullable|string|max:190',
            'mail_port'          => 'nullable|integer|min:1|max:65535',
            'mail_encryption'    => 'required|in:tls,ssl,none',
            'mail_username'      => 'nullable|string|max:190',
            'mail_password'      => 'nullable|string|max:190',
            'mail_from_address'  => 'nullable|email|max:190',
            'mail_from_name'     => 'nullable|string|max:120',
            'mail_admin_address' => 'nullable|email|max:190',
            'store_name'         => 'nullable|string|max:120',
            'store_tagline'      => 'nullable|string|max:120',
            'store_phone'        => 'nullable|string|max:60',
            'store_whatsapp'     => 'nullable|string|max:60',
            'store_email'        => 'nullable|email|max:190',
            'store_address'      => 'nullable|string|max:255',
            'store_hours'        => 'nullable|string|max:120',
            'store_facebook'     => 'nullable|url|max:255',
            'store_instagram'    => 'nullable|url|max:255',
            'store_twitter'      => 'nullable|url|max:255',
            'store_pinterest'    => 'nullable|url|max:255',
        ]);

        $data['mail_enabled'] = $request->boolean('mail_enabled') ? '1' : '0';

        // Keep the stored password when the field is left blank
        if (($data['mail_password'] ?? '') === '') {
            unset($data['mail_password']);
        }

        Setting::setMany($data);

        return back()->with('success', 'Settings saved successfully.');
    }

    public function test(Request $request)
    {
        $request->validate(['test_email' => 'required|email']);

        if (!Setting::applyMailConfig()) {
            return back()->with('error', 'Email is disabled or the SMTP host is empty. Save your SMTP details first.');
        }

        try {
            Mail::raw(
                "This is a test email from " . Setting::get('store_name', 'Zelari') . ".\n\nIf you can read this, your SMTP settings are working.",
                fn ($m) => $m->to($request->test_email)->subject('✅ Test email from ' . Setting::get('store_name', 'Zelari'))
            );
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Sending failed: ' . $e->getMessage());
        }

        return back()->with('success', "Test email sent to {$request->test_email}. Check the inbox (and spam folder).");
    }
}
