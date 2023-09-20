<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'age',
        'sex',
        'marital_status',
        'phone',
        'email',
        'igreja',
        'time',
        'time_convertion',
        'batizado',
        'alredy_voluntary'
    ];


    public function setPhoneAttribute($value)
    {
        $limpaString = preg_replace("/[^0-9]/", "", $value);
        $limpaString = str_replace(" ", "", $limpaString);
        $this->attributes['phone'] = $limpaString;
    }
    public function getNameAttribute()
    {
        $name = $this->attributes['name'];
        // Converte o nome para minúsculas
        $nome = strtolower($name);
        // Capitaliza a primeira letra de cada palavra
        $nomeFormatado = ucwords($nome);
        return $nomeFormatado;
    }
    public function getEmailAttribute()
    {
        $email = $this->attributes['email'];
        // Converte o nome para minúsculas
        $nome = strtolower($email);
        return $nome;
    }

    public function getCreatedAtAttribute()
    {
        $created_at = $this->attributes['created_at'];
        $data = Carbon::create($created_at);
        $dataFormatada = $data->format('d/m/Y h:m');
        return $dataFormatada ?? '';
    }

    public function getSurnameAttribute()
    {
        $name = $this->attributes['surname'];
        // Converte o nome para minúsculas
        $nome = strtolower($name);
        // Capitaliza a primeira letra de cada palavra
        $nomeFormatado = ucwords($nome);
        return $nomeFormatado;
    }
    public function getSexAttribute()
    {
        $sexo = $this->attributes['sex'];
        if ($sexo === 'F') {
            return 'Feminino';
        }
        if ($sexo === 'M') {
            return 'Masculino';
        }
    }
    public function getPhoneAttribute()
    {
        $phone = $this->attributes['phone'];
        if (strlen($phone) === 11) {
            // Formatar o número no formato desejado
            $codigoPais = substr($phone, 0, 2);
            $ddd = substr($phone, 2, 2);
            $prefixo = substr($phone, 4, 4);
            $sufixo = substr($phone, 8, 4);

            return "+55 (41) 9 $prefixo-$sufixo";
        }
        return $phone;
    }
    public function getBatizadoAttribute()
    {
        $batizado = $this->attributes['batizado'];
        if ($batizado) {
            return "Sim";
        }
        return 'Não';
    }
    public function getAlredyVoluntaryAttribute()
    {
        $batizado = $this->attributes['alredy_voluntary'];
        if ($batizado) {
            return "Sim";
        }
        return 'Não';
    }
}
