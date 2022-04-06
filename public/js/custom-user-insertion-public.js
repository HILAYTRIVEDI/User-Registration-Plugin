(function ($) {
  "use strict";
  $(document).ready(function () {
    $(".custom-pagination .page-numbers:first-child").addClass("current");

    // if ($("#custom-user-tool__search--form").length > 0) {
    //   var datePickerIdfrom = document.getElementById(
    //     "custom-user-tool__search--dobfrom"
    //   );
    //   datePickerIdfrom.max = new Date().toISOString().split("T")[0];
    //   var datePickerIdto = document.getElementById(
    //     "custom-user-tool__search--dobto"
    //   );
    //   datePickerIdto.max = new Date().toISOString().split("T")[0];
    // }
    // if ($("#contact").length > 0) {
    //   var datePickerId = document.getElementById("date_of_birth");
    //   datePickerId.max = new Date().toISOString().split("T")[0];
    // }
    var form = $("#contact");

    form.validate({
      errorPlacement: function errorPlacement(error, element) {
        element.before(error);
      },
      rules: {
        profile_photo: {
          required: true,
        },
        email: {
          required: true,
          email: true,
        },
      },
    });
    form.children("div").steps({
      headerTag: "h3",
      bodyTag: "section",
      transitionEffect: "slideLeft",
      onStepChanging: function (event, currentIndex, newIndex) {
        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
      },
      onFinishing: function (event, currentIndex) {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
      },
      onFinished: function (event, currentIndex) {
        event.preventDefault();
        var fd = new FormData(form[0]);
        fd.append("action", "custom_user_insertion_form");
        fd.append("nonce", Custom_User_params.nonce);
        fd.append("userAvatar", $("#profile_photo")[0].files[0]);
        var inputs = $("#contact :input");
        inputs.each(function () {
          fd.append(this.name, $(this).val());
        });
        $.ajax({
          url: Custom_User_params.ajaxurl,
          type: "POST",
          data: fd,
          contentType: false,
          processData: false,
          success: function (response) {
            console.log(response);
            if (response != "") {
              $("#captcha-error-message").text(
                "Form Submitted Sucessfully , Please verify your account through email..."
              );
              $("#captcha-error-message").css("color", "green");
              window.setTimeout(function () {
                form[0].reset();
                window.location.replace(document.location.origin + "/login/");
              }, 2000);
            } else {
              $("#captcha-error-message").text(
                "Invalid input given or User already registered"
              );
              $("#captcha-error-message").css("color", "red");
            }
          },
        });
      },
    });
  });

  $(document).ready(function () {
    $("#custom_user_skill").select2();
    $("#custom_user_cat").select2();
  });

  $(document).on("click", "#custom-user-tool__search--submit", function (e) {
    var keyWord = $("#custom-user-tool__search--keyword").val();
    var dobfrom = $("#custom-user-tool__search--dobfrom").val();
    var dobto = $("#custom-user-tool__search--dobto").val();
    var skills = $("#custom-user-tool__search--skill").val();
    var category = $("#custom_user_cat_public").val();
    var ratings = $("#custom-user-tool__search--ratings").val();

    var data = {
      action: "custom_search_listing_data",
      keyWord,
      skills,
      category,
      ratings,
      dobfrom,
      dobto,
      nonce: Custom_User_params.nonce,
    };

    $.ajax({
      url: Custom_User_params.ajaxurl,
      data: data,
      beforeSend: function () {
        $("#custom-user-registration-form-container").addClass("loading");
      },
      success: function (response) {
        console.log(response);
        $("#custom-user-registration-form-container").removeClass("loading");
        $(".custom-user-tool__list").replaceWith(response);
      },
      error: function () {
        alert("Opps! Something went wrong Please try again");
      },
    });
  });

  $(document).on("submit", "#custom-user-login-form", function (e) {
    e.preventDefault();
    var loginformEmail = $("#loginformEmail").val();
    var loginformPassword = $("#loginformPassword").val();
    var data = {
      action: "custom_user_login_verification",
      loginformEmail,
      loginformPassword,
      nonce: Custom_User_params.nonce,
    };
    $.ajax({
      url: Custom_User_params.ajaxurl,
      data: data,
      type: "POST",
      success: function (response) {
        var jsonData = JSON.parse(response);
        if (jsonData.success == 1) {
          $("#custom-user-login-form-error").text(
            "Login Successfull , Welcome Thanks for choosing us"
          );
          $("#custom-user-login-form-error").css("color", "green");
        } else {
          $("#custom-user-login-form-error").text(
            "Please register your account first. If already registered wait for admin approval"
          );
          $("#custom-user-login-form-error").css("color", "red");
        }
      },
    });
  });

  $(document).on("change", "#custom-user-tool__search--ratings", function (e) {
    $("#custom-user-tool__search--ratingsvalue").text($(this).val());
  });

  $(document).on("keypress", ".user_input", function (e) {
    var regex = new RegExp("^[a-zA-Z0-9_ s\r\n]+$");
    var key = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (!regex.test(key)) {
      e.preventDefault();
      return false;
    }
  });

  $(document).on("change", "#profile_photo", function () {
    const [file] = this.files;
    if (file) {
      $("#profile_photo_preview").css("display", "block");
      $("#profile_photo_preview").attr("src", URL.createObjectURL(file));
    }
  });

  $(document).on("click", ".custom-pagination .page-numbers", function () {
    $(".custom-pagination .page-numbers").removeClass("current");
    $(this).addClass("current");
    var pageNo = parseInt($(this).attr("page-no"));
    ajax_call(pageNo);
  });

  function ajax_call(pageNo) {
    var keyWord = $("#custom-user-tool__search--keyword").val();
    var dobfrom = $("#custom-user-tool__search--dobfrom").val();
    var dobto = $("#custom-user-tool__search--dobto").val();
    var skills = $("#custom-user-tool__search--skill").val();
    var category = $("#custom_user_cat_public").val();
    var ratings = $("#custom-user-tool__search--ratings").val();

    var data = {
      action: "custom_search_listing_data",
      keyWord,
      skills,
      category,
      ratings,
      dobfrom,
      dobto,
      page_no: pageNo,
      nonce: Custom_User_params.nonce,
    };

    $.ajax({
      url: Custom_User_params.ajaxurl,
      data: data,
      success: function (response) {
        $(".custom-user-tool__list").replaceWith(response);
        var currentPage = $(".custom-user-tool__list").attr("current_page");
        log;
        $(".custom-pagination")
          .find(".page-number" + currentPage)
          .addClass("current");
      },
    });
  }
})(jQuery);
