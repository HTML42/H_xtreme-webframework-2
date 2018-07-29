/**
 * 
 * @returns {undefined}
 */
var GDPRX = function (lang) {
    this.scripts = {};
    this.not_inserted = [];
    this.lang = (typeof lang == 'string' ? lang : 'en');
    if (typeof GX_texts[this.lang] == 'undefined') {
        console.error('GX-lang "' + this.lang + '" is not defined.');
    } else {
        this.texts = GX_texts[this.lang];
    }
};

GDPRX.prototype.startup = function (force) {
    if (GX_is_bot)
        return false;
    if (this.not_inserted.length > 0 || force === true) {
        if (isset($) && $('.gdprx').length == 0) {
            $('body').append('<div class="gdprx"><ul>' + this.html() + '</ul></div>');
            $('.gdprx [data-key] [data-accept]').click(function () {
                var this_key = $(this).closest('[data-key').data('key');
                document.cookie = 'gdpr_status_' + this_key + '=1';
                location.reload(true);
            });
            $('.gdprx [data-key] [data-decline]').click(function () {
                var this_key = $(this).closest('[data-key').data('key');
                document.cookie = 'gdpr_status_' + this_key + '=0';
                location.reload(true);
            });
            $('.gdprx .gx_close').click(function () {
                $('.gdprx').remove();
            });
        }
    }
};
GDPRX.prototype.html = function () {
    var _this = this;
    var html = '', html_parts = [];
    //
    html_parts.push('<div class="gx_close">' + _this.texts.layer_close + '</div>');
    html_parts.push('<div class="gx_caption">' + _this.texts.layer_caption + '</div>');
    //
    $$.foreach(this.scripts, function (script_data, key) {
        var row = '<div class="gx_script_caption">' + key + '</div>';
        row += '<div class="gx_script_text">' + script_data.description + '</div>';
        if (GX_privacy(key, script_data.default)) {
            row += '<div class="gx_script_status">' + _this.texts.status_text + ': <b>' + _this.texts.status_on + '</b>';
            row += '&nbsp; <div class="gx_button" data-decline>' + _this.texts.button_decline + '</div></div>';
        } else {
            row += '<div class="gx_script_status">' + _this.texts.status_text + ': <b>' + _this.texts.status_off + '</b>';
            row += '&nbsp; <div class="gx_button" data-accept>' + _this.texts.button_accept + '</div></div>';
        }
        html_parts.push('<li data-key="' + key + '">' + row + '</li>');
    });
    html = html_parts.join('');
    return html;
};

GDPRX.prototype.add_script = function (script_data) {
    var _this = this;
    if (typeof script_data.name == 'string' && typeof script_data.src == 'string') {
        if (typeof script_data.default == 'undefined') {
            script_data.default = true;
        }
        var key = script_data.name;
        if (has_attr(this.scripts, key)) {
            console.error('(GDPR) Script "' + key + '" has already be added');
        } else {
            this.scripts[key] = script_data;
            if (GX_privacy(key, script_data.default)) {
                _this.insert_script(script_data.src, script_data.callback);
            } else {
                _this.not_inserted.push(key);
            }
        }
    }
};
GDPRX.prototype.insert_script = function (src, callback) {
    var script = document.createElement('script');
    script.async = true;
    script.defer = true;
    script.type = "text/JavaScript";
    script.src = src;
    if (is_function(callback)) {
        script.onload = function () {
            setTimeout(function () {
                execute(callback);
            }, 50);
        };
    }
    setTimeout(function () {
        document.head.appendChild(script);
    }, 10);
};

function GX_privacy(key, _default) {
    if (typeof key == 'string') {
        if (typeof _default == 'undefined') {
            _default = true;
        }
        //
        var accepted = (document.cookie.search('gdpr_status_' + key + '=1') != -1);
        var untouched = (document.cookie.search('gdpr_status_' + key + '=') == -1);
        var is_active = (accepted || (untouched && _default));
        //
        return is_active;
    }
    return false;
}
;
var GX_lib = {
    analytics: {
        "default": false,
        "description": {
            en: "Google Analytics tracks your clicks and views on a server, owned by google.",
            de: "Google Analytics speichert deine Website-Aktionen auf einem Server von Google."
        }
    },
    maps: {
        "default": true,
        "description": {
            en: "Google Maps.",
            de: "Google Maps."
        }
    }
};
var GX_predefined = {
    analytics: function (tracking_id, description, lang) {
        if (typeof tracking_id != 'undefined') {
            if (!isset(lang)) {
                lang = 'en';
            }
            if (!isset(description)) {
                description = GX_lib.analytics.description[lang];
            }
            return {
                "name": "Analytics",
                "src": "https://www.googletagmanager.com/gtag/js?id=" + tracking_id,
                "description": description,
                "default": GX_lib.maps.default,
                "callback": function () {
                    window.dataLayer = window.dataLayer || [];
                    function gtag() {
                        dataLayer.push(arguments);
                    }
                    gtag('anonymizeIp', true);
                    gtag('js', new Date());
                    gtag('config', tracking_id);
                }
            };
        }
        return null;
    },
    maps: function (api_key, libraries, callback, lang) {
        if (typeof api_key != 'undefined') {
            if (!isset(lang)) {
                lang = 'en';
            }
            window.__init_map__ = function() {
                execute(callback);
            };
            if(!is_array(libraries)) {
                libraries = "";
            } else {
                libraries = "&libraries=" + libraries.join(',');
            }
            return {
                "name": "Google Maps",
                "src": "https://maps.googleapis.com/maps/api/js?key=" + api_key + libraries + "&callback=__init_map__",
                "description": GX_lib.maps.description[lang],
                "default": GX_lib.maps.default
            };
        }
        return null;
};
var GX_texts = {
    de: {
        button_accept: 'Akzeptieren',
        button_decline: 'Ausschalten',
        layer_close: 'Schließen',
        layer_caption: 'Datenschutzeinstellungen.',
        status_text: 'Aktuelle einstellung',
        status_on: 'AN',
        status_off: 'AUS'
    },
    en: {
        button_accept: 'Accept',
        button_decline: 'Decline',
        layer_close: 'Close layer',
        layer_caption: 'Privacy-Settings',
        status_text: 'Current status',
        status_on: 'ON',
        status_off: 'OFF'
    }
};
var GX_known_bots = ['google page speed', 'google search console', 'googlebot', 'www.google.com', 'google web preview', 'google-site-verification',
    'bingbot', 'slurp', 'duckduckbot', 'baiduspider', 'yandexbot', 'sogou', 'exabot', 'facebot', 'ia_archiver'];
var GX_is_bot = false;
for (var i in GX_known_bots) {
    if (navigator.userAgent.toLowerCase().search(GX_known_bots[i]) != -1) {
        GX_is_bot = true;
        break;
    }
}