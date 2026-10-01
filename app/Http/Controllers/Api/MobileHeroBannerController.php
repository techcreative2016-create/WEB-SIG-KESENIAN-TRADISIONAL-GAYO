<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MobileHeroBanner;

class MobileHeroBannerController extends Controller
{
    public function index()
    {
        $banners = MobileHeroBanner::where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'desc')
            ->get();
            
        // Map data to match Flutter model expectations exactly
        $formattedBanners = $banners->map(function ($banner) {
            return [
                'title' => $banner->title,
                'subtitle' => $banner->subtitle,
                'tag' => $banner->tag ?? '',
                'imageUrl' => $banner->image_path ? asset($banner->image_path) : '',
                'stats' => $banner->stats ?? [],
                'sectionTitle' => $banner->section_title ?? '',
                'description' => $banner->description ?? '',
                'infoRows' => $banner->info_rows ?? [],
                'quote' => $banner->quote ?? '',
                'tipsTitle' => $banner->tips_title ?? '',
                'tips' => $banner->tips ?? [],
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedBanners
        ]);
    }
}
