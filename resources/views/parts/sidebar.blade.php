<!-- Start Doc Sidebar -->
<aside data-simplebar class="doc-sidebar">
    <button class="menu-button menu-close">
        <span></span>
        <span></span>
    </button>
    <nav class="sidenav" id="navbar-example2">
        <ul class="nav flex-column">
            @if($repo->pagesForStarted->isNotEmpty())
                <li>
                    <h6><i class="lni lni-list"></i> Getting Started</h6>
                </li>
                @foreach($repo->pagesForStarted as $page)
                    <li class="nav-item">
                        <a href="#{{ $page['id'] }}" @class(['nav-link', 'active' => $loop->first])>{{ $page['name'] }}</a>
                    </li>
                @endforeach
            @endif
            @if($repo->pagesForApi->isNotEmpty())
                <li>
                    <h6><i class="lni lni-notepad"></i> Api</h6>
                </li>
                @foreach($repo->pagesForApi as $page)
                    <li class="nav-item">
                        <a href="#{{ $page['id'] }}" @class(['nav-link'])>{{ $page['name'] }}</a>
                    </li>
                @endforeach
            @endif
            @if($repo->pagesForElements->isNotEmpty())
                <li>
                    <h6><i class="lni lni-grid-alt"></i> Elements</h6>
                </li>
                @foreach($repo->pagesForElements as $page)
                    <li class="nav-item">
                        <a href="#{{ $page['id'] }}" @class(['nav-link'])>{{ $page['name'] }}</a>
                    </li>
                @endforeach
            @endif
        </ul>
    </nav>
</aside>
<!-- End Doc Sidebar -->
