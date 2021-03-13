<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Creat User - {{@$site_option->name}}</title>
    <!-- Bootstrap -->
    <link href="{{url()}}/public/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{url()}}/public/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{url()}}/public/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{url()}}/public/vendors/animate.css/animate.min.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{url()}}/public/build/css/custom.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  </head>
<style type="text/css">
  .login_content h1:before,.login_content h1:after{
    background: transparent;
  }
</style>
  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>
      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form method="post">
              <h1>New manager account</h1>
              @include('errors.note')
              <div>
                <input type="text" name="name" class="form-control" placeholder="Name" required="" value="{{old('name')}}" />
              </div>
              <div>
                <input type="text" name="email" class="form-control" placeholder="Email" required="" value="{{old('email')}}" />
              </div>
              <div>
                <input type="text" name="account" class="form-control" placeholder="Account" required=""  value="{{old('account')}}"/>
              </div>
              <div>
                <input type="password" name="password" class="form-control" placeholder="Password" required="" />
              </div>
              <div>
                <input type="password" name="re_password" class="form-control" placeholder="Password retype" required="" />
              </div>
              <div>
                <input class="btn btn-default submit" type="submit" name="submit" value="Login">
              </div>
              <div class="clearfix"></div>
              <div class="separator">
                <div class="clearfix"></div>
                <br />
                <div>
                  <h1><span><img style="width:45px;" src="{{url('public/images/logotrtcms-d.png')}}"></span> Spaussio - Gentelella Template</h1>
                  <p>©2016 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                </div>
              </div>
              {{csrf_field()}}
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>
