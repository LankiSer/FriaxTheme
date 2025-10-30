jQuery(document).ready(function ($) {
  function makeNotice(notice) {
    $("#noticer").html(notice).addClass("active");
    setTimeout(function () {
      $("#noticer").removeClass("active");
    }, 3000);
  }

  // ============ FAVORITE NOTIFICATION ===========

  $(document).on("add_to_favorites", function () {
    makeNotice("Товар добавлен в избранное!");
  });
  $(document).on("remove_to_favorites", function () {
    makeNotice("Товар удалён из избранного!");
  });
  $(document).on("remove_to_favorites_all", function () {
    makeNotice("Товары удалены из избранного!");
  });

  // ============= ADD TO CART NOTIFICATION ==============
  $(document).on("added_to_cart", function () {
    makeNotice("Товар добавлен в корзину!");
  });
  $(document).on("removed_from_cart", function () {
    makeNotice("Товар удалён из корзины!");
  });

  // ============= CART MODIFICATION NOTIFICATION ==============
  $(document).on("woocommerce_update_cart_item", function () {
    makeNotice("Кол-во товара изменено!");
  });

  $(document).on("woocommerce_remove_cart_item", function () {
    makeNotice("Товар удалён из корзины!");
  });
});
