<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#{{ $group->groupID }}"
        aria-expanded="true" aria-controls="{{ $group->groupID }}">
        <i class="fas fa-fw fa-cog"></i>
        <span>{{ $group->title }}</span>
    </a>
    <div id="{{ $group->groupID }}" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            @foreach ($group->items as $item)

              @switch ($item->type)

                @case('heading')
                  <h6 class="collapse-header">{{ $item->text }}</h6>
                  @break

                @case('item')
                @default
                  <a class="collapse-item" href="{{ $item->link }} ">@if (isset($item->icon)) <i class="fas fa-fw {{ $item->icon }}"></i> @endif{{ $item->text }}</a>

                @case('divider')
                  <!-- Divider -->
                  <hr class="sidebar-divider">
                  @break

              @endswitch

            @endforeach
        </div>
    </div>
</li>
