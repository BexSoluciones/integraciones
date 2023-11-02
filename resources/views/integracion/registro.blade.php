<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Registro</title>
    @include('layout.head')
  </head>
  <body>
    <style>
      /*form styles*/
      #msform {
        width: 400px;
        margin: 50px auto;
        text-align: center;
        position: relative;
      }
      #msform fieldset {
        background: white;
        border: 0 none;
        border-radius: 3px;
        box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
        padding: 20px 30px;
        box-sizing: border-box;
        width: 80%;
        margin: 0 10%;

        /*stacking fieldsets above each other*/
        position: relative;
      }
      /*Hide all except first fieldset*/
      #msform fieldset:not(:first-of-type) {
        display: none;
      }
      /*inputs*/
      #msform input,
      #msform textarea {
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-bottom: 10px;
        width: 100%;
        box-sizing: border-box;
        font-family: sans-serif;
        color: #000;
        font-size: 17px;
      }
      /*buttons*/
      #msform .action-button {
        width: 100px;
        background: #27ae60;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 1px;
        cursor: pointer;
        padding: 10px;
        margin: 10px 5px;
        text-decoration: none;
        font-size: 14px;
      }
      #msform .action-button:hover,
      #msform .action-button:focus {
        box-shadow: 0 0 0 2px white, 0 0 0 3px #27ae60;
      }
      /*headings*/
      .fs-title {
        font-size: 15px;
        text-transform: uppercase;
        color: #2c3e50;
        margin-bottom: 10px;
      }
      .fs-subtitle {
        font-weight: normal;
        font-size: 13px;
        color: #666;
        margin-bottom: 20px;
      }
      /*progressbar*/
      #progressbar {
        margin-bottom: 0px;
        overflow: hidden;
        /*CSS counters to number the steps*/
        counter-reset: step;
      }
      #progressbar li {
        list-style-type: none;
        color: black;
        text-transform: uppercase;
        font-size: 9px;
        width: 33.33%;
        float: left;
        position: relative;
      }
      #progressbar li:before {
        content: counter(step);
        counter-increment: step;
        width: 40px;
        line-height: 40px;
        display: block;
        font-size: 20px;
        color: #333;
        background: white;
        border-radius: 3px;
        margin: 0 auto 5px auto;
      }
      /*progressbar connectors*/
      #progressbar li:after {
        content: "";
        width: 100%;
        height: 2px;
        background: white;
        position: absolute;
        left: -50%;
        top: 9px;
        z-index: -1; /*put it behind the numbers*/
      }
      #progressbar li:first-child:after {
        /*connector not needed before the first step*/
        content: none;
      }
      /*marking active/completed steps green*/
      /*The number of the step and the connector before it = green*/
      #progressbar li.active:before,
      #progressbar li.active:after {
        background: #27ae60;
        color: white;
      }
    </style>

    <div class="preloader">
      <div class="loading-mask"></div>
      <div class="loading-mask"></div>
      <div class="loading-mask"></div>
      <div class="loading-mask"></div>
      <div class="loading-mask"></div>
    </div>
   
    <main class="site-wrapper">
      <div class="pt-table">
        <div class="pt-tablecell page-contact relative">
          <!-- .close -->
          <a href="{{ url('/panel') }}" class="page-close"><i class="tf-ion-close"></i></a>
          <!-- /.close -->

          <div class="container">
            <div class="row">
              <div class="col-xs-12 col-md-offset-1 col-md-10 col-lg-offset-2 col-lg-8">
                <div class="page-title text-center">
                  <h3>MONTAR <span class="primary">INTEGRACION</span> <span class="title-bg">BEX</span></h3>
                </div>
      


                <!-- multistep form -->
                <form id="msform">
                @csrf
                  <!-- progressbar -->
                  <ul id="progressbar">
                    <li class="active">Seleccionar DB</li>
                    <li>Alias DB</li>
                    <li>Migracion</li>
                  </ul>
                  <!-- fieldsets -->
                  <fieldset>
                    <h2 class="fs-title">EXTRAER ESTRUCTURA</h2>
                    <h3 class="fs-subtitle">Selecciona la Base de Datos la cual extraeras su estructura</h3>
                    <select id="nameDB" class="form-select form-select-lg mb-3" aria-label="Large select example">
                      <option selected disabled>SELECCIONA UNA DB</option>
                      @foreach($databases as $db)
                        <option value="{!! $db !!}">{!! $db !!}</option>
                      @endforeach
                    </select>
                    <input type="button" name="next" class="next action-button" value="Siguiente" />
                  </fieldset>
                  <fieldset>
                    <h2 class="fs-title">Alias Base de Datos</h2>
                    <input type="text" id="aliasDB" name="aliasDB" placeholder="Alias DB" />
                    <input type="button" name="previous" class="previous action-button" value="Anterior" />
                    <input type="button" id="extraer" name="extraer" class="next action-button" value="Extraer" />
                  </fieldset>
                  <fieldset>
                    <h2 class="fs-title">Extraer Estructura</h2>
                    <h3 class="fs-subtitle">Selecciona la Base de Datos la cual extraeras su estructura</h3>
                    <a class="submit action-button" target="_top">Extraer</a>
                  </fieldset>
                </form>




              </div>
            </div>
          </div>
        </div>
      </div>
      <footer>
        @include('layout.footer')
        <p class="text-center">BEX SOLUCIONES</p>
      </footer>
    </main>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{!! asset('js/assembly-integration/form-registerDB.js') !!}"></script>

    <script>
       //jQuery time
      var current_fs, next_fs, previous_fs; //fieldsets
      var left, opacity, scale; //fieldset properties which we will animate
      var animating; //flag to prevent quick multi-click glitches

      $(".next").click(function () {
        if (animating) return false;
        animating = true;

        current_fs = $(this).parent();
        next_fs = $(this).parent().next();

        //activate next step on progressbar using the index of next_fs
        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate(
          { opacity: 0 },
          {
            step: function (now, mx) {
              //as the opacity of current_fs reduces to 0 - stored in "now"
              //1. scale current_fs down to 80%
              scale = 1 - (1 - now) * 0.2;
              //2. bring next_fs from the right(50%)
              left = now * 50 + "%";
              //3. increase opacity of next_fs to 1 as it moves in
              opacity = 1 - now;
              current_fs.css({
                transform: "scale(" + scale + ")",
                position: "inherit"
              });
              next_fs.css({ left: left, opacity: opacity });
            },
            duration: 800,
            complete: function () {
              current_fs.hide();
              animating = false;
            },
            //this comes from the custom easing plugin
            easing: "easeInOutBack"
          }
        );
      });

      $(".previous").click(function () {
        if (animating) return false;
        animating = true;

        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        //de-activate current step on progressbar
        $("#progressbar li")
          .eq($("fieldset").index(current_fs))
          .removeClass("active");

        //show the previous fieldset
        previous_fs.show();
        //hide the current fieldset with style
        current_fs.animate(
          { opacity: 0 },
          {
            step: function (now, mx) {
              //as the opacity of current_fs reduces to 0 - stored in "now"
              //1. scale previous_fs from 80% to 100%
              scale = 0.8 + (1 - now) * 0.2;
              //2. take current_fs to the right(50%) - from 0%
              left = (1 - now) * 50 + "%";
              //3. increase opacity of previous_fs to 1 as it moves in
              opacity = 1 - now;
              current_fs.css({ left: left });
              previous_fs.css({
                transform: "scale(" + scale + ")",
                opacity: opacity
              });
            },
            duration: 800,
            complete: function () {
              current_fs.hide();
              animating = false;
            },
            //this comes from the custom easing plugin
            easing: "swing"
          }
        );
      });

      var guardarButton = document.querySelector('input[name="extraer"]');
      guardarButton.addEventListener('click', function() {
        //Funcion extraida de js/assembly-integration/form-registroDB.js
        registerAssembly();
      });

    </script>
  </body>
</html>
