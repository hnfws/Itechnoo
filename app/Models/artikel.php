<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    // Nama tabel di database (sesuai migration)
    protected $table = 'artikels';

    // WAJIB ADA agar Eloquent bisa menyimpan data
    protected $fillable = [
        'admin_id',
        'title',
        'content',
        'image',
        'status',
    ];
}