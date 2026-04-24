<x-app-layout>
    <div class="p-6">
        <h1>Λεπτομέριες Project</h1>

        <p><strong>Πελάτης:</strong> {{ $project->client->company_name }}</p>
        <p><strong>Περιγραφή:</strong> {{ $project->title }}</p>
        <p><strong>Κατάσταση:</strong> {{ $project->status }}</p>
        <p><strong>Προθεσμία:</strong> {{ $project->due_date }}</p>

        <hr style="margin: 20px 0;">

        <h2>Ανέβασμα αρχείων</h2>

        <form method="POST" action="{{ route('projects.documents.store', $project) }}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file">
            <button type="submit">Upload</button>
        </form>

        <hr style="margin: 20px 0;">

        <h2>Έγγραφα</h2>

        <ul>
            @forelse ($project->documents as $document)
                <li>
                    {{ $document->name }}
                    -
                    <a href="{{ route('documents.download', $document) }}">Download</a>
                </li>
            @empty
                <li>Δεν υπάρχουν αρχεία ακόμα.</li>
            @endforelse
        </ul>
    </div>
</x-app-layout>