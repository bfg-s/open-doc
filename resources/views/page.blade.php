@extends('open-doc::layout')

@section('content')
    <main>
        <button class="menu-button menu-open">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="doc_overlay"></div>
        <div class="container doc_container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-4 col-12">
                    @include('open-doc::parts.sidebar')
                </div>
                <div class="col-xl-9 col-lg-9 col-12" style="padding-top: 26px">
                    <section class="main-section" data-bs-target="#navbar-example3" tabindex="0">
                        <div class="doc-main-wrapper">

                            @if ($repo->pagesForStarted->isNotEmpty())
                                @foreach($repo->pagesForStarted as $page)
                                    <div id="{{ $page['id'] }}" class="welcome mb-30">
                                        @if($page['description'])
                                            <h1 class="mb-30">
                                                {{ $page['description'] }}
                                            </h1>
                                        @endif
                                        {!! $page['html'] !!}
                                    </div>
                                @endforeach
                            @endif

                            @if ($repo->pagesForApi->isNotEmpty())
                                <div class="content pt-30 pb-20">
                                    <div class="doc-title">
                                        <h2>Api</h2>
                                    </div>
                                </div>
                                @foreach($repo->pagesForApi as $page)
                                    <div id="{{ $page['id'] }}" class="welcome mb-30">
                                        @if($page['description'])
                                            <h1 class="mb-30">
                                                {{ $page['description'] }}
                                            </h1>
                                        @endif
                                        {!! $page['html'] !!}
                                    </div>
                                @endforeach
                           @endif

                            @if ($repo->pagesForElements->isNotEmpty())
                                <div class="content pt-30 pb-20">
                                    <div class="doc-title">
                                        <h2>Elements</h2>
                                    </div>
                                </div>
                                @foreach($repo->pagesForElements as $page)
                                    <div id="{{ $page['id'] }}" class="welcome mb-30">
                                        @if($page['description'])
                                            <h1 class="mb-30">
                                                {{ $page['description'] }}
                                            </h1>
                                        @endif
                                        {!! $page['html'] !!}
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>
@endsection
