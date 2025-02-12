document.addEventListener('DOMContentLoaded', function() {

    const topScrollbar = document.getElementById('top-scrollbar');
    const tableContainer = document.getElementById('table');
    const widthTableContainer = document.getElementById('get-width');
    topScrollbar.querySelector('div').style.width = `${widthTableContainer.offsetWidth}px`; //viene impostata la lunghezza del div da far scorrere sopra la tabella

    // Sincronizzano le barre di scorrimento superiore e inferiore
    topScrollbar.addEventListener('scroll', function () {
      tableContainer.scrollLeft = topScrollbar.scrollLeft;
    });

    tableContainer.addEventListener('scroll', function () {
      topScrollbar.scrollLeft = tableContainer.scrollLeft;
});

});
