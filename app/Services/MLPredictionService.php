<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\KategoriGangguan;
use App\Models\User;

class MLPredictionService
{
    private string $mlServiceUrl;
    private string $apiKey;
    private float $confidenceThreshold = 0.70;
    private int $timeout = 10;

    public function __construct()
    {
        $this->mlServiceUrl = env('ML_SERVICE_URL', 'http://localhost:5001');
        $this->apiKey = env('ML_API_KEY', 'rahasia-super-aman-123');
    }

    /**
     * Predict ticket priority using ML model
     * 
     * @param array $ticketData ['kategori_gangguan_id', 'judul', 'deskripsi']
     * @param User $user
     * @return array
     */
    public function predictPriority(array $ticketData, User $user): array
    {
        try {
            // Validate input
            if (!isset($ticketData['kategori_gangguan_id']) || 
                !isset($ticketData['judul']) || 
                !isset($ticketData['deskripsi'])) {
                throw new \Exception('Data tiket tidak lengkap');
            }

            // Get kategori gangguan
            $kategoriGangguan = KategoriGangguan::find($ticketData['kategori_gangguan_id']);
            
            if (!$kategoriGangguan) {
                throw new \Exception('Kategori gangguan tidak ditemukan');
            }

            // Check if user has profile
            if (!$user->profile) {
                throw new \Exception('User belum memiliki profile. Silakan lengkapi profile terlebih dahulu.');
            }

            // Check if profile has kategori pelanggan
            if (!$user->profile->kategori_pelanggan_id) {
                throw new \Exception('Kategori pelanggan belum diatur. Silakan lengkapi profile terlebih dahulu.');
            }

            $kategoriPelanggan = $user->profile->kategoriPelanggan;
            
            if (!$kategoriPelanggan) {
                throw new \Exception('Kategori pelanggan tidak valid');
            }
            
            // Prepare payload for ML API
            $payload = [
                'judul' => $ticketData['judul'],
                'deskripsi' => $ticketData['deskripsi'],
                'kategori_gangguan' => $kategoriGangguan->nama_gangguan,
                'kategori_pelanggan' => $kategoriPelanggan->nama_kategori,
            ];
            
            Log::info('ML Prediction Request', [
                'user_id' => $user->id,
                'payload' => $payload
            ]);
            
            // Call ML API
            $response = Http::timeout($this->timeout)
                ->withHeaders(['X-Api-Key' => $this->apiKey])
                ->post("{$this->mlServiceUrl}/predict", $payload);

            if ($response->successful()) {
                $result = $response->json();
                
                Log::info('ML Prediction Success', [
                    'user_id' => $user->id,
                    'result' => $result
                ]);

                return [
                    'success' => true,
                    'prioritas' => $result['prioritas'],
                    'confidence' => $result['confidence'],
                    'kategori_gangguan_nama' => $kategoriGangguan->nama_gangguan,
                    'kategori_pelanggan_nama' => $kategoriPelanggan->nama_kategori,
                    'is_confident' => $result['confidence'] >= $this->confidenceThreshold,
                    'needs_review' => $result['confidence'] < $this->confidenceThreshold,
                    'ml_predicted_at' => now(),
                    'ml_features' => [
                        'input' => $payload,
                        'output' => $result,
                        'model_version' => '1.0.0',
                        'service_url' => $this->mlServiceUrl,
                    ],
                ];
            }

            throw new \Exception('ML service returned error: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('ML Prediction Failed', [
                'error' => $e->getMessage(),
                'ticket_data' => $ticketData,
                'user_id' => $user->id,
                'has_profile' => $user->profile !== null,
                'has_kategori_pelanggan' => $user->profile?->kategori_pelanggan_id !== null,
            ]);

            // Fallback to rule-based prediction
            return $this->fallbackPrediction($ticketData, $user);
        }
    }

    /**
     * Fallback rule-based prediction when ML service unavailable
     * 
     * @param array $ticketData
     * @param User $user
     * @return array
     */
    private function fallbackPrediction(array $ticketData, User $user): array
    {
        Log::warning('Using fallback prediction (rule-based)');

        $kategoriGangguan = KategoriGangguan::find($ticketData['kategori_gangguan_id']);
        $kategoriPelanggan = $user->profile?->kategoriPelanggan;
        
        $prioritas = 'Rendah'; // Default
        
        // Simple rule-based logic
        $urgentKeywords = [
            'mati', 'down', 'tidak bisa', 'error', 'urgent', 
            'darurat', 'rusak', 'crash', 'total', 'putus'
        ];
        
        $descLower = strtolower($ticketData['deskripsi'] . ' ' . $ticketData['judul']);
        
        $isUrgent = false;
        foreach ($urgentKeywords as $keyword) {
            if (str_contains($descLower, $keyword)) {
                $isUrgent = true;
                break;
            }
        }
        
        // Rule 1: Urgent keywords → Tinggi
        if ($isUrgent) {
            $prioritas = 'Tinggi';
        } 
        // Rule 2: Perusahaan → Sedang (minimal)
        elseif ($kategoriPelanggan && $kategoriPelanggan->nama_kategori === 'Perusahaan') {
            $prioritas = 'Sedang';
        }
        // Rule 3: Kategori "Gangguan Jaringan" atau "Gangguan Sistem" → Sedang
        elseif ($kategoriGangguan && in_array($kategoriGangguan->nama_gangguan, ['Gangguan Jaringan', 'Gangguan Sistem'])) {
            $prioritas = 'Sedang';
        }
        
        return [
            'success' => false,
            'prioritas' => $prioritas,
            'confidence' => 0.50, // Low confidence for fallback
            'kategori_gangguan_nama' => $kategoriGangguan?->nama_gangguan ?? 'Unknown',
            'kategori_pelanggan_nama' => $kategoriPelanggan?->nama_kategori ?? 'Unknown',
            'is_confident' => false,
            'needs_review' => true, // Always need review for fallback
            'ml_predicted_at' => now(),
            'ml_features' => [
                'fallback' => true,
                'reason' => 'ML service unavailable',
                'rules_applied' => [
                    'urgent_keywords' => $isUrgent,
                    'is_perusahaan' => $kategoriPelanggan?->nama_kategori === 'Perusahaan',
                ],
            ],
        ];
    }

    /**
     * Check ML service health
     * 
     * @return array
     */
    public function checkHealth(): array
    {
        try {
            $response = Http::timeout(5)->get("{$this->mlServiceUrl}/health");
            
            if ($response->successful()) {
                return array_merge(['connected' => true], $response->json());
            }

            return [
                'connected' => false,
                'error' => 'Service returned error: ' . $response->status()
            ];

        } catch (\Exception $e) {
            return [
                'connected' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get ML service status
     * 
     * @return array
     */
    public function getMLStatus(): array
    {
        try {
            $response = Http::timeout(5)
                ->withHeaders(['X-Api-Key' => $this->apiKey])
                ->get("{$this->mlServiceUrl}/retrain/status");

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'error' => 'Failed to get ML status',
                'status_code' => $response->status()
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }
}