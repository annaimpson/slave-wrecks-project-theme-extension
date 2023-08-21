(function () {
  'use strict';

  const openNav = document.getElementById("open-nav"),
      nav = document.getElementById("nav"),
      navBody = document.getElementsByClassName("header__nav-body-wrap"),
      header = document.getElementById("header"),
      navButtons = document.querySelectorAll(".header__nav-list-btn"),
      closeDropdown = document.querySelectorAll(".header__nav-dropdown-close-btn");

  toggleNav();
  toggleDropdown();

  function toggleNav() {
    openNav.addEventListener("click", function(){
      nav.classList.toggle("header__nav-content-wrap--open");
      openNav.classList.toggle("header__menu-btn--open");
      document.body.classList.toggle("swp__body-freeze");
      navBody[0].classList.toggle("header__nav-body-wrap--open");

      if(window.innerWidth < 960 && nav) {
        nav.style.top = header.offsetHeight + "px";
      } else {
        nav.style.top = "inherit";
      }
    });

    //corrects top offset on window resize
    window.addEventListener("resize", function() {
      if(window.innerWidth < 960 && nav) {
        nav.style.top = header.offsetHeight + "px";
      } else {
        nav.style.top = "inherit";
      }
    });

    document.addEventListener('keydown', function(e){
      if(e.key === "Escape") {
        openNav.classList.remove("header__menu-btn--open");
        document.body.classList.remove("swp__body-freeze");
      }
    });
  }

  function toggleDropdown() {
    for (let i = 0; i < navButtons.length; i++) {
      const dropdown = navButtons[i].nextElementSibling;

      if(window.innerWidth >= 960) {
        navButtons[i].addEventListener("click", function(){
          // Ignore click if nav item doesn't have a dropdown
          if (!dropdown) {
            return;
          }

          if(navButtons[i].classList.contains('header__nav-list-btn--show')) {
            navButtons[i].classList.remove('header__nav-list-btn--show');
            document.body.classList.remove("swp__body-freeze");
          } else {
            closeOpenNavDropdowns();
            navButtons[i].classList.add('header__nav-list-btn--show');
            document.body.classList.add("swp__body-freeze");
          }

        });

        document.addEventListener('keydown', function(e){
          if(e.key === "Escape") {
            navButtons[i].classList.remove('header__nav-list-btn--show');
            document.body.classList.remove("swp__body-freeze");
          }
        });

        for (let j = 0; j < closeDropdown.length; j++) {
          closeDropdown[j].addEventListener("click", function () {
            navButtons[j].classList.remove('header__nav-list-btn--show');
            document.body.classList.remove("swp__body-freeze");
          });
        }
      }

      //corrects top offset on window resize
      window.addEventListener('resize', (event) => {
        if (window.innerWidth < 960 && nav) {
          // closeOpenNavDropdowns();
          navButtons[i].classList.remove('header__nav-list-btn--show');
          document.body.classList.remove("swp__body-freeze");        }
      });
    }
  }

  function closeOpenNavDropdowns() {
    Array
        // Cast HTMLElement Node List to an array
        .from(navButtons)
        // Only get nav dropdowns that are open
        .filter((navButton) => {
          return navButton.classList.contains('header__nav-list-btn--show');
        })
        // Loop over all `navButtons`
        .forEach((navButton) => {
          // Get dropdown element
          const dropdown = navButton.nextElementSibling;

          // Remove class that shows dropdown
          navButton.classList.remove('header__nav-list-btn--show');

          // // Close dropdown
          // dropdown.style.left = '-35%';
        });
  }

})();
