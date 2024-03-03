@inject('repo', Bfg\OpenDoc\Repositories\DocumentationRepository::class)
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $repo->title }} Documentation</title>
    <!-- ========== All CSS files linkup ========= -->
    <link rel="stylesheet" href="{{ asset('/vendor/open-doc/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/vendor/open-doc/LineIcons.3.0.css') }}" />
    <link rel="stylesheet" href="{{ asset('/vendor/open-doc/simple-bar.css') }}" />
    <link rel="stylesheet" href="{{ asset('/vendor/open-doc/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('/vendor/open-doc/highlight.min.css') }}" />
</head>

<body class="position-relative" data-bs-spy="scroll" data-bs-target="#navbar-example2" tabindex="0">

<!-- Preloader -->
<div class="preloader">
    <div class="preloader-inner">
        <div class="preloader-icon">
            <span></span>
            <span></span>
        </div>
    </div>
</div>
<!-- /End Preloader -->

@yield('content')

<!-- ========================= scroll-top ========================= -->
<a href="#" class="scroll-top" style="display: none">
    <i class="lni lni-chevron-up"></i>
</a>

<!-- ========= All Javascript files linkup ======== -->
<script src="{{ asset('/vendor/open-doc/popper.min.js') }}"></script>
<script src="{{ asset('/vendor/open-doc/bootstrap.min.js') }}"></script>
<script src="{{ asset('/vendor/open-doc/simple-bar.js') }}"></script>
<script src="{{ asset('/vendor/open-doc/main.js') }}"></script>
<script src="{{ asset('/vendor/open-doc/highlight.min.js') }}"></script>
<script>
    var menuButtonOpen = document.querySelector(".menu-open");
    var menuButtonClose = document.querySelector(".menu-close");
    var sidebar = document.querySelector(".doc-sidebar");
    var docOverlay = document.querySelector(".doc_overlay");

    menuButtonOpen.addEventListener("click", () => {
        sidebar.classList.add("show");
        docOverlay.classList.add('open');
    });
    menuButtonClose.addEventListener("click", () => {
        sidebar.classList.remove("show");
        docOverlay.classList.remove('open');
    });
    docOverlay.addEventListener("click", () => {
        sidebar.classList.remove("show");
        docOverlay.classList.remove('open');
    });


    // ===== copy code
    const copyButton = document.querySelectorAll('.copy-btn');
    copyButton.forEach(element => {
        element.addEventListener('click', (e) => {
            var elem = e.target.parentElement.children[1].innerText;
            var el = document.createElement('textarea');

            console.log(elem)
            el.value = elem;

            document.body.appendChild(el);

            el.select();
            document.execCommand("copy");
            alert(`Code Copied!`)
            document.body.removeChild(el)
        })
    });
</script>

<script>
    // first, find all the div.code blocks
    // document.querySelectorAll('code').forEach(el => {
    //     // then highlight each
    //     hljs.highlightElement(el);
    // });

    hljs.highlightAll();
</script>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>

</body>

</html>
