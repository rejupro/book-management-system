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

  let originalRowHTML = "";

  $(document).on("click", ".btn-quick-click", function (e) {
    e.preventDefault();
    console.log("Quick Edit Clicked");

    // Cancel any previous edit if exists
    if ($(".btn_cancel").length > 0) {
      $(".btn_cancel").trigger("click");
    }

    const $currentRow = $(this).closest("tr");
    originalRowHTML = $currentRow.prop("outerHTML"); // Store full row HTML

    var name = $currentRow.find("span.name").text();
    var author = $currentRow.find("span.author").text();
    var book_price = $currentRow.find("span.book_price").text();
    var book_id = $currentRow.find("span.id").text();

    const editHTML = `
        <tr class="quick-edit-row">
            <td colspan="7">
                <table>
                    <tr>
                        <td>Quick Edit</td>
                    </tr>
                    <tr>
                        <td>Book Name</td>
                        <td><input type="text" class="book_name" value="${name}" name="book_name"></td>
                    </tr>
                    <tr>
                        <td>Book Author</td>
                        <td><input type="text" class="book_author" value="${author}" name="book_author"></td>
                    </tr>
                    <tr>
                        <td>Book Cost</td>
                        <td><input type="text" class="book_price_ajax" value="${book_price}" name="book_price"></td>
                    </tr>
                    <tr>
                        <td>
                            <button type="button" class="button button-primary bms_btn_save" book_id="${book_id}">Update</button>
                            <button type="button" class="button button-secondary btn_cancel" =">Cancel</button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    `;

    $currentRow.replaceWith(editHTML);
  });

  $(document).on("click", ".bms_btn_save", function (e) {
    e.preventDefault();
    console.log("Save Clicked");

    const $currentRow = $(this).closest("tr");
    const bookId = $(this).attr("book_id");
    const bookName = $(".book_name").val();
    const bookAuthor = $(".book_author").val();
    const bookPrice = $(".book_price_ajax").val();

    // Perform AJAX request to save the changes
    $.ajax({
      url: bms_plugin_ajax_url, // WordPress AJAX URL
      method: "POST",
      data: {
        action: "bms_updated_book",
        book_id: bookId,
        book_name: bookName,
        author_name: bookAuthor,
        book_price: bookPrice,
        param: "save_quick_form",
      },
      success: function (response) {
        if (response.success) {
          window.location.reload(); // Reload the page to reflect changes
        } else {
          alert("Error updating book. Please try again.");
        }
      },
      error: function () {
        alert("Error connecting to server. Please try again.");
      },
    });
  });

  // Cancel handler
  $(document).on("click", ".btn_cancel", function () {
    $(".quick-edit-row").replaceWith(originalRowHTML);
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const response = document.getElementById("save-response");
  if (response) {
    setTimeout(function () {
      response.style.transition = "opacity 1s";
      response.style.opacity = "0";
      setTimeout(() => (response.style.display = "none"), 1000);
    }, 2800);
  }
});
