<header style="background: #285f5d" class="page-header page-header-dark pb-10">
    <div class="container-xl px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="{{$icon ?? 'users'}}"></i></div>
                        {{ $title }}
                    </h1>
                    <div class="page-header-subtitle">{{ $sub_title ?? '' }}</div>
                </div>
            </div>
        </div>
    </div>
</header>
