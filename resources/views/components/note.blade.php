<li>
  <a href="#" class="@if ($note->editable()) edit-note-button @else view-note-button @endif" data-get-url="{{ route('notes.get', $note->id) }}" style="background-color: #{{ $note->colour }}">
    <div class="note-header">
      <div class="note-creator">{!! $note->printCreatorHtml() !!}</div>
      <div class="note-date">{{ $note->getNoteCreationDate() }}</div>
    </div>
    <h2>{{ $note['title'] }}</h2>
    <div class="note-tags">{!! $note->printTagsHtml() !!}</div>
    <hr/>
    <p>{!! nl2br($note['text']) !!}</p>

  </a>
</li>
