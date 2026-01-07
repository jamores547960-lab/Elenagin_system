<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sale; // <-- NEW: Import the Sale model

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes; 

    // 1. ADD ROLE CONSTANTS HERE
    const ROLE_ADMIN = 'admin';
    const ROLE_INVENTORY = 'employee';
    const ROLE_CASHIER = 'cashier'; // <-- NEW: Cashier Role Constant

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // This column is crucial for role-based access control         
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', 
        ];
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
    
    // 2. ADD SALES RELATIONSHIP
    /**
     * Get the sales records made by the user (Cashier).
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales()
    {
        // Links this User (Cashier) to all Sale records they created.
        return $this->hasMany(Sale::class, 'user_id'); 
    }
}