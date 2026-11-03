@extends('layouts.app')

@section('title', 'Detail Analisis - ' . $aset->nama_aset)

@section('content')

<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js"></script>

@php
    $kriterias = \App\Models\Kriteria::all();
    
    // Identifikasi asal game / ekosistem secara dinamis untuk keperluan presentasi TA
    $isSteam = (strtolower($aset->jenis_aset) === 'steam');
    $namaGame = $isSteam ? 'Counter-Strike 2' : 'Ethereum Network';
    $ikonGame = $isSteam ? 'ti-brand-steam' : 'ti-currency-ethereum';
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap');
    
    /* ====================================================
       VARIABEL WARNA PREMIUM (MENDUKUNG MODE GELAP & TERANG)
       ==================================================== */
    :root {
        --color-background-primary: #0a0f1d;
        --color-background-secondary: #0f172a;
        --color-text-primary: #ffffff;
        --color-text-secondary: #94a3b8;
        --color-border-secondary: rgba(0, 212, 255, 0.16);
        --color-border-tertiary: rgba(0, 212, 255, 0.06);
        --color-track-bg: rgba(255, 255, 255, 0.05);
        --color-grid-line: rgba(0, 212, 255, 0.03);
        --accent-cyan: #00d4ff;
    }

    [data-theme="light"], .light {
        --color-background-primary: #f1f5f9;
        --color-background-secondary: #ffffff;
        --color-text-primary: #0f172a;
        --color-text-secondary: #64748b;
        --color-border-secondary: rgba(15, 23, 42, 0.14);
        --color-border-tertiary: rgba(15, 23, 42, 0.05);
        --color-track-bg: rgba(15, 23, 42, 0.08);
        --color-grid-line: rgba(15, 23, 42, 0.03);
        --accent-cyan: #0284c7;
    }

    /* FULLSCREEN & SIDEBAR REMOVAL OVERRIDE HACK */
    aside, .sidebar, [id*="sidebar"], [class*="sidebar"] {
        display: none !important;
    }

    main, .main-content, .content-wrapper, div.flex-1, [class*="main-content"] {
        margin-left: 0 !important;
        padding-left: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    body, html, .bg-background, main, div.overflow-y-auto, .D-container-detail {
        background-color: var(--color-background-primary) !important;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .D-container-detail {
        font-family: 'Space Grotesk', sans-serif;
        color: var(--color-text-primary);
        padding: 32px;
        min-height: 100vh;
    }

    .panel-premium {
        background: var(--color-background-secondary);
        border: 1px solid var(--color-border-tertiary);
        border-radius: 20px;
        padding: 24px;
        height: 100%;
        transition: background 0.3s, border-color 0.3s;
    }

    /* KARTU VISUAL SHOWCASE UTAMA */
    .img-showcase-card {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        background: #060e20;
        border: 1px solid var(--color-border-tertiary);
    }

    /* MODERN NEON FINANCIAL CHART STYLING */
    .chart-container-cyber {
        position: relative;
        height: 150px;
        border-radius: 12px;
        background-image: 
            linear-gradient(var(--color-grid-line) 1px, transparent 1px),
            linear-gradient(90deg, var(--color-grid-line) 1px, transparent 1px);
        background-size: 24px 24px;
        background-position: center;
        border-bottom: 1px solid var(--color-border-secondary);
    }
    
    .chart-bar-mock {
        width: 12px;
        background: linear-gradient(180deg, var(--accent-cyan) 0%, rgba(0, 212, 255, 0.2) 60%, transparent 100%);
        border-radius: 20px 20px 0 0;
        box-shadow: 0 0 12px rgba(0, 212, 255, 0.4);
        transition: all 0.3s ease;
        position: relative;
    }
    
    .chart-bar-mock::after {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 6px;
        height: 6px;
        background: #ffffff;
        border-radius: 50%;
        box-shadow: 0 0 8px var(--accent-cyan);
    }

    .chart-bar-mock:hover {
        background: linear-gradient(180deg, #4dabf7 0%, var(--accent-cyan) 50%, transparent 100%);
        box-shadow: 0 0 20px rgba(0, 212, 255, 0.7);
        transform: scaleY(1.03);
    }

    /* PROGRESS BAR KRITERIA SPK */
    .progress-track-premium {
        width: 100%;
        height: 6px;
        background: var(--color-track-bg);
        border-radius: 10px;
        overflow: hidden;
    }
    .progress-fill-premium {
        height: 100%;
        background: linear-gradient(90deg, var(--accent-cyan) 0%, #4dabf7 100%);
        border-radius: 10px;
    }

    /* PREMIUM ACTION UTILITY */
    .btn-action-route {
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        width: 100%;
    }
    .btn-blue-gradient {
        background: linear-gradient(90deg, var(--accent-cyan) 0%, #4dabf7 100%);
        color: #0a0f1d !important;
        box-shadow: 0 4px 14px rgba(0, 212, 255, 0.25);
    }
    .btn-blue-gradient:hover {
        background: linear-gradient(90deg, #00bfe6 0%, #339af0 100%);
        transform: translateY(-2px);
    }
    .btn-outline-dark {
        background: transparent;
        color: var(--color-text-primary) !important;
        border: 1px solid var(--color-border-secondary) !important;
    }
    .btn-outline-dark:hover {
        background: rgba(255, 255, 255, 0.02);
        border-color: var(--accent-cyan) !important;
    }

    /* TOPBAR ACTION UTILS */
    .icon-btn { 
        width: 42px; 
        height: 40px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        border: 1px solid var(--color-border-secondary); 
        border-radius: 12px; 
        cursor: pointer; 
        color: var(--color-text-secondary); 
        background: transparent; 
        transition: all 0.2s; 
    }
    .icon-btn:hover { 
        color: var(--accent-cyan); 
        border-color: var(--accent-cyan); 
        background: rgba(0, 212, 255, 0.05); 
    }
    
    .text-muted {
        color: var(--color-text-secondary) !important;
    }
</style>

<header class="w-full border-b d-flex justify-content-between align-items-center px-5" style="height: 84px; background: var(--color-background-secondary); border-color: var(--color-border-tertiary) !important;">
    <div style="font-size: 15px; font-weight: 600; color: var(--color-text-secondary); letter-spacing: 0.5px;" class="font-mono mb-0">
        CYBER-FINANCE PROTOCOL &gt; <span style="color: var(--accent-cyan); font-weight: 700;">DETAIL ASET</span>
    </div>
    <div class="d-flex align-items-center gap-3">
        <div class="icon-btn" id="themeToggle" title="Ubah Tema" style="font-size: 16px;"><i class="ti ti-sun" id="themeIcon" aria-hidden="true"></i></div>
        <a href="{{ route('aset-digital.index') }}" class="btn-sync-premium py-2 px-4 rounded-3 d-flex align-items-center gap-2 text-decoration-none" style="border: 1px solid var(--color-border-secondary); color: var(--accent-cyan); font-size: 13px; font-weight: 700; height: 40px; transition: all 0.2s;">
            <i class="ti ti-arrow-back-up" style="font-size: 15px;"></i> Kembali ke Pasar
        </a>
    </div>
</header>

<div class="D-container-detail">

    <div class="row g-4">
        
        <div class="col-lg-7">
            <div class="d-flex flex-column gap-4">
                
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge rounded-1 text-uppercase font-mono" style="background: rgba(0, 212, 255, 0.15); color: var(--accent-cyan); font-size: 10px; letter-spacing: 0.5px; border: 0.5px solid rgba(0, 212, 255, 0.3);">
                            {{ $aset->jenis_aset === 'opensea' ? 'Legendary NFT' : 'Premium Skin' }}
                        </span>
                        <span class="text-muted font-mono" style="font-size: 11px;">ID: AZ-99{{ $aset->id }}</span>
                    </div>
                    <h1 class="fw-bold text-uppercase mb-1" style="font-size: 30px; letter-spacing: -0.5px; color: var(--color-text-primary);">
                        {{ str_replace('-', ' ', $aset->nama_aset) }}
                    </h1>
                    <p class="font-mono text-uppercase m-0" style="font-size: 12px; color: var(--accent-cyan) !important; font-weight: 600;">
                        <i class="ti {{ $ikonGame }}"></i> Game/Ecosystem: {{ $namaGame }}
                    </p>
                </div>

                <div class="img-showcase-card" style="background: #060e20; position: relative;">
                    @php
                        $modelSlug = \Illuminate\Support\Str::slug($aset->nama_aset);
                    @endphp

                    <model-viewer 
                        id="cyber3DViewer"
                        src="{{ asset('images/assets/' . $modelSlug . '.glb') }}"
                        alt="3D Model {{ $aset->nama_aset }}"
                        camera-controls
                        auto-rotate
                        shadow-intensity="1"
                        exposure="1.2"
                        loading="eager"
                        style="width: 100%; height: 100%; aspect-ratio: 16 / 9; --poster-color: transparent; display: none;">
                    </model-viewer>

                    <img id="fallbackStaticImage" 
                         src="{{ asset('images/assets/' . $modelSlug . '.png') }}" 
                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=600&auto=format';" 
                         class="w-100 object-fit-cover" 
                         style="aspect-ratio: 16 / 9; filter: brightness(0.95);"
                         alt="{{ $aset->nama_aset }}">
                    
                    <div class="position-absolute bottom-0 start-0 m-4" style="pointer-events: none; z-index: 10;">
                        <p id="3DStatusBadge" class="font-mono text-uppercase text-white-50 mb-0" style="font-size: 10px; letter-spacing: 1px; opacity: 0.7;">
                            Premium Digital Collection
                        </p>
                        <h4 class="fw-bold text-white text-uppercase m-0" style="font-size: 18px;">{{ str_replace('-', ' ', $aset->nama_aset) }}</h4>
                    </div>
                </div>

                <div class="panel-premium">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ti ti-chart-candle text-primary" style="color: var(--accent-cyan)!important; font-size: 18px;"></i>
                            <h5 class="fw-bold m-0" style="font-size: 15px; color: var(--color-text-primary);">Market Performance</h5>
                        </div>
                        <span class="badge font-mono py-1 px-2.5" style="font-size: 11px; background: rgba(0,212,255,0.12); color: var(--accent-cyan)!important; border: 1px solid rgba(0,212,255,0.2);">LIVE TERMINAL</span>
                    </div>

                    <div class="chart-container-cyber d-flex align-items-end justify-content-between px-4 mb-1">
                        <div class="chart-bar-mock" style="height: 35%;"></div>
                        <div class="chart-bar-mock" style="height: 55%;"></div>
                        <div class="chart-bar-mock" style="height: 25%;"></div>
                        <div class="chart-bar-mock" style="height: 75%;"></div>
                        <div class="chart-bar-mock" style="height: 45%;"></div>
                        <div class="chart-bar-mock" style="height: 85%;"></div>
                        <div class="chart-bar-mock" style="height: 65%;"></div>
                        <div class="chart-bar-mock" style="height: 90%;"></div>
                        <div class="chart-bar-mock" style="height: 50%;"></div>
                        <div class="chart-bar-mock" style="height: 80%;"></div>
                    </div>

                    <div class="d-flex justify-content-between px-2 mb-4 font-mono text-muted" style="font-size: 10px; opacity: 0.8;">
                        <span>00:00</span>
                        <span>04:00</span>
                        <span>08:00</span>
                        <span>12:00</span>
                        <span>16:00</span>
                        <span>20:00</span>
                        <span>24:00</span>
                    </div>

                     @php
                         $c1 = $kriterias->where('kode_kriteria', 'C1')->first();
                         $c2 = $kriterias->where('kode_kriteria', 'C2')->first();

                         // Stored values
                         $storedValC1 = $c1 ? ($aset->penilaians->where('kriteria_id', $c1->id)->first()?->nilai ?? 0) : 0;
                         $storedValC2 = $c2 ? ($aset->penilaians->where('kriteria_id', $c2->id)->first()?->nilai ?? 0) : 0;

                         // Prefer live API data when available (does not mutate DB)
                         $liveHarga = isset($liveData['harga_beli']) && $liveData['harga_beli'] > 0 ? $liveData['harga_beli'] : null;
                         $liveVolume = isset($liveData['volume_24h']) && $liveData['volume_24h'] > 0 ? $liveData['volume_24h'] : null;

                         $valC1 = $liveHarga ?? $storedValC1;
                         $valC2 = $liveVolume ?? $storedValC2;

                         $coin = $aset->jenis_aset === 'opensea' ? 'ETH' : 'USD';
                        
                         $rawData = json_decode($aset->raw_data, true) ?? [];
                         $previousFloor = $rawData['previous_floor_price'] ?? 0;

                         // Prefer live 1d change from API or stored JSON when available
                         $liveChange = isset($liveData['floor_change_1d']) ? $liveData['floor_change_1d'] : ($rawData['floor_change_1d'] ?? null);
                          
                         $hasChangeData = ($liveChange !== null || $previousFloor > 0);

                         if ($liveChange !== null) {
                             $changeSinceLast = $liveChange;
                             $changeColor = $changeSinceLast > 0 ? 'text-success' : ($changeSinceLast < 0 ? 'text-danger' : 'text-muted');
                             $changeSign = $changeSinceLast > 0 ? '+' : '';
                         } else {
                             $changeSinceLast = $previousFloor > 0 && $valC1 > 0 ? (($valC1 - $previousFloor) / $previousFloor) * 100 : 0;
                             $changeColor = $changeSinceLast > 0 ? 'text-success' : ($changeSinceLast < 0 ? 'text-danger' : 'text-muted');
                             $changeSign = $changeSinceLast > 0 ? '+' : '';
                         }
                     @endphp
                     <div class="row text-center font-mono g-2">
                         <div class="col-4 border-end" style="border-color: var(--color-border-tertiary)!important;">
                             <p class="text-muted text-uppercase mb-1" style="font-size: 10px; tracking-wider">Floor Price</p>
                             <span class="fw-bold" style="font-size: 15px; color: var(--color-text-primary);">{{ round($valC1, 2) }} {{ $coin }}</span>
                         </div>
                         <div class="col-4 border-end" style="border-color: var(--color-border-tertiary)!important;">
                             <p class="text-muted text-uppercase mb-1" style="font-size: 10px; tracking-wider">1d Floor</p>
                             <span class="fw-bold {{ $changeColor }}" style="font-size: 15px;">
                                 {{ $hasChangeData ? $changeSign . number_format($changeSinceLast, 1) . '%' : '0.0%' }}
                             </span>
                         </div>
                         <div class="col-4">
                             <p class="text-muted text-uppercase mb-1" style="font-size: 10px; tracking-wider">Vol. (24h)</p>
                             <span class="fw-bold" style="font-size: 15px; color: var(--color-text-primary);">{{ number_format(round($valC2, 2), 0, ',', '.') }} {{ $coin }}</span>
                         </div>
                      </div>
                 </div>

                <div class="row g-3 text-center font-mono">
                    <div class="col-6 col-sm-3">
                        <div class="p-3 rounded-3" style="background: var(--color-background-secondary); border: 1px solid var(--color-border-tertiary);">
                            <i class="ti ti-category text-muted d-block mb-1" style="font-size: 16px;"></i>
                            <span class="text-muted d-block" style="font-size: 9px; text-transform: uppercase;">Platform</span>
                            <span class="fw-bold text-truncate d-block mt-0.5" style="font-size: 12px; color: var(--color-text-primary);">{{ strtoupper($aset->jenis_aset) }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="p-3 rounded-3" style="background: var(--color-background-secondary); border: 1px solid var(--color-border-tertiary);">
                            <i class="ti {{ $ikonGame }} text-muted d-block mb-1" style="font-size: 16px; color: var(--accent-cyan) !important;"></i>
                            <span class="text-muted d-block" style="font-size: 9px; text-transform: uppercase;">Game Origin</span>
                            <span class="fw-bold d-block mt-0.5 text-truncate" style="font-size: 12px; color: var(--color-text-primary);" title="{{ $namaGame }}">{{ $namaGame }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="p-3 rounded-3" style="background: var(--color-background-secondary); border: 1px solid var(--color-border-tertiary);">
                            <i class="ti ti-crown text-muted d-block mb-1" style="font-size: 16px;"></i>
                            <span class="text-muted d-block" style="font-size: 9px; text-transform: uppercase;">Rarity Scale</span>
                            <span class="fw-bold d-block mt-0.5" style="font-size: 12px; color: var(--color-text-primary);">Top Tier</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="p-3 rounded-3" style="background: var(--color-background-secondary); border: 1px solid var(--color-border-tertiary);">
                            <i class="ti ti-lock-open text-success d-block mb-1" style="font-size: 16px;"></i>
                            <span class="text-muted d-block" style="font-size: 9px; text-transform: uppercase;">Status</span>
                            <span class="fw-bold text-success d-block mt-0.5" style="font-size: 12px;">Tradable</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-5">
            <div class="d-flex flex-column gap-4 h-100">
                
                <div class="panel-premium d-flex flex-column gap-4">
                    <div>
                        <h4 class="fw-bold m-0" style="font-size: 18px; color: var(--color-text-primary);">Analisis Keputusan</h4>
                        <p class="text-muted mb-0" style="font-size: 12px;">Kalkulasi kecocokan parameter bobot nilai alternatif.</p>
                    </div>

                    <div class="d-flex flex-column align-items-center justify-content-center py-2">
                        <div class="position-relative d-flex align-items-center justify-content-center" style="width: 140px; height: 140px;">
                            <svg width="140" height="140" viewBox="0 0 140 140" style="transform: rotate(-90deg);">
                                <circle cx="70" cy="70" r="58" stroke="var(--color-track-bg)" stroke-width="8" fill="transparent" />
                                <circle cx="70" cy="70" r="58" stroke="var(--accent-cyan)" stroke-width="8" fill="transparent" 
                                        stroke-dasharray="364.4" stroke-dashoffset="72.8" stroke-linecap="round" />
                            </svg>
                            <div class="position-absolute text-center">
                                <span class="fw-bold font-mono d-block" style="font-size: 26px; line-height: 1; color: var(--color-text-primary);">0.814</span>
                                <span class="text-uppercase text-muted font-mono" style="font-size: 9px; letter-spacing: 0.5px; font-weight: 600;">Topsis Score</span>
                            </div>
                        </div>
                        <p class="text-center text-muted font-mono mt-3 mb-0 px-2" style="font-size: 11px; line-height: 1.5;">
                            Aset ini memiliki efisiensi sebesar <span class="text-success fw-bold">81.4%</span> dibandingkan Solusi Ideal Positif (PIS).
                        </p>
                    </div>

                    <div class="d-flex flex-column gap-4.5 py-1">
                        <span class="text-muted text-uppercase fw-bold font-mono tracking-wider mb-1" style="font-size: 10px;">Matriks Penilaian Kriteria</span>

                        @foreach($kriterias as $k)
                            @php
                                $p = $aset->penilaians->where('kriteria_id', $k->id)->first();
                                $nilai = $p ? $p->nilai : 0;
                                $percent = $nilai > 5 ? min(($nilai / ($k->kode_kriteria === 'C1' && $nilai > 2000 ? 1500 : 150)) * 100, 100) : ($nilai / 5) * 100;
                            @endphp
                            <div class="mb-1">
                                <div class="d-flex justify-content-between align-items-center mb-2 font-mono" style="font-size: 11px;">
                                    <span class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.3px;">
                                        {{ $k->kode_kriteria }}. {{ str_replace(' Saat Ini', '', $k->nama_kriteria) }} 
                                        <span class="text-uppercase text-muted" style="font-size: 9px; opacity: 0.8;">({{ $k->jenis }})</span>
                                    </span>
                                    <span class="fw-bold" style="color: var(--color-text-primary);">
                                        {{ is_numeric($nilai) ? round($nilai, 1) : $nilai }}
                                    </span>
                                </div>
                                <div class="progress-track-premium">
                                    <div class="progress-fill-premium" style="width: {{ $percent ?: 15 }}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="p-3 rounded-3 font-mono mt-2" style="background: rgba(255,255,255,0.01); border: 1px solid var(--color-border-tertiary);">
                        <span class="text-muted d-block mb-2 text-uppercase fw-semibold" style="font-size: 10px;">Distance To Ideal</span>
                        <div class="d-flex justify-content-between text-center" style="font-size: 11px;">
                            <div class="w-50 text-start">
                                <span class="text-success d-block" style="font-size: 9px; font-weight: bold;">POSITIVE IDEAL (PIS)</span>
                                <span class="fw-bold" style="font-size: 13px; color: var(--color-text-primary);">0.024</span>
                            </div>
                            <div class="w-50 text-end border-start" style="border-color: var(--color-border-tertiary)!important;">
                                <span class="text-danger d-block" style="font-size: 9px; font-weight: bold;">NEGATIVE IDEAL (NIS)</span>
                                <span class="fw-bold" style="font-size: 13px; color: var(--color-text-primary);">0.892</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('aset-digital.edit', $aset->id) }}" class="btn-action-route btn-blue-gradient">
                        <i class="ti ti-edit"></i> Edit Matriks Penilaian Aset
                    </a>
                    
                    <form action="{{ route('aset-digital.destroy', $aset->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alternatif aset ini?')" class="w-100 p-0 m-0">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="btn-action-route btn-outline-dark" style="color: #ef4444 !important; border-color: rgba(239, 68, 68, 0.2) !important; background: rgba(239, 68, 68, 0.02);">
                            <i class="ti ti-trash"></i> Hapus Alternatif dari Portofolio
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const themeToggleBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const htmlElement = document.documentElement;
        const bodyElement = document.body;

        const currentTheme = localStorage.getItem('theme') || 'dark';
        applyTheme(currentTheme);

        themeToggleBtn.addEventListener('click', () => {
            const activeTheme = htmlElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            localStorage.setItem('theme', activeTheme);
            applyTheme(activeTheme);
        });

        function applyTheme(theme) {
            htmlElement.setAttribute('data-theme', theme);
            if (theme === 'light') {
                htmlElement.classList.remove('dark');
                htmlElement.classList.add('light');
                bodyElement.classList.remove('dark');
                bodyElement.classList.add('light');
                if(themeIcon) themeIcon.className = 'ti ti-moon text-primary';
            } else {
                htmlElement.classList.remove('light');
                htmlElement.classList.add('dark');
                bodyElement.classList.remove('light');
                bodyElement.classList.add('dark');
                if(themeIcon) themeIcon.className = 'ti ti-sun text-warning';
            }
        }

        // ====================================================
        // 🚀 SMART CHECKER: MENEMBAK LANGSUNG KE IMAGES/ASSETS
        // ====================================================
        const viewer3D = document.getElementById('cyber3DViewer');
        const staticImg = document.getElementById('fallbackStaticImage');
        const statusBadge = document.getElementById('3DStatusBadge');

        if (viewer3D) {
            // Lakukan pengecekan asinkron apakah file .glb berhasil dipanggil atau 404
            fetch(viewer3D.getAttribute('src'), { method: 'HEAD' })
                .then(response => {
                    if (response.ok) {
                        // Jika file GLB terdeteksi ada di folder publik lokal, aktifkan mode 3D
                        staticImg.style.display = 'none';
                        viewer3D.style.display = 'block';
                        statusBadge.innerHTML = '🎮 3D INTERACTIVE TERMINAL';
                    } else {
                        console.log("File .glb mengembalikan status non-200. Fallback gambar aktif.");
                    }
                })
                .catch(err => console.log('Pengecekan model 3D gagal, fallback gambar digunakan:', err));
        }
    });
</script>
@endsection