<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConsentMessage;
use App\Http\Requests\ConsentMessageRequest;

class ConsentMessageController extends Controller
{
    public function index(){
        $data['menu'] = 'Consent Message';
     
        $data['consent'] = ConsentMessage::findorFail('1');
        return view('admin.consent-message.edit',$data);
    }

    public function update(ConsentMessageRequest $request,$id){
        $inputs = $request->all();
        $consent = ConsentMessage::findOrFail($id);
        $consent->update($inputs);

        \Session::flash('success','Message has been updated successfully!');
        return redirect()->route('consent.index');
    }
}
