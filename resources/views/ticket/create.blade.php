<x-app-layout>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <h1 class="text-white text-lg font-bold">Create New Support Ticket</h1>
        <div class="w-full sm:max-w-xl mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <form method="post" action="{{ route('ticket.store') }}" enctype="multipart/form-data">
                @csrf
                {{-- Email Address --}}
                <div class="mt-4">
                    <x-input-label for="title" :value="_('Title')" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" autofocus
                        for="title" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />

                </div>

                <div class="mt-4">
                    <x-input-label for="description" :value="_('Description')" />
                    <x-textarea value="" id="description" name="description" />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />

                </div>

                <div class="mt-4">
                    <x-input-label for="attachment" :value="_('Attachment (if any)')" />
                    <x-input-file id="attachment" name="attachment" />

                    <x-input-error :messages="$errors->get('attachment')" class="mt-2" />

                </div>
                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="my-4 block ml-auto">Create</x-primary-button>
                </div>
            </form>
        </div>



    </div>


</x-app-layout>
