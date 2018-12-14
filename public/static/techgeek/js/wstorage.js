var wo = new function() {

    this.set = function(key,val, json_seri=false) {
        if (json_seri) {
            sessionStorage.setItem(key,JSON.stringify(val));
        } else {
            sessionStorage.setItem(key, val);
        }
    };

    this.get = function(key, json_seri=false) {
        if (sessionStorage.getItem(key)===null) {
            return null;
        }
        if (json_seri) {
            return JSON.parse(sessionStorage.getItem(key));
        } else {
            return sessionStorage.getItem(key);        
        }
    };

    this.clear = function() {
        sessionStorage.clear();
    };

    this.remove = function (key) {
        sessionStorage.removeItem(key);
    };

    this.has = function (key) {
        if (sessionStorage.getItem(key) === null) {
            return true;
        }
        return false;
    }

};
