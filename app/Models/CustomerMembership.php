<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CustomerMembership extends Model
{
    protected $fillable = [
        'customer_id',
        'membership_number',
        'tier',
        'stamp',
        'total_orders',
        'total_spent',
        'member_since',
        'family_since',
        'public_token',
        'member_code',
    ];

    protected $casts = [
        'member_since' => 'datetime',
        'family_since' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function rewardClaims(): HasMany
    {
        return $this->hasMany(MembershipRewardClaim::class);
    }

    public function isFamily(): bool
    {
        return $this->tier === 'family';
    }

    protected static function booted(): void
    {
        static::creating(function ($membership) {

            if (! $membership->public_token) {
                $membership->public_token = Str::uuid();
            }

        });

        static::created(function ($membership) {

            if (! $membership->member_code) {

                $membership->updateQuietly([
                    'member_code' => sprintf(
                        'SKL-%06d',
                        $membership->id
                    ),
                ]);

            }

        });
    }

    public function availableRewards()
    {
        return $this->rewardClaims()
            ->whereNull('used_at');
    }

    public function shareText(): string
    {
        return "Halo {$this->customer->name} 👋

Terima kasih sudah mempercayakan sepatu kamu kepada Soklenn.

Mulai hari ini, kamu resmi menjadi bagian dari Membership Soklenn 🎉

━━━━━━━━━━━━━━━

📌 Member Code
{$this->member_code}

⭐ Status Member
".ucfirst($this->tier)."

👟 Progress Stamp
{$this->stamp}/15

🔗 Kartu Membership
".route('membership.card', $this->public_token).'

━━━━━━━━━━━━━━━

Ada satu tujuan spesial yang bisa kamu capai.

Saat berhasil mengumpulkan 15 stamp, status membership kamu akan otomatis naik menjadi ⭐ Soklenn Family.

Status ini kami berikan untuk pelanggan yang paling setia mempercayakan perawatan sepatunya kepada Soklenn.

Jadi jangan lupa simpan kartu membership kamu ya. Setiap transaksi akan membawa kamu semakin dekat ke reward berikutnya.

Sampai ketemu lagi di kunjungan berikutnya 👟✨

Clean, Fresh, Soklenn.';
    }
}
