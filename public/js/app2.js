

//-------------------------------------------
//
// Handling table rows that are clickable
//
//-------------------------------------------

$('.table-cell-clickable').click(function(e) {

  //Prevent default action
  e.preventDefault();

  //Get HREF on cell
  href = $(this).attr('href');

  //If non-null, update URL
  if (href!=null)
    window.location = href;

});


//-------------------------------------------
//
// Handling links and icons that trigger Sweet Alerts
//
//-------------------------------------------

$('.sa-trigger').click(function (e) {

  //Prevent default action
  e.preventDefault();

  //Default values for buttons

  //Confirm button text
  if ($(this).attr('sa-confirm-text'))
    confirmButtonText = $(this).attr('sa-confirm-text');
  else
    confirmButtonText = "Yes";

  //Cancel button text
  if ($(this).attr('sa-cancel-text'))
    cancelButtonText = $(this).attr('sa-cancel-text');
  else
    cancelButtonText = "Cancel";

  //Icon
  if ($(this).attr('sa-icon'))
    icon = $(this).attr('sa-icon');
  else
    icon = null;

  Swal.fire({
    title: $(this).attr('sa-title'),
    text: $(this).attr('sa-text'),
    icon: icon,
    showCancelButton: true,
    confirmButtonColor: '#4e73df',
    cancelButtonColor: '#d33',
    confirmButtonText: confirmButtonText,
    cancelButtonText: cancelButtonText,
  }).then((result) => {
    if (result.isConfirmed) {
      window.location = $(this).attr('href');
    }
  });


});


//-------------------------------------------
//
// Remove laravel message notifications after given time
//
//-------------------------------------------

$(function(){
  setTimeout(function () {
    $(".alert-notification").slideUp();}, 5000);
});

$(function(){
  setTimeout(function () {
    $(".notify").fadeOut();}, 5000);
});
