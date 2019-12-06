<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SanbornsCheckAccounts extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'sanborns_id'
    ];

    public function returns()
    {
        return $this->hasOne(SanbornsTotalDevoluciones::class,'cuenta', 'sanborns_id');
    }

    public function charges()
    {
        return $this->hasOne(SanbornsTotalCobros::class,'cuenta', 'sanborns_id');
    }

    protected $table = 'sanborns_check_accounts';
}
