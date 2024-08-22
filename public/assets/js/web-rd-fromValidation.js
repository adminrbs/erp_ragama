//------------------validation avoid submission -----------------
 (function () {
  'use strict';
  window.addEventListener('load', function () {
    // Fetch all the forms to apply custom Bootstrap validation styles
    var forms = document.getElementsByClassName('needs-validation');

    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function (form) {
      form.addEventListener('submit', function (event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
        form.classList.remove('was-validated');

      }, false);
    });
  }, false);
})(); 

//-----------------------------------------------------


$(document).ready(function () {

  
  $('form').on('reset', function () {
    resetForm();
  });

  //---------------- bootstrap validation----------------------//

  $('.form-control').on('input', function () {
    validateInput($(this));
  });


  $('.form-control').on('focusout', function () {
    validateInput($(this));
  });


  function validateInput(input) {
    var value = input.val();

    if (input.prop('required') && value.trim() === '') {
      input.removeClass('is-valid').addClass('is-invalid');
      return;
    }

    if (input.attr('type') === 'number' && !$.isNumeric(value)) {
      input.removeClass('is-valid').addClass('is-invalid');
      return;
    }

    if (input.attr('type') === 'tel' && !/^\d+$/.test(value)) {
      input.removeClass('is-valid').addClass('is-invalid');
      return;
    }

    if (input.attr('type') === 'email' && !validateEmail(value)) {
      input.removeClass('is-valid').addClass('is-invalid');
      return;
    }

    input.removeClass('is-invalid').addClass('is-valid');
  }


  // Email validation regular expression
  function validateEmail(email) {
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(email);
  }


  // Remove all classes from form inputs
  function resetForm() {
    $('.form-control').removeClass('is-valid is-invalid');

  }



  //----------------------allowing only numbers to telephone------------//

  $('input[type="tel"]').on('input', function () {
    var value = $(this).val();
    $(this).val(value.replace(/\D/g, ''));
  });


  //--------------preventing mouse wheel on number textboxes------------------//

  $('input[type="number"]').on('wheel', function (e) {
    e.preventDefault();
  });


  //--------------preventing minus numbers on type numbers textbox---------//

  $('input[type="number"]').on('keypress', function (e) {
    if (e.which === 45) {
      e.preventDefault(); // Prevent '-' (minus) key from being entered
    }
  });


});





