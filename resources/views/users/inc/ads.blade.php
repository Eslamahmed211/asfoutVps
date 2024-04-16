@if (isset($ads[0]->id))
<div class="container-fluid overflow-hidden mt-0 p-0 rounded mb-3">


    <div id="carouselExampleControls" class="carousel slide m-auto w-100" data-bs-ride="carousel">

        <div class="carousel-inner">

            @foreach ($ads as $ads)
                @php
                    $img = str_replace('public', 'storage', $ads->img);
                @endphp

                <div class="carousel-item @if ($loop->index == 0) active @endif">
                    <a target="_blank" href="{{ $ads->link }}"> <img alt="{{ $ads->alt }}"
                            src="{{ asset("$img") }}" class="d-block w-100"></a>
                </div>
            @endforeach

        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>

    </div>

</div>
@endif
