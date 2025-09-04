(function ($) {
  var frame;

  $("#syp-company-logo-upload").on("click", function (e) {
    e.preventDefault();

    if (frame) {
      frame.open();
      return;
    }

    frame = wp.media({
      title: (SYP_LOGO_I18N && SYP_LOGO_I18N.title) || "Select or Upload Logo",
      button: {
        text: (SYP_LOGO_I18N && SYP_LOGO_I18N.button) || "Use this logo",
      },
      multiple: false,
    });

    frame.on("select", function () {
      var a = frame.state().get("selection").first().toJSON();
      $("#syp-company-logo").val(a.id);
      $("#syp-company-logo-preview").attr("src", a.url).show();
      $("#syp-company-logo-remove").show();
    });

    frame.open();
  });

  $("#syp-company-logo-remove").on("click", function () {
    $("#syp-company-logo").val("");
    $("#syp-company-logo-preview").hide();
    $(this).hide();
  });
})(jQuery);
