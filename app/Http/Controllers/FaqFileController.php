<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreFaqFileRequest;
use App\Models\FaqFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FaqFileController extends Controller
{
    /**
     * Display a listing of the user's FAQ files.
     */
    public function index(Request $request): JsonResponse
    {
        $files = $request->user()->faqFiles()->latest()->get();

        return response()->json($files);
    }

    /**
     * Store a newly uploaded FAQ file.
     */
    public function store(StoreFaqFileRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $type = $file->getClientOriginalExtension();

            // Ensure directory exists
            $uploadPath = 'faq_files/'.$user->id;
            if (! Storage::disk('public')->exists($uploadPath)) {
                Storage::disk('public')->makeDirectory($uploadPath);
            }

            $path = $file->storeAs($uploadPath, Str::random(16).'_'.$originalName, 'public');

            $faqFile = FaqFile::create([
                'user_id' => $user->id,
                'path' => $path,
                'original_name' => $originalName,
                'type' => $type,
                'uploaded_at' => now(),
            ]);

            return response()->json($faqFile, 201);

        } catch (\Exception $e) {
            Log::error('FAQ file upload failed', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'The file failed to upload.',
            ], 500);
        }
    }

    /**
     * Display the specified FAQ file metadata.
     */
    public function show(FaqFile $faqFile): JsonResponse
    {
        Gate::authorize('view', $faqFile);

        return response()->json($faqFile);
    }

    /**
     * Update (replace) the specified FAQ file.
     */
    public function update(StoreFaqFileRequest $request, FaqFile $faqFile): JsonResponse
    {
        Gate::authorize('update', $faqFile);
        $user = $request->user();
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $type = $file->getClientOriginalExtension();
        $newPath = $file->storeAs(
            'faq_files/'.$user->id,
            Str::random(16).'_'.$originalName,
            'public'
        );
        // Delete old file
        Storage::disk('public')->delete($faqFile->path);
        // Update DB record
        $faqFile->update([
            'path' => $newPath,
            'original_name' => $originalName,
            'type' => $type,
            'uploaded_at' => now(),
        ]);

        return response()->json($faqFile);
    }

    /**
     * Remove the specified FAQ file from storage.
     */
    public function destroy(FaqFile $faqFile): JsonResponse
    {
        Gate::authorize('delete', $faqFile);
        Storage::disk('public')->delete($faqFile->path);
        $faqFile->delete();

        return response()->json(['message' => 'File deleted.']);
    }
}
