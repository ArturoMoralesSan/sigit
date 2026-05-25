<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',        
        'area_id',        
        'subject',        
        'name',        
        'date',        
        'start_time',        
        'end_time',        
        'status',        
        'color' ,
        'asignature'   
    ];       
    
    protected $casts = [
        'date' => 'date:Y-m-d'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function getStartDateTimeAttribute()
    {
        return $this->date->format('Y-m-d') . ' ' . $this->start_time;
    }

    public function getEndDateTimeAttribute()
    {
        return $this->date->format('Y-m-d') . ' ' . $this->end_time;
    }
}
