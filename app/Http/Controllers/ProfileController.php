<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;

class ProfileController extends Controller {
    public function viewProfile()
    {
        $user = Auth::user();
        $s3Data = prepareS3Data();

        return view('profile.profile', compact('user', 's3Data'));
    }

    public function saveProfile(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'password' => 'confirmed|min:6',
            'birthday' => 'required|max:25',
            'phone' => 'required|max:50',
            'hospital_name' => 'required|max:255',
            'hospital_level' => 'required|max:50',
            'hospital_address1' => 'required|max:255',
            'hospital_city' => 'required|max:255',
            'hospital_state' => 'required|max:50',
            'hospital_zipcode' => 'required|max:50',
        ]);

        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->email = $request['email'];
        if ($request['password']) $user->password = bcrypt($request['password']);
        $user->birthday = $request['birthday'];
        $user->phone = $request['phone'];
        $user->address = $request['address'];
        $user->unit = $request['unit'];
        $user->city = $request['city'];
        $user->state = $request['state'];
        $user->zipcode = $request['zipcode'];
        $user->hospital_name = $request['hospital_name'];
        $user->hospital_level = $request['hospital_level'];
        $user->hospital_ntdb = $request['hospital_ntdb'];
        $user->hospital_tqip = $request['hospital_tqip'];
        $user->hospital_address1 = $request['hospital_address1'];
        $user->hospital_address2 = $request['hospital_address2'];
        $user->hospital_address3 = $request['hospital_address3'];
        $user->hospital_city = $request['hospital_city'];
        $user->hospital_state = $request['hospital_state'];
        $user->hospital_zipcode = $request['hospital_zipcode'];
        $user->ssn = $request['ssn'];
        $user->credentials = $request['credentials'];
        $user->state_license = $request['state_license'];
        $user->discussion_emails_enabled = $request['discussion_emails_enabled'];
        $user->save();

        session()->flash('profileUpdated', 'Profile has been updated!');
        return redirect()->action('ProfileController@viewProfile');
    }

    public function saveAvatar(Request $request) {
        $this->validate($request, [
            'fileKeys' => 'required',
        ]);

        $user = Auth::user();
        $user->avatar = $request->fileKeys[0];
        $user->save();

        session()->flash('avatarUpdated', 'Profile photo has been updated!');
        return redirect()->action('ProfileController@viewProfile');
    }
}
