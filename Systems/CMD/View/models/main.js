App.handler = {
    init : function(){
        $('main a[role="link"]').click((e)=>{
            e.preventDefault();
            let ref = $(e.currentTarget).attr('href');
            App.workingArea.load(ref);
        });
    },
    release : function(){
        $('main a[role="link"]').off('click');
    }
};