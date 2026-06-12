<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;


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

    public function storeForTask(Request $request, \App\Models\Task $task)
    {
        $request->validate([
            'file' => 'required|file|max:5120',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        Document::create([
            'project_id' => $task->project_id,
            'task_id' => $task->id,
            'name' => $file->getClientOriginalName(),
            'file_path' => $path,
        ]);

        return redirect()->route('tasks.show', $task)->with('success', 'Το αρχείο ανέβηκε.');
    }

    public function download(Request $request, Document $document)
    {
        $path = storage_path('app/public/' . $document->file_path);

        if ($request->query('inline')) {
            $mime = Storage::disk('public')->mimeType($document->file_path) ?? 'application/octet-stream';
            return response()->file($path, ['Content-Type' => $mime]);
        }

        return response()->download($path, $document->name);
    }

    public function destroy(Document $document)
    {
        $taskOwnerId = $document->task ? $document->task->user_id : null;

        if (Auth::id() !== $taskOwnerId && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        // delete file from storage if exists
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Το αρχείο διαγράφηκε.');
    }
}
