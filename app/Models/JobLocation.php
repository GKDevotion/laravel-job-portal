<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class JobLocation extends Model { protected $fillable=['city','state','country','slug','is_active']; protected function casts(): array { return ['is_active'=>'boolean']; } }
