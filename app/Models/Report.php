<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'image_path',
        'location_name', 'latitude', 'longitude',
        'ai_summary', 'ai_safety_advice', 'ai_gov_action',
        'ai_severity_score', 'upvote_count', 'priority_score', 'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function upvotes(): HasMany
    {
        return $this->hasMany(ReportUpvote::class);
    }

    /**
     * Hitung ulang skor prioritas gabungan:
     * (AI Severity * 60%) + (Upvote Weight * 40%)
     */
    public function recalculatePriorityScore(): void
    {
        $severityWeight = $this->ai_severity_score * 0.60;
        
        // Pembobotan upvote logaritmik/cap (Maks 100 poin)
        $upvoteScore = min($this->upvote_count * 5, 100); 
        $upvoteWeight = $upvoteScore * 0.40;

        $this->priority_score = round($severityWeight + $upvoteWeight, 2);
        $this->save();
    }
}