@props(['model', 'type'])

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/jquery-ui.min.js"></script>


<script>
    function up(e) {

        var span = e.parentNode,

            td = span.parentNode;
        td = td.parentNode



        $(td).each(function() {
            $(td).insertBefore($(td).prev('tr'));
        });



    }

    function down(e) {

        var span = e.parentNode,

            td = span.parentNode;
        td = td.parentNode


        $(td).each(function() {
            $(td).insertAfter($(td).next('tr'));
        });


    }
</script>

<script>
    function UpdateOrder() {

        const promises = [];


        let ids = $('.ids')

        for (let i = 0; i < ids.length; i++) {

            let id = ids[i].value;


            @if (isset($type))
                const promise = fetch(`/{{ $type }}/{{ $model }}/changeOrder?id=${id}&order=${i}`);
            @else
                const promise = fetch(`/admin/{{ $model }}/changeOrder?id=${id}&order=${i}`);
            @endif
            promises.push(promise);

        }

        Promise.all(promises)
            .then((responses) => {
                Swal.fire({
                    title: 'تم',
                    text: "تم تغيير الترتيب بنجاح",
                    icon: 'success',
                    confirmButtonText: 'فهمت',
                    preConfirm: function() {
                        location.reload();
                    }
                }).then((result) => {
                    location.reload();
                });


            })

            .catch((err) => {
                console.error(err);
            });



    }
</script>

<script>
    var row;

    function start() {
        row = event.target;
    }

    function dragover() {
        var e = event;
        e.preventDefault();

        let children = Array.from(e.target.parentNode.parentNode.children);
        if (children.indexOf(e.target.parentNode) > children.indexOf(row))
            e.target.parentNode.after(row);
        else
            e.target.parentNode.before(row);
    }
</script>
