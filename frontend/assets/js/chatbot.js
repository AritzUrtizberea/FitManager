/* chatbot.js - Lógica mejorada */

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

    // 2. CREAR EL HTML DEL CHAT
    const chatHTML = `
        <button class="chatbot-toggler">
            <span>💬</span>
        </button>
        <div class="chatbot">
            <header>
                <h2 style="margin-left: 10px;">Asistente Virtual</h2>
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

    // 3. VARIABLES Y FUNCIONES
    const chatbotToggler = document.querySelector(".chatbot-toggler");
    const faqContainer = document.getElementById("faq-options");
    const chatbox = document.getElementById("chatbox");

    // Función para crear botones
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
        // 1. Añadir mensaje del USUARIO (Derecha)
        const liUser = document.createElement("li");
        liUser.classList.add("chat", "outgoing");
        liUser.innerHTML = `<p>${preguntaTexto}</p>`;
        chatbox.appendChild(liUser);

        // Scroll al fondo
        chatbox.scrollTop = chatbox.scrollHeight;

        // 2. Simular un pequeño retraso para que parezca que "piensa"
        setTimeout(() => {
            // Añadir mensaje del BOT (Izquierda)
            const liBot = document.createElement("li");
            liBot.classList.add("chat", "incoming");
            liBot.innerHTML = `<p>${respuestaTexto}</p>`;
            chatbox.appendChild(liBot);
            
            // Scroll al fondo de nuevo
            chatbox.scrollTop = chatbox.scrollHeight;
        }, 600);
    }

    // Abrir / Cerrar
    chatbotToggler.addEventListener("click", () => {
        document.body.classList.toggle("show-chatbot");
        const icon = chatbotToggler.querySelector("span");
        icon.innerText = document.body.classList.contains("show-chatbot") ? "✖" : "💬";
    });

    // Iniciar
    cargarBotones();
});