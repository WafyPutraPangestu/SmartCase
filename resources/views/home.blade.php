<x-layout>
    <div class="container">
        <h1>Welcome to the Home Page</h1>
        <p>This is the content of your home page.</p>
        <a href="{{ route('login.index') }}" class="text-blue-500 hover:underline">Login</a>
        <a href="{{ route('register.index') }}" class="text-blue-500 hover:underline">Register</a>
    </div>
</x-layout>