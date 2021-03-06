$(function() {
  $.ajaxSetup({
    'beforeSend': function(xhr, settings) {
      var getCookie;
      getCookie = function(name) {
        var cookie, cookieValue, cookies, i;
        cookieValue = null;
        if (document.cookie && document.cookie !== '') {
          cookies = document.cookie.split(';');
          i = 0;
          while (i < cookies.length) {
            cookie = jQuery.trim(cookies[i]);
            if (cookie.substring(0, name.length + 1) === name + '=') {
              cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
              break;
            }
            i++;
          }
        }
        return cookieValue;
      };
      if (settings.type === 'POST' || settings.type === 'PUT' || settings.type === 'DELETE') {
        if (!(/^http:.*/.test(settings.url) || /^https:.*/.test(settings.url))) {
          xhr.setRequestHeader('X-CSRF-TOKEN', getCookie('csrf'));
        }
      }
    }
  });
});
