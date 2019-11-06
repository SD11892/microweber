mw.tools.progressDefaults = {
    skin: 'mw-ui-progress',
    action: mw.msg.loading + '...',
};

mw.tools.progress = function (obj) {
    if (typeof obj.element === 'string') {
        obj.element = mw.$(obj.element)[0];
    }
    if (obj.element === null || !obj.element) return false;
    if (!!obj.element.progressOptions) {
        return obj.element.progressOptions;
    }
    var obj = $.extend({}, mw.tools.progressDefaults, obj);
    var progress = mwd.createElement('div');
    progress.className = obj.skin;
    progress.innerHTML = '<div class="mw-ui-progress-bar" style="width: 0%;"></div><div class="mw-ui-progress-info">' + mw.tools.progressDefaults.action + '</div><span class="mw-ui-progress-percent">0%</span>';
    progress.progressInfo = obj;
    var options = {
        progress: progress,
        show: function () {
            this.progress.style.display = 'block';
        },
        hide: function () {
            this.progress.style.display = 'none'
        },
        remove: function () {
            progress.progressInfo.element.progressOptions = undefined;
            mw.$(this.progress).remove();
        },
        set: function (v, action) {
            if (v > 100) {
                v = 100;
            }
            if (v < 0) {
                v = 0;
            }
            action = action || this.progress.progressInfo.action;
            mw.$('.mw-ui-progress-bar', this.progress).css('width', v + '%');
            mw.$('.mw-ui-progress-percent', this.progress).html(v + '%');
        }
    };
    progress.progressOptions = obj.element.progressOptions = options;
    obj.element.appendChild(progress);
    return options;
}
mw.tools.loading = function (element, progress, speed) {
    /*

     progress:number 0 - 100,
     speed:string, -> 'slow', 'normal, 'fast'

     mw.tools.loading(true) -> slowly animates to 95% on body
     mw.tools.loading(false) -> fast animates to 100% on body

     */
    function set(el, progress, speed) {
        speed = speed || 'normal';
        mw.tools.removeClass(el, 'mw-progress-slow');
        mw.tools.removeClass(el, 'mw-progress-normal');
        mw.tools.removeClass(el, 'mw-progress-fast');
        mw.tools.addClass(el, 'mw-progress-' + speed);
        element.__loadingTime = setTimeout(function () {
            el.querySelector('.mw-progress-index').style.width = progress + '%';
        }, 10)

    }

    if (typeof element === 'boolean') {
        progress = !!element;
        element = mwd.body;
    }
    if (typeof element === 'number') {
        progress = element;
        element = mwd.body;
    }
    if (element === document || element === mwd.documentElement) {
        element = mwd.body;
    }
    element = mw.$(element)[0]
    if (element === null || !element) return false;
    if (element.__loadingTime) {
        clearTimeout(element.__loadingTime)
    }
    var isLoading = mw.tools.hasClass(element, 'mw-loading');
    var el = element.querySelector('.mw-progress');
    if (!el) {
        el = document.createElement('div');
        el.className = 'mw-progress';
        el.innerHTML = '<div class="mw-progress-index"></div>';
        if (element === mwd.body) el.style.position = 'fixed';
        element.appendChild(el);
    }
    var pos = mw.CSSParser(element).get.position();
    if (pos == 'static') {
        element.style.position = 'relative';
    }
    if (progress) {
        if (progress === true) {
            set(el, 95, speed || 'slow')
        }
        else if (typeof progress === 'number') {
            progress = progress <= 100 ? progress : 100;
            progress = progress >= 0 ? progress : 0;
            set(el, progress, speed)
        }
    }
    else {
        if (el) {
            set(el, 100, speed || 'fast')
        }
        element.__loadingTime = setTimeout(function () {
            mw.$(element).removeClass('mw-loading-defaults mw-loading');
            mw.$(el).remove()
        }, 700)
    }
};
