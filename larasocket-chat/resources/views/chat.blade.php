<x-app-layout>

    <x-slot name="header">
        <div class="header-form">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Partidas') }}
            </h2>
            <a href="#" class="bg-info border border-transparent rounded-md inline-flex items-center px-4 py-1" >
                {{ __('Adicionar') }}
            </a> 
        </div>
    </x-slot>

    <x-slot name="header" class="container" id="app">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Messages</h2>

                <div
                    class="clearfix"
                    v-for="message in messages" >
                    @{{ message.user.name }}: @{{ message.message }}
                </div>

                <div class="input-group">
                    <input
                        type="text"
                        name="message"
                        class="form-control"
                        placeholder="Type your message here..."
                        v-model="newMessage"
                        @keyup.enter="sendMessage" >

                    <x-button
                        class="btn btn-primary"
                        @click="sendMessage" >
                        Send
                    </x-button>
                </div>
            </div>
        </div>
    </x-slot > 

</x-app-layout>