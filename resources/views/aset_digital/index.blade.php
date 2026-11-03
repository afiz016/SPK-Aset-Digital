@extends('layouts.app')

@section('title', 'Pasar Aset - Alternatif')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap');
    
    /* ====================================================
       VARIABEL WARNA PREMIUM (SINKRON DENGAN DASHBOARD)
       ==================================================== */
    :root {
        --color-background-primary: #0a0f1d;
        --color-background-secondary: #0f172a;
        --color-text-primary: #ffffff;
        --color-text-secondary: #94a3b8;
        --color-border-secondary: rgba(0, 212, 255, 0.16);
        --color-border-tertiary: rgba(0, 212, 255, 0.06);
        --accent-cyan: #00d4ff;
    }

    [data-theme="light"], .light {
        --color-background-primary: #f1f5f9;
        --color-background-secondary: #ffffff;
        --color-text-primary: #0f172a;
        --color-text-secondary: #64748b;
        --color-border-secondary: rgba(15, 23, 42, 0.14);
        --color-border-tertiary: rgba(15, 23, 42, 0.05);
        --accent-cyan: #0284c7;
    }

    /* ANTI-BLEED FIX: Memaksa Layout Mengikuti Tema */
    body, html, .bg-background, main, div.overflow-y-auto {
        background-color: var(--color-background-primary) !important;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .D-container {
        font-family: 'Space Grotesk', sans-serif;
        background: var(--color-background-primary);
        color: var(--color-text-primary);
        padding: 28px;
        display: flex;
        flex-direction: column;
        gap: 26px;
        min-height: 100vh;
    }

    /* KARTU BENTO GLASSMORPHISM DESIGN DIKUSTOMISASI */
    .glass-card-premium {
        background: var(--color-background-secondary);
        border: 1px solid var(--color-border-tertiary);
        border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }
    .glass-card-premium:hover {
        border-color: rgba(0, 212, 255, 0.3);
        box-shadow: 0 0 30px rgba(0, 212, 255, 0.15);
        transform: translateY(-5px);
    }

    /* AREA WADAH FOTO */
    .img-container-premium {
        position: relative;
        width: 100%;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        background: #060e20;
    }
    .img-container-premium img {
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-card-premium:hover .img-container-premium img {
        transform: scale(1.08);
    }

    /* TOPBAR HEADER ACTIONS */
    .icon-btn { 
        width: 36px; 
        height: 34px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        border: 0.5px solid var(--color-border-secondary); 
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

    /* PREMIUM ACTION BUTTONS */
    .btn-add-premium {
        background: linear-gradient(90deg, var(--accent-cyan) 0%, #4dabf7 100%);
        color: #0a0f1d !important;
        border: none !important;
        border-radius: 12px;
        padding: 11px 24px;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 14px rgba(0, 212, 255, 0.25);
    }
    .btn-add-premium:hover {
        background: linear-gradient(90deg, #00bfe6 0%, #339af0 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 212, 255, 0.45);
    }

    .btn-sync-premium {
        background: transparent;
        color: var(--accent-cyan) !important;
        border: 1px solid var(--color-border-secondary) !important;
        border-radius: 12px;
        padding: 11px 22px;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-sync-premium:hover:not(:disabled) {
        background: rgba(0, 212, 255, 0.06) !important;
        border-color: var(--accent-cyan) !important;
        transform: translateY(-2px);
    }

    /* STEPPER PROGRESS SEGMENT */
    .stepper-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        margin-bottom: 20px;
        padding: 0 40px;
    }
    .stepper-line {
        position: absolute;
        top: 50%;
        left: 40px;
        right: 40px;
        height: 2px;
        background: #1e293b;
        transform: translateY(-50%);
        z-index: 1;
    }
    .stepper-line-progress {
        position: absolute;
        top: 50%;
        left: 40px;
        width: 50%;
        height: 2px;
        background: var(--accent-cyan);
        transform: translateY(-50%);
        z-index: 1;
    }
    .step-node {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .step-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #1e293b;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s;
    }
    .step-node.active .step-circle {
        background: var(--accent-cyan);
        color: #0a0f1d;
        box-shadow: 0 0 15px rgba(0, 212, 255, 0.4);
    }
    .step-text {
        font-size: 11px;
        color: var(--color-text-secondary);
        margin-top: 6px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .step-node.active .step-text {
        color: var(--accent-cyan);
    }

    /* MODERN MINI ACTION ACTION PILLS */
    .btn-action-premium {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: 1px solid transparent;
        background: rgba(255,255,255,0.03);
        color: var(--color-text-secondary);
        text-decoration: none;
    }
    .btn-action-view:hover { color: var(--accent-cyan); border-color: var(--accent-cyan); background: rgba(0, 212, 255, 0.05); }
    .btn-action-edit:hover { color: #f59e0b; border-color: #f59e0b; background: rgba(245, 158, 11, 0.05); }
    .btn-action-delete:hover { color: #ef4444; border-color: #ef4444; background: rgba(239, 68, 68, 0.05); }
</style>

<div class="w-full border-b d-flex justify-content-between align-items-center px-4 py-3 mb-0" style="background: var(--color-background-secondary); border-color: var(--color-border-tertiary) !important; min-height: 70px; box-sizing: border-box; border-bottom: 0.5px solid var(--color-border-tertiary) !important;">
    <div style="font-size: 15px; font-weight: 700; color: var(--accent-cyan); letter-spacing: 0.5px; display: inline-flex; align-items: center; margin: 0; padding: 0; line-height: 1; font-family: 'JetBrains Mono', monospace;">
        LANGKAH 2: ALTERNATIF ASET DIGITAL
    </div>
    <div class="d-flex align-items-center gap-3">
        <div class="icon-btn" id="themeToggle" title="Ubah Tema"><i class="ti ti-sun" id="themeIcon" aria-hidden="true"></i></div>
        <div class="icon-btn" onclick="window.location.reload();" title="Refresh Data"><i class="ti ti-refresh" aria-hidden="true"></i></div>
    </div>
</div>

<div class="D-container">
    
    <div class="stepper-wrap">
        <div class="stepper-line"></div>
        <div class="stepper-line-progress"></div>
        
        <div class="step-node active">
            <div class="step-circle">1</div>
            <div class="step-text">Kriteria</div>
        </div>
        <div class="step-node active">
            <div class="step-circle">2</div>
            <div class="step-text">Alternatif</div>
        </div>
        <div class="step-node">
            <div class="step-circle">3</div>
            <div class="step-text">Hasil</div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <p style="color: var(--color-text-secondary); font-size: 14px;" class="mb-0">Daftarkan dan analisis portofolio alternatif investasi aset digital Anda.</p>
        </div>
        <div class="d-flex gap-2">
            <button id="btnSyncApi" class="btn-sync-premium">
                <i class="ti ti-refresh" id="iconSync"></i> Sync Data API
            </button>
            <a href="{{ route('aset-digital.create') }}" class="btn-add-premium">
                <i class="ti ti-plus" style="font-size: 13px;"></i> Tambah Aset
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 text-success rounded-3 p-3 mb-0" style="background: rgba(16, 185, 129, 0.1);">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
        @forelse($asetDigitals as $aset)
            @php
                $platform = strtolower($aset->jenis_aset);
                $badgeStyle = 'background: rgba(0, 212, 255, 0.2); color: #00d4ff; backdrop-filter: blur(8px); border: 1px solid rgba(0, 212, 255, 0.3);';
                $iconPlatform = 'ti-box';
                
                if (stripos($platform, 'opensea') !== false) {
                    $badgeStyle = 'background: rgba(30, 144, 255, 0.25); color: #8ed5ff; backdrop-filter: blur(8px); border: 1px solid rgba(30, 144, 255, 0.3);';
                    $iconPlatform = 'ti-ship';
                } elseif (stripos($platform, 'steam') !== false) {
                    $badgeStyle = 'background: rgba(245, 158, 11, 0.25); color: #f59e0b; backdrop-filter: blur(8px); border: 1px solid rgba(245, 158, 11, 0.3);';
                    $iconPlatform = 'ti-brand-steam';
                }

                $defaultPlaceholder = 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=600&auto=format&fit=crop';
            @endphp
            
            <div class="col">
                <div class="glass-card-premium">
                    
                    <div class="img-container-premium">
                        <img src="{{ asset('images/assets/' . \Illuminate\Support\Str::slug($aset->nama_aset) . '.png') }}" 
                             onerror="this.onerror=null; this.src='{{ $defaultPlaceholder }}';" 
                             class="w-100 h-100 object-fit-cover" 
                             alt="{{ $aset->nama_aset }}">
                             
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge rounded-pill text-uppercase px-2.5 py-1.5 d-flex align-items-center gap-1" style="{{ $badgeStyle }} font-size: 10px; font-weight: 700;">
                                <i class="ti {{ $iconPlatform }}"></i> {{ $aset->jenis_aset }}
                            </span>
                        </div>
                    </div>

                    <div class="p-4 d-flex flex-column justify-between gap-3">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="text-truncate" style="max-width: 70%;">
                                    <h3 class="fw-bold mb-0 text-truncate text-uppercase" style="color: var(--color-text-primary); font-size: 15px;" title="{{ $aset->nama_aset }}">
                                        {{ str_replace('-', ' ', $aset->nama_aset) }}
                                    </h3>
                                    <p style="color: var(--color-text-secondary); font-size: 11px;" class="mb-0 text-uppercase">{{ $aset->jenis_aset }} • Collection</p>
                                </div>
                                <div class="text-end">
                                    @php
                                        $rawData = json_decode($aset->raw_data, true) ?? [];
                                        $previousFloor = $rawData['previous_floor_price'] ?? 0;
                                        $c1Kriteria = $kriterias->where('kode_kriteria', 'C1')->first();
                                        $pC1 = $c1Kriteria ? $aset->penilaians->where('kriteria_id', $c1Kriteria->id)->first() : null;
                                        $storedNilaiC1 = $pC1 ? $pC1->nilai : 0;

                                        // Prefer live value when available
                                        $liveHarga = isset($liveDataMap[$aset->id]['harga_beli']) && $liveDataMap[$aset->id]['harga_beli'] > 0 ? $liveDataMap[$aset->id]['harga_beli'] : null;
                                        $nilaiC1 = $liveHarga ?? $storedNilaiC1;

                                        // Prefer live 1d change from API or stored JSON when available
                                        $liveChange = isset($liveDataMap[$aset->id]['floor_change_1d']) ? $liveDataMap[$aset->id]['floor_change_1d'] : ($rawData['floor_change_1d'] ?? null);
                                        
                                        $hasChangeData = ($liveChange !== null || $previousFloor > 0);

                                        if ($liveChange !== null) {
                                            $changeSinceLast = $liveChange;
                                            $changeColor = $changeSinceLast > 0 ? 'text-success' : ($changeSinceLast < 0 ? 'text-danger' : 'text-muted');
                                            $changeSign = $changeSinceLast > 0 ? '+' : '';
                                        } else {
                                            $changeSinceLast = $previousFloor > 0 && $nilaiC1 > 0 ? (($nilaiC1 - $previousFloor) / $previousFloor) * 100 : 0;
                                            $changeColor = $changeSinceLast > 0 ? 'text-success' : ($changeSinceLast < 0 ? 'text-danger' : 'text-muted');
                                            $changeSign = $changeSinceLast > 0 ? '+' : '';
                                        }
                                    @endphp
                                    
                                    @if($hasChangeData)
                                        <span class="font-mono fw-bold {{ $changeColor }}" style="font-size: 12px;">
                                            {{ $changeSign }}{{ number_format($changeSinceLast, 1) }}%
                                        </span>
                                        <p class="text-uppercase m-0 text-muted" style="font-size: 9px; tracking-wider">1d Floor</p>
                                    @else
                                        <span class="text-muted font-mono fw-bold" style="font-size: 12px;">0.0%</span>
                                        <p class="text-uppercase m-0 text-muted" style="font-size: 9px; tracking-wider">1d Floor</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-end justify-content-between pt-3 border-t" style="border-top: 1px solid var(--color-border-tertiary) !important;">
                            <div>
                                <p style="color: var(--color-text-secondary); font-size: 10px; margin-bottom: 2px;" class="text-uppercase fw-semibold tracking-wider">Harga Saat Ini</p>
                                <p class="fw-bold font-mono mb-0" style="color: var(--accent-cyan); font-size: 17px;">
                                    @php
                                        // Menarik data matriks kriteria C1 (Harga Beli) - prioritaskan live
                                        $c1Kriteria = $kriterias->where('kode_kriteria', 'C1')->first();
                                        $pC1 = $c1Kriteria ? $aset->penilaians->where('kriteria_id', $c1Kriteria->id)->first() : null;
                                        $storedNilaiC1 = $pC1 ? $pC1->nilai : 0;
                                        $liveHarga = isset($liveDataMap[$aset->id]['harga_beli']) && $liveDataMap[$aset->id]['harga_beli'] > 0 ? $liveDataMap[$aset->id]['harga_beli'] : null;
                                        $nilaiC1 = $liveHarga ?? $storedNilaiC1;
                                    @endphp
                                    {{ is_numeric($nilaiC1) ? round($nilaiC1, 2) : $nilaiC1 }} 
                                    <span style="font-size: 11px; font-weight: 500;">{{ $platform === 'opensea' ? 'ETH' : 'USD' }}</span>
                                </p>
                            </div>

                            <div class="d-flex gap-1">
                                <a href="{{ route('aset-digital.show', $aset->id) }}" class="btn-action-premium btn-action-view" title="Lihat Detail">
                                    <i class="ti ti-eye"></i>
                                </a>
                                <a href="{{ route('aset-digital.edit', $aset->id) }}" class="btn-action-premium btn-action-edit" title="Edit Matriks">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <form action="{{ route('aset-digital.destroy', $aset->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alternatif aset ini?')" class="d-inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-premium btn-action-delete" title="Hapus Aset">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12 w-full text-center py-5" style="color: var(--color-text-secondary);">
                <div class="glass-card-premium p-5 d-flex flex-column align-items-center justify-content-center gap-2">
                    <i class="ti ti-box-off fs-1"></i>
                    <h5 class="fw-bold mb-1 mt-2" style="color: var(--color-text-primary);">Belum Ada Alternatif Terdaftar</h5>
                    <p class="mb-0 text-sm">Silakan klik tombol "Tambah Aset" atau picu sinkronisasi API pasar bursa.</p>
                </div>
            </div>
        @endforelse
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

        const btnSync = document.getElementById('btnSyncApi');
        if(btnSync) {
            btnSync.addEventListener('click', function() {
                btnSync.disabled = true;
                btnSync.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Menghubungkan API...`;

                fetch("{{ route('aset-digital.sync') }}")
                    .then(response => response.json())
                    .then(data => {
                        let msg = data.message + '\n\n';
                        if (data.results && data.results.length > 0) {
                            data.results.forEach(r => {
                                const status = r.success ? '✓' : '✗';
                                const src = r.api_live ? '🟢 API Live' : '🟡 Fallback';
                                const detail = r.success
                                    ? `Harga: ${r.harga_beli} | Vol: ${r.volume_24h} | ${src}`
                                    : `Error: ${r.message}`;
                                msg += `${status} ${r.nama_aset} (${r.platform})\n   ${detail}\n`;
                            });
                        }
                        alert(msg);
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('❌ Terjadi kesalahan jaringan saat fetch data.');
                        window.location.reload();
                    });
            });
        }
    });
</script>
@endsection