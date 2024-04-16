@section('css')
    <style>
        form {
            display: flex;
            align-items: center;
            flex-direction: column;
        }

        input {
            background: white !important;
        }

        .excel {
            background-color: rgba(40, 199, 111, .1803921569);
            border-radius: 50%;
            width: 8rem;
            height: 8rem;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }

        .excel_title {
            width: 22.75rem;
            font-family: cairo;
            color: #6e6b7b;
            font-size: 1rem;
            line-height: 1.3125rem;
            text-align: center;
            margin-top: 15px;

        }

        @media(max-width:993px) {
            #search {

                width: 100% !important;
            }
        }

        #search {
            width: 25.9375rem;
            /* height: 2.4375rem; */
        }

        .export {
            background: rgb(34 197 94 /1);
            border-color: rgb(34 197 94 /1);
            margin-top: 15px;
        }
    </style>
@endsection
