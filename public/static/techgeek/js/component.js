function ListPageTemp (id) {
    var temp = `
        <form onsubmit="return false;">
            <div class="input-group">
                <div class="input-group-label">
                    <a href="javascript:ClearListSearch();" style="color:#671234;">
                        X
                    </a>
                </div>
                <input type="text" placeholder="Keywords" id="keywords" class="input-group-field">
                <div class="input-group-button">
                    <input type="submit" class="button secondary" value="Q" onclick="ListSearch();">
                </div>
            </div>
        </form>
    `;
    var f = document.querySelector(id);
    if (f !== null) {
        f.innerHTML = temp;
    } else {
        return temp;
    }
}

function PageTemp(id = null) {
    var temp = `
        <div class="grid-x" style="text-align:center;line-height:2.5rem;width:100%;">
            <div class="cell small-4 medium-4 large-4" id="com-prev-page" style="border-right:solid 0.05rem #e5e5e5;">
                <span>&lt;&lt;</span>
            </div>

            <div class="cell small-4 medium-4 large-4" id="com-jump-page">
                <span id="com-cur-page">1</span>/<span id="com-total-page">1</span>
            </div>

            <div class="cell small-4 medium-4 large-4" id="com-next-page" style="border-left:solid 0.05rem #e5e5e5;">
                <span>&gt;&gt;</span>
            </div>
        </div>
    `;

    if (id !== null && document.querySelector(id)) {
        document.querySelector(id).innerHTML = temp;
    } else {
        return temp;
    }
};

function ComJumpPage (callback) {
    var page = prompt('跳转至第几页？', '');
    if (page === null) {
        return ;
    }

    var total = document.querySelector('#com-total-page');
    var cur = document.querySelector('#com-cur-page');
    var end_page = parseInt(total.innerHTML);
    page = parseInt(page);
    var cur_page = parseInt(cur.innerHTML);

    if (page >0 && page <= end_page && page != cur_page) {
        if (typeof callback === 'function') {
            cur.innerHTML = page;
            callback(page);
        }
    }
}

function ComPrevPage (callback) {
    var cur = document.querySelector('#com-cur-page');
    var cur_page = parseInt(cur.innerHTML);
    if (cur_page > 1) {
        cur_page -= 1;
        if (typeof callback === 'function') {
            cur.innerHTML = cur_page;
            callback(cur_page);
        }
    }
}

function ComNextPage(callback) {
    var total = document.querySelector('#com-total-page');
    var cur = document.querySelector('#com-cur-page');
    var cur_page = parseInt(cur.innerHTML);
    var total_page = parseInt(total.innerHTML);
    if (cur_page < total_page) {
        cur_page += 1;
        if (typeof callback === 'function') {
            cur.innerHTML = cur_page;
            callback(cur_page);
        }
    }
}

function ComFirstPage(callback) {
    document.querySelector('#com-cur-page').innerHTML = 1;
    callback(1);
}

function ComLastPage(callback) {
    var total = document.querySelector('#com-total-page');
    var cur = document.querySelector('#com-cur-page');
    var last_page = parseInt(total.innerHTML);
    console.log(last_page);
    if (last_page > 0 && typeof callback === 'function') {
        cur.innerHTML = last_page;
        callback(last_page);
    }
}

function ComSetPageInfo(cur_page, total_page) {
    document.querySelector('#com-cur-page').innerHTML = cur_page;
    document.querySelector('#com-total-page').innerHTML = total_page;
}

function  ComPageEvent (callback) {
    document.querySelector('#com-prev-page').addEventListener('click', function(e){
        ComPrevPage(callback);
    });

    document.querySelector('#com-next-page').addEventListener('click', function(e){
        ComNextPage(callback);
    });

    document.querySelector('#com-jump-page').addEventListener('click', function(e){
        ComJumpPage(callback);
    });

    /* document.querySelector('#com-prev-page').addEventListener('dblclick', function(e){
        ComFirstPage(callback);
    });

    document.querySelector('#com-next-page').addEventListener('dblclick', function(e){
        ComLastPage(callback);
    }); */

};
