<div class="navbar">
    <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/projects">Projects</a></li>
        <li><a href="/hire-me">Hire Me</a></li>
        @if (Auth::check())
            <li>
                <form method="POST" action="/logout" id="logout-form">
                    @csrf
                    <button form="logout-form">Log Out</button>
                </form>
            </li>
        @else
            <li><a href="/login">Log In</a></li>
            <li><a href="/register">Register</a></li>
        @endif
    </ul>
</div>
