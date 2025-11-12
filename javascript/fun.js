 const wrapper = document.getElementById('cardsWrapper');
    const cardWidth = wrapper.querySelector('.card').offsetWidth + 20; // +margen
    let scrollPosition = 0;

    document.querySelector('.next').addEventListener('click', () => {
      const maxScroll = -(cardWidth * (wrapper.children.length - 1));
      if (scrollPosition > maxScroll) {
        scrollPosition -= cardWidth;
        wrapper.style.transform = `translateX(${scrollPosition}px)`;
      }
    });

    document.querySelector('.prev').addEventListener('click', () => {
      if (scrollPosition < 0) {
        scrollPosition += cardWidth;
        wrapper.style.transform = `translateX(${scrollPosition}px)`;
      }
    });
