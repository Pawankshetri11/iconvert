@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">System Updates</h1>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Current Version</h2>
                    <p class="text-gray-500">{{ $currentVersion }}</p>
                </div>
                <button id="checkUpdatesBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Check for Updates
                </button>
            </div>

            <div id="updateStatus" class="hidden border-t border-gray-200 pt-6">
                <!-- Data injected via JS -->
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('checkUpdatesBtn').addEventListener('click', function() {
    const btn = this;
    const statusDiv = document.getElementById('updateStatus');
    
    btn.disabled = true;
    btn.innerHTML = 'Checking...';
    
    fetch('{{ route("admin.updates.check") }}')
        .then(response => response.json())
        .then(data => {
            statusDiv.classList.remove('hidden');
            
            if (data.has_update) {
                statusDiv.innerHTML = `
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    New version <strong>${data.latest_version}</strong> is available!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-md font-medium text-gray-900 dark:text-white">Release Notes</h3>
                        <pre class="bg-gray-100 dark:bg-gray-700 p-3 rounded mt-2 text-sm overflow-auto text-gray-700 dark:text-gray-300 font-mono">${data.release_notes}</pre>
                    </div>
                `;
            } else {
                statusDiv.innerHTML = `
                     <div class="bg-green-50 border-l-4 border-green-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    You are running the latest version.
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            btn.disabled = false;
            btn.innerHTML = 'Check for Updates';
        })
        .catch(err => {
            console.error(err);
            btn.disabled = false;
            btn.innerHTML = 'Check for Updates';
            alert('Failed to check for updates.');
        });
});
</script>
@endsection
