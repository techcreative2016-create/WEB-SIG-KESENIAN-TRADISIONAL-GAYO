<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MobileHeroBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MobileHeroBannerController extends Controller
{
    public function index()
    {
        $banners = MobileHeroBanner::orderBy('urutan', 'asc')->get();
        return view('master.mobile-hero.index', compact('banners'));
    }

    public function create()
    {
        return view('master.mobile-hero.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'tag' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'section_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'quote' => 'nullable|string',
            'tips_title' => 'nullable|string|max:255',
            'urutan' => 'integer',
        ]);

        $data = $request->except(['image', 'stats', 'info_rows', 'tips']);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/mobile_hero');
            $data['image_path'] = str_replace('public/', 'storage/', $path);
        }

        // Handle JSON arrays from dynamic form inputs
        $data['stats'] = $this->formatJsonArray($request->input('stats_icon'), $request->input('stats_label'));
        $data['info_rows'] = $this->formatJsonArrayInfoRows($request->input('info_icon'), $request->input('info_label'), $request->input('info_value'));
        $data['tips'] = array_values(array_filter($request->input('tips', [])));
        $data['is_active'] = $request->has('is_active');

        MobileHeroBanner::create($data);

        return redirect()->route('master.mobile-hero.index')->with('sukses', 'Banner berhasil ditambahkan.');
    }

    public function edit(MobileHeroBanner $mobileHero)
    {
        return view('master.mobile-hero.edit', compact('mobileHero'));
    }

    public function update(Request $request, MobileHeroBanner $mobileHero)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'tag' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'section_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'quote' => 'nullable|string',
            'tips_title' => 'nullable|string|max:255',
            'urutan' => 'integer',
        ]);

        $data = $request->except(['image', 'stats', 'info_rows', 'tips']);
        
        if ($request->hasFile('image')) {
            if ($mobileHero->image_path) {
                $oldPath = str_replace('storage/', 'public/', $mobileHero->image_path);
                Storage::delete($oldPath);
            }
            $path = $request->file('image')->store('public/mobile_hero');
            $data['image_path'] = str_replace('public/', 'storage/', $path);
        }

        // Handle JSON arrays
        $data['stats'] = $this->formatJsonArray($request->input('stats_icon'), $request->input('stats_label'));
        $data['info_rows'] = $this->formatJsonArrayInfoRows($request->input('info_icon'), $request->input('info_label'), $request->input('info_value'));
        $data['tips'] = array_values(array_filter($request->input('tips', [])));
        $data['is_active'] = $request->has('is_active');

        $mobileHero->update($data);

        return redirect()->route('master.mobile-hero.index')->with('sukses', 'Banner berhasil diperbarui.');
    }

    public function destroy(MobileHeroBanner $mobileHero)
    {
        if ($mobileHero->image_path) {
            $oldPath = str_replace('storage/', 'public/', $mobileHero->image_path);
            Storage::delete($oldPath);
        }
        $mobileHero->delete();
        return redirect()->route('master.mobile-hero.index')->with('sukses', 'Banner berhasil dihapus.');
    }

    private function formatJsonArray($icons, $labels)
    {
        $result = [];
        if (is_array($icons) && is_array($labels)) {
            for ($i = 0; $i < count($icons); $i++) {
                if (!empty($icons[$i]) && !empty($labels[$i])) {
                    $result[] = ['icon' => $icons[$i], 'label' => $labels[$i]];
                }
            }
        }
        return empty($result) ? null : $result;
    }

    private function formatJsonArrayInfoRows($icons, $labels, $values)
    {
        $result = [];
        if (is_array($icons) && is_array($labels) && is_array($values)) {
            for ($i = 0; $i < count($icons); $i++) {
                if (!empty($icons[$i]) && !empty($labels[$i]) && !empty($values[$i])) {
                    $result[] = [
                        'icon' => $icons[$i],
                        'label' => $labels[$i],
                        'value' => $values[$i]
                    ];
                }
            }
        }
        return empty($result) ? null : $result;
    }
}
