<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lang = $request->header('Accept-Language', $request->query('lang', 'en'));
        if (!in_array($lang, ['en', 'bn'])) $lang = 'en';

        $title = is_array($this->title) ? ($this->title[$lang] ?? $this->title['en'] ?? '') : ($this->title ?? '');
        $industry = is_array($this->industry) ? ($this->industry[$lang] ?? $this->industry['en'] ?? '') : ($this->industry ?? '');
        $challenge = is_array($this->challenge) ? ($this->challenge[$lang] ?? $this->challenge['en'] ?? '') : ($this->challenge ?? '');
        $solution = is_array($this->solution) ? ($this->solution[$lang] ?? $this->solution['en'] ?? '') : ($this->solution ?? '');
        $result = is_array($this->result) ? ($this->result[$lang] ?? $this->result['en'] ?? '') : ($this->result ?? '');

        // Safely extract meta
        $meta = is_array($this->meta) ? $this->meta : [];

        // Branding
        $color = $meta['color'] ?? '#6366f1';
        $logoColor = $meta['logo_color'] ?? '#06b6d4';
        $logoText = $meta['logo_text'] ?? strtoupper($this->client);
        $logoIcon = $meta['logo_icon'] ?? 'TrendingUp';

        // Project details
        $duration = $meta['duration'] ?? '6 Weeks';
        $team = $meta['team'] ?? '4 Members';
        $launched = $meta['launched'] ?? 'Mar 2024';
        $tag = is_array($meta['tag'] ?? null) 
            ? ($meta['tag'][$lang] ?? $meta['tag']['en'] ?? $industry) 
            : ($meta['tag'] ?? $industry);

        // Problem, Solution, Result sub-objects
        $stats = is_array($meta['stats'] ?? null) 
            ? ($meta['stats'][$lang] ?? $meta['stats']['en'] ?? []) 
            : ($meta['stats'] ?? []);
        if (empty($stats)) {
            $stats = $lang === 'bn' 
                ? ['সময় ও শ্রম হ্রাস', 'উন্নত প্রক্রিয়া', 'ডিজিটাল উপস্থিতি'] 
                : ['Reduced overhead', 'Optimized workflows', 'Digital integration'];
        }

        $tech = $meta['tech'] ?? ['Next.js', 'Laravel API', 'TailwindCSS'];
        
        $metrics = $meta['metrics'] ?? [
            ['label' => 'Time Saved', 'value' => '80%', 'icon' => 'Calendar'],
            ['label' => 'Efficiency', 'value' => '+45%', 'icon' => 'Database']
        ];

        // Format Banner Image URL
        $bannerImage = $this->image_path
            ? (preg_match('#^(https?://|/)#', $this->image_path)
                ? $this->image_path
                : \Illuminate\Support\Facades\Storage::disk('public')->url($this->image_path))
            : null;

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'client' => $this->client,
            'title' => $title,
            'industry' => $industry,
            'bannerImage' => $bannerImage,
            'color' => $color,
            'logoColor' => $logoColor,
            'logoText' => $logoText,
            'logoIcon' => $logoIcon,
            'duration' => $duration,
            'team' => $team,
            'launched' => $launched,
            'tag' => $tag,
            'problem' => [
                'title' => $lang === 'bn' ? 'সমস্যা' : 'The Problem',
                'subtitle' => $lang === 'bn' ? 'উৎপাদনশীলতার অভাব' : 'Legacy Hurdles',
                'desc' => $challenge,
                'stats' => $stats
            ],
            'solution' => [
                'title' => $lang === 'bn' ? 'সমাধান' : 'Our Solution',
                'subtitle' => $lang === 'bn' ? 'কাস্টম টেকনোলজি' : 'Modern Deployment',
                'desc' => $solution,
                'tech' => $tech
            ],
            'result' => [
                'title' => $lang === 'bn' ? 'ফলাফল' : 'The Result',
                'subtitle' => $lang === 'bn' ? 'পরিমাপযোগ্য প্রবৃদ্ধি' : 'Proven Outcomes',
                'desc' => $result,
                'metrics' => $metrics
            ],
            'status' => $this->status,
        ];
    }
}