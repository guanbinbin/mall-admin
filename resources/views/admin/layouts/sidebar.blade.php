<aside>
    <div id="sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu">
            <li class="{{ active_class(if_route_pattern('home')) }}">
                <a class="" href="{{ route('home') }}">
                    <i class="icon-dashboard"></i>
                    <span>控制台</span>
                </a>
            </li>

            @foreach($rule as $item)
                @if(!isset($item['son']))
                    <li class="{{ active_class(if_route_pattern($item['route'])) }}">
                        <a href="javascript:;">
                            <i class="{{ $item['icon'] }}"></i>
                            <span>{{ $item['name'] }}</span>
                        </a>
                    </li>
                @else
                    <li class="sub-menu {{ active_class(if_route_pattern($item['route'])) }}">
                        <a href="javascript:;">
                            <i class="{{ $item['icon'] }}"></i>
                            <span>{{ $item['name'] }}</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub">
                            @foreach($item['son'] as $i)
                                <li class="{{ active_class(if_route_pattern($i['route'])) }}">
                                    <a class="active" href="{{ route($i['route']) }}">
                                        {{ $i['name'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>