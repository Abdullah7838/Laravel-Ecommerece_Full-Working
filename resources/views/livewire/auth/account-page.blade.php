<div class="bg-white py-12 sm:py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">My Account</h1>
                <p class="mt-4 text-lg text-gray-500">Manage your account details and preferences</p>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex flex-col items-center">
                        <div class="mb-6">
                            <img class="h-32 w-32 rounded-full ring-4 ring-blue-600" 
                                src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=300&h=300&q=80" 
                                alt="{{ Auth::user()->name }}">
                        </div>

                        <div class="w-full space-y-6">
                            <div class="border-b border-gray-200 pb-5">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Personal Information</h3>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Full name</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">{{ Auth::user()->name }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email address</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">{{ Auth::user()->email }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Member since</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">{{ Auth::user()->created_at->format('F j, Y') }}</dd>
                                </div>
                            </div>

                            <div class="pt-5 border-t border-gray-200">
                                <div class="flex justify-end">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V7.414l-5-5H3zm7 5a1 1 0 00-1 1v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 12.586V9a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>