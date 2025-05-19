<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments'; 

    protected $fillable = [
        'document',  
        'name',
        'datetime', 
    ]; 

    protected function setNameAttribute($name)
    {
        $this->attributes['name'] = trim(strip_tags($name ?? ''));
    }   

    protected function setDocumentAttribute($document)
    {
        $this->attributes['document'] = preg_replace('/\D/', '', $document); 
    }   
}