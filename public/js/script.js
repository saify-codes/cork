$(document).ready(function () {

  $('.notification .delete').on('click', function () {
    $(this).parent('.notification').remove();
  });

  // Get all "navbar-burger" elements
  const $navbarBurgers = $('.navbar-burger');

  // Add a click event on each of them
  $navbarBurgers.on('click', function () {
    // Get the target from the "data-target" attribute
    const target = $(this).data('target');
    const $target = $('#' + target);

    // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
    $(this).toggleClass('is-active');
    $target.toggleClass('is-active');
  });

  $('#testimonials .switch input').change(function () {
    const testimonial_id = this.value
    const status = Number(this.checked)
    $.ajax({
      url: '/ajax', // The URL to which the request is sent
      method: 'POST',      // The HTTP method (GET, POST, PUT, DELETE, etc.)
      dataType: 'json',   // The expected data type of the response
      data:{testimonial_id, status, action: 'toggle_testimonial'},
      success: function (response) { // Callback function to handle successful response
        console.log('Data received:', response);
      },
    });

  })

});
