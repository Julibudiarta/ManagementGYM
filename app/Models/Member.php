<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class member extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'birthdate',
        'gender',
        'photo',
        'identification',
    ];
    public function Transactions(){
        return $this->hasMany(Transactions::class);
    }
    public function memberType(){
        return $this->belongsTo(Membership_type::class,'membership_id');
    }

    public function Membership(){
        return $this->hasMany(Member_subscriptions::class,'member_id');
    }
    public function checkIns()
    {
        return $this->hasMany(CheckIn::class);
    }
        public function lastCheckIn()
    {
        return $this->hasOne(CheckIn::class)->latest();
    }

    public function activeMembership()
    {
        return $this->hasOne(Member_subscriptions::class, 'member_id')
            ->latestOfMany(); // Mengambil paket aktif terbaru
    }

    // jumlah sisa waktu hitungan hari Memberhip yang di beli keseluruhan
    public function durationMembership(){
        $dateNow = date("Y-m-d"); // Tanggal sekarang
        $endDate = date("Y-m-d", strtotime($this->activeMembership()->value('end_date'))); // Ambil tanggal akhir membership
    
        if (!$endDate) {
            return 0; // Jika tidak ada end_date, kembalikan 0
        }
    
        // Hitung selisih hari
        $duration = (strtotime($endDate) - strtotime($dateNow)) / (60 * 60 * 24);
        if($duration < 0){
            return 0;
        }
        return $duration;
    }

}
