/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1",
    wsHost: import.meta.env.VITE_PUSHER_HOST
        ? import.meta.env.VITE_PUSHER_HOST
        : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
});

const userId = document.querySelector('meta[name="user-id"]').content;

window.Echo.private(`App.Models.User.${userId}`).notification(
    (notification) => {
        updateNotificationBell(notification);
    }
);

function updateNotificationBell(notification) {
    const notificationCount = document.querySelector(".notification-count");
    const dropdownMenu = document.querySelector(".dropdown-menu");

    // Incrementa el contador o inicializa si no existe
    let currentCount = parseInt(notificationCount?.innerText) || 0;
    notificationCount.innerText = currentCount + 1;
    notificationCount.style.display = "inline";

    // Crear un nuevo elemento de notificación
    const listItem = document.createElement("li");
    listItem.classList.add("dropdown-item");

    const link = document.createElement("a");
    link.href = `/notification/show/${notification.id}`; // Ajusta la ruta según corresponda
    link.classList.add("text-decoration-none");
    link.innerText = notification.data.message; // Ajusta según los datos de la notificación

    listItem.appendChild(link);
    dropdownMenu.appendChild(listItem); 
    // fetch(`/notifications/unread/`, {
    //     method: "GET",
    //     headers: {
    //         "Content-Type": "application/json",
    //         "X-CSRF-TOKEN": "{{ csrf_token() }}", // Asegúrate de que esto esté bien definido
    //     },
    // })
    //     .then((response) => response.json())
    //     .then((data) => {
    //         console.log("🚀 ~ .then ~ data:", data);
    //         const notificationCount = document.getElementById("item_notification");
    //         notificationCount.style.display="none";
    //         notificationCount.style.display = "inline";
    //         // Actualizar el contador de notificaciones
    //         // const notificationCount =
    //         //     document.getElementById("notification-count");

    //         // // Limpiar las notificaciones anteriores en el menú desplegable
    //         // const dropdownMenu = document.querySelector(".dropdown-menu");
    //         // dropdownMenu.innerHTML = ""; // Limpia las notificaciones previas

    //         // // Añadir nuevas notificaciones al menú desplegable
    //         // if (data.length > 0) {
    //         //     notificationCount.innerText = data.length; // Suponiendo que el contador de no leídas viene en la respuesta
    //         //     data?.forEach((notification) => {
    //         //         const listItem = document.createElement("li");
    //         //         listItem.classList.add("dropdown-item");

    //         //         // Crea el enlace a la notificación
    //         //         const link = document.createElement("a");
    //         //         link.href = `/notification/show/${notification.id}`; // Asegúrate de que la ruta sea correcta
    //         //         link.classList.add("text-decoration-none");
    //         //         link.innerText = notification.message; // Cambia esto según cómo estés estructurando el mensaje

    //         //         listItem.appendChild(link);
    //         //         dropdownMenu.appendChild(listItem);
    //         //     });
    //         // } else {
    //         //     // Si no hay notificaciones
    //         //     const emptyItem = document.createElement("li");
    //         //     emptyItem.classList.add("dropdown-item", "text-muted");
    //         //     emptyItem.innerText = "No hay notificaciones";
    //         //     dropdownMenu.appendChild(emptyItem);
    //         // }
    //     })
    //     .catch((error) => {
    //         console.log(
    //             "Error",
    //             "Hubo un problema al procesar la solicitud.",
    //             error
    //         );
    //     });
}
