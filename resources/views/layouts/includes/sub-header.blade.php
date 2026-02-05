<div class="subheader py-2  subheader-solid " id="kt_subheader">
    <div  class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <div class="d-flex align-items-right flex-wrap mr-1">
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5"> {{ $page_title }}</h5>
        </div>

        <div class="d-flex align-items-center pt-4">
            @if (request()->is('/') || request()->is('weekly') || request()->is('monthly') || request()->is('daily'))
            <div class="filter-toggle btn-group float-right" style="margin-bottom: 1rem;margin-right: 1rem;">
                <a href="{{route('dashboard')}}"> <div class="btn btn-primary btn-sm today-btn data-btn active" >All</div></a>
                &nbsp;
                <a href="{{route('dashboard.daily')}}"> <div class="btn btn-primary btn-sm week-btn data-btn"> Daily</div></a>
                &nbsp;
                <a href="{{route('dashboard.weekly')}}"><div class="btn btn-success btn-sm month-btn data-btn" >Weekly</div></a>
                &nbsp;
                <a href="{{route('dashboard.monthly')}}"><div class="btn btn-info btn-sm year-btn data-btn" >Monthly</div></a>
            </div>
            @endif


            <ol class="breadcrumb float-right pull-right">
                <li><a href=""><i class="fas fa-home"></i> Dashboard</a></li>
                @if (!empty($breadcrumb))
                    @foreach ($breadcrumb as $item)
                        @if(!isset($item['link']))
                        <li class="active">{{ $item['name'] }}</li>
                        @else
                        <li><a href="{{ $item['link'] }}">{{ $item['name'] }}</a></li>
                        @endif
                    @endforeach
                @endif
            </ol>
        </div>
    </div>
</div>
