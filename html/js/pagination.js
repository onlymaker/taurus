function pagination(current, total, context) {
    current = Number(current);
    total = Number(total);

    // pager range to display, i.e. 1 2 3 4 5
    let range = 5;
    let start = (current % range) ? (Math.floor(current / range) * range + 1) : (Math.floor((current - 1) / range) * range + 1);
    let stop = Math.min(start + range - 1, total);

    let html = "";
    if (current > 1) {
        html += '<li class="page-item"><a class="page-link" href="' + context + (current - 1) + '">Prev</a></li>';
    } else {
        html += '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
    }
    if (start < stop) {
        for (let i = start; i <= stop; i ++) {
            if (i === current) {
                html += '<li class="page-item active"><a class="page-link" href="javascript:void(0)">' + i + '</a></li>';
            } else {
                html += '<li class="page-item"><a class="page-link" href="' + context + i + '">' + i + '</a></li>';
            }
        }
    } else {
        html += '<li class="page-item active"><a class="page-link" href="javascript:void(0)">' + current + '</a></li>';
    }
    if (current < total) {
        html += '<li class="page-item"><a class="page-link" href="' + context + (current + 1) + '">Next</a></li>';
    } else {
        html += '<li class="page-item disabled"><a class="page-link">Next</a></li>';
    }
    return html;
}
