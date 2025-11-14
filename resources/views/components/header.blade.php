<nav class="bg-gray-800 text-white shadow-md">
<div class="flex items-center justify-between py-4  px-5">
    <div class="logo">
        <h1>Smart Case</h1>
    </div>

    <div class=" space-x-4">
        
        <a href="/">Home</a>
        @can('admin')
        <a href="/about">About</a>
        @endcan
        @can('user')
        <a href="/contact">Contact</a>
        @endcan
    </div>
@guest 
<div class="space-x-4">
    <a href="{{ route('login.index') }}">Login</a>
    <a href="{{ route('register.index') }}">Register</a>
</div>
@endguest
@auth
<div class="space-x-4">
    <span>Welcome, {{ Auth::user()->name }}</span>
    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit">Logout</button>
    </form>
    </div>
    @endauth
</div>
</nav>