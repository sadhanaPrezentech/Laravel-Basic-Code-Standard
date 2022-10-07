$(document).ready(function () {
    $("#frm-Inquiry").validate({
        rules: {
            name: {
                required: true,
            },
            email: {
                required: true,
            },
            description: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please provide name title.",
            },
            email: {
                required: "Please provide email title.",
            },
            description: {
                required: "Please provide description title.",
            },

        },
    });
    document.getElementById("frm-Inquiry").addEventListener("submit",function(evt)
    {

    var response = grecaptcha.getResponse();
    if(response.length == 0)
    {
      //reCaptcha not verified
      $('.recaptcha-error').html('please verify you are humann!');

      evt.preventDefault();
      return false;
    }
    //captcha verified
    //do the rest of your validations here

  });
});
