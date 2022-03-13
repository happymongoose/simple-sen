
//-----------------------------------------------------------------------------------
//
// Notes
//
//-----------------------------------------------------------------------------------

var note_tags=null;

// Initialisation

function notesInit(all_tags) {

  note_tags = new tagger($('#note-tags'), {
      allow_duplicates: false,
      allow_spaces: false,
      add_on_blur: true,
      wrap: true,
      link: function() { return false; },
      completion: {
          list: all_tags
      }
  });

}

// Set tags

function notesSetTags(tag_string) {

    //Remove pre-existing tags
    var existing_tag_strings = $('#note-tags').val();
    var existing_tag_array = existing_tag_strings.split(",");
    existing_tag_array.forEach(function (tag_text) {
      note_tags[0].remove_tag(tag_text);
    });

    //Add given tags
    var tag_array = tag_string.split(",");
    tag_array.forEach(function (tag_text) {
      note_tags[0].add_tag(tag_text);
    });

}


//--------------------------
//
// Validation
//
//--------------------------

//Input validator
var note_validator;


$(document).ready(function () {
  note_validator = $('#note-edit-form').validate({
      rules: {
        "note-title": {
          required: true,
          maxlength: 255,
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      }
  });
});


//--------------------------
//
// Colour picker
//
//--------------------------


//Colorpicker instance
var note_colour_picker;

$(function () {
  // Basic instantiation:
  $('#note-colour-group').colorpicker({
    useAlpha:false,
    format: "hex",
    useHashPrefix: false,
    extensions: [
      {
        name: 'swatches',
        options: {
          colors: { 'Yellow':'#ffffcc', 'Green':'#ccffcc', 'Pink':'#ffccff', 'Blue':'#ccffff', 'Red':'#ffcccc', 'Purple':'#ccccff', 'White':'#ffffff', 'Grey':'#dddddd'  },
          namesAsValues: true
        }
      }
    ]
  }).on('colorpickerCreate', function(e) {
    note_colour_picker = e.colorpicker;
  });

  // Example using an event, to change the color of the #demo div background:
  $('#demo-input').on('colorpickerChange', function(event) {
    $('#demo').css('background-color', event.color.toString());
  });

});


//--------------------------
//
// New note
//
//--------------------------

$('#new-note-button').click(function (e) {

  //Prevent default action
  e.preventDefault();

  //Insert data into add dialog
  $('#note-edit-form').attr('action', $('#note-routes').data('store-url'));
  $('#note-modal-title').text("Add a new note");
  $('#note-title').val('');
  $('#note-text').val('');
  notesSetTags("");
  $('#note-instance-id').val('student');
  $('#note-class').val('');
  $('#note-colour').val("#ffc");
  $('#note-colour-square').css("background-color", "#ffc");
  $('#note-allow-edit').prop('checked', false);
  $('#note-edit-group').show();
  $('#note-delete-group').hide();

  //Update colour picker default colour
  note_colour_picker.setValue("ffc");

  //Reset errors on dialog validation
  note_validator.resetForm();
  $('#note-modal .invalid-feedback').remove()
  $('#note-modal input').removeClass('is-valid');
  $('#note-modal input').removeClass('is-invalid');

  //Open modal
  $('#note-modal').modal('show');

});


//--------------------------
//
// Edit note
//
//--------------------------

$('.edit-note-button').click(function (e) {

  //Prevent default action
  e.preventDefault();

  //Get fetch URL
  var url = $(this).data('get-url');

  //Load view
  $.ajax({
    url: url,
    type: 'GET',
    datatype: 'html'
  })
  .done(function(data) {

    //Insert data into edit dialog
    $('#note-edit-form').attr('action', $('#note-routes').data('update-url'));
    $('#note-modal-title').text("Edit note");
    notesSetTags(data['tag_string']);
    $('#note-title').val(data['title']);
    $('#note-text').val(data['text']);
    $('#note-colour').val(data['colour']);
    $('#note-colour').val("data['colour']");
    $('#note-colour-square').css("background-color", "#"+data['colour']);
    $('#note-id').val(data['id']);
    $('#note-allow-edit').prop('checked', data['allow_edit']==1);

    //If user has full access rights to note, allow them to change the 'editable' checkbox and delete the note
    if (data['full_access_rights']) {
      $('#note-edit-group').show();
      $('#note-delete-group').show();
      if (data['user_owns'])
        $('#note-form-own').hide();
      else
        $('#note-form-own').show();
    }
    else {
      $('#note-edit-group').hide();
      $('#note-delete-group').hide();
    }

    //Finally... if this is for the user's private notes, never enable the sharing button
    if (data['class']=="user")
      $('#note-edit-group').hide();


    //Update colour picker default colour
    note_colour_picker.setValue(data['colour']);

    //Reset errors on dialog validation
    note_validator.resetForm();
    $('#note-edit-modal .invalid-feedback').remove()
    $('#note-edit-modal input').removeClass('is-valid');
    $('#note-edit-modal input').removeClass('is-invalid');

    //Show modal
    $('#note-modal').modal('show');
  })
  .fail(function() {
    console.log("Unable to populate modal with note (#note-edit-modal)");
  })

});

//When user is not admin, just allow them to view notes
$('.view-note-button').click(function (e) {
  e.preventDefault();
});

//--------------------------
//
// Save note
//
//--------------------------

$('#note-form-save').click(function (e) {

  //Prevent default action
  e.preventDefault();

  //Submit if the form is valid
  if ($('#note-edit-form').valid()) {
    $('#note-colour').val(note_colour_picker.getValue().substring(1));
    $('#note-edit-form').submit();
  }

});


//--------------------------
//
// Delete note
//
//--------------------------

$('#note-form-delete').click(function (e) {

  //Prevent default action
  e.preventDefault();

  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to undo this action.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!'
    }
  ).then((result)=>{
    if (result.isConfirmed) {
      //Convert from from an add-update form to a delete form
      $('#note-edit-form').attr('action', $('#note-routes').data('delete-url'));
      $('#note-action').val("delete");
      $('#note-edit-form').submit();
    }
  });

});

//--------------------------
//
// Take ownership
//
//--------------------------

$('#note-form-own').click(function (e) {

  //Prevent default action
  e.preventDefault();

  //Convert from from an add-update form to a delete form
  $('#note-edit-form').attr('action', $('#note-routes').data('ownership-url'));
  $('#note-action').val("put");
  $('#note-edit-form').submit();

});
