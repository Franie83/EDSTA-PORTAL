<?php

namespace App\Helpers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Cloudinary\Cloudinary;

class Helpers
{
    /**
     * Return a displayable image URL (absolute URL, storage URL, or placeholder).
     */
        public static function imageUrl(?string $path, string $placeholder = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MDAiIGhlaWdodD0iNDAwIiB2aWV3Qm94PSIwIDAgNjAwIDQwMCI+PHJlY3Qgd2lkdGg9IjYwMCIgaGVpZ2h0PSI0MDAiIGZpbGw9IiNlOWVjZWYiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9InNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMjQiIGZpbGw9IiM5NGEzYjgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5ObyBJbWFnZTwvdGV4dD48L3N2Zz4='): string
    {
        if (empty($path)) {
            return $placeholder;
        }
        
        // If it's already a full URL (Cloudinary or external), return as is
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        
        // Clean the path
        $cleanPath = $path;
        if (Str::startsWith($cleanPath, '/')) {
            $cleanPath = substr($cleanPath, 1);
        }
        if (Str::startsWith($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }
        
        // Check local storage first just in case it exists
        if (Storage::disk('public')->exists($cleanPath)) {
            return asset('storage/' . $cleanPath);
        }

        // If Cloudinary is configured, reconstruct the URL without duplicating
        // the edoodyssey folder if it is already included in the saved path.
        $cloudName = config('services.cloudinary.cloud_name');
        if ($cloudName) {
            $publicPath = Str::startsWith($cleanPath, 'edoodyssey/')
                ? $cleanPath
                : 'edoodyssey/' . $cleanPath;

            return "https://res.cloudinary.com/{$cloudName}/image/upload/{$publicPath}";
        }

        return $placeholder;
    }

    /**
     * Write an audit log entry.
     */
    public static function logAction(string $action, string $entityType = '', ?int $entityId = null, ?string $description = null): void
    {
        try {
            AuditLog::create([
                'user_id'     => Auth::id(),
                'action'      => $action,
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
                'description' => $description ?? $action,
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silently fail – audit log should never break the main flow
        }
    }

    /**
     * Award heritage points to a user.
     */
    public static function awardPoints(\App\Models\User $user, int $points, string $reason = ''): void
    {
        $user->increment('heritage_points', $points);
        if ($reason) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title'   => "Heritage Points Earned",
                'message' => "You earned {$points} heritage points: {$reason}",
                'type'    => 'points',
            ]);
        }
    }

    /**
     * Upload a file to Cloudinary when configured, otherwise use local storage.
     * If Cloudinary is configured but unavailable, fail explicitly instead of
     * silently saving a temporary local copy on production hosting.
     */
    public static function saveUpload($file, string $folder = 'uploads'): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');

        if ($cloudName && $apiKey && $apiSecret) {
            try {
                $cloudinary = new Cloudinary([
                    'cloud' => [
                        'cloud_name' => $cloudName,
                        'api_key' => $apiKey,
                        'api_secret' => $apiSecret,
                    ],
                ]);

                $upload = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                    'folder' => 'edoodyssey/' . trim($folder, '/'),
                    'public_id' => time() . '_' . Str::random(10),
                    'resource_type' => 'image',
                ]);

                $secureUrl = $upload['secure_url'] ?? null;

                if (!is_string($secureUrl) || !Str::startsWith($secureUrl, 'https://')) {
                    throw new \RuntimeException('Cloudinary did not return a valid HTTPS image URL.');
                }

                return $secureUrl;
            } catch (\Throwable $e) {
                \Log::error('Cloudinary upload failed.', [
                    'folder' => $folder,
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                ]);

                throw new \RuntimeException(
                    'Image upload failed. Please check the Cloudinary configuration and try again.',
                    0,
                    $e
                );
            }
        }

        // Production uploads must use persistent cloud storage.
        $hasAnyCloudinaryCredential = $cloudName || $apiKey || $apiSecret;
        $hasAllCloudinaryCredentials = $cloudName && $apiKey && $apiSecret;

        if ($hasAnyCloudinaryCredential && !$hasAllCloudinaryCredentials) {
            throw new \RuntimeException(
                'Cloudinary configuration is incomplete. Please configure all required credentials.'
            );
        }

        if (app()->environment('production')) {
            throw new \RuntimeException(
                'Cloudinary is not configured. Image uploads are unavailable until cloud storage is configured.'
            );
        }

        // Local storage fallback is for development only.
        try {
            return $file->store($folder, 'public') ?: null;
        } catch (\Throwable $e) {
            \Log::error('Local image upload failed.', [
                'folder' => $folder,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            throw new \RuntimeException('Image upload failed. Please try again.', 0, $e);
        }
    }
    /**
     * Generate an 8-char unique reference code.
     */
    public static function generateReference(): string
    {
        return strtoupper(Str::random(3) . now()->format('ymd') . Str::random(2));
    }
}