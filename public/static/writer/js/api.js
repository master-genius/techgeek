var api_table = {
    get_user_menu : {
        url    : _sysv.api + '/w/role/menu',
        method : 'get',
        args   : 'none'
    },

    group_list : {
        url:_sysv.api + '/u/rs/group/list',
        method : 'get',
        args : 'none'
    },

    media_list : {
        url : _sysv.mapi + '/m/media/list',
        method : 'get',
        args : 'none'
    },

    media_upload : {
        url : _sysv.mapi + '/mu/media/upload',
        method : 'upload',
        args : 'none'
    },

    media_del : {
        url : _sysv.mapi + '/m/media/delete',
        method : 'post',
        args : 'none'
    },

    rs_get : {
        url : _sysv.api + '/w/rs/get',
        method : 'get',
        args : 'must'
    },

    rs_list : {
        url : _sysv.api + '/u/rs/list',
        method : 'get',
        args : 'none'
    },

    rs_update : {
        url : _sysv.api + '/w/rs/update',
        method : 'post',
        args : 'none'
    },

    rs_add : {
        url : _sysv.api + '/w/rs/add',
        method : 'post',
        args : 'none'
    },

    rs_addpub : {
        url : _sysv.api + '/w/rs/addpub',
        method : 'post',
        args : 'none'
    },

    rs_del : {
        url : _sysv.api + '/w/rs/delete',
        method : 'post',
        args : 'none'
    },

    myinfo : {
        url : _sysv.api + '/r/userinfo',
        method : 'get',
        args : 'none'
    },

    setnickname : {
        url : _sysv.api + '/r/set/nickname',
        method : 'post',
        args : 'none'
    },

    lecture_add : {
        url : _sysv.api + '/w/lecture/add',
        method : 'post',
        args : 'none'
    },

    lecture_delete : {
        url : _sysv.api + '/w/lecture/delete',
        method : 'post',
        args : 'none'
    },

    lecture_list : {
        url : _sysv.api + '/w/lecture/list',
        method : 'get',
        args : 'none'
    },

    lecture_update : {
        url : _sysv.api + '/w/lecture/update',
        method : 'post',
        args : 'none'
    }

};

function show_login_block() {
    var html = `
        <div class="grid-x" style="margin-top:1.2rem;">
            <div class="cell small-1 medium-2 large-3">&nbsp;</div>
            <div class="cell small-10 medium-8 large-6">
                    <div class="grid-x">
                        <div class="cell small-6">
                            <h4>用户登录</h4>
                        </div>
                        <div class="cell small-6">
                            <a href="/user/register" style="float:right;color:#4a4a4a;">
                                注册
                            </a>
                        </div>
                    </div>

                <form onsubmit="return false;">
                    <label>用户名</label>
                    <input type="text" value="" id="username" placeholder="用户名" required>

                    <label>密码</label>
                    <input type="password" value="" id="passwd" required oninput="clear_tip();">

                    <input type="submit" class="button small success" onclick="ajax_login()" value="登录">
                </form>
                <a href="/user/forget/passwd" style="font-size:86%;color:#453456;">
                    忘记密码
                </a>
                <p id="ret-tip"></p>
            </div>
            <div class="cell small-1 medium-2 large-3">&nbsp;</div>
        </div>
    `;

    show_post_cover();
    set_post_cover_data(html);
}

function hide_login_block() {
    hide_post_cover();
}

function ajax_login() {

    var data = {
        username: brutal.autod('#username').trim(),
        passwd  : brutal.autod('#passwd').trim()
    };

    if (data.username.length == 0 || data.passwd.length == 0) {
        brutal.autod('#ret-tip', '请填写相应字段');
        return false;
    }

    raj.post({
        url     : `${_sysv.auth_api}/user/login`,
        data    : brutal.jsontodata(data),
        success : function(xr) {
            if (xr.status == 0) {
                document.cookie = `api_token=${xr.token}; path=/; domain=.d.com`;
                hide_login_block();
                location.reload(true);
            } else {
                brutal.autod('#ret-tip', xr.errinfo);
            }
        }
    });

}

var jsonqry = function (jsd){
    var data = '';
    for(var k in jsd){
        if(typeof jsd[k] == 'object'){
            jsd[k] = jsd[k].toString();
        }
        data += k+'='+encodeURIComponent(jsd[k])+'&';
    }
    return data.substring(0,data.length-1);
};

function redirect_login() {
    location.href = "/back/login/admin";
}

function get_token_str(url) {
    //var token = wstg_getItem('api-token');
    var cook = document.cookie;
    var cook_split = cook.split(';').filter(p => p.length > 0);
    var token = '';
    for (var i=0; i<cook_split.length; i++) {
        if (cook_split[i].search('api_token=') >= 0) {
            token = cook_split[i].substring(10);
            break;
        }
    }

    token = encodeURIComponent(token);
    if (url.indexOf('?') >= 0) {
        return '&api_token=' + token;
    } else {
        return '?api_token=' + token; 
    }
}

function api_get(xd) {
    xd.url += get_token_str(xd.url);

    raj.get({
        url : xd.url,
        //data : xd.data,
        success : function (xr) {
            if (xr.status == 10204) {
                show_login_block();
            } else {
                xd.success(xr);
            }
        },
        except : xd.except,
        error : xd.error

    });
}

function api_post(xd) {
    xd.url += get_token_str(xd.url);
    
    raj.post({
        url : xd.url,
        data : xd.data,
        success : function (xr) {
            if (xr.status == 10204) {
                show_login_block();
            } else {
                xd.success(xr);
            }
        },
        except : xd.except,
        error : xd.error
    });
}

function api_upload(file, xd) {
    xd.url += get_token_str(xd.url);
    raj.uploadOne(file, xd);
}

var _apicall = function(api) {
    if (typeof api_table[api.name] === undefined) {
        return false;
    }

    var a = api_table[api.name];

    if (a.args === 'must' && typeof api.args === undefined) {
        return false;
    }

    var complete_url = a.url;
    if (typeof api.args !== undefined 
        && (typeof api.args === 'string' || typeof api.args === 'number')
    ) {
        complete_url += '/' + api.args;
    }

    if (typeof api.querystr === 'string') {
        complete_url += '?' + api.querystr;
    }

    if (a.method === 'post' && typeof api.data === undefined) {
        return false;
    }

    var data = '';
    if (typeof api.data === 'string') {
        data = api.data;
    } else if (typeof api.data === 'object') {
        data = jsonqry(api.data);
    }

    if (a.method === 'get') {
        api_get({
            url : complete_url,
            success : api.success,
            error : api.error,
            except : api.except
        });
    } else if (a.method === 'post') {
        api_post ({
            url : complete_url,
            data : data,
            success : api.success,
            error : api.error,
            except : api.except
        });
    } else if (a.method === 'upload') {
        api_upload(api.file, {
            url : complete_url,
            upload_name : api.upload_name,
            success : function(xr) {
                if (xr.status == 10204) {
                    show_login_block();
                } else {
                    api.success(xr);
                }
            },
            error : api.error,
            except : api.except
        });
    }
    return true;
};
