<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $menu }}</h1>
            </div>
            @if(isset($breadcrumb))
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        @foreach($breadcrumb as $item)
                            <li class="breadcrumb-item"><a href="{{ $item['route'] }}">{{ $item['title'] }}</a></li>
                        @endforeach
                        @if(isset($active))
                            <li class="breadcrumb-item active">{{ $active }}</li>
                        @endif
                    </ol>
                </div>
            @endif
        </div>
    </div>
</section>