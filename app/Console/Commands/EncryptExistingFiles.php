<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Services\EncryptionService;
use App\Models\PlantRequest;
use App\Models\SiteVisit;

class EncryptExistingFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:encrypt-existing 
                            {--type=all : Type of files to encrypt (all, site-visits, pdfs)}
                            {--dry-run : Preview what would be encrypted without actually encrypting}
                            {--force : Force encryption without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encrypt existing unencrypted files (site visits, RFQ PDFs, inquiry PDFs)';

    protected $encryptionService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EncryptionService $encryptionService)
    {
        parent::__construct();
        $this->encryptionService = $encryptionService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $type = $this->option('type');
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        $this->info('🔍 Scanning for unencrypted files...');
        $this->newLine();

        $filesToEncrypt = $this->collectFiles($type);

        if (empty($filesToEncrypt)) {
            $this->info('✅ No unencrypted files found!');
            return 0;
        }

        $this->displaySummary($filesToEncrypt);

        if ($dryRun) {
            $this->info('');
            $this->warn('🔍 DRY RUN MODE - No files were encrypted');
            $this->info('Remove --dry-run flag to actually encrypt these files');
            return 0;
        }

        // Confirm before proceeding
        if (!$force) {
            if (!$this->confirm('Do you want to encrypt these ' . count($filesToEncrypt) . ' files?')) {
                $this->warn('⚠️  Operation cancelled');
                return 0;
            }
        }

        // Encrypt files
        $this->newLine();
        $this->info('🔐 Starting encryption...');
        $this->newLine();

        $progressBar = $this->output->createProgressBar(count($filesToEncrypt));
        $progressBar->start();

        $encrypted = 0;
        $failed = 0;
        $errors = [];

        foreach ($filesToEncrypt as $fileInfo) {
            try {
                $this->encryptFile($fileInfo);
                $encrypted++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = [
                    'file' => $fileInfo['path'],
                    'error' => $e->getMessage()
                ];
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->newLine();

        // Display results
        $this->displayResults($encrypted, $failed, $errors);

        return 0;
    }

    /**
     * Collect files that need encryption
     */
    private function collectFiles(string $type): array
    {
        $files = [];

        if ($type === 'all' || $type === 'site-visits') {
            $files = array_merge($files, $this->collectSiteVisitFiles());
        }

        if ($type === 'all' || $type === 'pdfs') {
            $files = array_merge($files, $this->collectPdfFiles());
        }

        return $files;
    }

    /**
     * Collect site visit files
     */
    private function collectSiteVisitFiles(): array
    {
        $files = [];

        $siteVisits = SiteVisit::all();

        foreach ($siteVisits as $siteVisit) {
            // Check client_data_checklist
            $clientData = $siteVisit->client_data_checklist ?? [];
            if (is_array($clientData)) {
                foreach ($clientData as $itemKey => $items) {
                    if (is_array($items)) {
                        foreach ($items as $index => $item) {
                            if (isset($item['path']) && Storage::exists($item['path'])) {
                                // Check if not already encrypted
                                if (!$this->encryptionService->isEncrypted($item['path'])) {
                                    $files[] = [
                                        'path' => $item['path'],
                                        'original_filename' => $item['original_name'] ?? basename($item['path']),
                                        'type' => 'site_visit_client_data',
                                        'site_visit_id' => $siteVisit->id,
                                        'uploaded_by' => $item['uploaded_by'] ?? $siteVisit->user_id ?? 1,
                                        'item_key' => $itemKey,
                                        'item_index' => $index,
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            // Check proposal_checklist
            $proposalData = $siteVisit->proposal_checklist ?? [];
            if (is_array($proposalData)) {
                foreach ($proposalData as $itemKey => $items) {
                    if (is_array($items)) {
                        foreach ($items as $index => $item) {
                            if (isset($item['path']) && Storage::exists($item['path'])) {
                                if (!$this->encryptionService->isEncrypted($item['path'])) {
                                    $files[] = [
                                        'path' => $item['path'],
                                        'original_filename' => $item['original_name'] ?? basename($item['path']),
                                        'type' => 'site_visit_proposal',
                                        'site_visit_id' => $siteVisit->id,
                                        'uploaded_by' => $item['uploaded_by'] ?? 1,
                                        'item_key' => $itemKey,
                                        'item_index' => $index,
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            // Check media_files
            $mediaFiles = $siteVisit->media_files ?? [];
            if (is_array($mediaFiles)) {
                foreach ($mediaFiles as $index => $item) {
                    if (isset($item['path']) && Storage::exists($item['path'])) {
                        if (!$this->encryptionService->isEncrypted($item['path'])) {
                            $files[] = [
                                'path' => $item['path'],
                                'original_filename' => $item['original_name'] ?? basename($item['path']),
                                'type' => 'site_visit_media',
                                'site_visit_id' => $siteVisit->id,
                                'uploaded_by' => $siteVisit->user_id ?? 1,
                                'media_index' => $index,
                            ];
                        }
                    }
                }
            }
        }

        return $files;
    }

    /**
     * Collect PDF files from plant requests
     */
    private function collectPdfFiles(): array
    {
        $files = [];

        $plantRequests = PlantRequest::whereNotNull('pdf_path')->get();

        foreach ($plantRequests as $request) {
            if (Storage::exists($request->pdf_path)) {
                if (!$this->encryptionService->isEncrypted($request->pdf_path)) {
                    $files[] = [
                        'path' => $request->pdf_path,
                        'original_filename' => basename($request->pdf_path),
                        'type' => $request->request_type === 'user' ? 'inquiry_pdf' : 'rfq_pdf',
                        'plant_request_id' => $request->id,
                        'uploaded_by' => 1, // System generated
                    ];
                }
            }
        }

        return $files;
    }

    /**
     * Display summary of files to be encrypted
     */
    private function displaySummary(array $files): void
    {
        $summary = [
            'site_visit_client_data' => 0,
            'site_visit_proposal' => 0,
            'site_visit_media' => 0,
            'rfq_pdf' => 0,
            'inquiry_pdf' => 0,
        ];

        foreach ($files as $file) {
            $summary[$file['type']]++;
        }

        $this->table(
            ['File Type', 'Count'],
            [
                ['Site Visit - Client Data', $summary['site_visit_client_data']],
                ['Site Visit - Proposals', $summary['site_visit_proposal']],
                ['Site Visit - Media Files', $summary['site_visit_media']],
                ['RFQ PDFs', $summary['rfq_pdf']],
                ['Inquiry PDFs', $summary['inquiry_pdf']],
                ['TOTAL', count($files)],
            ]
        );
    }

    /**
     * Encrypt a single file and update database references
     */
    private function encryptFile(array $fileInfo): void
    {
        // Encrypt the file
        $result = $this->encryptionService->encryptFile(
            $fileInfo['path'],
            $fileInfo['original_filename'],
            $fileInfo['uploaded_by']
        );

        if (!$result['success']) {
            throw new \Exception($result['error'] ?? 'Encryption failed');
        }

        // Update database references
        $this->updateDatabaseReferences($fileInfo, $result['encrypted_path']);
    }

    /**
     * Update database references to point to encrypted file
     */
    private function updateDatabaseReferences(array $fileInfo, string $encryptedPath): void
    {
        if ($fileInfo['type'] === 'site_visit_client_data') {
            $siteVisit = SiteVisit::find($fileInfo['site_visit_id']);
            $clientData = $siteVisit->client_data_checklist;
            $clientData[$fileInfo['item_key']][$fileInfo['item_index']]['path'] = $encryptedPath;
            $siteVisit->client_data_checklist = $clientData;
            $siteVisit->save();
        } elseif ($fileInfo['type'] === 'site_visit_proposal') {
            $siteVisit = SiteVisit::find($fileInfo['site_visit_id']);
            $proposalData = $siteVisit->proposal_checklist;
            $proposalData[$fileInfo['item_key']][$fileInfo['item_index']]['path'] = $encryptedPath;
            $siteVisit->proposal_checklist = $proposalData;
            $siteVisit->save();
        } elseif ($fileInfo['type'] === 'site_visit_media') {
            $siteVisit = SiteVisit::find($fileInfo['site_visit_id']);
            $mediaFiles = $siteVisit->media_files;
            $mediaFiles[$fileInfo['media_index']]['path'] = $encryptedPath;
            $siteVisit->media_files = $mediaFiles;
            $siteVisit->save();
        } elseif (in_array($fileInfo['type'], ['rfq_pdf', 'inquiry_pdf'])) {
            $plantRequest = PlantRequest::find($fileInfo['plant_request_id']);
            $plantRequest->pdf_path = $encryptedPath;
            $plantRequest->save();
        }
    }

    /**
     * Display encryption results
     */
    private function displayResults(int $encrypted, int $failed, array $errors): void
    {
        if ($encrypted > 0) {
            $this->info("✅ Successfully encrypted {$encrypted} file(s)");
        }

        if ($failed > 0) {
            $this->error("❌ Failed to encrypt {$failed} file(s)");
            $this->newLine();
            $this->error('Errors:');
            foreach ($errors as $error) {
                $this->line("  • {$error['file']}: {$error['error']}");
            }
        }

        if ($encrypted > 0) {
            $this->newLine();
            $this->info('📍 Encrypted files location: storage/app/encrypted/');
            $this->info('📊 Database tracking: encrypted_files table');
        }
    }
}
