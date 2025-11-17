// La función original está bien, pero la encerraremos para asegurarnos de que se ejecute después de que se cargue el contenido PHP.
// En un archivo .php, el DOM ya estará generado cuando se ejecute este script.

(function() {
    const wrapper = document.getElementById("cardsWrapper");
    
    // Si no se encuentra el wrapper (por ejemplo, en la página de administración), detenemos el script.
    if (!wrapper) return;

    // Aseguramos que 'cards' se obtenga DESPUÉS de que PHP haya cargado los elementos.
    let cards = Array.from(wrapper.children);
    
    // Si no hay tarjetas, no hay carrusel
    if (cards.length === 0) return;

    const btnNext = document.querySelector(".next");
    const btnPrev = document.querySelector(".prev");
    
    let cardWidth;
    // Si el contenido se carga dinámicamente, el índice debe ser el número actual de tarjetas para el "punto medio"
    let index = cards.length; 

    function calcWidth() {
      // Necesitamos recalcular las tarjetas si se han clonado
      const currentCards = Array.from(wrapper.children);
      if (currentCards.length === 0) return;

      const style = window.getComputedStyle(currentCards[0]);
      const margin = parseFloat(style.marginLeft) + parseFloat(style.marginRight);
      cardWidth = currentCards[0].offsetWidth + margin;
      
      // Actualizar la lista 'cards' después de la clonación
      cards = currentCards; 
    }

    function cloneCards() {
      // Asegurarse de que no estamos clonando si ya se hizo antes (esto puede ser complicado en un entorno PHP)
      // Para simplificar, asumiremos que los elementos originales son los primeros N en la lista.
      const initialCards = Array.from(wrapper.children).slice(0, index);

      // Limpiar clones antiguos (si esta función se llamara varias veces)
      // Aunque en PHP esto solo pasa una vez al cargar la página
      
      // Clonar al final
      initialCards.forEach(card => wrapper.appendChild(card.cloneNode(true)));
      // Clonar al principio
      initialCards.slice().reverse().forEach(card => wrapper.prepend(card.cloneNode(true)));

      // Recalcular la lista de tarjetas y el ancho después de la clonación
      calcWidth();
    }

    // El carrusel necesita al menos un elemento para funcionar
    if (cards.length > 0) {
        cloneCards();
        wrapper.style.transform = `translateX(-${index * cardWidth}px)`;
    }


    function moveNext() {
      index++;
      wrapper.style.transition = "transform 0.4s ease";
      wrapper.style.transform = `translateX(-${index * cardWidth}px)`;
      
      // si estamos cerca del borde derecho, resetear al medio
      setTimeout(() => {
        // En un carrusel clonado, el total de tarjetas es 3 veces el original.
        // El reset ocurre cuando llegamos al final del primer set clonado + original.
        if (index >= cards.length - index/3) { // Más seguro, aproximadamente 2 * original.length
          wrapper.style.transition = "none";
          index = cards.length / 3; // Volver al inicio del set original
          calcWidth(); // Recalcular por si acaso
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
          index = cards.length / 3; // Volver al inicio del set original
          calcWidth(); // Recalcular por si acaso
          wrapper.style.transform = `translateX(-${index * cardWidth}px)`;
        }
      }, 400);
    }

    btnNext.addEventListener("click", moveNext);
    btnPrev.addEventListener("click", movePrev);

    window.addEventListener("resize", () => {
      // Re-calcular ancho y posición al cambiar el tamaño de la ventana
      calcWidth();
      wrapper.style.transition = "none";
      wrapper.style.transform = `translateX(-${index * cardWidth}px)`;
    });

})();