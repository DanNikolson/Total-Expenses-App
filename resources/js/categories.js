window.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".edit-category-button").forEach((button) => {
    button.addEventListener("click", function (e) {
      const categoryId = e.currentTarget.getAttribute("data-id");

      //placeholder
      console.log(categoryId);
    });
  });
});
