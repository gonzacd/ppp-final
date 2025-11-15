const wrapper = document.getElementById("cardsWrapper");
const cards = Array.from(wrapper.children);
const btnNext = document.querySelector(".next");
const btnPrev = document.querySelector(".prev");

let cardWidth;
let index = cards.length; // arrancar en el “medio”

function calcWidth() {
  const style = window.getComputedStyle(cards[0]);
  const margin = parseFloat(style.marginLeft) + parseFloat(style.marginRight);
  cardWidth = cards[0].offsetWidth + margin;
}

function cloneCards() {
  cards.forEach(card => wrapper.appendChild(card.cloneNode(true)));
  cards.forEach(card => wrapper.prepend(card.cloneNode(true)));
}

cloneCards();
calcWidth();
wrapper.style.transform = `translateX(-${index * cardWidth}px)`;

function moveNext() {
  index++;
  wrapper.style.transition = "transform 0.4s ease";
  wrapper.style.transform = `translateX(-${index * cardWidth}px)`;
  // si estamos cerca del borde derecho, resetear al medio
  setTimeout(() => {
    if (index >= cards.length * 2) {
      wrapper.style.transition = "none";
      index = cards.length;
      wrapper.style.transform = `translateX(-${index * cardWidth}px)`;
    }
  }, 400);
}

function movePrev() {
  index--;
  wrapper.style.transition = "transform 0.4s ease";
  wrapper.style.transform = `translateX(-${index * cardWidth}px)`;
  setTimeout(() => {
    if (index <= 0) {
      wrapper.style.transition = "none";
      index = cards.length;
      wrapper.style.transform = `translateX(-${index * cardWidth}px)`;
    }
  }, 400);
}

btnNext.addEventListener("click", moveNext);
btnPrev.addEventListener("click", movePrev);

window.addEventListener("resize", () => {
  calcWidth();
  wrapper.style.transition = "none";
  wrapper.style.transform = `translateX(-${index * cardWidth}px)`;
});
