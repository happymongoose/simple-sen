<!-- Sidebar -->

<?php
  use App\Helpers\Sidebar;

  //Load side bar object
  $sidebar = new Sidebar();
?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route("home") }}">
      <img src="{{ asset("img/simple-sen-logo-white.svg") }}" class="sidebar-logo"/>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    @foreach ($sidebar->items as $item)
      @switch($item->type)

        @case('item')
        @default
          <!-- Menu item ({{ $item->text }}) -->
          <x-sidebar-item text="{{ $item->text }}" icon="{{ $item->icon }}" link="{{ $item->link }}" />
          @break

        @case('divider')

          <!-- Divider -->
          <hr class="sidebar-divider d-none d-md-block">
          @break

        @case('group')
          <!-- Menu -->
          <x-sidebar-group :group="$item" />
          @break

      @endswitch

    @endforeach

</ul>
