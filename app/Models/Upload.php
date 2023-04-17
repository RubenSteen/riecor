<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Upload extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Upload $upload) {
            $unique_name = Str::uuid();
            $upload->path = "{$upload->path}/{$unique_name}.{$upload->original_extension}";
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'path',
        'user_id',
        'original_name',
        'original_extension',
        'original_size',
        'original_mime_type',
    ];
}
