<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'document_image',
        'healthcare_provider_id'
      
    ];
    use HasFactory;
    public function healthcareProvider()
    {
        return $this->belongsTo(HealthcareProvider::class);
    }
}
