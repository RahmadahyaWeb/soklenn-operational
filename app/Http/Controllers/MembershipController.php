<?php

namespace App\Http\Controllers;

use App\Models\CustomerMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MembershipController extends Controller
{
    public function saveCard(
        Request $request,
        CustomerMembership $membership
    ) {
        $image = $request->input('image');

        $image = str_replace(
            'data:image/png;base64,',
            '',
            $image
        );

        $image = str_replace(
            ' ',
            '+',
            $image
        );

        $fileName =
            $membership->member_code.'.png';

        Storage::disk('public')->put(
            "membership-cards/{$fileName}",
            base64_decode($image)
        );

        $membership->update([
            'card_image' => "membership-cards/{$fileName}",
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
