@extends('layouts.admin')

@section('title', 'Content Editor')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-white">Content Editor</h2>
            <p class="text-gray-400">Edit frontend text, headers, footers, and other content</p>
        </div>
        <button onclick="showCreateModal()" class="bg-yellow-600 text-black px-4 py-2 rounded hover:bg-yellow-500 font-semibold">
            Add New Content
        </button>
    </div>

    <!-- Content Groups -->
    @foreach($groups as $groupKey => $groupName)
        @if(isset($contents[$groupKey]))
            <div class="stat-card">
                <h3 class="text-xl font-semibold text-white mb-4">{{ $groupName }}</h3>

                <form method="POST" action="{{ route('admin.update-content') }}">
                    @csrf
                    <div class="space-y-4">
                        @foreach($contents[$groupKey] as $content)
                            <div class="bg-gray-800 p-4 rounded-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="text-white font-medium">{{ $content->label ?: $content->key }}</h4>
                                        @if($content->description)
                                            <p class="text-gray-400 text-sm">{{ $content->description }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $content->is_active ? 'bg-green-800 text-green-200' : 'bg-red-800 text-red-200' }}">
                                            {{ $content->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <form method="POST" action="{{ route('admin.toggle-content', $content) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-blue-400 hover:text-blue-300 text-sm">
                                                {{ $content->is_active ? 'Disable' : 'Enable' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.delete-content', $content) }}" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this content?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 text-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                @if($content->type === 'html')
                                    <textarea name="contents[{{ $content->id }}][value]" rows="4"
                                              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 font-mono text-sm">{{ $content->value }}</textarea>
                                @elseif($content->type === 'json')
                                    <textarea name="contents[{{ $content->id }}][value]" rows="4"
                                              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 font-mono text-sm">{{ $content->value }}</textarea>
                                @else
                                    <input type="text" name="contents[{{ $content->id }}][value]" value="{{ $content->value }}"
                                           class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                                @endif

                                <input type="hidden" name="contents[{{ $content->id }}][key]" value="{{ $content->key }}">
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-yellow-600 text-black px-6 py-3 rounded hover:bg-yellow-500 font-semibold">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        @endif
    @endforeach

    @if($contents->isEmpty())
        <div class="stat-card text-center">
            <div class="text-6xl mb-4">📝</div>
            <h3 class="text-xl font-semibold text-white mb-2">No Content Items</h3>
            <p class="text-gray-400 mb-4">Start by adding your first content item.</p>
            <button onclick="showCreateModal()" class="bg-yellow-600 text-black px-6 py-3 rounded hover:bg-yellow-500 font-semibold">
                Add First Content
            </button>
        </div>
    @endif
</div>

<!-- Create Content Modal -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md">
            <h3 class="text-xl font-semibold text-white mb-4">Add New Content</h3>

            <form method="POST" action="{{ route('admin.create-content') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Key</label>
                        <input type="text" name="key" required
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                        <p class="text-gray-400 text-xs mt-1">Unique identifier (e.g., hero_title)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Group</label>
                        <select name="group" required
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                            @foreach(\App\Models\Content::getGroups() as $key => $name)
                                <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Type</label>
                        <select name="type" required
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                            @foreach(\App\Models\Content::getTypes() as $key => $name)
                                <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Label</label>
                        <input type="text" name="label"
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                        <p class="text-gray-400 text-xs mt-1">Human readable name</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                        <input type="text" name="description"
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                        <p class="text-gray-400 text-xs mt-1">What is this content for?</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Value</label>
                        <textarea name="value" rows="3" required
                                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="hideCreateModal()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Cancel
                    </button>
                    <button type="submit" class="bg-yellow-600 text-black px-4 py-2 rounded hover:bg-yellow-500 font-semibold">
                        Create Content
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function hideCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}
</script>
@endsection