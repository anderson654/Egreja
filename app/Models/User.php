<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'firstname',
        'lastname',
        'email',
        'password',
        'address',
        'city',
        'country',
        'postal',
        'about',
        'is_active',
        'role_id',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Always encrypt the password when it is updated.
     *
     * @param $value
     * @return string
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function prayer_requests()
    {
        return $this->hasMany(PrayerRequest::class);
    }

    public function getCreatedAtAttribute()
    {
        $newDataCarbom = Carbon::parse($this->attributes['created_at']);
        $dataFormat = $newDataCarbom->format('d/m/Y');
        return $dataFormat;
    }
    public function getPhoneAttribute()
    {
        $phone = $this->attributes['phone'];
        if (strlen($phone) === 12) {
            // Formatar o número no formato desejado
            $codigoPais = substr($phone, 0, 2);
            $ddd = substr($phone, 2, 2);
            $prefixo = substr($phone, 4, 4);
            $sufixo = substr($phone, 8, 4);

            return "+$codigoPais ($ddd) 9 $prefixo-$sufixo";
        }
        return $phone;
    }

    public function setPhoneAttribute($value)
    {
        $limpaString = preg_replace("/[^0-9]/", "", $value);
        if (substr($limpaString, 0, 2) === '55') {
            $limpaString = substr($limpaString, 2);
        }
        $limpaString = str_replace(" ", "", $limpaString);
        $this->attributes['phone'] = $limpaString;
    }


    public static function createNewUserZapi($phone, $dados)
    {
        $user = User::where('phone','like', "%".substr($phone, -8))->first();
        
        if (!$user) {
            $newUser = new User();
            $newUser->phone = $phone;
            $newUser->username = $dados['senderName'] ?? 'anonimo';
            $newUser->email = 'e_greja_' . rand(1, 1000000000) . '@gmail.com';
            $newUser->password = '123456789';
            $newUser->role_id = 4;
            if (!$newUser->save()) {
                Log::info('Erro ao salvar User: ' . $phone);
            }
            $user = $newUser;
        }
        return $user;
    }

    /**
     * Pega todos os Voluntarios que não estão em atendimento
     * @return Collection
     */
    public static function getVoluntariesNotAttending()
    {
        $users = User::where('role_id', 3)
            ->whereDoesntHave('conversations', function ($query) {
                $query->whereIn('status_conversation_id', [1, 2]);
            })
            ->whereNotNull('phone')
            ->get();

        return $users;
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    /**
     * Verifica se o user esta em alguma conversa
     * @param int $userId
     * @return bool
     */
    public static function verifyUserInAttending($userId)
    {
        return Conversation::where('user_id', $userId)->where('status_conversation_id', 1)->exists();
    }
}
