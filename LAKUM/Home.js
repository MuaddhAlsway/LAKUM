const menuIcon = document.querySelector('.ri-menu-line');
const closeIcon = document.querySelector('.ri-close-line');
const menu = document.querySelector('.MenuHeader ul');

menuIcon.addEventListener('click', () => {
  menu.classList.add('active'); // show menu
  menuIcon.style.display = 'none'; // hide hamburger
  closeIcon.style.display = 'block'; // show close
});

closeIcon.addEventListener('click', () => {
  menu.classList.remove('active'); // hide menu
  menuIcon.style.display = 'block'; // show hamburger
  closeIcon.style.display = 'none'; // hide close
});




const sliderTrack = document.getElementById("sliderTrack");
let slides = Array.from(sliderTrack.children);

// Duplicate slides to ensure seamless infinite scrolling
while (sliderTrack.scrollWidth < window.innerWidth * 2) {
  slides.forEach(slide => {
    const clone = slide.cloneNode(true);
    sliderTrack.appendChild(clone);
  });
  slides = Array.from(sliderTrack.children);
}

let scrollPosition = 0;
const speed = 1; // pixels per frame, increase for faster scroll

function animateSlider() {
  scrollPosition += speed;
  if (scrollPosition >= sliderTrack.scrollWidth / 2) {
    scrollPosition = 0; // reset for infinite effect
  }
  sliderTrack.style.transform = `translateX(-${scrollPosition}px)`;
  requestAnimationFrame(animateSlider);
}

animateSlider();



document.addEventListener("DOMContentLoaded", () => {
  const monthItems = document.querySelectorAll(".monthsSidebar li");
  const events = document.querySelectorAll(".eventItem");

  monthItems.forEach(month => {
    month.addEventListener("click", () => {
      monthItems.forEach(m => m.classList.remove("active"));
      month.classList.add("active");

      const selectedMonth = month.dataset.month;
      events.forEach(event => {
        event.style.display = event.dataset.month === selectedMonth ? "block" : "none";
      });
    });
  });

  // Show first month by default
  monthItems[0].click();
});
 const track = document.querySelector('.slider-track');
    const prevBtn = document.querySelector('.nav.prev');
    const nextBtn = document.querySelector('.nav.next');
    const cards = document.querySelectorAll('.card');

    let isDragging = false;
    let startX = 0;
    let currentTranslate = 0;
    let prevTranslate = 0;
    let animationID = 0;

    const cardWidth = cards[0].offsetWidth + 20; // card + gap

    // Drag events
    track.addEventListener('mousedown', dragStart);
    track.addEventListener('mousemove', dragMove);
    track.addEventListener('mouseup', dragEnd);
    track.addEventListener('mouseleave', dragEnd);

    track.addEventListener('touchstart', dragStart);
    track.addEventListener('touchmove', dragMove);
    track.addEventListener('touchend', dragEnd);

    function dragStart(e) {
      isDragging = true;
      startX = getX(e);
      animationID = requestAnimationFrame(animation);
      track.style.transition = 'none';
    }

    function dragMove(e) {
      if (!isDragging) return;
      const currentX = getX(e);
      const diff = currentX - startX;
      currentTranslate = prevTranslate + diff;
    }

    function dragEnd() {
      cancelAnimationFrame(animationID);
      isDragging = false;

      // Snap to nearest card
      const movedBy = currentTranslate - prevTranslate;
      if (movedBy < -50) {
        currentTranslate = prevTranslate - cardWidth;
      } else if (movedBy > 50) {
        currentTranslate = prevTranslate + cardWidth;
      } else {
        currentTranslate = prevTranslate;
      }

      // Boundaries
      const maxTranslate = 0;
      const minTranslate = -(track.scrollWidth - track.parentElement.offsetWidth);

      if (currentTranslate > maxTranslate) currentTranslate = maxTranslate;
      if (currentTranslate < minTranslate) currentTranslate = minTranslate;

      prevTranslate = currentTranslate;
      track.style.transition = 'transform 0.4s ease';
      track.style.transform = `translateX(${currentTranslate}px)`;
    }

    function animation() {
      setSliderPosition();
      if (isDragging) requestAnimationFrame(animation);
    }

    function setSliderPosition() {
      track.style.transform = `translateX(${currentTranslate}px)`;
    }

    function getX(e) {
      return e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
    }

    // Buttons
    nextBtn.addEventListener('click', () => {
      currentTranslate -= cardWidth * 3; // move 3 images
      dragEnd();
    });

    prevBtn.addEventListener('click', () => {
      currentTranslate += cardWidth * 3;
      dragEnd();
    });


    