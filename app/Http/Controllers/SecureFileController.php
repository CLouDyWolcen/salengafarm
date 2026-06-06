<?php

namespace App\Http\Controllers;

use App\Models\EncryptedFile;
use App\Services\EncryptionService;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SecureFileController extends Controller
{
    protected $encryptionService;

    public function __construct(EncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    /**
     * Download an encrypted file
     * 
     * @param int $id Encrypted file ID
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download($id)
    {
        try {
            $encryptedFile = EncryptedFile::findOrFail($id);
            
            // Authorization check
            if (!$this->canAccessFile($encryptedFile)) {
                abort(403, 'Unauthorized access to this file.');
            }
            
            // Audit log
            AuditService::logFileAccess(
                $encryptedFile->id,
                'Downloaded',
                $encryptedFile->original_filename
            );
            
            // Stream decrypted file
            return $this->encryptionService->streamDecryptedFile($id);
            
        } catch (\Exception $e) {
            Log::error('Secure file download failed', [
                'encrypted_file_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Failed to download file. Please try again.');
        }
    }

    /**
     * View an encrypted file in browser (inline)
     * 
     * @param int $id Encrypted file ID
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        try {
            $encryptedFile = EncryptedFile::findOrFail($id);
            
            // Authorization check
            if (!$this->canAccessFile($encryptedFile)) {
                abort(403, 'Unauthorized access to this file.');
            }
            
            // Audit log
            AuditService::logFileAccess(
                $encryptedFile->id,
                'Viewed',
                $encryptedFile->original_filename
            );
            
            // View decrypted file
            return $this->encryptionService->viewDecryptedFile($id);
            
        } catch (\Exception $e) {
            Log::error('Secure file view failed', [
                'encrypted_file_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            abort(500, 'Failed to view file.');
        }
    }

    /**
     * Check if current user can access the file
     * 
     * @param EncryptedFile $encryptedFile
     * @return bool
     */
    private function canAccessFile(EncryptedFile $encryptedFile): bool
    {
        $user = Auth::user();
        
        // Admins and super admins can access all files
        if ($user->hasAdminAccess()) {
            return true;
        }
        
        // Check if file is a site visit document
        if (str_contains($encryptedFile->original_path, 'site-visits/')) {
            return $this->canAccessSiteVisitFile($encryptedFile);
        }
        
        // Check if file is an RFQ/inquiry PDF
        if (str_contains($encryptedFile->original_path, 'pdfs/')) {
            return $this->canAccessPdfFile($encryptedFile);
        }
        
        // Default: User can only access files they uploaded
        return $encryptedFile->uploaded_by === $user->id;
    }

    /**
     * Check if user can access site visit file
     * 
     * @param EncryptedFile $encryptedFile
     * @return bool
     */
    private function canAccessSiteVisitFile(EncryptedFile $encryptedFile): bool
    {
        $user = Auth::user();
        
        // Find related site visit by checking if file path is in any site visit's data
        $siteVisits = \App\Models\SiteVisit::where('user_id', $user->id)->get();
        
        foreach ($siteVisits as $siteVisit) {
            // Check client_data_checklist
            $clientData = $siteVisit->client_data_checklist ?? [];
            if (is_array($clientData)) {
                foreach ($clientData as $items) {
                    if (is_array($items)) {
                        foreach ($items as $item) {
                            if (isset($item['path']) && $item['path'] === $encryptedFile->original_path) {
                                return true;
                            }
                        }
                    }
                }
            }
            
            // Check proposal_checklist
            $proposalData = $siteVisit->proposal_checklist ?? [];
            if (is_array($proposalData)) {
                foreach ($proposalData as $items) {
                    if (is_array($items)) {
                        foreach ($items as $item) {
                            if (isset($item['path']) && $item['path'] === $encryptedFile->original_path) {
                                return true;
                            }
                        }
                    }
                }
            }
            
            // Check media_files
            $mediaFiles = $siteVisit->media_files ?? [];
            if (is_array($mediaFiles)) {
                foreach ($mediaFiles as $item) {
                    if (isset($item['path']) && $item['path'] === $encryptedFile->original_path) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }

    /**
     * Check if user can access PDF file (RFQ/inquiry)
     * 
     * @param EncryptedFile $encryptedFile
     * @return bool
     */
    private function canAccessPdfFile(EncryptedFile $encryptedFile): bool
    {
        $user = Auth::user();
        
        // Find related plant request by checking pdf_path
        $plantRequest = \App\Models\PlantRequest::where('pdf_path', $encryptedFile->encrypted_path)
            ->orWhere('pdf_path', $encryptedFile->original_path)
            ->first();
        
        if ($plantRequest) {
            // User can access if the request email matches their email
            return strtolower(trim($plantRequest->email)) === strtolower(trim($user->email));
        }
        
        return false;
    }
}
