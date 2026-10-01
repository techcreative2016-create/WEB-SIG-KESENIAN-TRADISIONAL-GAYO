@extends('layouts.publik')

@section('title', 'Peta Interaktif | SIG Kesenian Gayo')
@section('body-class', 'map-layout')

@push('styles')
<style>
    /* Premium Always-Visible Map Marker Labels */
    .map-marker-label {
        background-color: rgba(19, 19, 19, 0.85) !important;
        color: #0D9373 !important;
        font-family: 'Poppins', sans-serif !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 12px !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        white-space: nowrap !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4) !important;
        backdrop-filter: blur(5px) !important;
        pointer-events: none !important; /* Let clicks pass through to the marker below */
        transition: all 0.2s ease !important;
    }
    .map-marker-label-sanggar {
        color: #ffb3b3 !important;
        border-color: rgba(192, 0, 48, 0.3) !important;
    }
    .map-marker-label-toko {
        color: #ffe16d !important;
        border-color: rgba(233, 196, 0, 0.3) !important;
    }
    .kerawang-strip {
        height: 4px;
        background: linear-gradient(90deg, #0D9373 0%, #E8B931 50%, #0D9373 100%);
    }
    .glass-panel {
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        background: var(--t-surface);
        border: 1px solid var(--t-outline-variant);
    }
    .dot-pattern {
        background-image: radial-gradient(#0D9373 0.5px, transparent 0.5px);
        background-size: 24px 24px;
        opacity: 0.1;
    }
    .map-container {
        background-color: #F0F7F4;
        position: relative;
        overflow: hidden;
    }
    /* Leaflet Overrides */
    .leaflet-div-icon {
        background: transparent !important;
        border: none !important;
    }
    .leaflet-popup-content-wrapper {
        background: #191919;
        color: #fff;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.1);
    }
    .leaflet-popup-tip {
        background: #191919;
    }
</style>
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@section('content')
<!-- Main Map Canvas -->
<main class="relative h-screen w-screen pt-16">
    <!-- Interactive Google Map -->
    <div id="map" class="absolute inset-0 z-0 map-container"></div>
    
    <!-- Loading Overlay -->
    <div id="mapLoading" class="absolute inset-0 bg-background/50 backdrop-blur-sm flex items-center justify-center z-20 transition-opacity duration-300">
        <div class="animate-spin rounded-full h-12 w-12 border-4 border-primary-container border-t-transparent"></div>
    </div>

    <!-- Map UI Overlay: Legends -->
    <div class="absolute bottom-24 right-4 md:right-10 glass-panel p-4 rounded-xl shadow-2xl z-30">
        <h3 class="font-bold text-label-md mb-3 text-primary text-sm">Legenda Peta</h3>
        <div class="space-y-2">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full" style="background:#e53935"></span>
                <span class="text-label-sm text-on-surface-variant text-xs">Sanggar Seni</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full" style="background:#9c27b0"></span>
                <span class="text-label-sm text-on-surface-variant text-xs">Jadwal Pertunjukan</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-[#2196f3]"></span>
                <span class="text-label-sm text-on-surface-variant text-xs">Toko Seni</span>
            </div>
        </div>
    </div>

    <!-- Directions Panel overlay (Not needed in Leaflet, we open in new tab) -->
    <div id="directionsPanel" class="hidden absolute top-20 right-4 bg-surface/90 backdrop-blur-md border border-[#0D9373]/10 p-4 rounded-xl shadow-2xl max-w-sm w-full z-30 max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-[#0D9373]/10">
            <h3 class="font-bold text-primary flex items-center gap-2"><span class="material-symbols-outlined">route</span> Navigasi Rute</h3>
            <button id="closeDirections" class="text-on-surface-variant hover:text-white"><span class="material-symbols-outlined text-sm">close</span></button>
        </div>
        <div id="directionsContent" class="text-sm">Silakan gunakan Google Maps App untuk rute yang akurat.</div>
    </div>

    <!-- SideNavBar / Filter Panel -->
    <aside class="fixed left-4 top-20 bottom-20 w-80 glass-panel rounded-xl overflow-hidden shadow-2xl z-40 transition-transform duration-300" id="sidebar">
        <div class="flex flex-col h-full py-6 space-y-6">
            <div class="px-6">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-12 h-12 rounded-lg bg-kerawang-green flex items-center justify-center shadow-lg shadow-kerawang-green/20">
                        <span class="material-symbols-outlined text-white">map</span>
                    </div>
                    <div>
                        <h2 class="font-headline-md text-headline-md font-bold text-primary tracking-tight">Eksplorasi GIS</h2>
                        <p class="text-on-surface-variant text-label-sm uppercase tracking-widest text-[10px]">PETA SENI DAN BUDAYA GAYO</p>
                    </div>
                </div>
            </div>
            
            <div class="px-6 space-y-3">
                <!-- Filter Kabupaten -->
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-sm group-hover:text-primary transition-colors" style="color:var(--t-on-surface-variant);">map</span>
                    <select id="filterKabupaten" class="w-full rounded-lg py-3 pl-10 pr-10 focus:ring-2 focus:ring-kerawang-green focus:border-transparent appearance-none cursor-pointer transition-colors" style="background:var(--t-surface-container-high); border:1px solid var(--t-outline-variant); color:var(--t-on-surface);">
                        <option value="">Semua Kabupaten</option>
                        <option value="Bener Meriah">Lihat Kesenian Bener Meriah</option>
                        <option value="Aceh Tengah">Lihat Kesenian Aceh Tengah</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-sm pointer-events-none" style="color:var(--t-on-surface-variant);">expand_more</span>
                </div>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-sm" style="color:var(--t-on-surface-variant);">search</span>
                    <input id="searchInput" class="w-full rounded-lg py-3 pl-10 pr-4 focus:ring-2 focus:ring-kerawang-green focus:border-transparent placeholder-gray-400" placeholder="Cari Sanggar atau Toko..." type="text" style="background:var(--t-surface-container-high); border:1px solid var(--t-outline-variant); color:var(--t-on-surface);"/>
                </div>
                <!-- Pencarian Wilayah -->
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-sm" style="color:var(--t-on-surface-variant);">location_on</span>
                    <input id="locationSearchInput" class="w-full rounded-lg py-3 pl-10 pr-4 focus:ring-2 focus:ring-kerawang-green focus:border-transparent placeholder-gray-400" placeholder="Cari Wilayah (Cth: Takengon)..." type="text" style="background:var(--t-surface-container-high); border:1px solid var(--t-outline-variant); color:var(--t-on-surface);"/>
                    <div id="locationSuggestions" class="absolute left-0 right-0 mt-1 rounded-lg shadow-2xl hidden z-50 max-h-64 overflow-y-auto" style="background:var(--t-surface-container); border:1px solid var(--t-outline-variant);"></div>
                </div>
            </div>

            <!-- Dummy element to prevent JS script reference crash -->
            <div id="defaultSidebar" class="hidden"></div>

            <!-- Menu / Filter Tabs -->
            <div class="flex-grow space-y-1 px-4 overflow-y-auto">
                <!-- Tab Sanggar (Active by default) -->
                <button id="tabSanggar" class="flex items-center w-full gap-4 px-4 py-4 rounded-lg transition-all active:scale-95 shadow-lg font-bold" style="background:var(--t-primary-container); color:var(--t-on-primary-container);">
                    <span class="material-symbols-outlined">theater_comedy</span>
                    <span class="font-bold text-label-md">1. Sanggar Seni</span>
                </button>
                
                <!-- Tab Toko -->
                <button id="tabToko" class="flex items-center w-full gap-4 px-4 py-4 rounded-lg transition-all hover:text-primary active:scale-95" style="color:var(--t-on-surface-variant);">
                    <span class="material-symbols-outlined">store</span>
                    <span class="font-label-md">2. Toko Seni</span>
                </button>
                
                <!-- Tab Pertunjukan -->
                <button id="tabPertunjukan" class="flex items-center w-full gap-4 px-4 py-4 rounded-lg transition-all hover:text-primary active:scale-95" style="color:var(--t-on-surface-variant);">
                    <span class="material-symbols-outlined">festival</span>
                    <span class="font-label-md">3. Pertunjukan</span>
                </button>
                
                <!-- Tab Semua -->
                <button id="tabSemua" class="flex items-center w-full gap-4 px-4 py-4 rounded-lg transition-all hover:text-primary active:scale-95" style="color:var(--t-on-surface-variant);">
                    <span class="material-symbols-outlined">explore</span>
                    <span class="font-label-md">4. Semua Wilayah</span>
                </button>
            </div>

            <div class="px-6 mt-auto">
                <button class="w-full bg-kerawang-green text-white py-4 rounded-xl font-bold shadow-xl shadow-kerawang-green/20 flex items-center justify-center gap-2 hover:bg-opacity-90 transition-all active:scale-95">
                    <span class="material-symbols-outlined">filter_list</span>
                    Filter Data
                </button>
                <div class="kerawang-strip mt-8 rounded-full opacity-60"></div>
            </div>
        </div>
    </aside>

    <!-- Contextual Detail Panel (Bottom Docked sliding up) -->
    <div class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-2xl translate-y-full transition-transform duration-500 ease-in-out z-50 px-4 md:px-0" id="detailPanel">
        <div class="bg-surface-container-high rounded-t-3xl shadow-2xl p-8 border-t border-white/[0.12] overflow-hidden">
            <div class="w-12 h-1.5 bg-white/10 rounded-full mx-auto mb-8 cursor-pointer" onclick="closeDetails()"></div>
            <div class="flex flex-col md:flex-row gap-8 items-start">
                <div class="w-full md:w-48 h-48 rounded-2xl overflow-hidden flex-shrink-0 bg-surface-container shadow-2xl border border-white/[0.1]">
                    <img class="w-full h-full object-cover" id="detailImg" src=""/>
                </div>
                <div class="flex-grow w-full">
                    <div class="flex justify-between items-start w-full gap-4">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-white bg-on-tertiary-container px-3 py-1 rounded" id="detailBadge">SANGGAR</span>
                            <h2 class="font-headline-md text-headline-md mt-3 text-primary" id="detailTitle">Nama Tempat</h2>
                            <p class="text-on-surface-variant flex items-center gap-1 mt-1 text-sm">
                                <span class="material-symbols-outlined text-sm">location_on</span>
                                <span id="detailLocation">Kabupaten</span>
                            </p>
                        </div>
                        <button class="p-2 hover:bg-white/[0.05] rounded-full text-on-surface-variant transition-colors" id="closeDetail">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    <p class="mt-6 text-on-surface-variant leading-relaxed text-sm" id="detailDesc">Deskripsi...</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6 bg-surface-container-low/50 p-4 rounded-xl border border-[#0D9373]/8 text-xs md:text-sm text-on-surface-variant">
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-primary-container text-base shrink-0 mt-0.5">map</span>
                            <p id="detailAddress" class="leading-normal"></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary-container text-base">phone</span>
                            <p id="detailPhone" class="font-mono"></p>
                        </div>
                        <div class="flex items-center gap-2 md:col-span-2">
                            <span class="material-symbols-outlined text-primary-container text-base">info</span>
                            <p id="detailStatus"></p>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex flex-col sm:flex-row gap-4 w-full">
                        <a href="" id="btnProfile" class="flex-1 bg-white text-black font-bold py-4 rounded-xl shadow-lg hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 text-center text-sm">
                            <span class="material-symbols-outlined">account_circle</span> Profil Lengkap
                        </a>
                        <button class="flex-1 bg-kerawang-yellow text-on-primary-fixed font-bold py-4 rounded-xl shadow-lg shadow-kerawang-yellow/10 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2" id="btnRoute">
                            <span class="material-symbols-outlined">directions</span> Kunjungi Lokasi
                        </button>
                        <button class="px-6 py-4 border-2 border-kerawang-green text-kerawang-green rounded-xl font-bold hover:bg-kerawang-green hover:text-white transition-all active:scale-95 flex items-center justify-center gap-2" id="btnHubungi">
                            <span class="material-symbols-outlined">call</span> Hubungi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection


@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    // Config
    const MARKERS_API_URL = '{{ url('/api/peta/markers') }}';
    const DETAIL_API_URL = '{{ url('/api/peta/detail') }}';
    
    // Map State
    let map;
    let markerLayerGroup;
    let polygonLayerGroup;
    let allData = [];
    let userLocation = null;

    // Organic polygon coordinate generator for "wilayah" based on point coordinates
    function generateWilayahPolygon(centerLat, centerLng, id) {
        const numPoints = 8;
        const radius = 0.0075; // roughly 700m to 1km radius
        const coords = [];
        
        // Consistent seed-based organic shape generation for each place
        for (let i = 0; i < numPoints; i++) {
            const angle = (i / numPoints) * 2 * Math.PI;
            const seed = Math.sin(id * 17.293 + i * 43.782) * 53942.3482;
            const randomFactor = 0.75 + (seed - Math.floor(seed)) * 0.5; // variance range 0.75 - 1.25
            
            const lat = centerLat + Math.cos(angle) * radius * randomFactor;
            const lng = centerLng + Math.sin(angle) * radius * randomFactor;
            coords.push([lat, lng]); // Leaflet uses [lat, lng]
        }
        return coords;
    }

    // Initialize Map
    function initMap() {
        const centerPos = [4.62, 96.84]; // Takengon / Bener Meriah area
        
        map = L.map('map', {
            center: centerPos,
            zoom: 11,
            zoomControl: false, // We can add it manually if needed, or rely on default top-left
        });

        // Menggunakan Google Maps Hybrid (Satelit + Label/Nama Jalan) secara langsung (tanpa API Key)
        L.tileLayer('https://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: '&copy; Google Maps'
        }).addTo(map);
        
        L.control.zoom({ position: 'topright' }).addTo(map);

        markerLayerGroup = L.layerGroup().addTo(map);
        polygonLayerGroup = L.layerGroup().addTo(map);

        fetchMarkers();
    }

    async function fetchMarkers() {
        document.getElementById('mapLoading').classList.remove('opacity-0', 'pointer-events-none');
        try {
            const response = await fetch(MARKERS_API_URL);
            const data = await response.json();
            
            if (data.success) {
                allData = data.data;
                renderMarkers(allData);
            }
        } catch (error) {
            console.error('Error fetching markers:', error);
        } finally {
            document.getElementById('mapLoading').classList.add('opacity-0', 'pointer-events-none');
        }
    }

    function renderMarkers(data) {
        // Clear existing layers
        markerLayerGroup.clearLayers();
        polygonLayerGroup.clearLayers();

        data.forEach(item => {
            const isSanggar = item.tipe === 'sanggar';
            const isPertunjukan = item.tipe === 'pertunjukan';
            const itemLat = parseFloat(item.lat);
            const itemLng = parseFloat(item.lng);
            
            if (isNaN(itemLat) || isNaN(itemLng)) return;

            // Generate beautifully styled wilayah boundary polygon
            const polygonCoords = generateWilayahPolygon(itemLat, itemLng, item.id);
            const polyColor = isSanggar ? '#e53935' : (isPertunjukan ? '#9c27b0' : '#0D9373');

            const polygon = L.polygon(polygonCoords, {
                color: polyColor,
                weight: 2,
                opacity: 0.8,
                fillColor: polyColor,
                fillOpacity: 0.15
            }).addTo(polygonLayerGroup);

            // Interactive Polygon events
            polygon.on('mouseover', () => {
                polygon.setStyle({
                    fillOpacity: 0.35,
                    weight: 4
                });
            });

            polygon.on('mouseout', () => {
                polygon.setStyle({
                    fillOpacity: 0.15,
                    weight: 2
                });
            });

            polygon.on('click', () => {
                showDetail(item.tipe, item.id);
            });

            // Create custom HTML marker using L.divIcon
            let markerColor, iconColor, iconName, labelColor, labelBorderColor;
            
            if (isSanggar) {
                markerColor = '#e53935';
                iconColor = 'white';
                iconName = 'theater_comedy';
                labelColor = '#ffb3b3';
                labelBorderColor = 'rgba(229,57,53,0.4)';
            } else if (isPertunjukan) {
                markerColor = '#9c27b0';
                iconColor = 'white';
                iconName = 'festival';
                labelColor = '#f3e5f5';
                labelBorderColor = 'rgba(156,39,176,0.3)';
            } else {
                markerColor = '#E8B931';
                iconColor = '#1B3A2D';
                iconName = 'storefront';
                labelColor = '#ffe16d';
                labelBorderColor = 'rgba(232,185,49,0.9)';
            }
            
            const customIcon = L.divIcon({
                className: 'custom-leaflet-marker',
                html: `
                    <div style="display:flex; flex-direction:column; align-items:center; width: 80px; margin-left: -40px; margin-top: -40px;">
                        <div style="background:${markerColor}; color:${iconColor}; width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; border: 2px solid white; box-shadow: 0 3px 6px rgba(0,0,0,0.4);">
                            <span class="material-symbols-outlined" style="font-size:16px;">${iconName}</span>
                        </div>
                        <div style="background:rgba(19, 19, 19, 0.85); color:${labelColor}; border: 1px solid ${labelBorderColor}; margin-top:3px; padding:2px 8px; border-radius:10px; font-size:10px; font-weight:bold; white-space:nowrap; box-shadow:0 2px 4px rgba(0,0,0,0.3); backdrop-filter: blur(5px);">
                            ${item.nama.length > 15 ? item.nama.substring(0, 15) + '..' : item.nama}
                        </div>
                    </div>
                `,
                iconSize: [0, 0], // The size is handled in HTML styling
                iconAnchor: [0, 0], // Center it using margins in the HTML
            });

            const marker = L.marker([itemLat, itemLng], { icon: customIcon }).addTo(markerLayerGroup);

            // InfoWindow logic using Popup
            const isAcara = item.tipe === 'pertunjukan';
            const actionOnClick = isAcara 
                ? `window.location.href='${item.detail_url}'` 
                : `showDetail('${item.tipe}', ${item.id})`;

            const popupContent = `
                <div style="color: #333; padding: 5px; max-width: 200px; text-align: center;">
                    <h3 style="font-weight: bold; margin-bottom: 5px; font-size: 14px; margin-top:0;">${item.nama}</h3>
                    <p style="font-size: 12px; margin-bottom: 10px; color:#666;">${item.kategori || item.tipe}</p>
                    <button onclick="${actionOnClick}" style="background: #0D9373; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: bold; color: #fff; width:100%;">Lihat Detail</button>
                </div>
            `;
            marker.bindPopup(popupContent, { closeButton: false, minWidth: 150 });

            marker.on('click', () => {
                showDetail(item.tipe, item.id);
            });
        });
    }

    // Sidebar Detail panel logic with premium bottom-slide transitions
    async function showDetail(tipe, id) {
        try {
            const response = await fetch(`${DETAIL_API_URL}/${tipe}/${id}`);
            const result = await response.json();
            if(result.success) {
                const item = result.data;
                
                document.getElementById('detailTitle').textContent = item.nama;
                document.getElementById('detailImg').src = item.foto;
                
                // Set and style badge dynamically
                const badge = document.getElementById('detailBadge');
                badge.textContent = item.kategori || tipe.toUpperCase();
                if (tipe === 'sanggar') {
                    badge.className = "text-[10px] font-bold uppercase tracking-widest text-white bg-on-tertiary-container px-3 py-1 rounded";
                } else if (tipe === 'pertunjukan') {
                    badge.className = "text-[10px] font-bold uppercase tracking-widest text-white px-3 py-1 rounded";
                    badge.style.backgroundColor = "#9c27b0";
                } else {
                    badge.className = "text-[10px] font-bold uppercase tracking-widest text-on-secondary-container bg-secondary-container px-3 py-1 rounded";
                    badge.style.backgroundColor = "";
                }
                
                document.getElementById('detailLocation').textContent = item.kabupaten;
                document.getElementById('detailDesc').textContent = item.deskripsi || 'Tidak ada deskripsi.';
                document.getElementById('detailAddress').textContent = item.alamat;
                document.getElementById('detailPhone').textContent = item.no_telepon || '-';
                
                let statusText = item.status || '-';
                if(item.jam_buka) statusText = `Jam Buka: ${item.jam_buka}`;
                document.getElementById('detailStatus').textContent = statusText;

                // Smoothly pan and zoom map to coordinates
                if (map && item.lat && item.lng) {
                    map.flyTo([parseFloat(item.lat), parseFloat(item.lng)], 15, {
                        duration: 1.5 // seconds
                    });
                }

                // Setup Profil Lengkap button
                const btnProfile = document.getElementById('btnProfile');
                if (btnProfile) {
                    btnProfile.innerHTML = (tipe === 'pertunjukan') 
                        ? '<span class="material-symbols-outlined">event_note</span> Detail Jadwal'
                        : '<span class="material-symbols-outlined">account_circle</span> Profil Lengkap';
                        
                    if (tipe === 'pertunjukan') {
                        btnProfile.href = `{{ url('/') }}/jadwal/${item.slug}`;
                    } else {
                        btnProfile.href = `{{ url('/') }}/${tipe}/${item.slug}`;
                    }
                }

                // Setup route button
                const btnRoute = document.getElementById('btnRoute');
                btnRoute.onclick = () => calculateAndDisplayRoute(item.lat, item.lng);

                // Setup Hubungi button
                const btnHubungi = document.getElementById('btnHubungi');
                if (btnHubungi) {
                    if (item.no_telepon && item.no_telepon !== '-') {
                        btnHubungi.style.display = 'flex';
                        btnHubungi.onclick = () => {
                            window.location.href = `tel:${item.no_telepon}`;
                        };
                    } else {
                        btnHubungi.style.display = 'none';
                    }
                }

                // Smoothly slide up details card
                const panel = document.getElementById('detailPanel');
                panel.classList.remove('translate-y-full');
                panel.classList.add('translate-y-0');
            }
        } catch (e) {
            console.error('Fetch detail failed', e);
        }
    }

    function closeDetails() {
        const panel = document.getElementById('detailPanel');
        panel.classList.add('translate-y-full');
        panel.classList.remove('translate-y-0');
    }

    document.getElementById('closeDetail').addEventListener('click', closeDetails);

    // Geocoding / Location Search Logic
    const locSearchInput = document.getElementById('locationSearchInput');
    const locSuggestions = document.getElementById('locationSuggestions');
    let debounceTimer;

    locSearchInput.addEventListener('input', (e) => {
        clearTimeout(debounceTimer);
        const query = e.target.value.trim();
        
        if (query.length < 3) {
            locSuggestions.classList.add('hidden');
            return;
        }

        debounceTimer = setTimeout(async () => {
            try {
                // Use Nominatim API for open source geocoding
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=5`);
                const results = await response.json();
                
                if (results.length > 0) {
                    locSuggestions.innerHTML = '';
                    results.forEach(place => {
                        const div = document.createElement('div');
                        div.className = 'px-4 py-3 hover:bg-white/[0.05] cursor-pointer text-sm text-white border-b border-white/[0.05] last:border-0 transition-colors';
                        div.innerHTML = `<div class="font-bold text-[#0D9373]">${place.display_name.split(',')[0]}</div><div class="text-xs text-gray-400 mt-1">${place.display_name}</div>`;
                        div.onclick = () => {
                            map.flyTo([place.lat, place.lon], 14, { duration: 1.5 });
                            locSearchInput.value = place.display_name.split(',')[0];
                            locSuggestions.classList.add('hidden');
                        };
                        locSuggestions.appendChild(div);
                    });
                    locSuggestions.classList.remove('hidden');
                } else {
                    locSuggestions.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400">Wilayah tidak ditemukan</div>';
                    locSuggestions.classList.remove('hidden');
                }
            } catch (error) {
                console.error("Geocoding error:", error);
            }
        }, 600);
    });

    document.addEventListener('click', (e) => {
        if (!locSearchInput.contains(e.target) && !locSuggestions.contains(e.target)) {
            locSuggestions.classList.add('hidden');
        }
    });

    // Filtering Logic
    function applyFilters() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const kabTerm = document.getElementById('filterKabupaten').value;

        const filtered = allData.filter(item => {
            const matchSearch = item.nama.toLowerCase().includes(searchTerm);
            const matchKab = kabTerm === "" || item.kabupaten === kabTerm;
            return matchSearch && matchKab;
        });

        renderMarkers(filtered);
    }

    document.getElementById('searchInput').addEventListener('input', applyFilters);
    
    document.getElementById('filterKabupaten').addEventListener('change', (e) => {
        applyFilters();
        // Fly to specific coordinates to simulate jumping between districts
        if(e.target.value === 'Bener Meriah') {
            map.flyTo([4.73, 96.86], 11, { duration: 1.5 });
        } else if(e.target.value === 'Aceh Tengah') {
            map.flyTo([4.62, 96.84], 11, { duration: 1.5 });
        } else {
            map.flyTo([4.67, 96.85], 10, { duration: 1.5 });
        }
    });

    // Directions / Routing using Google Maps in new tab
    function calculateAndDisplayRoute(destLat, destLng) {
        // Since Leaflet doesn't have a free routing API equivalent to Google Maps Directions Service out of the box,
        // it is standard practice to link to Google Maps external intent for actual driving directions.
        const url = `https://www.google.com/maps/dir/?api=1&destination=${destLat},${destLng}`;
        window.open(url, '_blank');
    }

    document.getElementById('closeDirections').addEventListener('click', () => {
        document.getElementById('directionsPanel').classList.add('hidden');
    });

    // Tab switching UI logic with premium Tailwind transitions & dynamic filter
    const tabs = [
        { id: 'tabSanggar', filterType: 'sanggar' },
        { id: 'tabToko', filterType: 'toko' },
        { id: 'tabPertunjukan', filterType: 'pertunjukan' },
        { id: 'tabSemua', filterType: 'semua' }
    ];

    tabs.forEach(tab => {
        const el = document.getElementById(tab.id);
        if (el) {
            el.addEventListener('click', () => {
                // Reset classes for all tabs to inactive state
                tabs.forEach(t => {
                    const btn = document.getElementById(t.id);
                    if (btn) {
                        btn.style.background = 'transparent';
                        btn.style.color = 'var(--t-on-surface-variant)';
                        btn.classList.remove('shadow-lg', 'font-bold');
                    }
                });

                // Add active classes to selected tab
                el.classList.add('shadow-lg', 'font-bold');
                
                if (tab.id === 'tabSanggar' || tab.id === 'tabSemua') {
                    el.style.background = 'var(--t-primary-container)';
                    el.style.color = 'var(--t-on-primary-container)';
                } else if (tab.id === 'tabToko') {
                    el.style.background = '#E8B931';
                    el.style.color = '#1B3A2D';
                } else if (tab.id === 'tabPertunjukan') {
                    el.style.background = '#9c27b0';
                    el.style.color = 'white';
                }

                // Close active detail panel
                closeDetails();

                // Filter and render markers
                if (tab.filterType === 'semua') {
                    renderMarkers(allData);
                    if (map) {
                        map.flyTo([4.62, 96.84], 11, { duration: 1.5 });
                    }
                } else {
                    const filtered = allData.filter(item => item.tipe === tab.filterType);
                    renderMarkers(filtered);
                    
                    if (filtered.length > 0 && map) {
                        const bounds = L.latLngBounds();
                        filtered.forEach(item => {
                            bounds.extend([parseFloat(item.lat), parseFloat(item.lng)]);
                        });
                        map.flyToBounds(bounds, { duration: 1.5, padding: [50, 50] });
                    }
                }
            });
        }
    });

    // Auto-collapse sidebar on smaller mobile devices
    if (window.innerWidth < 1024) {
        document.getElementById('sidebar').classList.add('-translate-x-full');
    }

    // Initialize Map on DOM Content Loaded
    document.addEventListener("DOMContentLoaded", function() {
        initMap();
    });
</script>
@endpush
