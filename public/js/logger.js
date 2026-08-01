// logger.js - production logger shimming
// Default: silent (no console output). Set window.DEBUG = true in console to re-enable.
(function(){
    try {
        if (typeof window.DEBUG === 'undefined') window.DEBUG = false;
        if (!window.DEBUG && typeof console !== 'undefined') {
            ['log','info','warn','error','debug','trace'].forEach(fn => {
                try { console[fn] = function(){}; } catch(e) {}
            });
        }
        // provide a safe log function that respects DEBUG
        window._log = function(){ if (window.DEBUG && console && console.log) console.log.apply(console, arguments); };
    } catch (e) {
        // noop
    }
})();