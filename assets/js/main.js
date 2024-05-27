(function() {
  "use strict";

  /**
   * Easy selector helper function
   */
  const select = (el, all = false) => {
    el = el.trim()
    if (all) {
      return [...document.querySelectorAll(el)]
    } else {
      return document.querySelector(el)
    }
  }

  /**
   * Easy event listener function
   */
  const on = (type, el, listener, all = false) => {
    let selectEl = select(el, all)
    if (selectEl) {
      if (all) {
        selectEl.forEach(e => e.addEventListener(type, listener))
      } else {
        selectEl.addEventListener(type, listener)
      }
    }
  }

  /**
   * Easy on scroll event listener 
   */
  const onscroll = (el, listener) => {
    el.addEventListener('scroll', listener)
  }

  /**
   * Navbar links active state on scroll
   */
  let navbarlinks = select('#navbar .scrollto', true)
  const navbarlinksActive = () => {
    let position = window.scrollY + 200
    navbarlinks.forEach(navbarlink => {
      if (!navbarlink.hash) return
      let section = select(navbarlink.hash)
      if (!section) return
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        navbarlink.classList.add('active')
      } else {
        navbarlink.classList.remove('active')
      }
    })
  }
  window.addEventListener('load', navbarlinksActive)
  onscroll(document, navbarlinksActive)

  /**
   * Scrolls to an element with header offset
   */
  const scrollto = (el) => {
    let header = select('#header')
    let offset = header.offsetHeight

    let elementPos = select(el).offsetTop
    window.scrollTo({
      top: elementPos - offset,
      behavior: 'smooth'
    })
  }

  /**
   * Toggle .header-scrolled class to #header when page is scrolled
   */
  let selectHeader = select('#header')
  if (selectHeader) {
    const headerScrolled = () => {
      if (window.scrollY > 100) {
        selectHeader.classList.add('header-scrolled')
      } else {
        selectHeader.classList.remove('header-scrolled')
      }
    }
    window.addEventListener('load', headerScrolled)
    onscroll(document, headerScrolled)
  }

  /**
   * Back to top button
   */
  let backtotop = select('.back-to-top')
  if (backtotop) {
    const toggleBacktotop = () => {
      if (window.scrollY > 100) {
        backtotop.classList.add('active')
      } else {
        backtotop.classList.remove('active')
      }
    }
    window.addEventListener('load', toggleBacktotop)
    onscroll(document, toggleBacktotop)
  }

  /**
   * Mobile nav toggle
   */
  on('click', '.mobile-nav-toggle', function(e) {
    select('#navbar').classList.toggle('navbar-mobile')
    this.classList.toggle('bi-list')
    this.classList.toggle('bi-x')
  })

  /**
   * Mobile nav dropdowns activate
   */
  on('click', '.navbar .dropdown > a', function(e) {
    if (select('#navbar').classList.contains('navbar-mobile')) {
      e.preventDefault()
      this.nextElementSibling.classList.toggle('dropdown-active')
    }
  }, true)

  /**
   * Scrool with ofset on links with a class name .scrollto
   */
  on('click', '.scrollto', function(e) {
    if (select(this.hash)) {
      e.preventDefault()

      let navbar = select('#navbar')
      if (navbar.classList.contains('navbar-mobile')) {
        navbar.classList.remove('navbar-mobile')
        let navbarToggle = select('.mobile-nav-toggle')
        navbarToggle.classList.toggle('bi-list')
        navbarToggle.classList.toggle('bi-x')
      }
      scrollto(this.hash)
    }
  }, true)

  /**
   * Scroll with ofset on page load with hash links in the url
   */
  window.addEventListener('load', () => {
    if (window.location.hash) {
      if (select(window.location.hash)) {
        scrollto(window.location.hash)
      }
    }
  });

  /**
   * Preloader
   */
  let preloader = select('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove()
    });
  }

  /**
   * Initiate glightbox 
   */
  const glightbox = GLightbox({
    selector: '.glightbox'
  });

  /**
   * Testimonials slider
   */
  new Swiper('.testimonials-slider', {
    speed: 600,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false
    },
    slidesPerView: 'auto',
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    },
    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 20
      },

      1200: {
        slidesPerView: 3,
        spaceBetween: 20
      }
    }
  });

  /**
   * Porfolio isotope and filter
   */
  window.addEventListener('load', () => {
    let portfolioContainer = select('.portfolio-container');
    if (portfolioContainer) {
      let portfolioIsotope = new Isotope(portfolioContainer, {
        itemSelector: '.portfolio-item'
      });

      let portfolioFilters = select('#portfolio-flters li', true);

      on('click', '#portfolio-flters li', function(e) {
        e.preventDefault();
        portfolioFilters.forEach(function(el) {
          el.classList.remove('filter-active');
        });
        this.classList.add('filter-active');

        portfolioIsotope.arrange({
          filter: this.getAttribute('data-filter')
        });
        portfolioIsotope.on('arrangeComplete', function() {
          AOS.refresh()
        });
      }, true);
    }

  });

  /**
   * Initiate portfolio lightbox 
   */
  const portfolioLightbox = GLightbox({
    selector: '.portfolio-lightbox'
  });

  /**
   * Portfolio details slider
   */
  new Swiper('.portfolio-details-slider', {
    speed: 400,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false
    },
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    }
  });

  /**
   * Animation on scroll
   */
  window.addEventListener('load', () => {
    AOS.init({
      duration: 1000,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    })
  });

  /**
   * Initiate Pure Counter 
   */
  new PureCounter();

})()

let nextButton = document.querySelector('.nav-next');
let prevButton = document.querySelector('.nav-prev');

nextButton.addEventListener('click', function(){
    let items = document.querySelectorAll('.slide-item');
    document.querySelector('.image-slider').appendChild(items[0]);
});

prevButton.addEventListener('click', function(){
    let items = document.querySelectorAll('.slide-item');
    document.querySelector('.image-slider').prepend(items[items.length - 1]);
});

/**
 *  Slideshow
 */
document.addEventListener('DOMContentLoaded', () => {
  let nextButton = document.querySelector('.next');
  let prevButton = document.querySelector('.prev');
  let slideIndex = 0;
  const slides = document.querySelectorAll('.card');
  const totalSlides = slides.length;

  nextButton.addEventListener('click', function() {
    slideIndex = (slideIndex + 1) % totalSlides;
    document.querySelector('.carousel-slide').style.transform = `translateX(-${slideIndex * 100}%)`;
  });

  prevButton.addEventListener('click', function() {
    slideIndex = (slideIndex - 1 + totalSlides) % totalSlides;
    document.querySelector('.carousel-slide').style.transform = `translateX(-${slideIndex * 100}%)`;
  });
});


/* Js for Calendar*/

<script>
    $(document).ready(function() {
        var isStartSelected = false;
        var startDate, endDate;

        $('#calendar').fullCalendar({
            defaultView: 'month',
            selectable: true,
            select: function(start, end) {
                if (!isStartSelected) {
                    startDate = start;
                    $('#reservationStartDate').val(start.format('YYYY-MM-DD'));
                    isStartSelected = true;
                } else {
                    endDate = end;
                    if (end.isAfter(startDate)) {
                        $('#reservationEndDate').val(end.subtract(1, 'days').format('YYYY-MM-DD'));
                        isStartSelected = false;
                    } else {
                        alert("Departure date must be after the arrival date.");
                    }
                }
            },
            minDate: new Date() // Gray out past dates
        });

        // Clear date function
        window.clearDate = function(inputId) {
            $('#' + inputId).val('');
            isStartSelected = false;
            if (inputId === 'reservationStartDate') {
                $('#reservationEndDate').val('');
            }
        };

        // Calculate price based on duration
        function calculatePrice(startTime, endTime) {
            var duration = moment.duration(moment(endTime, 'HH:mm').diff(moment(startTime, 'HH:mm')));
            var hours = duration.asHours();

            // Minimum schedule is 5 hours
            if (hours < 5) {
                hours = 5;
            }

            // Price is $2500 for 8 hours
            var pricePerHour = 2500 / 8;
            var price = pricePerHour * hours;
            return price.toFixed(2);
        }

        // Update price when start time, end time, adults, or children change
        $('#startTime, #endTime, #adults, #children').change(function() {
            var adults = parseInt($('#adults').val());
            var children = parseInt($('#children').val());

            // Limit number of children based on number of adults
            var maxChildren = 4 - adults;
            if (children > maxChildren) {
                children = maxChildren;
                $('#children').val(maxChildren);
            }

            // Calculate total guests (including adults and children)
            var totalGuests = adults + children;

            // Calculate price based on duration and total guests
            var startTime = $('#startTime').val();
            var endTime = $('#endTime').val();
            var price = calculatePrice(startTime, endTime);
            $('#total_amount').val(price);
        });
    });
</script>


