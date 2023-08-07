window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// // Fungsi untuk memeriksa status koneksi Pusher
// function checkPusherConnection() {
// console.log(window.Echo.channel("komentar").listen(".CommentCreated"));
// }

// // Interval pengecekan status koneksi setiap 5 detik (5000 milidetik)
// setInterval(checkPusherConnection, 5000);
  
var user_id = $('#user_id').val();
var ticketid = $('#ticket_id').val();

window.Echo.channel("messages-" + ticketid).listen("MessageCreated", function (event) {
    console.log(event.sender, user_id);
    if (event.sender === user_id) {
        return true;
    }else{

        sendComment(event.message, event.id_ticket, event.sender_name);
    }
});

function sendComment(comment, ticketid, sender_name) {
    // acronim
    let acronim = '';
    const regex = /\b(\w)/g;
    const matches = sender_name.toUpperCase().match(regex);
    if (matches) {
        acronim = matches.join('');
    }

    // date
    var currentDate = new Date();

    var hours = ("0" + currentDate.getHours()).slice(-2);
    var minutes = ("0" + currentDate.getMinutes()).slice(-2);
    var datenow = hours + ":" + minutes;

    var formattedText = comment.replace(/\n/g, "<br>");

    let html = '<ul class="list-unstyled chat-conversation-list"><li class="incoming-message">\n' +
                   '<a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="'+ sender_name +'">\n' +
                        '<div class="message-acronim mb-1">\n' +
                            '<div class="circle-acronim">\n' + acronim +                         
                                '</div>\n' +
                            '</div>\n' +
                        '</div>\n' +
                    '</a>\n' +
                    '<div class="incoming-message-content mb-1">\n' +
                        '<p class="mb-0 ctext-content">'+ formattedText +'</p>\n' +
                    '</div>\n' +
                    '</li>\n' +
                    '<li class="incoming-message-time mt-0 mb-2">\n' +
                        '<small class="text-muted time">' + datenow + '</small>\n' +
                    '</li></ul>';

    var chatBody = document.querySelector("#chat-card-"+ticketid);
    chatBody.insertAdjacentHTML("beforeend", html);
    
    // Mengambil elemen pesan terakhir (pesan baru yang ditambahkan)
    var newMessage = chatBody.lastElementChild.previousElementSibling;

    // Mengatur class 'animate__animated animate__fadeInUp' pada elemen pesan baru
    newMessage.classList.add('animate__animated', 'animate__fadeInUp');

    // Melakukan scroll ke pesan terakhir dengan animasi smooth
    chatBody.scrollTo({ left: 0, top: chatBody.scrollHeight, behavior: "smooth" });  
}
