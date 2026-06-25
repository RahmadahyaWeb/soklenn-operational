<?php

namespace App\Http\Controllers;

use App\Models\CustomerMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MembershipController extends Controller
{
    public function saveCard(
        Request $request,
        CustomerMembership $membership
    ) {
        $request->validate([
            'image' => ['required', 'string'],
        ]);

        try {

            $image = $request->input('image');

            if (! str_starts_with($image, 'data:image/png;base64,')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid image format.',
                ], 422);
            }

            $image = preg_replace(
                '/^data:image\/png;base64,/',
                '',
                $image
            );

            $image = str_replace(' ', '+', $image);

            $binary = base64_decode($image, true);

            if ($binary === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to decode image.',
                ], 422);
            }

            if (strlen($binary) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image is empty.',
                ], 422);
            }

            $fileName = $membership->member_code.'.png';

            $path = "membership-cards/{$fileName}";

            Storage::disk('public')->put(
                $path,
                $binary
            );

            $membership->update([
                'card_image' => $path,
            ]);

            return response()->json([
                'success' => true,
                'path' => $path,
            ]);

        } catch (\Throwable $e) {

            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);

        }
    }
}
