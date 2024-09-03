<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Str;


class ProfileUpdateController extends Controller
{

    public function edit(string $id)
    {
        $data['menu'] = 'Edit Profile';
        $data['profile_update'] = User::where('id',$id)->findorFail($id);

        return view('admin.profile-update.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileUpdateRequest $request, string $id)
    {
        // return true;
        if(empty($request['password'])){
            unset($request['password']);
        }

        $input = $request->all();

        $user = User::findorFail($id);

        if ($file = $request->file('image')) {
            $imageName = Str::random(20) . "." . $file->getClientOriginalExtension();
            
            $file->move(public_path('uploads/users'), $imageName);

            $input['image'] = 'uploads/users/' . $imageName;

            if (!empty($user->image) && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
        } else {
            $input['image'] = $user->image;
        }

        $user->update($input);

        \Session::flash('success','Profile has been updated successfully!');
        return redirect()->route('profile_update.edit', ['profile_update' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
