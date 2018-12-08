var rsAdd = {

    page_form : {
        tag : 'form',
        attr : {
            onsubmit : function() {
                return false;
            },
            id  : 'resource-form',
        },

        data : [
            {
                tag  : 'input',
                attr : {
                    type  : 'hidden',
                    id    : 'rs-cover',
                },
                data : ''
            },

            {
                tag  : 'label',
                attr : {
                    for : 'CoverFileUpload',
                    class : 'button primary'
                },
                data : '封面图片'
            },
            {
                tag  : 'input',
                attr : {
                    id    : 'CoverFileUpload',
                    class : 'show-for-sr',
                    type  : 'file',
                    onchange : function(t) {
                        if (t.target.files) {
                            var data = new FormData();
                            data.append('image-upload', t.target.files[0]);
                            matchMedia.request({
                                method : 'POST',
                                url    : `${_upload_api}/media/upload`,
                                data   : data
                            }).then((r) => {

                            });
                        }
                    }
                }
            },

            {
                tag  : 'button',
                attr : {
                    onclick  : function(t) {

                    }
                }
            }

        ]
    },

    page    : {
        tag     : 'div',
        attr    : {
            class : 'grid-x',

        }
    },

    view : function() {
        return MithTemp(rsAdd.page);
    },

    oncreate : function() {

    }
};
