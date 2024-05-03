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
      data: { testimonial_id, status, action: 'toggle_testimonial' },
      success: function (response) { // Callback function to handle successful response
        console.log('Data received:', response);
      },
    });

  })

  $('#password-reset-form').submit(function (event) {
    event.preventDefault()
    $(this).find('button')
    .addClass('is-loading')
    
    $.ajax({
      url: '/ajax?action=send_otp',
      method: 'POST',
      dataType: 'json',
      data: $(this).serialize(),
      success: response => {
        $(this).toggleClass('is-hidden')
        $('#otp-form')
          .toggleClass('is-hidden')
          .find('h2')
          .append($(this).find('input').val())
      },
      error: err => {
        const { error } = err.responseJSON
        $(this).find('.help').text(error)
      }

    })
  });

  $('#otp-form input').on('input', function () {

    const boxes = [...$('#otp-form input')]
    if (boxes.every(box => box.value != '')) {
      const otp = boxes.map(box => box.value).join('')
      boxes.forEach(box => box.disabled = true);
      $.ajax({
        url: '/ajax?action=verify_otp',
        method: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify({ otp }),
        success: response => {
          $('#otp-form').toggleClass('is-hidden')
          $('#password-create-form').toggleClass('is-hidden')
        },
        error: err => {
          const { error } = err.responseJSON
          $('#otp-form').find('.help').text(error)
          boxes.forEach(box => box.disabled = false)
          boxes[boxes.length - 1].focus()
        }

      })
    }

  })

  $('#password-create-form').submit(function (event) {
    event.preventDefault()

    $.ajax({
      url: '/ajax?action=create_new_password',
      method: 'POST',
      dataType: 'json',
      data: $(this).serialize(),
      success: response => {
        window.location.replace('/login')
      },
      error: err => {
        const { error } = err.responseJSON
        $(this).find('.help').text(error)
      }

    })
  })

})
