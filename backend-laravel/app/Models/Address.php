<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'zip_code',
        'country',
        'phone',
        'is_default',
        'type',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // Relacionamentos
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeShipping($query)
    {
        return $query->whereIn('type', ['shipping', 'both']);
    }

    public function scopeBilling($query)
    {
        return $query->whereIn('type', ['billing', 'both']);
    }

    // Métodos
    public function setAsDefault(): void
    {
        // Remover default de outros endereços do usuário
        $this->user->addresses()->update(['is_default' => false]);

        // Definir este como default
        $this->update(['is_default' => true]);
    }

    public function getFormattedAddressAttribute(): string
    {
        return "{$this->street}, {$this->number}" .
               ($this->complement ? ", {$this->complement}" : '') .
               ", {$this->neighborhood}, {$this->city}/{$this->state} - {$this->zip_code}";
    }
}
