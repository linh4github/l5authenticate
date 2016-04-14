
<ul>
    @foreach($errors->all() as $error)
        <li>{{$error}}</li>
    @endforeach
</ul>
<form action="{{ route('auth.login') }}" method="post">
    <input type="text" name="email" placeholder="email" value="{{ old('email') }}">
    <input type="text" name="password" placeholder="password" value="{{ old('password') }}">
    <label>
        Remember me: <input type="checkbox" name="remember" checked="{{ old('remember', true) }}">
    </label>
    <button type="submit">Login</button>
</form>