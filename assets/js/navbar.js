
// NAVBAR ACTIVE BACKGROUND
const links = document.querySelectorAll("#menu li a");
const logo = document.querySelector(".logo> #logo");

for (let i = 0; i < links.length; i++) {
  links[i].addEventListener("click", function() {
    for (let j = 0; j < links.length; j++) {
      links[j].classList.remove("active");
    }
    this.classList.add("active");
  });
}

logo.addEventListener("click", function() {
  for (let i = 0; i < links.length; i++) {
    links[i].classList.remove("active");
  }
});
