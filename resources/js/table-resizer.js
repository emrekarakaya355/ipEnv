
    document.addEventListener('DOMContentLoaded', function () {
    const createResizableTable = function (table) {
    const cols = table.querySelectorAll('th');

    cols.forEach(function (col) {
    const resizer = document.createElement('div');
    resizer.classList.add('resizer');
    col.appendChild(resizer);

    resizer.addEventListener('mousedown', function (e) {
    e.preventDefault();

    let startX = e.clientX;
    let startWidth = col.offsetWidth;

    const onMouseMove = function (e) {
    const newWidth = startWidth + (e.clientX - startX);
    col.style.width = `${newWidth}px`; // Yalnızca seçilen sütunu genişlet
};

    const onMouseUp = function () {
    document.removeEventListener('mousemove', onMouseMove);
    document.removeEventListener('mouseup', onMouseUp);
};

    document.addEventListener('mousemove', onMouseMove);
    document.addEventListener('mouseup', onMouseUp);
});
});
};

    createResizableTable(document.querySelector('.resizable-table'));
});

