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
