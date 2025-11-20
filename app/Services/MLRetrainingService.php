<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MLRetrainingService
{
    private string $mlServiceUrl;
    private string $apiKey;
    private int $retrainThreshold = 500;
    private int $timeout = 10;

    public function __construct()
    {
        $this->mlServiceUrl = env('ML_SERVICE_URL');
        $this->apiKey = env('ML_API_KEY');
        
    }

    /**
     * Check counter and trigger retrain if threshold reached
     * 
     * @return void
     */
    public function checkAndTriggerRetrain(): void
    {
        // Get counter from cache
        $counter = Cache::get('ml_retrain_counter', 0);
        $counter++;
        
        Log::info("ML Retrain Counter: {$counter}/{$this->retrainThreshold}");
        
        // Update counter
        Cache::put('ml_retrain_counter', $counter, now()->addDays(30));
        
        // Check if threshold reached
        if ($counter >= $this->retrainThreshold) {
            Log::info("🎯 Threshold reached! Triggering ML retrain...");
            
            // Export data & trigger retrain
            $this->exportAndRetrain();
            
            // Reset counter
            Cache::put('ml_retrain_counter', 0, now()->addDays(30));
            Cache::put('ml_last_retrain', now(), now()->addDays(30));
            
            Log::info("✅ ML retrain triggered, counter reset");
        }
    }

    /**
     * Export tickets to CSV and trigger ML retrain
     * 
     * @return void
     */
    private function exportAndRetrain(): void
    {
        try {
            // Get all tickets with prioritas (confirmed by admin or ML)
            $tickets = Ticket::with(['kategoriGangguan'])
                ->whereNotNull('prioritas')
                ->whereNotNull('kategori_gangguan_nama')
                ->whereNotNull('kategori_pelanggan_nama')
                ->get();

            if ($tickets->isEmpty()) {
                Log::warning("No tickets available for retraining");
                return;
            }

            Log::info("Exporting {$tickets->count()} tickets for retraining");

            // Build CSV content
            $csvContent = "judul,deskripsi,kategori_gangguan,kategori_pelanggan,waktu_lapor,prioritas\n";
            
            foreach ($tickets as $ticket) {
                $csvContent .= $this->escapeCSV($ticket->judul) . ","
                    . $this->escapeCSV($ticket->deskripsi) . ","
                    . $this->escapeCSV($ticket->kategori_gangguan_nama) . ","
                    . $this->escapeCSV($ticket->kategori_pelanggan_nama) . ","
                    . $ticket->created_at->format('Y-m-d H:i:s') . ","
                    . $ticket->prioritas . "\n";
            }

            // Save CSV to storage
            $csvPath = storage_path('app/ml_training_data.csv');
            file_put_contents($csvPath, $csvContent);

            Log::info("CSV exported to: {$csvPath}");

            // Copy to ML service dataset folder
            $this->copyToMLDataset($csvPath);

            // Trigger retrain via API
            $this->triggerRetrain();

        } catch (\Exception $e) {
            Log::error("Export & retrain failed: " . $e->getMessage(), [
                'exception' => $e
            ]);
        }
    }

    /**
     * Escape CSV values
     * 
     * @param string|null $value
     * @return string
     */
    private function escapeCSV(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        // Remove newlines
        $value = str_replace(["\r", "\n"], ' ', $value);
        
        // Escape quotes
        $value = str_replace('"', '""', $value);
        
        // Wrap in quotes if contains comma or quote
        if (strpos($value, ',') !== false || strpos($value, '"') !== false) {
            $value = '"' . $value . '"';
        }
        
        return $value;
    }

    /**
     * Copy CSV to ML dataset folder
     * 
     * @param string $csvPath
     * @return void
     */
    private function copyToMLDataset(string $csvPath): void
    {
        // Path ke ML dataset folder
        // Sesuaikan dengan struktur folder kamu
        $mlDatasetPath = base_path('../ml-apps/dataset/training_data.csv');
        
        try {
            if (file_exists(dirname($mlDatasetPath))) {
                if (copy($csvPath, $mlDatasetPath)) {
                    Log::info("✅ CSV copied to ML dataset: {$mlDatasetPath}");
                } else {
                    Log::error("❌ Failed to copy CSV to ML dataset");
                }
            } else {
                Log::warning("ML dataset directory not found: " . dirname($mlDatasetPath));
            }
        } catch (\Exception $e) {
            Log::error("Failed to copy CSV to ML dataset: " . $e->getMessage());
        }
    }

    /**
     * Trigger retrain via ML API
     * 
     * @return void
     */
    private function triggerRetrain(): void
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders(['X-Api-Key' => $this->apiKey])
                ->post("{$this->mlServiceUrl}/retrain");

            if ($response->successful()) {
                Log::info("✅ ML retrain API called successfully", [
                    'response' => $response->json()
                ]);
            } else {
                Log::error("ML retrain API failed", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }

        } catch (\Exception $e) {
            Log::error("Failed to call ML retrain API: " . $e->getMessage());
        }
    }

    /**
     * Manual retrain (triggered by admin)
     * 
     * @return array
     */
    public function manualRetrain(): array
    {
        Log::info("Manual retrain triggered by admin", [
            'admin_id' => Auth::id()
        ]);
        
        try {
            $this->exportAndRetrain();
            
            return [
                'success' => true,
                'message' => 'Model retraining started',
                'timestamp' => now()->toISOString(),
                'tickets_exported' => Ticket::whereNotNull('prioritas')->count()
            ];
        } catch (\Exception $e) {
            Log::error("Manual retrain failed: " . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to start retraining',
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ];
        }
    }

    /**
     * Get retrain status
     * 
     * @return array
     */
    public function getStatus(): array
    {
        try {
            // Get ML service status
            $response = Http::timeout(5)
                ->withHeaders(['X-Api-Key' => $this->apiKey])
                ->get("{$this->mlServiceUrl}/retrain/status");

            $modelStatus = $response->successful() ? $response->json() : null;

        } catch (\Exception $e) {
            $modelStatus = [
                'error' => 'Cannot connect to ML service',
                'message' => $e->getMessage()
            ];
        }

        return [
            'counter' => Cache::get('ml_retrain_counter', 0),
            'threshold' => $this->retrainThreshold,
            'next_retrain_at' => $this->retrainThreshold - Cache::get('ml_retrain_counter', 0),
            'last_retrain' => Cache::get('ml_last_retrain'),
            'model_status' => $modelStatus,
            'total_tickets' => Ticket::whereNotNull('prioritas')->count(),
            'percentage' => round((Cache::get('ml_retrain_counter', 0) / $this->retrainThreshold) * 100, 2),
        ];
    }

    /**
     * Reset counter manually
     * 
     * @return void
     */
    public function resetCounter(): void
    {
        Cache::put('ml_retrain_counter', 0, now()->addDays(30));
        Log::info("ML retrain counter manually reset", [
            'admin_id' => Auth::id()
        ]);
    }

    /**
     * Set custom threshold
     * 
     * @param int $threshold
     * @return void
     */
    public function setThreshold(int $threshold): void
    {
        $this->retrainThreshold = $threshold;
        Log::info("ML retrain threshold changed to: {$threshold}", [
            'admin_id' => Auth::id()
        ]);
    }

    /**
     * Get current threshold
     * 
     * @return int
     */
    public function getThreshold(): int
    {
        return $this->retrainThreshold;
    }
}