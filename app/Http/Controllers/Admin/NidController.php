<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NidDb;
use Illuminate\Http\Request;

class NidController extends Controller
{
    public function index()
    {
        $nids = NidDb::orderByDesc('user_id')->paginate(20);
        return view('admin.nid', compact('nids'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nid_number' => ['required', 'string', 'max:255', 'unique:nid_db,nid_number'],
            'name' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date'],
        ]);
        NidDb::create($validated);
        return redirect()->route('admin.nid')->with('success', 'NID added');
    }

    public function update(Request $request, NidDb $nid)
    {
        $validated = $request->validate([
            'nid_number' => ['required', 'string', 'max:255', 'unique:nid_db,nid_number,' . $nid->user_id . ',user_id'],
            'name' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date'],
        ]);
        $nid->update($validated);
        return redirect()->route('admin.nid')->with('success', 'NID updated');
    }

    public function destroy(NidDb $nid)
    {
        $nid->delete();
        return redirect()->route('admin.nid')->with('success', 'NID deleted');
    }
}


