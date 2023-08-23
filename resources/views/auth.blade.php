<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Todo App">
        <meta name="author" content="MOCHAMMAD DANNY SETYAWAN">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Todo App - Autentikasi Masuk</title>
        <link href="{{asset('assets/lib/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
        <link href="{{ asset('assets/lib/ionicons/css/ionicons.min.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('assets/css/dashforge.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/dashforge.auth.css')}}">
        <style>
            body {
                font-family: "Poppins", Tahoma, Geneva, sans-serif;
                font-weight: normal;
                font-size: 14px;
                line-height: 24px;
                color: #42464e;
            }
            .banner1 {
                background-image: url('{{asset('assets/img/bgnew.jpg')}}');
                background-position-y: center;
            }
        </style>
    </head>
    <body>
        <section class="get-appointment banner1 ">
            <div class="pd-t-30 pd-b-0 text-center"></div>
        </section>
        <section class="bg-lightgrey appointment-from">
            <div class="container adj-w">
                <div class="row box-shadow-02 form-letak1">
                    <div class="col-12 col-md-12 appointment-inner">
                        <div id="pricelistForm" style="display: ;">
                            <h4 class="text-center" id="welcome">Welcome to Todo App</h4>
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if ($message = Session::get('error1'))
                            <div class="text-center alert alert-danger">
                                {{ $message }}
                            </div>
                            @endif
                            @if ($message = Session::get('success'))
                            <div class="text-center alert alert-success">
                                {{ $message }}
                            </div>
                            @endif
                            <form action="{{url('login')}}" id="log" method="post" autocomplete="off">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label style="text-align: left;">Email</label>
                                    <input name="nm" type="email" class="form-control rounded-0" placeholder="Your Email" minlength="3" maxlength="50" tabindex="1" autofocus required>
                                </div>
                                <div class="form-group">
                                    <div class="d-flex justify-content-between mg-b-5">
                                        <label class="mg-b-0-f">Password</label>
                                    </div>
                                    <input name="pw" type="password" class="form-control rounded-0" placeholder="Your Password" tabindex="2" minlength="3" maxlength="16" required>
                                </div>
                                <button type="submit" class="loginbtn btn btn-block rounded-0"><i class="fa fa-key"></i> Sign In</button>
                                <div class="text-center pt-3">
                                    <a href="javascript:void(0)" onclick="register();" style="color:inherit">Register</a>
                                </div>
                            </form>
                            <form style="display:none;" id="reg" method="post" autocomplete="off">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label style="text-align: left;">Name</label>
                                    <input id="name" name="name" type="text" class="form-control rounded-0 " placeholder="Fullname" minlength="3" maxlength="50" tabindex="1" autofocus>
                                </div>
                                <div class="form-group">
                                    <label style="text-align: left;">Email</label>
                                    <input id="email" name="email" type="email" class="form-control rounded-0 " placeholder="Your Email" minlength="3" maxlength="50" tabindex="1" autofocus>
                                </div>
                                <div class="form-group">
                                    <div class="d-flex justify-content-between mg-b-5">
                                        <label class="mg-b-0-f">Password</label>
                                    </div>
                                    <input id="password" name="password" type="password" class="form-control rounded-0" placeholder="Password Maximum 16 Character" tabindex="2" minlength="3" maxlength="16">
                                </div>
                                <button type="submit" onclick="create();" class="regbtn btn btn-block btn-primary rounded-0">Create Account</button>
                                <div class="text-center pt-3">
                                    <a href="javascript:void(0)" onclick="backToLogin()" style="color:inherit">Back to login</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
            </div> 
        </section>
        
        <footer class="footer" style="display: block;position: fixed;z-index: 9999;bottom: 0;width: 100%;">
            <div style="margin-top: 0;text-align: center;">
                <span>&copy; 2023 Dibuat oleh <a href="https://instagram.com/mdannys_">Mochammad Danny Setyawan</a> Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</span>
            </div>
        </footer>
        <script src="{{ asset('assets/lib/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/libraries.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        <script>
            function register(){
                $("#welcome").text('Register');
                $(".alert-danger").html('').hide();
                $("#reg").show();
                $("#log").hide();
            }

            function backToLogin(){
                $("#welcome").text('Welcome to Todo App');
                $("#log").show();
                $("#reg").hide();
            }
            $(document).ready(function(){
                $("#reg").submit(function(e){
                    e.preventDefault();
                    var name    = $('#name').val();
                    var email   = $('#email').val();
                    var pass    = $('#password').val();
                    if(name.length == 0){
                        $('#name').focus();
                        Swal.fire({
                            icon: 'info',
                            title: 'Info',
                            text: 'Column name has not been filled',
                            timer : 3000
                        });
                        return false
                    }
                    if(email.length == 0){
                        $('#email').focus();
                        Swal.fire({
                            icon: 'info',
                            title: 'Info',
                            text: 'Column email has not been filled',
                            timer : 3000
                        });
                        return false
                    }
                    if(pass.length == 0){
                        $('#password').focus();
                        Swal.fire({
                            icon: 'info',
                            title: 'Info',
                            text: 'Column password has not been filled',
                            timer : 3000
                        });
                        return false
                    }
                    $.ajax({
                        url      : "{{ url('register') }}",
                        type     : "POST",
                        data     : $("#reg").serialize(),
                        dataType : "json",
                        beforeSend : function(){
                            Swal.showLoading();
                        },
                        success : function(data){
                            if(data.results.code == 500){
                                Swal.fire({
                                    title: "Error",
                                    text: data.results.description,
                                    icon: "error",
                                    timer:5000,
                                });
                                return false;
                            }
                            if(data.results.code == 400){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.results.description,
                                    timer : 3000
                                });
                                return false;
                            }else if(data.results.code == 200){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: data.results.description,
                                    timer : 3000
                                });
                                backToLogin();
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown){
                            Swal.fire({
                                title: "Error",
                                text: 'Data Processing Failed, Please Check Your Internet Connection Or Try Logging In Again',
                                icon: "error",
                                timer:3000,
                            });
                        }
                    }); 
                });
            });
        </script>
    </body>
</html>
