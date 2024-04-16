<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>404</title>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/adminstyle.css?ver=2.1') }}">

    <Style>
        body {
            --tw-text-opacity: 1;
            color: var(--mainColor);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
        }

        .error-code {
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            font-size: 8rem;
            line-height: 1;
            font-weight: 800;
        }

        .error-container {
            margin: auto;
            margin-top: 2rem;
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding-left: 1.25rem;
            padding-right: 1.25rem;
        }

        .error-icon {
            height: 9rem;
            width: 9rem;
        }

        .error-title {
            font-size: 2rem;
            line-height: 2rem;
            font-weight: 600;
            font-family: Alexandria, sans-serif;
            color: black;
            text-align: center
        }

        .error-description {
            margin-top: 1rem;
            margin-bottom: 2rem;
            color: black
        }

        .xbutton {
            font-weight: 700;
            padding: 0.5rem 1rem;

            font-size: 14px;
        }

        @media (max-width:768px) {
            .error-code {

                font-size: 5rem;

            }

            .error-icon {
                width: 7rem;
                height: 7rem;
            }

            .error-title {
                font-size: 1.5rem
            }
        }
    </Style>
</head>

<body>
    <div class='error-container max-w-md text-center'>
        <h2 class="error-code">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="error-icon">
                <path fill="currentColor"
                    d="M256,16C123.452,16,16,123.452,16,256S123.452,496,256,496,496,388.548,496,256,388.548,16,256,16ZM403.078,403.078a207.253,207.253,0,1,1,44.589-66.125A207.332,207.332,0,0,1,403.078,403.078Z">
                </path>
                <rect width="176" height="32" x="168" y="320" fill="currentColor"></rect>
                <polygon fill="currentColor"
                    points="210.63 228.042 186.588 206.671 207.958 182.63 184.042 161.37 162.671 185.412 138.63 164.042 117.37 187.958 141.412 209.329 120.042 233.37 143.958 254.63 165.329 230.588 189.37 251.958 210.63 228.042">
                </polygon>
                <polygon fill="currentColor"
                    points="383.958 182.63 360.042 161.37 338.671 185.412 314.63 164.042 293.37 187.958 317.412 209.329 296.042 233.37 319.958 254.63 341.329 230.588 365.37 251.958 386.63 228.042 362.588 206.671 383.958 182.63">
                </polygon>
            </svg>
            404
        </h2>

        <p class="error-title">عذرا، هذه الصفحة غير موجودة</p>

        <p class="error-description">جرب استكشاف صفحات أخرى.</p>

        <div style="display: flex;gap:20px">

            <x-link title="الصفحة الرئيسية " path="/home"></x-link>
            <x-link title="رجوع " path="{{ URL::previous() }}"></x-link>

        </div>
    </div>
</body>

</html>
