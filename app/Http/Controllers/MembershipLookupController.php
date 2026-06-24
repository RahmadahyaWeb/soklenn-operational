<?php

namespace App\Http\Controllers;

use App\Models\CustomerMembership;
use Illuminate\Http\Request;

class MembershipLookupController extends Controller
{
    public function index()
    {
        return view('membership.lookup');
    }

    public function search(Request $request)
    {
        $request->validate([
            'member_code' => ['required'],
        ]);

        $membership = CustomerMembership::where(
            'member_code',
            strtoupper($request->member_code)
        )->first();

        if (! $membership) {
            return back()->withErrors([
                'member_code' => 'Member tidak ditemukan.',
            ]);
        }

        return redirect()->route(
            'membership.card',
            $membership->public_token
        );
    }
}
