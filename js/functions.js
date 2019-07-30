var func_uri = window.location.pathname.substr(1);
if (func_uri.indexOf('admin') != -1) {
    func_uri = func_uri.split('/');
    func_uri = func_uri[1];
}
var session = getCookie('userdata');
var refresh = 0;

function request() {
    $.ajax({
        method: 'POST',
        url: 'index.php',
        data: {
            ajax: 'ajax',
            uri: window.location.pathname,
            get: window.location.search,
            session_id: session
        },
        dataType: 'text',
        success: function(result) {
            $('div.' + func_uri).replaceWith(result);
        }
    });
}

function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}

function reloading() {
    if (refresh == 1) {
        request();
    }
}

setInterval(reloading, 5000);

