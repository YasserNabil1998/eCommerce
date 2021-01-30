$(function () {
  "use strict";

  // switch between Login
  $(".login-page h1 span").click(function () {
    $(this).addClass("active").siblings().removeClass("active");

    $(".login-page form").hide();

    $("." + $(this).data("class")).fadeIn(100);
  });

  //Trigger The SelectBox
  $("select").selectBoxIt({
    autoWidth: false,
  });

  // Add Asterisk On Required Field

  $("input").each(function () {
    if ($(this).attr("required") === "required") {
      $(this).after('<span class="asterisk">*</span>');
    }
  });

  //Hide Placeholder on Form Focus

  $("[placeholder]")
    .focus(function () {
      $(this).attr("data-text", $(this).attr("placeholder"));

      $(this).attr("placeholder", "");
    })
    .blur(function () {
      $(this).attr("placeholder", $(this).attr("data-text"));
    });

  //Confirmation Message On Button

  $(".confirm").click(function () {
    return confirm("Are You Sure");
  });

  //Carate New Add
  $(".live").keyup(function () {
    $($(this).data("class")).text($(this).val());
  });
});
