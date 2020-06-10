<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href={{asset("assets/img/icon.png")}}>
    <title>Authentication Tickets</title>

    <!-- Custom fonts for this template-->
    <link href={{ asset("vendor/fontawesome-free/css/all.min.css")}} rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href={{ asset("assets/css/sb-admin-2.min.css?v=1")}} rel="stylesheet">
    <link href={{ asset("assets/css/app.css?v=1") }} rel="stylesheet">
</head>

<body class="bg-vue">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-8 col-md-8 p-5">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <div class="img-login">
                                            <img src={{asset("assets/img/icon.png")}} alt="logo" title="logo" width="100" height="auto">
                                        </div>
                                        <h1 class="h4 text-gray-900 mb-4 mt-4">Welcome again!</h1>

                                        @if (count($errors))
                                            <div class="form-group">
                                                <div class="alert alert-danger fade show">
                                                    <ul>
                                                        @foreach($errors->all() as $error)
                                                            <li>{{$error}}</li>
                                                        @endforeach
                                                    </ul>

                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif

                                        @if (Session::has('errorLogin'))
                                            <div class="form-group">
                                                <div class="alert alert-danger fade show">
                                                    {{ session('errorLogin') }}
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif

                                    </div>

                                    <form class="user" method="post" id="form" action="{{url('login')}}">
                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="login" name="login" placeholder="Type your user..." >
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Type your password..." >
                                        </div>
                                        <button class="btn btn-vue btn-user btn-block" type="submit"> Login </button>
                                        <hr>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src={{ asset("vendor/jquery/jquery.min.js")}}></script>
    <script src={{ asset("vendor/bootstrap/js/bootstrap.bundle.min.js")}}></script>

    <!-- Core plugin JavaScript-->
    <script src={{ asset("vendor/jquery-easing/jquery.easing.min.js")}}></script>

    <!-- Custom scripts for all pages-->
    <script src={{ asset("assets/js/sb-admin-2.min.js")}}></script>
</body>
</html>
