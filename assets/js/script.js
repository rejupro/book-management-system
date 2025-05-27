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
});
