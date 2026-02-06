/* chatbot.js - Lógica Accesible */

document.addEventListener("DOMContentLoaded", function() {

    // 1. TUS PREGUNTAS Y RESPUESTAS
    const preguntas = [
        { 
            pregunta: "¿Cuál es el horario?", 
            respuesta: "Nuestro horario es de Lunes a Viernes de 9:00 a 18:00. 🕒" 
        },
        { 
            pregunta: "¿Hacen envíos?", 
            respuesta: "Sí, realizamos envíos a todo el país en 24/48 horas. 🚚" 
        },
        { 
            pregunta: "¿Dónde están ubicados?", 
            respuesta: "Estamos en la calle Falsa 123, Madrid. 📍" 
        },
        { 
            pregunta: "Hablar con un humano", 
            respuesta: "Puedes escribirnos a soporte@ejemplo.com o llamarnos al 900-123-123. 📞" 
        }
    ];

    // 2. CREAR EL HTML DEL CHAT (¡Aquí agregamos el botón de cerrar!)
    const chatHTML = `
        <button class="chatbot-toggler">
            <span>💬</span>
        </button>
        <div class="chatbot">
            <header style="display: flex; justify-content: space-between; align-items: center; padding-right: 15px;">
                <h2 style="margin-left: 10px;">Asistente Virtual</h2>
                <button class="close-btn" aria-label="Cerrar chat" style="background: none; border: none; color: white; cursor: pointer; font-size: 1.5rem;">
                    ✖
                </button>
            </header>
            <ul class="chatbox" id="chatbox">
                <li class="chat incoming">
                    <p>¡Hola! 👋 Soy tu asistente virtual. <br>Selecciona una opción abajo:</p>
                </li>
            </ul>
            <div class="chat-input" id="faq-options">
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML("beforeend", chatHTML);

    // 3. VARIABLES
    const chatbotToggler = document.querySelector(".chatbot-toggler");
    const closeBtn = document.querySelector(".close-btn"); // <--- Nueva variable
    const faqContainer = document.getElementById("faq-options");
    const chatbox = document.getElementById("chatbox");

    // 4. FUNCIONES

    // Función para cerrar el chat (Reutilizable)
    const cerrarChat = () => {
        document.body.classList.remove("show-chatbot");
        // Resetear el icono del botón flotante a "burbuja"
        chatbotToggler.querySelector("span").innerText = "💬";
    };

    // Función para crear botones de preguntas
    function cargarBotones() {
        faqContainer.innerHTML = ""; 
        preguntas.forEach(item => {
            const btn = document.createElement("button");
            btn.classList.add("faq-btn");
            btn.innerText = item.pregunta;
            
            btn.addEventListener("click", () => {
                gestionarClick(item.pregunta, item.respuesta);
            });
            
            faqContainer.appendChild(btn);
        });
    }

    // Función principal de interacción
    function gestionarClick(preguntaTexto, respuestaTexto) {
        // Mensaje USUARIO
        const liUser = document.createElement("li");
        liUser.classList.add("chat", "outgoing");
        liUser.innerHTML = `<p>${preguntaTexto}</p>`;
        chatbox.appendChild(liUser);
        chatbox.scrollTop = chatbox.scrollHeight;

        // Simular respuesta BOT
        setTimeout(() => {
            const liBot = document.createElement("li");
            liBot.classList.add("chat", "incoming");
            liBot.innerHTML = `<p>${respuestaTexto}</p>`;
            chatbox.appendChild(liBot);
            chatbox.scrollTop = chatbox.scrollHeight;
        }, 600);
    }

    // EVENT LISTENERS

    // 1. Botón flotante (Abrir/Cerrar)
    chatbotToggler.addEventListener("click", () => {
        document.body.classList.toggle("show-chatbot");
        const icon = chatbotToggler.querySelector("span");
        // Cambiar icono dependiendo si está abierto o cerrado
        icon.innerText = document.body.classList.contains("show-chatbot") ? "✖" : "💬";
    });

    // 2. Botón interno "X" (Cerrar) - ¡ESTO ARREGLA TU PROBLEMA!
    closeBtn.addEventListener("click", cerrarChat);

    // Iniciar
    cargarBotones();
});