<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database didefinisikan manual karena menggunakan 'category' (singular).
     */
    protected $table = 'categories';

    /**
     * Kolom yang dapat diisi melalui mass assignment.
     */
protected $fillable = [
    'name',
    'slug'
];

    /**
     * Relasi One-to-Many ke Product untuk menghitung TOTAL PRODUCT.
     */
    public function products(): HasMany
    {
        // Parameter kedua 'category_id' merujuk pada foreignId di tabel product
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}