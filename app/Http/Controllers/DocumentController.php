<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Project;


class DocumentController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        Document::create([
            'project_id' => $project->id,
            'name' => $file->getClientOriginalName(),
            'file_path' => $path,
        ]);

        return redirect()->route('projects.show', $project)->with('success', 'Το αρχείο ανέβηκε.');
    }

    public function download(Document $document)
    {
        return response()->download(storage_path('app/public/' . $document->file_path));
    }
}
