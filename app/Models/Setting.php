<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /** Keys stored encrypted at rest. */
    protected const SECRET_KEYS = ['mail_password'];

    public static function defaults(): array
    {
        return [
            'mail_enabled'       => '1',
            'mail_mailer'        => 'smtp',
            'mail_host'          => '',
            'mail_port'          => '587',
            'mail_encryption'    => 'tls',
            'mail_verify_peer'   => '1',
            'mail_username'      => '',
            'mail_password'      => '',
            'mail_from_address'  => '',
            'mail_from_name'     => 'Zelari',
            'mail_admin_address' => '',
            'store_name'         => 'Zelari',
            'store_tagline'      => 'Smart Shopping',
            'store_phone'        => '+92 300 0000000',
            'store_whatsapp'     => '',
            'store_email'        => 'support@zelari.com',
            'store_address'      => 'Gulberg III, Lahore, Pakistan',
            'store_hours'        => 'Mon – Sat, 9 AM – 6 PM PKT',
            'store_facebook'     => '',
            'store_instagram'    => '',
            'store_twitter'      => '',
            'store_pinterest'    => '',
        ];
    }

    /** All settings as [key => value], cached. */
    public static function allValues(): array
    {
        return Cache::remember('settings.all', 300, function () {
            if (!Schema::hasTable('settings')) {
                return static::defaults();
            }
            $rows = static::query()->pluck('value', 'key')->all();
            foreach (static::SECRET_KEYS as $k) {
                if (!empty($rows[$k])) {
                    try { $rows[$k] = Crypt::decryptString($rows[$k]); } catch (\Throwable) { $rows[$k] = ''; }
                }
            }
            return array_merge(static::defaults(), $rows);
        });
    }

    public static function get(string $key, $default = null)
    {
        $all = static::allValues();
        return array_key_exists($key, $all) && $all[$key] !== null && $all[$key] !== '' ? $all[$key] : $default;
    }

    public static function set(string $key, $value): void
    {
        if (in_array($key, static::SECRET_KEYS, true) && $value !== null && $value !== '') {
            $value = Crypt::encryptString($value);
        }
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('settings.all');
    }

    public static function setMany(array $values): void
    {
        foreach ($values as $k => $v) {
            static::set($k, $v);
        }
    }

    /** Remember the outcome of the last send attempt (shown on the settings page). */
    public static function recordMailResult(?string $error): void
    {
        try {
            static::set('mail_last_error', $error ? mb_substr($error, 0, 500) : '');
            static::set('mail_last_at', now()->toDateTimeString());
        } catch (\Throwable) {}
    }

    /** Push the saved SMTP settings into the runtime mail config. Returns true when mail is configured. */
    public static function applyMailConfig(): bool
    {
        $s = static::allValues();
        if (($s['mail_enabled'] ?? '1') !== '1') {
            return false;
        }
        $mailer = $s['mail_mailer'] ?: 'smtp';
        if ($mailer === 'smtp' && empty($s['mail_host'])) {
            return false; // nothing configured yet
        }
        $port = (int) ($s['mail_port'] ?: 587);
        // Implicit TLS ("SSL") only works on 465; every other port uses STARTTLS/plain, which
        // Symfony negotiates automatically. Picking by port avoids a mismatched dropdown breaking mail.
        $scheme = $port === 465 ? 'smtps' : 'smtp';
        config([
            'mail.default'                   => $mailer,
            'mail.mailers.smtp.transport'    => 'smtp',
            'mail.mailers.smtp.scheme'       => $scheme,
            'mail.mailers.smtp.url'          => null,
            'mail.mailers.smtp.host'         => trim($s['mail_host']),
            'mail.mailers.smtp.port'         => $port,
            'mail.mailers.smtp.username'     => $s['mail_username'] !== '' ? trim($s['mail_username']) : null,
            'mail.mailers.smtp.password'     => $s['mail_password'] !== '' ? $s['mail_password'] : null,
            'mail.mailers.smtp.timeout'      => 60,
            'mail.mailers.smtp.verify_peer'  => (($s['mail_verify_peer'] ?? '1') === '1'),
            'mail.mailers.smtp.auto_tls'     => true,
            'mail.from.address'              => $s['mail_from_address'] ?: ($s['mail_username'] ?: 'no-reply@zelari.com'),
            'mail.from.name'                 => $s['mail_from_name'] ?: ($s['store_name'] ?? 'Zelari'),
        ]);
        // Forget any resolved mailer so the new config is used
        app('mail.manager')->forgetMailers();
        return true;
    }
}
