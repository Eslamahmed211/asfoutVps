<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>500</title>
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
            <svg class="error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">  <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>  <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>  <line x1="6" y1="6" x2="6.01" y2="6"></line>  <line x1="6" y1="18" x2="6.01" y2="18"></line></svg>
            500
        </h2>

        <p class="error-title"> خطأ بالخادم </p>

        <p class="error-description">   هناك خطأ ما اثناء تشغيل تلك الصفحة اذا تقرر الامر يرجي التواصل معنا  </p>

        <div style="display: flex;gap:20px">

            <x-link title="الصفحة الرئيسية " path="/home"></x-link>
            <x-link title="رجوع " path="{{ URL::previous() }}"></x-link>

        </div>
    </div>
</body>

</html>
