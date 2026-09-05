<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Report extends Model
{
    use HasFactory;

    // Nilai status yang valid beserta labelnya
    const STATUSES = [
        'belum diverifikasi'     => 'Pending',
        'terverifikasi'    => 'Terverifikasi',
        'terverifikasi_in_progress' => 'Dalam Perbaikan',
        'resolved'    => 'Selesai',
        'rejected'    => 'Ditolak',
    ];

    protected $fillable = [
        'reporter_key',
        'reporter',
        'phone',
        'admin_id',
        'title',
        'description',
        'image',
        'latitude',
        'longitude',
        'location',
        'severity',
        'urgency',
        'priority_score',
        'potential_risk',
        'ai_masyarakat',
        'ai_adm',
        'status',
        'show_name',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function upvotes(): HasMany
    {
        return $this->hasMany(ReportUpvote::class);
    }

    // Label status dalam Bahasa Indonesia
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    // Warna teks per status (dipakai di Blade)
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
    'belum diverifikasi'        => 'text-amber-500',
    'terverifikasi'             => 'text-green-600',
    'terverifikasi_in_progress' => 'text-brand-600',
    'resolved'                  => 'text-green-700',
    'rejected'                  => 'text-danger',
    default                     => 'text-ink-muted',
};

    }

    /**
     * Hitung ulang skor prioritas:
     * (Bobot Severity AI * 60%) + (Upvote Score * 40%)
     */
    public function recalculatePriorityScore(): void
    {
        $severityScore = match (strtolower($this->severity ?? 'rendah')) {
            'critical', 'tinggi', 'high' => 100,
            'medium', 'sedang'           => 60,
            default                      => 30,
        };

        $severityWeight = $severityScore * 0.60;
        
        // Hitung total vote langsung dari relasi report_upvotes
        $upvoteCount = $this->upvotes()->count();
        $upvoteScore = min($upvoteCount * 5, 100); // Max 100 poin
        $upvoteWeight = $upvoteScore * 0.40;

        $this->priority_score = round($severityWeight + $upvoteWeight, 2);
        $this->save();
    }

    // Accessor untuk mendapatkan string level prioritas ('tinggi', 'menengah', 'rendah')
public function getPriorityLevelAttribute(): string
{
    if ($this->priority_score >= 80 || strtolower($this->severity ?? '') === 'tinggi') {
        return 'tinggi';
    }

    if ($this->priority_score >= 50 || strtolower($this->severity ?? '') === 'sedang') {
        return 'menengah';
    }

    return 'rendah';
}

    /**
     * Accessor untuk mendapatkan nama pelapor yang ter-sensor dinamis berdasarkan huruf depan nama.
     * Dipanggil di Blade dengan: $report->formatted_reporter
     */
    public function getFormattedReporterAttribute(): string
    {
        // 1. Jika show_name = true (centang), tampilkan nama asli
        if ($this->show_name) {
            return $this->reporter;
        }

        // 2. Jika nama kosong
        if (empty($this->reporter)) {
            return 'Anonim';
        }

        // 3. Sensor kata per kata berdasarkan huruf depan masing-masing kata
        $words = explode(' ', trim($this->reporter));
        $maskedWords = array_map(function ($word) {
            if (empty($word)) return '';
            
            // Mengambil 1 huruf pertama dari kata tersebut
            $firstLetter = Str::substr($word, 0, 1); 
            
            // Menggabungkan huruf pertama dengan bintang
            return $firstLetter . '****'; 
        }, $words);

        return implode(' ', $maskedWords);
    }

}