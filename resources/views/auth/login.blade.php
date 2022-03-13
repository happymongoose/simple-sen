@extends('layouts.auth')

@section('content')

<div class="container" style="height: 100vh; display: flex; align-items: center; justify-content: center;">

    <!-- Outer Row -->
    <div class="row justify-content-center" style="min-width: 100%;">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Welcome</h1>
                                </div>
                                <form class="user" id="login-form" method="POST" action="{{ route('login') }}">
                                    <!-- CSRF Token -->
                                    @csrf
                                    <div class="form-group">
                                        <input
                                          type="email"
                                          class="form-control form-control-user @error('email') is-invalid @enderror"
                                          id="email"
                                          name="email"
                                          aria-describedby="emailHelp"
                                          value="{{ old('email') }}"
                                          required
                                          autocomplete="email"
                                          placeholder="Email"
                                          autofocus>

                                          @error('email')
                                              <span class="invalid-feedback" role="alert">
                                                  <strong>{{ $message }}</strong>
                                              </span>
                                          @enderror

                                    </div>

                                    <div class="form-group">
                                        <input
                                          type="password"
                                          id="password"
                                          name="password"
                                          class="form-control  form-control-user @error('password') is-invalid @enderror"
                                          required
                                          placeholder="Password"
                                          autocomplete="current-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox"
                                              class="custom-control-input"
                                              id="remember"
                                              name="remember"
                                              {{ old('remember') ? 'checked' : '' }}>

                                            <label class="custom-control-label" for="remember">Remember
                                                Me</label>
                                        </div>

                                    </div>

                                    <a href="#" class="btn btn-primary btn-user btn-block" id="submit-button">
                                        Login
                                    </a>

                                </form>

                                <hr>

                                <div class="text-center">
                                    <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="{{ route('register') }}">Create an Account!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

</div>

@endsection

@section("scripts")

<script>

$('#password').keypress(function(event) {
    if ( event.which === 13 ) {
        $('#login-form').submit();
        return false;
    }
});

$('#submit-button').click(function(e) {
  e.preventDefault();
  $('#login-form').submit();
})

</script>

@endsection
