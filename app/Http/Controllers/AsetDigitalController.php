<?php

namespace App\Http\Controllers;

use App\Models\AsetDigital;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use App\Services\MarketplaceApiService;
class AsetDigitalController extends Controller
{
    public function index()
    {
        $asetDigitals = AsetDigital::with('penilaians.kriteria')->get();
        $kriterias = Kriteria::all();

        // Fetch live data per asset (OpenSea & Steam). Note: this performs multiple HTTP calls.
        $liveDataMap = [];
        foreach ($asetDigitals as $aset) {
            $platform = strtolower($aset->jenis_aset);
            if ($platform === 'opensea') {
                try {
                    $f = MarketplaceApiService::fetchOpenSeaData($aset->nama_aset);
                    if ($f && !isset($f['error'])) {
                        $liveDataMap[$aset->id] = $f;
                    }
                } catch (\Exception $e) {
                    // ignore failures per-item
                }
            } elseif ($platform === 'steam' || $platform === 'steam market') {
                try {
                    $f = MarketplaceApiService::fetchSteamMarketData('730', $aset->nama_aset);
                    if ($f && !isset($f['error'])) {
                        $liveDataMap[$aset->id] = $f;
                    }
                } catch (\Exception $e) {
                    // ignore failures per-item
                }
            }
        }

        return view('aset_digital.index', compact('asetDigitals', 'kriterias', 'liveDataMap'));
    }

    public function create()
    {
        $kriterias = Kriteria::all();
        return view('aset_digital.create', compact('kriterias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_aset' => 'required|string|max:255',
            'jenis_aset' => 'required|string|max:255',
            'nilai' => 'required|array',
        ]);

        $aset = AsetDigital::create([
            'nama_aset' => $request->nama_aset,
            'jenis_aset' => $request->jenis_aset,
        ]);

        foreach ($request->nilai as $kriteriaId => $val) {
            Penilaian::create([
                'aset_digital_id' => $aset->id,
                'kriteria_id' => $kriteriaId,
                'nilai' => $val,
            ]);
        }

        return redirect()->route('aset-digital.index')->with('success', 'Aset digital berhasil ditambahkan.');
    }

    public function show($id)
    {
        $aset = AsetDigital::with('penilaians.kriteria')->findOrFail($id);

        $liveData = null;
        try {
            $platform = strtolower($aset->jenis_aset);
            if ($platform === 'opensea') {
                $f = MarketplaceApiService::fetchOpenSeaData($aset->nama_aset);
                if ($f && !isset($f['error'])) {
                    $liveData = $f;
                }
            } elseif ($platform === 'steam' || $platform === 'steam market') {
                $f = MarketplaceApiService::fetchSteamMarketData('730', $aset->nama_aset);
                if ($f && !isset($f['error'])) {
                    $liveData = $f;
                }
            }
        } catch (\Exception $e) {
            // ignore live fetch errors for show page; fallback to stored values
            $liveData = null;
        }

        return view('aset_digital.show', compact('aset', 'liveData'));
    }

    public function edit($id)
    {
        $aset = AsetDigital::with('penilaians')->findOrFail($id);
        $kriterias = Kriteria::all();
        return view('aset_digital.edit', compact('aset', 'kriterias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_aset' => 'required|string|max:255',
            'jenis_aset' => 'required|string|max:255',
            'nilai' => 'required|array',
        ]);

        $aset = AsetDigital::findOrFail($id);
        $aset->update([
            'nama_aset' => $request->nama_aset,
            'jenis_aset' => $request->jenis_aset,
        ]);

        foreach ($request->nilai as $kriteriaId => $val) {
            Penilaian::updateOrCreate(
                [
                    'aset_digital_id' => $aset->id,
                    'kriteria_id' => $kriteriaId,
                ],
                ['nilai' => $val]
            );
        }

        return redirect()->route('aset-digital.index')->with('success', 'Aset digital berhasil diperbarui.');
    }

    public function destroy($id)
    {
        AsetDigital::findOrFail($id)->delete();
        return redirect()->route('aset-digital.index')->with('success', 'Aset digital berhasil dihapus.');
    }

    public function syncData()
    {
        $asets = AsetDigital::all();
        $results = [];
        $successCount = 0;

        foreach ($asets as $aset) {
            $data = null;
            $platform = strtolower($aset->jenis_aset);

            if ($platform === 'opensea') {
                $data = MarketplaceApiService::fetchOpenSeaData($aset->nama_aset);
            } elseif ($platform === 'steam market' || $platform === 'steam') {
                $data = MarketplaceApiService::fetchSteamMarketData('730', $aset->nama_aset);
            }

            $itemResult = [
                'nama_aset' => $aset->nama_aset,
                'platform' => $platform,
                'success' => false,
                'harga_beli' => 0,
                'volume_24h' => 0,
                'api_live' => false,
                'message' => '',
            ];

            if ($data && !isset($data['error'])) {
                $saveResult = MarketplaceApiService::saveAsetFromAPI($data);
                $itemResult['harga_beli'] = $data['harga_beli'] ?? 0;
                $itemResult['volume_24h'] = $data['volume_24h'] ?? 0;
                $itemResult['success'] = $saveResult['success'] ?? false;
                $itemResult['api_live'] = $saveResult['api_live'] ?? false;
                $itemResult['message'] = $saveResult['message'] ?? '';
                if ($saveResult['success']) {
                    $successCount++;
                }
            } elseif ($data && isset($data['error'])) {
                $itemResult['message'] = $data['error'];
            } else {
                $itemResult['message'] = 'Platform tidak didukung';
            }

            $results[] = $itemResult;
        }

        return response()->json([
            'success' => $successCount > 0,
            'message' => "{$successCount}/" . count($asets) . " aset berhasil disinkronkan.",
            'results' => $results
        ]);
    }
}