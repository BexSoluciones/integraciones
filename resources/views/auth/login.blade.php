<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="{!! asset('css/bootstrap.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('css/login/style.css') !!}">
  </head>
  <body>
    <script type="text/javascript" src="{!! asset('js/bootstrap.bundle.min.js') !!}"></script>

    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-md-2"></div>
        <div class="col-lg-6 col-md-8 login-box">
          <div class="col-lg-12 login-key">
            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" color="white" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 16 16">
              <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
            </svg>
          </div>
          <div class="col-lg-12 login-title">
            LOGIN INTEGRACIONES
          </div>

          <div class="col-lg-12 login-form">
            <div class="col-lg-12 login-form">
              <form id="loginForm">
                <div id="alert"></div>
                @csrf
                <div class="form-group">
                  <label class="form-control-label">USUARIO</label>
                  <div class="input-group">
                    <div class="input-group-text">
                      <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                      </svg>
                    </div>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Username">
                  </div>
                </div>
                <div class="form-group">
                  <label class="form-control-label">CONTRASEÑA</label>
                  <div class="input-group">
                    <div class="input-group-text">
                      <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/>
                      </svg>
                    </div>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                  </div>
                </div>

                <div class="col-lg-12 loginbttm">
                    <div class="col-lg-6 login-btm login-text"></div>
                    <div class="col-lg-6 login-btm login-button">
                        <button type="submit" class="btn btn-outline-primary">LOGIN</button>
                    </div>
                </div>
              </form>
            </div>
          </div>
          <div class="col-lg-3 col-md-2"></div>
        </div>
      </div>
    </div>

    <script type="text/javascript" src="{!! asset('js/jquery-3.6.0.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/login/form-login.js') !!}"></script>

  </body>
</html>