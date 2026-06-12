<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Σχόλια</h1>
                <p class="text-gray-500 mt-1">Δες και πρόσθεσε σχόλια για αυτή την εργασία</p>
                
            </div>
            <a href="{{ route('tasks.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">Όλες οι εργασίες</a>
        </div>

        <div class="bg-white shadow rounded-xl p-6">
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <form method="POST" action="{{ route('tasks.comments.store', $task) }}" class="mb-0">
                    @csrf
                    <label for="content" class="sr-only">Νέο σχόλιο</label>
                    <textarea id="content" name="content" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 resize-y focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Γράψε ένα σχόλιο..."></textarea>
                    <div class="mt-2 flex items-center justify-between">
                        <div class="text-sm text-gray-500">Σύντομα σχόλια και παρατηρήσεις</div>
                        <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none">Προσθήκη</button>
                    </div>
                </form>

                <form method="POST" action="{{ route('tasks.documents.store', $task) }}" enctype="multipart/form-data" class="mb-0">
                    @csrf
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Ανέβασε αρχείο</label>
                    <div class="flex items-center gap-2">
                        <input id="file" name="file" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:bg-gray-100 file:text-gray-700" />
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700">Ανέβασμα</button>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Μέγιστο 5MB.</p>
                </form>
            </div>

            <hr class="my-6">

            @if($task->documents->isNotEmpty())
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-2">Συνημμένα αρχεία</h3>
                    <div class="space-y-3">
                        @foreach($task->documents as $doc)
                            @php
                                $size = \Illuminate\Support\Facades\Storage::disk('public')->exists($doc->file_path)
                                    ? \Illuminate\Support\Facades\Storage::disk('public')->size($doc->file_path)
                                    : null;
                                $mime = $size ? \Illuminate\Support\Facades\Storage::disk('public')->mimeType($doc->file_path) : null;
                                if ($size) {
                                    if ($size >= 1024 * 1024) {
                                        $displaySize = round($size / (1024 * 1024), 2) . ' MB';
                                    } else {
                                        $displaySize = round($size / 1024, 1) . ' KB';
                                    }
                                } else {
                                    $displaySize = '-';
                                }
                            @endphp

                            <div class="flex items-center justify-between bg-gray-50 border rounded-md p-3">
                                <div class="flex items-center gap-3">
                                    <div class="text-2xl">📎</div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-800">{{ $doc->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $displaySize }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($mime && (str_starts_with($mime, 'image/') || $mime === 'application/pdf'))
                                        <button type="button" onclick="openPreview('{{ route('documents.download', $doc) }}?inline=1','{{ $mime }}')" class="text-sm text-indigo-600 hover:underline">Προεπισκόπηση</button>
                                    @endif
                                    <a href="{{ route('documents.download', $doc) }}" class="text-sm text-blue-600 hover:underline">Λήψη</a>

                                    @if(auth()->id() === $task->user_id || auth()->user()->hasRole('admin'))
                                        <form method="POST" action="{{ route('documents.destroy', $doc) }}" onsubmit="return confirm('Θες σίγουρα να διαγράψεις αυτό το αρχείο;')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:underline">Διαγραφή</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($task->comments->isEmpty())
                <div class="text-gray-600">Δεν υπάρχουν σχόλια ακόμα.</div>
            @else
                <div class="space-y-4">
                    @foreach ($task->comments as $comment)
                        <div class="bg-white shadow-sm border rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-sm font-semibold text-gray-800">{{ $comment->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
                                </div>
                                @if(auth()->id() === $comment->user_id)
                                    <div class="flex items-center space-x-2">
                                        <button type="button" onclick="toggleEdit({{ $comment->id }})" class="text-sm text-blue-600 hover:underline">Επεξεργασία</button>

                                        <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Θες σίγουρα να διαγράψεις αυτό το σχόλιο;')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:underline">Διαγραφή</button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            <div id="comment-content-{{ $comment->id }}" class="mt-3 text-gray-700 whitespace-pre-line">{{ $comment->content }}</div>

                            @if(auth()->id() === $comment->user_id)
                                <form method="POST" action="{{ route('comments.update', $comment) }}" id="edit-form-{{ $comment->id }}" class="mt-3 hidden">
                                    @csrf
                                    @method('PUT')
                                    <label for="content-{{ $comment->id }}" class="sr-only">Επεξεργασία σχολίου</label>
                                    <textarea id="content-{{ $comment->id }}" name="content" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 resize-y focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ $comment->content }}</textarea>
                                    <div class="mt-2 flex items-center justify-end gap-2">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">Αποθήκευση</button>
                                        <button type="button" onclick="toggleEdit({{ $comment->id }})" class="inline-flex items-center px-3 py-2 bg-gray-200 text-gray-800 text-sm rounded-lg">Ακύρωση</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<script>
function toggleEdit(id) {
    const form = document.getElementById('edit-form-' + id);
    const content = document.getElementById('comment-content-' + id);
    if (!form) return;
    form.classList.toggle('hidden');
    if (content) content.classList.toggle('hidden');
}
</script>

<!-- Preview modal -->
<div id="preview-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg overflow-hidden max-w-4xl w-full mx-4">
        <div class="flex items-center justify-between p-3 border-b">
            <div id="preview-title" class="text-sm font-medium text-gray-800">Preview</div>
            <button onclick="closePreview()" class="text-gray-600 hover:text-gray-900">✕</button>
        </div>
        <div id="preview-body" class="p-4" style="min-height:360px; display:flex; align-items:center; justify-content:center;">
            <!-- content inserted by JS -->
        </div>
    </div>
</div>

<script>
function openPreview(url, mime) {
    const modal = document.getElementById('preview-modal');
    const body = document.getElementById('preview-body');
    const title = document.getElementById('preview-title');
    body.innerHTML = '';

    if (mime && mime.startsWith('image/')) {
        const img = document.createElement('img');
        img.src = url;
        img.style.maxWidth = '100%';
        img.style.maxHeight = '70vh';
        body.appendChild(img);
        title.textContent = 'Προεπισκόπηση εικόνας';
    } else if (mime === 'application/pdf') {
        const iframe = document.createElement('iframe');
        iframe.src = url;
        iframe.style.width = '100%';
        iframe.style.height = '70vh';
        iframe.frameBorder = 0;
        body.appendChild(iframe);
        title.textContent = 'Προεπισκόπηση PDF';
    } else {
        body.textContent = 'Preview δεν υποστηρίζεται για αυτόν τον τύπο αρχείου.';
        title.textContent = 'Preview';
    }

    modal.classList.remove('hidden');
}

function closePreview() {
    const modal = document.getElementById('preview-modal');
    const body = document.getElementById('preview-body');
    body.innerHTML = '';
    modal.classList.add('hidden');
}
</script>