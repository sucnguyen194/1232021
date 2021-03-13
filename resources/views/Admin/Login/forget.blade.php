<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Forget Password - {{@$site_option->name}}</title>
  <!-- Bootstrap -->
  <link href="{{url()}}/public/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="{{url()}}/public/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="{{url()}}/public/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- Animate.css -->
  <!-- Custom Theme Style -->
  <link href="{{url()}}/public/build/css/custom.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="{{url()}}/public/admin/js/particles.js" type="text/javascript"></script>

</head>
<body class="login">
  <div id="particles-js"></div>
  <div class="cont">
    <div class="bg_container">
      <div class="demo">
        <div class="login">
         @include('errors.note')
         <div style="position: absolute; top: 30px; left: 0;right:0"><img src="{{url('public/images/logo.png')}}" alt="" width="300"></div>
         <div class="login__form">
          <form method="post">
            <div class="login__row">
              <svg class="login__icon name svg-icon" viewBox="0 0 20 20">
                <path d="M0,20 a10,8 0 0,1 20,0z M10,0 a4,4 0 0,1 0,8 a4,4 0 0,1 0,-8" />
              </svg>
              <input type="text" name="email" class="login__input email" required="required" placeholder="Email"/>
            </div>
            <button type="submit" name="submit" class="login__submit">Sign in</button>
             <p class="login__signup">Bạn đã có tài khoản? &nbsp;<a href="{{url('admin')}}">Đăng nhập</a></p>
            <p class="login__signup">Bản quyền thuộc về &nbsp;<a href="mailto:spaussio@gmail.com" target="_blank">Spaussio</a></p>
            {{csrf_field()}}
          </form>
        </div>
      </div>
    </div>
  </div>
  <style type="text/css" media="screen">
  .demo .alert__view {
    top: 20%;
    right: 10%;
    left: 10%;
  }
  .demo {
    position: absolute;
    top: 50%;
    left: 47%;
    margin-left: -15rem;
    margin-top: -26.5rem;
    width: 40rem;
    height: 53rem;
    overflow: hidden;
  }
}
@media(max-width:768px){
  .demo {
   left: 50%;
   width: 30rem;
 }
}
.login {
  position: relative;
  height: 100%;
  background: -webkit-linear-gradient(top, rgba(146, 135, 187, 0.8) 0%, rgba(60, 69, 76, 0.7) 100%);
  background: linear-gradient(to bottom, rgba(146, 135, 187, 0.4) 0%, rgba(146, 135, 187, 0.4) 100%);
  -webkit-transition: opacity 0.1s, -webkit-transform 0.3s cubic-bezier(0.17, -0.65, 0.665, 1.25);
  transition: opacity 0.1s, -webkit-transform 0.3s cubic-bezier(0.17, -0.65, 0.665, 1.25);
  transition: opacity 0.1s, transform 0.3s cubic-bezier(0.17, -0.65, 0.665, 1.25);
  transition: opacity 0.1s, transform 0.3s cubic-bezier(0.17, -0.65, 0.665, 1.25), -webkit-transform 0.3s cubic-bezier(0.17, -0.65, 0.665, 1.25);
  -webkit-transform: scale(1);
  transform: scale(1);
}
</style>
<script type="text/javascript">
  /* ---- particles.js config ---- */
  particlesJS("particles-js", {
    "particles": {
      "number": {
        "value": 7000,
        "density": {
          "enable": true,
          "value_area": 50000
        }
      },
      "color": {
        "value": "#ffffff"
      },
      "shape": {
        "type": "circle",
        "stroke": {
          "width": 0,
          "color": "#000000"
        },
        "polygon": {
          "nb_sides": 5
        },
        "image": {
          "src": "img/github.svg",
          "width": 100,
          "height": 100
        }
      },
      "opacity": {
        "value": 0.5,
        "random": false,
        "anim": {
          "enable": false,
          "speed": 1,
          "opacity_min": 0.1,
          "sync": false
        }
      },
      "size": {
        "value": 3,
        "random": true,
        "anim": {
          "enable": false,
          "speed": 40,
          "size_min": 0.1,
          "sync": false
        }
      },
      "line_linked": {
        "enable": true,
        "distance": 150,
        "color": "#ffffff",
        "opacity": 0.4,
        "width": 1
      },
      "move": {
        "enable": true,
        "speed": 6,
        "direction": "none",
        "random": false,
        "straight": false,
        "out_mode": "out",
        "bounce": false,
        "attract": {
          "enable": false,
          "rotateX": 600,
          "rotateY": 1200
        }
      }
    },
    "interactivity": {
      "detect_on": "canvas",
      "events": {
        "onhover": {
          "enable": true,
          "mode": "grab"
        },
        "onclick": {
          "enable": true,
          "mode": "push"
        },
        "resize": true
      },
      "modes": {
        "grab": {
          "distance": 140,
          "line_linked": {
            "opacity": 1
          }
        },
        "bubble": {
          "distance": 400,
          "size": 40,
          "duration": 2,
          "opacity": 8,
          "speed": 3
        },
        "repulse": {
          "distance": 200,
          "duration": 0.4
        },
        "push": {
          "particles_nb": 4
        },
        "remove": {
          "particles_nb": 2
        }
      }
    },
    "retina_detect": true
  });

</script>
</body>
</html>
