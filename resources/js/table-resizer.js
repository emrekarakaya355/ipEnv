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
                    const newWidth = Math.max(30, startWidth + (e.clientX - startX)); // 30 px minimum genişlik
                    col.style.width = `${newWidth}px`;
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

