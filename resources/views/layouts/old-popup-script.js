 /*
  let test = document.getElementById("test");

  // Ce gestionnaire ne sera exécuté qu'une fois
  // lorsque le curseur se déplace sur la liste
  test.addEventListener(
    "mouseenter",
    function (event) {
      // on met l'accent sur la cible de mouseenter
      event.target.style.color = "purple";

      // on réinitialise la couleur après quelques instants
      setTimeout(function () {
        event.target.style.color = "";
      }, 500);
    },
    false,
  );

  // Ce gestionnaire sera exécuté à chaque fois que le curseur
  // se déplacera sur un autre élément de la liste
  test.addEventListener(
    "mouseover",
    function (event) {
      // on met l'accent sur la cible de mouseover
      event.target.style.color = "orange";

      // on réinitialise la couleur après quelques instants
      setTimeout(function () {
        event.target.style.color = "";
      }, 500);
    },
    false,
  );*/