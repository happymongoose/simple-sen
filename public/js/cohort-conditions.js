
//-----------------------------------------------------------------------------------
//
// Cohort conditions
//
//-----------------------------------------------------------------------------------

var cohortConditions;
var conditionBulderDiv;
var conditionRowCount=0;

//-----------------------------------------------------------------------------------
//
// Event handling
//
//-----------------------------------------------------------------------------------

function dropConditionElement(area, element) {
  //Reset CSS attributes
  $(element).attr('display', 'block');
  $(element).attr('position', "relative");
  $(element).css({left: 'inherit', top:'inherit'});
  $(element).removeClass("ui-state-active");
  //Append to given area
  $(area).append(element);
}

$('#condition-area').droppable({
  accept: '.condition-block',
  classes: {
    "ui-droppable-active": "ui-state-active",
    "ui-droppable-hover": "ui-state-hover"
  },
  drop: function (event, ui) {
    //Get dragged element
    var dragged = ui.draggable;
    //Drop it into the correct div
    dropConditionElement($(this), dragged);
    //Get the selected elements input control ID and tagger element
    var inputControlID = "#" + ui.draggable.data('input-control');
    $(dragged).addClass("condition-active");
    var inputTagger = $(inputControlID).data('tagger');
    //Enable the interactive control on the element
    if (inputTagger!=null) inputTagger.allow_editing(true);
  }
});

$('#condition-source').droppable({
  accept: '.condition-block',
  classes: {
    "ui-droppable-active": "ui-state-active",
    "ui-droppable-hover": "ui-state-hover"
  },
  drop: function (event, ui) {
    //Get dragged element
    var dragged = ui.draggable;
    //Drop it into the correct div
    dropConditionElement($(this), dragged);
    //Get the selected elements input control ID and tagger element
    var inputControlID = "#" + ui.draggable.data('input-control');
    var inputTagger = $(inputControlID).data('tagger');
    $(dragged).removeClass("condition-active");
    $('#'+$(dragged).data('error-id')).hide();
    //Disable the interactive control on the element
    if (inputTagger!=null) {
      cohortConditionsRemoveAllTags(inputControlID, inputTagger);
      inputTagger.allow_editing(false);
    }
  }
});


//-----------------------------------------------------------------------------------
//
// Tags
//
//-----------------------------------------------------------------------------------

function cohortConditionsRemoveAllTags(element, tagger_element) {
  var existing_tag_strings = $(element).val();
  var existing_tag_array = existing_tag_strings.split(",");
  existing_tag_array.forEach(function (tag_text) {
    tagger_element.remove_tag(tag_text);
  });

}

function cohortConditionsInitTagger(element, allow_spaces, tag_string, default_tag_color) {

  if (default_tag_color==null)
      default_tag_color="#858796";

  //Set up tag manager for year groups
  var tag_element = tagger($(element), {
    allow_duplicates: false,
    allow_spaces: allow_spaces,
    add_on_blur: true,
    allow_new: false,
    wrap: true,
    allow_editing: false,
    case_sensitive: false,
    default_tag_color: default_tag_color,
    link: function() { return false; },
    completion: {
      list: tag_string,
      min_length: 1
    }
  })[0];
  $(element).data('tagger', tag_element);

}

//-----------------------------------------------------------------------------------
//
// Initialisation
//
//-----------------------------------------------------------------------------------

//Initialises condition blocks and place them on the screen (create)
function cohortConditionsInit(all_tags, year_groups_string, all_class_tags) {

  //Make all condition elements draggable
  $('.draggable').draggable({ revert: 'invalid', snap: "#condition-drop-area", snapTolerance: 30, snapMode: "inner"});

  //Set up tag manager for year groups
  cohortConditionsInitTagger('#input-year-groups', true, year_groups_string, '#4e73df');
  //Set up tag manager for classes
  cohortConditionsInitTagger('#input-classes', true, all_class_tags, '#6610f2');
  //Set up tag manager for 'pupil must have all...' tags
  cohortConditionsInitTagger('#input-all-tags', false, all_tags);
  //Set up tag manager for 'pupil has any one of...' tags
  cohortConditionsInitTagger('#input-any-tag', false, all_tags);

}

//Updates the tags on the given input ID
function cohortConditionsUpdateTags(id, tag_string) {

  //Get tagger for object
  var tagger = $(id).data('tagger');
  //Break tag string into an array
  var tag_array = tag_string.split(",");
  //Iterate over array and add tags to tagger for the condition block
  tag_array.forEach(function (tag_text) {
    tagger.add_tag(tag_text);
  });

}

//Make the specified condition block active (i.e. fill in with given tags, move to the "used" condition
//area, make available for editing)
function conhortConditionsActivateCondition(id, tagString) {
  //Get the input control for the condition block
  var inputControlID = "#" + $(id).data('input-control');
  //Update tags on the conditions input field
  cohortConditionsUpdateTags(inputControlID, tagString);
  //Add the condition-active class, indicating the condition is in use
  $(id).addClass("condition-active");
  //Enable the interactive tagger control on the element
  var inputTagger = $(inputControlID).data('tagger');
  inputTagger.allow_editing(true);
  //Move the condition from the rule bank to the active conditions area
  dropConditionElement('#condition-area', $(id));
}


//Fill in data for condition blocks (edit)
//data = array containing all of the conditions for this cohort
function cohortConditionsFill(conditions_data) {

  //Iterate over the data array
  conditions_data.forEach((condition) => {

    //Update the condition block, based on the "field" the condition operates on
    switch (condition['field']) {

      case 'year_group':
          conhortConditionsActivateCondition('#condition-year-group', condition['friendlyValue']);
        break;

      case 'tags':
        if (condition['filter']=="all") {
          conhortConditionsActivateCondition('#condition-all-tags', condition['friendlyValue']);
        }
        else {
          conhortConditionsActivateCondition('#condition-any-tag', condition['friendlyValue']);
        }
        break;

      case 'class':
        conhortConditionsActivateCondition('#condition-class', condition['friendlyValue']);
        break;

    }

  });
}
