@if (count($notes)>0)
<div class="notes">
  <ul>
    @foreach ($notes as $note)
      <x-note link="#" :note="$note" />
    @endforeach
  </ul>
</div>
<!-- end .notes -->
@endif
