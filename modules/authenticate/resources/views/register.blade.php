
<ul>
    @if (isset($errors))
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    @endif
</ul>
<form action="{{ route('auth.register') }}" method="post">
    <input type="text" name="email" placeholder="email" value="{{ old('email') }}">
    <input type="text" name="password" placeholder="password" value="{{ old('password') }}">
    <button type="submit">Register</button>
</form>