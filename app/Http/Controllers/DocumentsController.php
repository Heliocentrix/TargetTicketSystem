<?php

namespace TargetInk\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use TargetInk\Http\Controllers\Controller;
use TargetInk\Http\Requests;
use TargetInk\User;

class DocumentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ownCompany');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company_slug, $type)
    {
        $client = User::where('company_slug', '=', $company_slug)->first();
        if($type == 'seo') {
            $files = $client->seoFiles;
        }elseif($type == 'info') {
            $files = $client->infoFiles;
        } else {
            abort(404);
        }
        return view('documents', compact('files', 'type'));
    }

    public function secure_document_login(Request $request)
    {

        $client = User::findOrFail(auth()->user()->id);
        $type = $request->input('stype');
        $same_email = ($client->email != $request->input('s-email')) ? false : true ;

        if(Hash::check($request->input('s-password'),$client->password) && $same_email){
            session()->put('s-company_slug', $client->company_slug);
            return redirect($client->company_slug."/documents/".$type);
        }

        return redirect()->back()->with('secure_error', 'Incorrect credentials entered.');
    }

}
