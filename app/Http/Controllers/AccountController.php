<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;

class AccountController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProfile()
    {
        return view('account.profile');
    }

    /**
     * @param UpdateProfileRequest $req
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdateProfile(UpdateProfileRequest $req)
    {
        $user = auth()->user();

        if($user->update($req->all())) {
            session()->flash('success', 'Updated your account');
        } else {
            session()->flash('error', 'Could not update your account');
        }

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSocialMedia()
    {
        return view('account.social-media');
    }
}
