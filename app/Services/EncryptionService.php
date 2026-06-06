<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use App\Models\EncryptedFile;

class EncryptionService
{
    /**
     * Encrypt a file and store it securely
     * 
     * @param string $originalPath Path in storage (e.g., "site-visits/file.pdf")
     * @param string $originalFilename Original filename
     * @param int $uploadedBy User ID who uploaded the file
     * @return array ['encrypted_path' => string, 'encrypted_file_id' => int]
     */
    public function encryptFile(string $originalPath, string $originalFilename, ?int $uploadedBy = null): array
    {
        try {
            // Read the original file
            $fileContents = Storage::get($originalPath);
            
            if (!$fileContents) {
                throw new \Exception("File not found: {$originalPath}");
            }
            
            // Encrypt the file contents
            $encryptedContents = Crypt::encrypt($fileContents);
            
            // Generate unique encrypted filename
            $encryptedFilename = $this->generateEncryptedFilename($originalFilename);
            $encryptedPath = 'encrypted/' . $encryptedFilename;
            
            // Store encrypted file (using 'local' disk for security - not publicly accessible)
            Storage::disk('local')->put($encryptedPath, $encryptedContents);
            
            // Get file metadata
            $fileType = Storage::mimeType($originalPath);
            $fileSize = strlen($fileContents);
            
            // Create database record
            $encryptedFile = EncryptedFile::create([
                'original_path' => $originalPath,
                'encrypted_path' => $encryptedPath,
                'original_filename' => $originalFilename,
                'file_type' => $fileType,
                'file_size' => $fileSize,
                'uploaded_by' => $uploadedBy,
                'encryption_algorithm' => 'AES-256-CBC',
            ]);
            
            // Delete original unencrypted file for security
            Storage::delete($originalPath);
            
            Log::info('File encrypted successfully', [
                'original_path' => $originalPath,
                'encrypted_path' => $encryptedPath,
                'encrypted_file_id' => $encryptedFile->id,
                'uploaded_by' => $uploadedBy
            ]);
            
            return [
                'encrypted_path' => $encryptedPath,
                'encrypted_file_id' => $encryptedFile->id,
                'success' => true
            ];
            
        } catch (\Exception $e) {
            Log::error('File encryption failed', [
                'original_path' => $originalPath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return original path as fallback (graceful degradation)
            return [
                'encrypted_path' => $originalPath,
                'encrypted_file_id' => null,
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Decrypt and stream file directly to browser (no temp file)
     * 
     * @param int $encryptedFileId ID from encrypted_files table
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function streamDecryptedFile(int $encryptedFileId)
    {
        try {
            $encryptedFile = EncryptedFile::findOrFail($encryptedFileId);
            
            // Read encrypted contents
            $encryptedContents = Storage::disk('local')->get($encryptedFile->encrypted_path);
            
            if (!$encryptedContents) {
                throw new \Exception("Encrypted file not found: {$encryptedFile->encrypted_path}");
            }
            
            // Decrypt contents
            $decryptedContents = Crypt::decrypt($encryptedContents);
            
            Log::info('File decrypted for download', [
                'encrypted_file_id' => $encryptedFileId,
                'original_filename' => $encryptedFile->original_filename,
                'file_type' => $encryptedFile->file_type
            ]);
            
            // Stream response
            return response()->streamDownload(function() use ($decryptedContents) {
                echo $decryptedContents;
            }, $encryptedFile->original_filename, [
                'Content-Type' => $encryptedFile->file_type,
            ]);
            
        } catch (\Exception $e) {
            Log::error('File decryption failed', [
                'encrypted_file_id' => $encryptedFileId,
                'error' => $e->getMessage()
            ]);
            
            abort(500, 'Failed to decrypt file: ' . $e->getMessage());
        }
    }
    
    /**
     * View encrypted file in browser (inline display)
     * 
     * @param int $encryptedFileId ID from encrypted_files table
     * @return \Illuminate\Http\Response
     */
    public function viewDecryptedFile(int $encryptedFileId)
    {
        try {
            $encryptedFile = EncryptedFile::findOrFail($encryptedFileId);
            
            // Read encrypted contents
            $encryptedContents = Storage::disk('local')->get($encryptedFile->encrypted_path);
            
            if (!$encryptedContents) {
                throw new \Exception("Encrypted file not found: {$encryptedFile->encrypted_path}");
            }
            
            // Decrypt contents
            $decryptedContents = Crypt::decrypt($encryptedContents);
            
            Log::info('File decrypted for viewing', [
                'encrypted_file_id' => $encryptedFileId,
                'original_filename' => $encryptedFile->original_filename,
                'file_type' => $encryptedFile->file_type
            ]);
            
            // Return inline response
            return response($decryptedContents)
                ->header('Content-Type', $encryptedFile->file_type)
                ->header('Content-Disposition', 'inline; filename="' . $encryptedFile->original_filename . '"');
            
        } catch (\Exception $e) {
            Log::error('File view failed', [
                'encrypted_file_id' => $encryptedFileId,
                'error' => $e->getMessage()
            ]);
            
            abort(500, 'Failed to view file: ' . $e->getMessage());
        }
    }
    
    /**
     * Check if a file path is encrypted
     * 
     * @param string $path File path
     * @return bool
     */
    public function isEncrypted(string $path): bool
    {
        return EncryptedFile::where('encrypted_path', $path)->exists()
            || EncryptedFile::where('original_path', $path)->exists();
    }
    
    /**
     * Get encrypted file record by original path
     * 
     * @param string $originalPath Original file path
     * @return EncryptedFile|null
     */
    public function getEncryptedFileByPath(string $originalPath): ?EncryptedFile
    {
        return EncryptedFile::where('original_path', $originalPath)->first();
    }
    
    /**
     * Generate unique encrypted filename
     * 
     * @param string $originalFilename Original filename
     * @return string
     */
    private function generateEncryptedFilename(string $originalFilename): string
    {
        $extension = pathinfo($originalFilename, PATHINFO_EXTENSION);
        $timestamp = now()->format('YmdHis');
        $random = bin2hex(random_bytes(8));
        
        return "{$timestamp}_{$random}.enc";
    }
    
    /**
     * Delete encrypted file and its database record
     * 
     * @param int $encryptedFileId ID from encrypted_files table
     * @return bool
     */
    public function deleteEncryptedFile(int $encryptedFileId): bool
    {
        try {
            $encryptedFile = EncryptedFile::findOrFail($encryptedFileId);
            
            // Delete physical file
            if (Storage::disk('local')->exists($encryptedFile->encrypted_path)) {
                Storage::disk('local')->delete($encryptedFile->encrypted_path);
            }
            
            // Delete database record
            $encryptedFile->delete();
            
            Log::info('Encrypted file deleted', [
                'encrypted_file_id' => $encryptedFileId,
                'original_filename' => $encryptedFile->original_filename
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to delete encrypted file', [
                'encrypted_file_id' => $encryptedFileId,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
}
