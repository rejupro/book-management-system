jQuery(function ($) {
  // Function to handle the click event on the button
  $("#deactivate-book-management-system").on("click", function (e) {
    e.preventDefault(); // Prevent the default action of the button

    var hasConfirmed = confirm(
      "Are you sure you want to deactivate the Book Management System? This action cannot be undone."
    );
    if (hasConfirmed) {
      // If the user confirms, redirect to the deactivation URL
      window.location.href = $(this).attr("href");
    }
  });

  $("#frm-add-book").validate();

  let mediaUploader;

  $("#btn-upload-cover").on("click", function (e) {
    e.preventDefault();

    mediaUploader = wp.media({
      title: "Select or Upload a Book Cover",
      button: {
        text: "Use this cover",
      },
      multiple: false,
    });
    mediaUploader.open();

    mediaUploader.on("select", function () {
      const attachment = mediaUploader
        .state()
        .get("selection")
        .first()
        .toJSON();
      $("#cover_image").val(attachment.url);
      $("#cover-preview").attr("src", attachment.url).show();
    });
  });
});
