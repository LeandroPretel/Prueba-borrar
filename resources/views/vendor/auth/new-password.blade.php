<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva contraseña</title>
    <!-- Fonts -->
    <link rel="icon" type="image/x-icon" href="{{asset('images/icon-hires.png')}}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i"
          rel="stylesheet">
    <!-- Styles -->
    <style>
        html, body {
            font-family: 'OpenSans', sans-serif;
            width: 100%;
            height: 100%;
            margin: 0;
            font-size: 14px;
            min-height: 800px;
        }

        .content {
            position: relative;
            height: 100%;
            background-color: #fafafa;
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 800px;
        }

        .email-content {
            position: relative;
            padding-top: 80px;
            min-height: 580px;
        }

        .message-card {
            position: relative;
            z-index: 2;
            width: 50%;
            background-color: white !important;
            margin-left: 25%;
            border: 1px solid rgba(0, 0, 0, 0.12);
            /*   box-shadow: 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
            -webkit-box-shadow: 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
            -moz-box-shadow: 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12); */
            min-height: 400px;
            margin-bottom: 2.8rem;
        }

        .message-card-content {
            position: relative;
            width: 100%;
            height: 100%;
            -webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
            -moz-box-sizing: border-box; /* Firefox, other Gecko */
            box-sizing: border-box; /* Opera/IE 8+ */
            padding: 24px;
        }

        .message-card-content-logo {
            text-align: right;
            height: 55px;
            -webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
            -moz-box-sizing: border-box; /* Firefox, other Gecko */
            box-sizing: border-box; /* Opera/IE 8+ */
            position: relative;
        }

        #message-sent-img-container {
            z-index: 100;
            margin-left: 15%;
            position: relative;
            overflow: visible;
            max-height: 0;
            max-width: 0;
        }

        #message-sent-img {
            width: 280px;
            height: 160px;
            max-width: 280px;
            max-height: 160px;
            margin-top: 12px;
        }

        .message-card-content-logo img {
            max-width: 180px;
            max-height: 100%;
        }

        h1 {
            color: #4A4A4A !important;
            font-size: 18px;
            font-weight: 700;
            margin-top: 1rem;
            margin-bottom: 3.5rem;
            text-align: center;
        }

        .subtitle {
            font-size: 16px;
            margin-bottom: 2.2rem;
            color: #4A4A4A;
            text-align: center;
        }

        .normal-text {
            margin: 1.5rem 0;
            color: #4A4A4A !important;
            text-align: center;
            padding: 0 24px;
            line-height: 24px;
        }

        .help-text-container {
            margin-top: 1.4rem;
        }

        .help-text {
            font-size: 12px;
            text-align: center;
            margin-top: 0;
            color: #4A4A4A !important;
        }

        .help-link {
            text-decoration: none;
            color: #cb2b99;
        }

        #social-container {
            margin-top: 2rem;
            width: 70%;
            position: relative;
            margin-left: 15%;
            color: #4D0E39;
        }

        #social-icons-container {
            width: 100%;
            margin: 2rem auto;
            text-align: center;
        }

        #social-icons-container img {
            margin: 0 15px;
            width: 24px;
            height: 24px;
        }

        #social-copyright {
            width: 100%;
            text-align: center;
        }

        .email-button {
            box-sizing: border-box;
            position: relative;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            cursor: pointer;
            outline: 0;
            border: 1px solid #CB2B99;
            -webkit-tap-highlight-color: transparent;
            display: inline-block;
            white-space: nowrap;
            text-decoration: none;
            vertical-align: baseline;
            text-align: center;
            margin: 2.5rem auto;
            min-width: 88px;
            line-height: 36px;
            padding: 0 20px;
            border-radius: 2px;
            overflow: visible;
            transform: translate3d(0, 0, 0);
            transition: background .4s cubic-bezier(.25, .8, .25, 1), box-shadow 280ms cubic-bezier(.4, 0, .2, 1);
            background-color: rgba(203, 43, 153, 0.1);
            font-size: 14px;
            font-weight: 500;
        }

        .email-button span {
            opacity: 1;
            color: #CB2B99 !important;
        }

        #thanks-p {
            margin: 2rem auto 4.5rem auto;
            text-align: center;
            font-weight: 600;
            color: #4A4A4A !important
        }

        @media screen and (max-width: 960px) {
            .message-card {
                width: 80%;
                margin-left: 10%;
            }

            #message-sent-img-container {
                margin-left: 5%;
            }

            #message-sent-img {
                width: 200px;
                height: 140px;
                max-width: 200px;
                max-height: 140px;
            }

            .normal-text {
                padding: 0;
            }
        }

        @media screen and (max-width: 600px) {
            .message-card {
                width: 94%;
                margin-left: 3%;
            }

            #message-sent-img-container {
                margin-left: 24px;
            }

            #message-sent-img {
                width: auto;
                max-height: 55px;
                margin-top: 24px;
            }
        }
    </style>
</head>
<body>
<div class="content" style="background-image: url({{ URL::asset('images/bg.png') }})">
    <div class="email-content">
        <div id="message-sent-img-container">
            <img id="message-sent-img" src="{{asset('/images/fogg-message-sent.png')}}" alt="message-sent-image">
        </div>
        <div class="message-card">
            <div class="message-card-content">
                <div class="message-card-content-logo">
                    @if (isset($logoUrl) && $logoUrl !== '')
                        <img src=" {{$logoUrl}}" alt="AppLogo">
                    @else
                        <img src="{{asset('/images/logo.png')}}" alt="AppLogo">
                    @endif
                </div>
                <h1>{{$title}}</h1>
                <p class="subtitle">{{$subtitle}}</p>
                @foreach($text as $item)
                    <p class="normal-text">{{$item}}</p>
                @endforeach

                <p class="normal-text">
                    <a class="email-button" href="{{$url}}">
                        <span>ESTABLECER CONTRASEÑA</span>
                    </a>
                </p>

                <p id="thanks-p">Gracias por confiar en Redentradas</p>

                <div class="help-text-container">
                    @if(isset($helpEmail) || isset($helpWeb))
                        <p class="help-text">
                            Si tienes cualquier duda puedes ponerte en contacto con nosotros en
                        </p>
                        <p class="help-text">
                            @if(isset($helpEmail) && $helpEmail !== '')
                                <a class="help-link" href="mailto:{{$helpEmail}}">{{$helpEmail}}</a>
                                @if($helpWeb)
                                    o visitar nuestra página
                                @endif
                            @endif
                            @if(isset($helpWeb) && $helpWeb !== '')
                                <a class="help-link" href="{{$helpWeb}}">{{$helpWeb}}</a>
                            @endif
                        </p>
                    @endif
                </div>
            </div>
        </div>
        <div id="social-container">
            <div id="social-icons-container">
                <a target="_blank" href="https://www.facebook.com/redentradas">
                    <img src="{{asset('/images/facebook.png')}}" alt="Facebook">
                </a>
                <a target="_blank" href="https://twitter.com/redentradas">
                    <img src="{{asset('/images/twitter.png')}}" alt="Twitter">
                </a>
            </div>
            <div id="social-copyright">
                @redentradas {{ now()->year }}. All Rights Reserved
            </div>
        </div>
    </div>
</div>
</body>
</html>
