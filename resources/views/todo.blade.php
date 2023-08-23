<html lang="en"><head>   
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Todo App">
    <meta name="author" content="MOCHAMMAD DANNY SETYAWAN">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>To do Apps</title>
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
            background-color:#ededed;
        }
        .listtask{
            background: #dedede;
            padding: 10px;
            overflow:auto;
        }
    </style>
<body>
    <section class="get-appointment banner1x ">
        <div class="pd-t-30 pd-b-0 text-center"></div>
    </section>
    <section class="bg-lightgrey appointment-from">
        <div class="container adj-w">
            <div class="row box-shadow-02 form-letak1">
                <div class="col-12 col-md-12 appointment-inner">
                    <div id="pricelistForm" style="display: ;">
                        <h3 class="font-weight-bold">Todo App</h3>
                        <p>Welcome {{Session::get('name')}} <a href="{{url('logout')}}" class="badge badge-danger">Logout</a></p>
                        @if (count($errors) > 0)
                        <div class="text-center alert alert-danger">
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
                        <div class="input-group mb-3">
                            <input type="text" id="myTodo" class="form-control" placeholder="My New Todo" aria-label="" aria-describedby="basic-addon1">
                            <input type="hidden" id="idTodo" value="0">
                            <div class="input-group-prepend">
                                <button class="btn btn-success mr-2" id="addTodo" type="button"><i class="fa fa-plus"></i></button>
                                <button class="btn btn-white" id="refreshTodo" type="button"><i class="fas fa-sync"></i></button>
                            </div>
                        </div>
                        <div id="results"></div>
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
<script src="{{asset('assets/lib/jquery/jquery.min.js')}}"></script>
<script>
    function getData(){
        $.ajax({
            url      : "{{url('apps/todo/getdata')}}",
            dataType : "json",
            beforeSend:function(){
                Swal.showLoading();
            },
            success:function(json){
                Swal.close();
                if(json.results.code == 500){
                    Swal.fire({
                        title: "Error",
                        text: json.results.description,
                        icon: "error",
                        timer:5000,
                    });
                    return false;
                }
                if(json.results.code == 400){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: json.results.description,
                        timer : 3000
                    });
                    return false;
                }else if(json.results.code == 200){
                    $("#results").html(json.results.data);
                    $('#myTodo').val('');
                    $('#idTodo').val('');
                    $('#addTodo').html('<i class="fa fa-plus"></i>');
                }
            },
            error: function (jqXHR, textStatus, errorThrown){
                Swal.fire({
                    title: "Error",
                    text: 'Gagal Memproses Data, Silahkan Periksa Koneksi Internet Anda Atau Coba Login Kembali',
                    icon: "error",
                    timer:3000,
                });
            }
        });      
    }

    function edit(w){
        var id   = $(w).attr('data-id');
        var teks = $(w).attr('data-text');
        $("#idTodo").val(id);
        $("#myTodo").val(teks);
        $('#addTodo').html('<i class="fa fa-save"></i> Update');
    }

    function removetask(w){
        var id   = $(w).attr('data-id');
        Swal.fire({
            icon: 'info',
            title: 'Confirm',
            html: "Are You Sure Delete This Data?",
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url      : "{{ url('apps/todo/delete') }}"+"/"+id,
                    type     : "GET",
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
                            getData();
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
            }
        });
    }

    $(document).ready(function(){            

        getData();

        $("#refreshTodo").on('click',function(){
            getData();
        });
        
        $("#addTodo").on('click',function(){
            var todo = $('#myTodo').val();
            var k    = $('#idTodo').val();
            if(todo.length == 0){
                $('#myTodo').focus();
                Swal.fire({
                    icon: 'info',
                    title: 'Info',
                    text: 'Column Not Filled',
                    timer : 3000
                });
                return false;
            }
            $.ajax({
                url      : "{{ url('apps/todo/save') }}",
                type     : "POST",
                data     : {"_token": "{{ csrf_token() }}","todo":todo,"key":k},
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
                        $('#myTodo').val('');
                        $('#idTodo').val('');
                        $('#addTodo').html('<i class="fa fa-plus"></i>');
                        getData();
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
<script src="{{ asset('assets/lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/libraries.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
</html>