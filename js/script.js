$(document).ready(function () {
  const menu = $(".mobile-menu");
  const openMenu = $(".navbar__mobile-btn");
  openMenu.click(function () {
    menu.toggleClass("active");
    openMenu.toggleClass("active");
    return false;
  });
});
