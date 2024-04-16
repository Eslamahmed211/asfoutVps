@props(['title', 'action'])

<form action="{{ $action }}" method="post" class="mt-3">

    @csrf
    <div class="excel">
        <svg data-v-56adc236="" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 88.795 87.175">
            <path data-v-56adc236="" id="Icon_simple-microsoftexcel" data-name="Icon simple-microsoftexcel"
                d="M87.141,11H57.395V16.5h8.769V25.21H57.395v2.775h8.769V36.7H57.395v2.853h8.769V47.8H57.395v3.3h8.769v8.265H57.395v3.3h8.769v8.313H57.395v6.064H87.141c.47-.141.862-.7,1.177-1.658a8.113,8.113,0,0,0,.477-2.353V12c0-.474-.163-.758-.477-.858A4.07,4.07,0,0,0,87.141,11ZM83.282,70.983H69.012V62.677h14.27v8.306Zm0-11.61H69.012V51.1h14.27Zm0-11.573H69.012v-8.21h14.27V47.8Zm0-11.1H69.012V27.992h14.27V36.7Zm0-11.54H69.012V16.5h14.27v8.658ZM0,9.622v68.82L52.389,87.5V.328L0,9.652Zm31.052,52.06q-.3-.81-2.8-6.893c-1.661-4.055-2.664-6.415-2.956-7.089H25.2l-5.62,13.375-7.511-.507,8.909-16.649L12.82,27.27l7.659-.4L25.54,39.894h.1l5.716-13.619,7.914-.5L29.846,43.794l9.712,18.381-8.506-.5Z"
                transform="translate(0 -0.328)" fill="#21b463"></path>
        </svg>

    </div>

    <p class="excel_title">{{ $title }}</p>


    <div id="search">
        {{ $slot }}
    </div>

    <div class="excel_actions">
        <div>
            <button class="export mainBtn" type="submit"><svg class="mx-1" data-v-56adc236=""
                    xmlns="http://www.w3.org/2000/svg" width="12.221" height="16.295" viewBox="0 0 12.221 16.295"
                    class="mr-2">
                    <path data-v-56adc236="" id="Icon_awesome-file-download" data-name="Icon awesome-file-download"
                        d="M7.129,4.328V0H.764A.762.762,0,0,0,0,.764V15.531a.762.762,0,0,0,.764.764H11.457a.762.762,0,0,0,.764-.764V5.092H7.893A.766.766,0,0,1,7.129,4.328Zm2.433,6.727L6.493,14.1a.543.543,0,0,1-.765,0L2.66,11.055a.509.509,0,0,1,.358-.871H5.092V7.638A.509.509,0,0,1,5.6,7.129H6.62a.509.509,0,0,1,.509.509v2.546H9.2A.509.509,0,0,1,9.562,11.055ZM12,3.342,8.883.223A.763.763,0,0,0,8.342,0H8.147V4.074h4.074V3.88A.761.761,0,0,0,12,3.342Z"
                        fill="#fff"></path>
                </svg> تحميل ملف اكسيل </button>

        </div>
    </div>
</form>
