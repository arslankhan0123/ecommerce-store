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
            'mail_verify_peer'   => 'nullable|boolean',
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

        $data['mail_enabled']     = $request->boolean('mail_enabled') ? '1' : '0';
        $data['mail_verify_peer'] = $request->boolean('mail_verify_peer') ? '1' : '0';

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

        // ── Stage 1: can we even open a TCP socket to the server? ──
        $host = trim((string) Setting::get('mail_host'));
        $port = (int) Setting::get('mail_port', 587);
        if (Setting::get('mail_mailer', 'smtp') === 'smtp') {
            $errno = 0; $errstr = '';
            $sock = @stream_socket_client("tcp://{$host}:{$port}", $errno, $errstr, 10);
            if (!$sock) {
                $msg = "Cannot reach {$host}:{$port} (TCP) — {$errstr}. The port is blocked by a firewall/antivirus/ISP, or the host is wrong.";
                Setting::recordMailResult($msg);
                return back()->with('error', $msg);
            }
            // ── Stage 2: on 465, test the TLS handshake directly for a clearer message ──
            if ($port === 465) {
                stream_set_timeout($sock, 15);
                $verify = Setting::get('mail_verify_peer', '1') === '1';
                stream_context_set_option($sock, 'ssl', 'verify_peer', $verify);
                stream_context_set_option($sock, 'ssl', 'verify_peer_name', $verify);
                stream_context_set_option($sock, 'ssl', 'SNI_enabled', true);
                stream_context_set_option($sock, 'ssl', 'peer_name', $host);
                $ok = @stream_socket_enable_crypto($sock, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                if ($ok !== true) {
                    fclose($sock);
                    $msg = "TCP to {$host}:465 works, but the TLS handshake fails/times out. This is almost always antivirus 'mail shield' / SSL-scanning or a local firewall interfering with PHP. Try: port 587 with TLS, turn off 'Verify TLS certificate' below, or whitelist php.exe in your antivirus.";
                    Setting::recordMailResult($msg);
                    return back()->with('error', $msg);
                }
            }
            fclose($sock);
        }

        try {
            Mail::raw(
                "This is a test email from " . Setting::get('store_name', 'Zelari') . ".\n\nIf you can read this, your SMTP settings are working.",
                fn ($m) => $m->to($request->test_email)->subject('✅ Test email from ' . Setting::get('store_name', 'Zelari'))
            );
        } catch (\Throwable $e) {
            report($e);
            Setting::recordMailResult($e->getMessage());
            return back()->with('error', 'Sending failed: ' . $e->getMessage());
        }

        Setting::recordMailResult(null);
        return back()->with('success', "Test email sent to {$request->test_email}. Check the inbox (and spam folder).");
    }
}
