<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class CandidateProfile extends Model
{
    protected $fillable = ['user_id','phone','headline','location','bio','skills','resume_path','avatar_path','linkedin_url','portfolio_url'];
    protected function casts(): array { return ['skills' => 'array']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
