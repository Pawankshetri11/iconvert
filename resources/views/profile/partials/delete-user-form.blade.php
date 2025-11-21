<div>
    <button
        type="button"
        onclick="document.getElementById('delete-modal').classList.remove('hidden')"
        class="bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition-all duration-200 shadow-lg hover:shadow-red-500/25"
    >
        Delete Account
    </button>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-gray-800 rounded-2xl p-6 w-full max-w-md border border-red-700/50">
            <h2 class="text-xl font-bold text-red-300 mb-4">
                Are you sure you want to delete your account?
            </h2>

            <p class="text-gray-300 text-sm mb-6">
                Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
            </p>

            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="mb-6">
                    <label for="delete_password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <input
                        id="delete_password"
                        name="password"
                        type="password"
                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200"
                        placeholder="Enter your password"
                        required
                    />
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <button
                        type="button"
                        onclick="document.getElementById('delete-modal').classList.add('hidden')"
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors font-semibold"
                    >
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
