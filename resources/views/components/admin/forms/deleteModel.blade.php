@props(['model', 'id', 'withInput', 'withoutTitle', 'modelId' , 'type'])

<form method="post"
    @if (isset($type)) action="{{ url("$type/$model/destroy") }}" @else action="{{ url("admin/$model/destroy") }}" @endif
    class="modal fade" id="{{ $modelId ?? 'exampleModal' }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    @csrf
    @method('delete')
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">هل انت متاكد من ازالة ؟</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <input type="hidden" name="{{ $id }}">


            @if (!@isset($withoutTitle))

                <div class="modal-body">

                    <div id="model_title"> </div>


                    @if (@isset($withInput))
                        <input type="text" name="delete" id="delete" placeholder="اكتب هنا ازالة"
                            class="mt-3 w-100">
                    @endif



                </div>
            @endif

            <div class="modal-footer">
                <button class="btn btn-danger">ازالة</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
            </div>
        </div>
    </div>
</form>
