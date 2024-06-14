<?php

namespace App\Http\Controllers\Api;

use App\Models\Note;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::all();
        return response()->json([
            'message' => 'success',
            'data' => $notes
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'is_pinned' => 'required',
        ]);
        $note = Note::create($request->all());
        //if request has image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $note->image = $imageName;
            $note->save();
        }
        return response()->json([
            'message' => 'success',
            'data' => $note
        ], 201);
    }

    //update
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'is_pinned' => 'required',
        ]);
        $note = Note::find($id);
        $note->update($request->all());
        //if request has image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $note->image = $imageName;
            $note->save();
        }
        return response()->json([
            'message' => 'success',
            'data' => $note
        ], 200);
    }


    //delete
    public function destroy($id){
        $note = Note::find($id);
        $note->delete();

        if(!$note){
            return response()->json([
                'message' => 'Note not found'
            ], 404);
        }
        
        return response()->json([
            'message' => 'success',
        ], 200);
    }


    //detail
    public function show($id){
        $note = Note::find($id);
        return response()->json([
            'message' => 'success',
            'data' => $note
        ], 200);
    }
}