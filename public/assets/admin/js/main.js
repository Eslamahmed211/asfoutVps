let links = document.getElementById("aside");

try {
    tinymce.init({
        selector: "textarea.tiny",
        table_grid: false,
        resize_img_proportional: false,

        image_advtab: true,

        image_description: false,

        height: 500,
        directionality: "rtl",

        language: "en",

        grid_preset: "Bootstrap5",
        mobile: {
            menubar: true,
        },

        fontsize_formats: "8px 10px 12px 14px 16px 18px 20px 24px 36px",

        plugins:
            "preview  grid code  importcss  searchreplace autolink autosave save directionality  visualblocks visualchars fullscreen image link media  template codesample table charmap pagebreak nonbreaking anchor  insertdatetime advlist lists  wordcount   help   charmap    emoticons ",
        toolbar1:
            " undo redo grid_insert  | fontsizeselect |code  fullscreen bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | forecolor backcolor emoticons | preview",
        file_picker_callback(callback, value, meta) {
            let x =
                window.innerWidth ||
                document.documentElement.clientWidth ||
                document.getElementsByTagName("body")[0].clientWidth;
            let y =
                window.innerHeight ||
                document.documentElement.clientHeight ||
                document.getElementsByTagName("body")[0].clientHeight;
            tinymce.activeEditor.windowManager.openUrl({
                url: "/file-manager/tinymce5",
                title: "Laravel File manager",
                width: x * 0.8,
                height: y * 0.8,
                onMessage: (api, message) => {
                    callback(message.content, { text: message.text });
                },
            });
        },
    });
} catch (error) {}

$("#menu").click(function () {
    $(".layout").addClass("layout-show");
    links.classList.add("ul-show");
});

$(".layout").click(function () {
    links.classList.remove("ul-show");
    $("#search").removeClass("searchShow");
    $(".layout").removeClass("d-block");

    $(".layout").removeClass("layout-show");
});

function changeColor(colorName) {
    let color;
    let xbuttonBorder;
    let xbuttonBackground;

    if (colorName == "عنبري") {
        color = "rgb(245 158 11 /1)";
        xbuttonBorder = "rgb(180 83 9 /1)";
        xbuttonBackground = "rgb(245 158 11 /1)";
    } else if (colorName == "أزرق") {
        color = "rgb(59 130 246 /1)";
        xbuttonBorder = "rgb(29 78 216  /1)";
        xbuttonBackground = "rgb(59 130 246 /1)";
    } else if (colorName == "بنفسجي") {
        color = "rgb(79, 70, 156)";
        xbuttonBorder = "#2e238d";
        xbuttonBackground = "#4F469C";
    } else if (colorName == "سماوي مفتح") {
        color = "rgb(14 116 144 /1)";
        xbuttonBorder = "rgb(14 116 144 /1)";
        xbuttonBackground = "rgb(6 182 212 /1)";
    } else if (colorName == "زمردي") {
        color = "rgb(16 185 129 /1)";
        xbuttonBorder = "rgb(4 120 87  /1)";
        xbuttonBackground = "rgb(16 185 129 /1)";
    } else if (colorName == "رمادي") {
        color = "rgb(107 114 128/1)";
        xbuttonBorder = "rgb(55 65 81   /1)";
        xbuttonBackground = "rgb(107 114 128/1)";
    } else if (colorName == "زهري") {
        color = "rgb(236 72 153 /1)";
        xbuttonBorder = "rgb(190 24 93 /1)";
        xbuttonBackground = "rgb(236 72 153/1)";
    } else if (colorName == "أرجواني") {
        color = "rgb(168 85 247 /1)";
        xbuttonBorder = "rgb(126 34 206  /1)";
        xbuttonBackground = "rgb(168 85 247/1)";
    } else if (colorName == "احمر") {
        color = "rgb(239 68 68 /1)";
        xbuttonBorder = "rgb(185 28 28 /1)";
        xbuttonBackground = "rgb(239 68 68 /1)";
    } else if (colorName == "أخضر") {
        color = "rgb(34 197 94 /1)";
        xbuttonBorder = "rgb(21 128 61 /1)";
        xbuttonBackground = "rgb(34 197 94 /1)";
    } else if (colorName == "ليمي") {
        color = "rgb(132 204 22 /1)";
        xbuttonBorder = "rgb(77 124 15 /1)";
        xbuttonBackground = "rgb(132 204 22 /1)";
    } else if (colorName == "برتقالي") {
        color = "rgb(249 115 22 /1)";
        xbuttonBorder = "rgb(194 65 12/1)";
        xbuttonBackground = "rgb(249 115 22/1)";
    } else if (colorName == "سماوي") {
        color = "rgb(14 165 233 /1)";
        xbuttonBorder = "rgb(3 105 161/1)";
        xbuttonBackground = "rgb(14 165 233/1)";
    } else if (colorName == "شرشيري") {
        color = "rgb(20 184 166 /1)";
        xbuttonBorder = "rgb(15 118 110/1)";
        xbuttonBackground = "rgb(20 184 166/1)";
    } else if (colorName == "أصفر") {
        color = "rgb(234 179 8 /1)";
        xbuttonBorder = "rgb(161 98 7/1)";
        xbuttonBackground = "rgb(234 179 8 /1)";
    }

    let rootStyles = document.documentElement.style;
    rootStyles.setProperty("--mainColor", `${color}`);
    localStorage.setItem("color", `${color}`);

    rootStyles.setProperty("--xbutton-border", `${xbuttonBorder}`);
    localStorage.setItem("xbuttonBorder", `${xbuttonBorder}`);

    rootStyles.setProperty("--xbutton-background", `${xbuttonBackground}`);
    localStorage.setItem("xbuttonBackground", `${xbuttonBackground}`);
}


function toggleColors() {
    let colors = document.getElementById("colors");
    colors.classList.toggle("colorsShow");
}

function toggleColors() {
    let colors = document.getElementById("colors");
    colors.classList.toggle("colorsShow");
}

const toolbarOptions = [
    [
        {
            header: 1,
        },
        {
            header: 2,
        },
    ],
    ["bold", "italic", "underline", "strike", "clean"],
    [
        {
            align: "",
        },
        {
            align: "center",
        },
        {
            align: "right",
        },
        {
            align: "justify",
        },
    ],
    [
        {
            list: "ordered",
        },
        {
            list: "bullet",
        },
    ],
    ["link"],
    [
        {
            color: [],
        },
        {
            background: [],
        },
    ],
];

let quills = document.getElementsByClassName("quill");

for (let i = 0; i < quills.length; i++) {
    var quill = new Quill(quills[i], {
        theme: "snow",

        modules: {
            toolbar: toolbarOptions,
        },
        formats: [
            "bold",
            "align",
            "italic",
            "underline",
            "strike",
            "header",
            "link",
            "list",
            "color",
            "background",
        ],
    });

    quill.format("font", "cairo");
    quill.format("align", "right");
}

function validate() {
    let inputs = document.getElementsByClassName("checkThis");

    for (const input of inputs) {
        if ($(input).val() == "") {
            const instance = tippy(input);

            instance.setContent("الحقل ده مطلوب");

            $([document.documentElement, document.body]).animate(
                {
                    scrollTop: $(input).offset().top - 120,
                },
                0
            );

            instance.show();

            setTimeout(() => {
                instance.destroy();
            }, 3000);

            return;
        }
    }

    if ($("#hiddenArea")) {
        let value = $(".quill-container .ql-editor").html();
        $("#hiddenArea").val(value);
    }

    document.getElementById("theForm").submit();
}

window.onscroll = function () {
    localStorage.setItem("scrollPosition", window.scrollY);
};

// استرجاع وضع الإسكرول والتمرير إلى المكان المحفوظ
window.addEventListener("DOMContentLoaded", function () {
    if ($(".pagnate")) {
        return;
    }

    const scrollPosition = localStorage.getItem("scrollPosition");
    if (scrollPosition !== null) {
        window.scrollTo(0, scrollPosition);
        localStorage.removeItem("scrollPosition"); // حذف القيمة بمجرد استخدامها
    }
});

function addFav(event, element, id) {
    event.preventDefault();
    $.ajax({
        url: `/users/add_fav/${id}`,
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (response.status == "success") {
                try {
                    element.outerHTML = `<button onclick="delete_fav(event ,  this , ${id})" class="mainBtn d-flex favBtn w-50" style="border: none">المفضلة

                    <div class="svg">

                                                                    <svg class="heart" style="width:20px;height:20px ;" viewBox="0 0 24 24">
                                <path fill="#ff4966" d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z">
                                </path>
                            </svg>


                    </div>
                </button>`;

                    $("#NotificationCount").text(response.favCount);
                } catch (error) {}
            }
        },
    });
}

function delete_fav(event, element, id) {
    event.preventDefault();

    $.ajax({
        url: `/users/delete_fav/${id}`,
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (response.status == "success") {
                try {
                    element.outerHTML = `<button onclick="addFav(  event ,  this ,  ${id})" class="mainBtn d-flex favBtn w-50" style="border: none">المفضلة

                    <div class="svg">

                                                                    <svg class="heart opacity-75" style="width:20px;height:20px ;" viewBox="0 0 24 24">
                                <path fill="#aaa" d="M12.1 18.55L12 18.65L11.89 18.55C7.14 14.24 4 11.39 4 8.5C4 6.5 5.5 5 7.5 5C9.04 5 10.54 6 11.07 7.36H12.93C13.46 6 14.96 5 16.5 5C18.5 5 20 6.5 20 8.5C20 11.39 16.86 14.24 12.1 18.55M16.5 3C14.76 3 13.09 3.81 12 5.08C10.91 3.81 9.24 3 7.5 3C4.42 3 2 5.41 2 8.5C2 12.27 5.4 15.36 10.55 20.03L12 21.35L13.45 20.03C18.6 15.36 22 12.27 22 8.5C22 5.41 19.58 3 16.5 3Z">
                                </path>
                            </svg>



                    </div>
                </button>`;

                    $("#NotificationCount").text(response.favCount);
                } catch (error) {}
            }
        },
    });
}

function read(e, notificationId , type = "users") {
    let link = $(e).find("a").attr("href");

    $.ajax({
        type: "get",
        url: `/${type}/mark-notification-as-read/` + notificationId, // Replace with your actual route.

        success: function (response) {
            window.location.href = `${link}`;
        },
        error: function (xhr) {
            console.error("Error marking notification as read");
        },
    });
}

function read2(e, notificationId , type = "users") {
    let link = $(e).attr("data-link");

    $.ajax({
        type: "get",
        url: `/${type}/mark-notification-as-read/` + notificationId, // Replace with your actual route.

        success: function (response) {
            window.location.href = `${link}`;
        },
        error: function (xhr) {
            console.error("Error marking notification as read");
        },
    });
}


function show_search_filters() {
    let search = document.getElementById("search");
    $(".layout").addClass("layout-show");
    $(".layout").addClass("d-block");
    search.classList.add("searchShow");
}

$("#searchBtnClose").click(function () {
    $("#search").removeClass("searchShow");
    $(".layout").removeClass("layout-show");
    $(".layout").removeClass("d-block");
});

try {
  tippy("[data-tippy-content]");

  
    document.getElementById("resetBtn").addEventListener("click", function () {
        var originalURL = window.location.href.split("search")[0];

        history.replaceState({}, document.title, originalURL);

        window.location.href = originalURL;
    });
} catch (error) {}

function GetOrderDetails(id, type = "users") {
    $.ajax({
        url: `/${type}/orders/GetOrderDetailsAjax/${id}`,
        type: "GET",
        dataType: "json",

        beforeSend() {
            $("#loader").addClass("d-flex");
            $("#loader").removeClass("d-none");
        },

        success: function (response) {
            $("#loader").addClass("d-none");
            $("#loader").removeClass("d-flex");

            if (response.status == "success") {
                let data = response.data;

                $(".frameContent #reference").text(data.reference);
                $(".frameContent #name").text(data.name);
                $(".frameContent #mobile").text(data.phone);
                $(".frameContent #city").text(data.city);

                if (
                    data.status == "تم التوصيل شحن يدوي معلق" ||
                    data.status == "فشل التوصيل يدوي معلق" ||
                    data.status == "مؤجل تسليمها شحن يدوي"
                ) {
                    $(".frameContent #status").text("ارسال شحن يدوي");
                } else {
                    $(".frameContent #status").text(data.status);
                }

                $(".frameContent #status").removeClass();
                $(".frameContent #status")
                    .addClass("orderStatus")
                    .addClass(data.class);

                let cartona = ``;

                for (const detail of data.details) {
                    cartona += `
                      <tr>
                      <td><img class="prodcut_img"
                              src="${detail.img}"
                              alt="${detail.discription}"></td>

                      <td> ${detail.discription} </td>
                      <td>${detail.qnt}</td>
                      <td>${detail.total}</td>
                      <td>${detail.comissation}</td>
                  </tr>`;

                    $("#frameTable").html(cartona);
                }

                $("#frame").removeClass();
                $("#frame").addClass("frame").addClass("d-flex");
            }
        },
        error: function () {
            $("#loader").addClass("d-none");
            $("#loader").removeClass("d-flex");
        },
    });
}

$("#frame").click(function () {
    $("#frame").removeClass();
    $("#frame").addClass("frame").addClass("d-none");
});
$("#TestingFream").click(function () {
    $("#TestingFream").removeClass();
    $("#TestingFream").addClass("frame").addClass("d-none");
});

try {
    toastr.options = {
        closeButton: false,
        debug: false,
        newestOnTop: false,
        progressBar: false,
        positionClass: "toast-bottom-right",
        preventDuplicates: false,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "2000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
    };
} catch (error) {}
