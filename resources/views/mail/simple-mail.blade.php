<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$title}}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet">
    <!-- Styles -->
    <style>
        html, body {
            font-family: 'Roboto', sans-serif;
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
            padding: 24px 24px 24px 44px;
        }

        .message-card-content-logo {
            text-align: right;
            height: 55px;
            -webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
            -moz-box-sizing: border-box; /* Firefox, other Gecko */
            box-sizing: border-box; /* Opera/IE 8+ */
            position: relative;
        }

        .message-card-content-logo img {
            max-width: 180px;
            max-height: 100%;
        }

        h1 {
            color: #4A4A4A !important;
            font-size: 20px;
            font-weight: 500;
            margin-top: 0.2rem;
            margin-bottom: 2.5rem;
        }

        .subtitle {
            font-size: 16px;
            margin-bottom: 2.2rem;
            color: #4A4A4A;
        }

        .normal-text {
            margin: 1.5rem 0;
            color: #4A4A4A !important;
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
        }

        #powered-by {
            margin: 0 auto;
            position: relative;
            height: 55px;
            display: flex;
            overflow: hidden;
            width: 400px;
        }

        #powered-by img {
            max-height: 100%;
        }

        .border-div {
            font-size: 12px;
            border-bottom-width: 1px;
            border-bottom-style: solid;
            margin: 0 15px;
            width: 200px;
            height: 27px;
            border-color: rgba(0, 0, 0, 0.12) !important;
            position: relative;
            display: inline-block;
        }

        .border-div span {
            color: rgba(0, 0, 0, 0.54) !important;
            position: absolute;
            right: 0;
        }

        #social-container {
            margin-top: 2rem;
            width: 70%;
            position: relative;
            margin-left: 15%;
            color: white;
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

        #social-unsubscribe {
            width: 100%;
            text-align: center;
            margin-top: 2rem;
        }

        #social-unsubscribe p {
            margin: 0 0 .4rem 0;
        }

        #social-unsubscribe p a {
            color: inherit;
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
            border: none;
            -webkit-tap-highlight-color: transparent;
            display: inline-block;
            white-space: nowrap;
            text-decoration: none;
            vertical-align: baseline;
            text-align: center;
            margin: 2.7rem auto;
            min-width: 88px;
            line-height: 36px;
            height: 36px;
            padding: 0 16px;
            border-radius: 2px;
            overflow: visible;
            transform: translate3d(0, 0, 0);
            transition: background .4s cubic-bezier(.25, .8, .25, 1), box-shadow 280ms cubic-bezier(.4, 0, .2, 1);
            color: white !important;
            font-size: 14px;
            font-weight: 500;
        }

        @media screen and (max-width: 960px) {
            .message-card {
                width: 80%;
                margin-left: 10%;
            }
        }

        @media screen and (max-width: 600px) {
            .message-card {
                width: 94%;
                margin-left: 3%;
            }
        }
    </style>
</head>
<body>
<div class="content" style="background-image: url({{ URL::asset('images/bg.png') }})">
    <div class="email-content">
        <div class="message-card">
            <div class="message-card-content">
                <div class="message-card-content-logo">
                    @if (isset($logoUrl) && $logoUrl !== '')
                        <img src="{{$logoUrl}}" alt="AppLogo">
                    @else
                        <img src="{{asset('images/logo.png')}}" alt="AppLogo">
                    @endif
                </div>
                <h1>{{$title}}</h1>
                <p class="subtitle">{{$subtitle}}</p>
                @foreach($text as $item)
                    <p class="normal-text">{{$item}}</p>
                @endforeach

                @if(isset($url, $urlAction) && $url !== '' && $urlAction !== '')
                    <p class="normal-text">
                        <a class="email-button" style="background-color:{{$themeColor}};" href="{{$url}}">
                            <span>{{$urlAction}}</span>
                        </a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
</body>
</html>
