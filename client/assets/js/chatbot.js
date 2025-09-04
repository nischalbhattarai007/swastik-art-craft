// Chatbot Data and Logic
const chatbotData = {
    responses: {
        greetings: [
            "Hello! Welcome to Swastik Art & Craft! How can I help you today?",
            "Hi there! I'm here to assist you with our handcrafted products.",
            "Welcome! Feel free to ask me about our products, services, or anything else."
        ],
        products: [
            "We offer a wide range of handcrafted items including pottery, textiles, wood carvings, and traditional art pieces.",
            "Our products are made by skilled artisans using traditional techniques passed down through generations.",
            "You can browse our complete collection on the Products page. Each item is unique and handcrafted with love."
        ],
        bowls: [
            "Our handcrafted bowls are made from premium clay and wood materials. We have decorative ceramic bowls, wooden serving bowls, and traditional Nepalese pottery bowls.",
            "Bowl prices range from Rs. 500 to Rs. 3000 depending on size and craftsmanship. Each bowl is unique and hand-painted by local artisans.",
            "We offer various bowl types: decorative bowls, serving bowls, soup bowls, and ceremonial bowls. All are dishwasher safe and food-grade."
        ],
        pottery: [
            "Our pottery collection includes vases, bowls, plates, and decorative items. All made using traditional wheel throwing techniques.",
            "Pottery items are fired at high temperatures for durability. We use natural glazes and traditional Nepalese designs.",
            "Pottery prices start from Rs. 300 for small items up to Rs. 5000 for large decorative pieces."
        ],
        textiles: [
            "We offer handwoven textiles including scarves, shawls, bags, and traditional clothing. Made from natural fibers like cotton, wool, and silk.",
            "Our textile artisans use traditional looms and natural dyes. Each piece takes days to complete and features unique patterns.",
            "Textile prices range from Rs. 800 for scarves to Rs. 8000 for premium shawls and traditional garments."
        ],
        wood: [
            "Our wood crafts include carved figurines, decorative boxes, furniture, and kitchen utensils. Made from sustainable local hardwoods.",
            "Wood carving is done by master craftsmen using traditional tools. Each piece is hand-finished with natural oils.",
            "Wood craft prices vary from Rs. 400 for small items to Rs. 15000 for large furniture pieces."
        ],
        owner: [
            "Swastik Art & Craft is owned by a family passionate about preserving Nepalese traditional arts. We've been in business for over 15 years.",
            "Our owner started this business to support local artisans and bring authentic Nepalese crafts to the world market.",
            "The owner personally visits artisan workshops to ensure quality and fair trade practices. You can contact us at +977 9821589863."
        ],
        pricing: [
            "Our prices vary depending on the complexity and materials used. You can find specific prices on each product page.",
            "We offer competitive pricing for authentic handcrafted items. Check individual product pages for exact prices.",
            "Prices are listed in Nepalese Rupees (Rs.) on each product. We also accept inquiries for custom orders."
        ],
        shipping: [
            "We offer worldwide shipping for our products. Delivery time varies by location: Nepal (2-3 days), Asia (5-7 days), Europe/US (10-15 days).",
            "Shipping costs depend on your location and the size of your order. Contact us for specific shipping quotes.",
            "We ensure safe packaging to protect your handcrafted items during transit. Insurance available for valuable items."
        ],
        contact: [
            "You can reach us at +977 9821589863 or email us at Ushanraz@gmail.com",
            "We're located at 16 Paknajol Marg, Kathmandu 44600, Nepal. Visit our Contact page for more details.",
            "Feel free to WhatsApp us at +977 9821589863 for quick responses! Business hours: Mon-Sat 9AM-6PM."
        ],
        about: [
            "Swastik Art & Craft specializes in authentic Nepalese handicrafts made by skilled local artisans.",
            "We've been preserving traditional art forms while supporting local communities and artisan families for over 15 years.",
            "Our mission is to bring authentic handcrafted art to art lovers worldwide while ensuring fair trade practices."
        ],
        help: [
            "I can help you with information about our products, pricing, shipping, contact details, and more!",
            "Ask me about our handcrafted items, how to place orders, or anything about Swastik Art & Craft.",
            "Try asking: 'Tell me about bowls', 'Who is the owner?', 'What are your prices?', 'How do you ship?'"
        ]
    },
    
    keywords: {
        greetings: ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening', 'namaste'],
        products: ['product', 'item', 'craft', 'art', 'handmade', 'handicraft', 'collection'],
        bowls: ['bowl', 'bowls', 'ceramic bowl', 'wooden bowl', 'serving bowl', 'decorative bowl'],
        pottery: ['pottery', 'ceramic', 'clay', 'vase', 'pot', 'plate'],
        textiles: ['textile', 'fabric', 'scarf', 'shawl', 'bag', 'clothing', 'weaving'],
        wood: ['wood', 'wooden', 'carving', 'furniture', 'figurine', 'box'],
        owner: ['owner', 'founder', 'who owns', 'management', 'boss', 'proprietor'],
        pricing: ['price', 'cost', 'expensive', 'cheap', 'money', 'payment', 'buy', 'purchase', 'how much'],
        shipping: ['ship', 'delivery', 'send', 'transport', 'courier', 'post', 'international'],
        contact: ['contact', 'phone', 'email', 'address', 'location', 'reach', 'call', 'whatsapp'],
        about: ['about', 'company', 'story', 'history', 'mission', 'vision', 'who are you'],
        help: ['help', 'assist', 'support', 'guide', 'what can you do', 'commands']
    }
};

class Chatbot {
    constructor() {
        this.isOpen = false;
        this.messageCount = 0;
        this.init();
    }

    init() {
        this.createChatbotHTML();
        this.bindEvents();
        this.showWelcomeMessage();
    }

    createChatbotHTML() {
        const chatbotHTML = `
            <!-- Chatbot -->
            <div id="chatbot" class="fixed bottom-4 right-4 bg-white rounded-2xl shadow-2xl w-80 h-70 hidden z-50 border border-amber-200">
                <div class="bg-gradient-to-r from-amber-600 to-orange-600 text-white p-4 rounded-t-2xl flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-robot text-sm"></i>
                        </div>
                        <h3 class="font-semibold">Swastik Assistant</h3>
                    </div>
                    <button onclick="chatbot.toggle()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-4 h-48 overflow-y-auto bg-gray-50" id="chatMessages">
                    <!-- Messages will be added here -->
                </div>
                <div class="p-4 border-t bg-white rounded-b-2xl">
                    <div class="flex">
                        <input type="text" id="chatInput" placeholder="Type your message..." 
                               class="flex-1 border border-gray-300 rounded-l-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        <button onclick="chatbot.sendMessage()" class="bg-gradient-to-r from-amber-600 to-orange-600 text-white px-4 py-2 rounded-r-lg hover:from-amber-700 hover:to-orange-700 transition-all">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    <div class="mt-2 flex flex-wrap gap-1">
                        <button onclick="chatbot.quickReply('bowls')" class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-full hover:bg-amber-200 transition-colors">Bowls</button>
                        <button onclick="chatbot.quickReply('owner')" class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-full hover:bg-amber-200 transition-colors">Owner</button>
                        <button onclick="chatbot.quickReply('contact')" class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-full hover:bg-amber-200 transition-colors">Contact</button>
                        <button onclick="chatbot.quickReply('shipping')" class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-full hover:bg-amber-200 transition-colors">Shipping</button>
                    </div>
                </div>
            </div>

            <!-- Chatbot Toggle Button -->
            <button id="chatToggleBtn" onclick="chatbot.toggle()" 
                    class="fixed bottom-24 right-4 bg-gradient-to-r from-amber-600 to-orange-600 text-white p-3 rounded-full shadow-lg hover:from-amber-700 hover:to-orange-700 transition-all transform hover:scale-110 z-50">
                <i class="fas fa-comments text-lg"></i>
                <span id="chatNotification" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden text-xs">1</span>
            </button>
        `;

        document.body.insertAdjacentHTML('beforeend', chatbotHTML);
    }

    bindEvents() {
        const chatInput = document.getElementById('chatInput');
        if (chatInput) {
            chatInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.sendMessage();
                }
            });
        }
    }

    toggle() {
        const chatbot = document.getElementById('chatbot');
        const notification = document.getElementById('chatNotification');
        const toggleBtn = document.getElementById('chatToggleBtn');
        
        this.isOpen = !this.isOpen;
        chatbot.classList.toggle('hidden');
        
        if (this.isOpen) {
            notification.classList.add('hidden');
            toggleBtn.classList.add('hidden');
            document.getElementById('chatInput').focus();
        } else {
            toggleBtn.classList.remove('hidden');
        }
    }

    showWelcomeMessage() {
        setTimeout(() => {
            if (!this.isOpen) {
                this.addBotMessage(this.getRandomResponse('greetings'));
                this.showNotification();
            }
        }, 3000);
    }

    showNotification() {
        const notification = document.getElementById('chatNotification');
        if (!this.isOpen) {
            notification.classList.remove('hidden');
        }
    }

    sendMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        
        if (message) {
            this.addUserMessage(message);
            input.value = '';
            this.showTooltip('Message sent successfully!');
            
            setTimeout(() => {
                const response = this.generateResponse(message);
                this.addBotMessage(response);
            }, 1000);
        }
    }

    quickReply(topic) {
        const response = this.getRandomResponse(topic);
        this.addBotMessage(response);
    }

    addUserMessage(message) {
        const messages = document.getElementById('chatMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = 'mb-3 text-right';
        messageDiv.innerHTML = `
            <div class="bg-gradient-to-r from-amber-600 to-orange-600 text-white p-3 rounded-lg inline-block max-w-xs">
                <p class="text-sm">${message}</p>
            </div>
        `;
        messages.appendChild(messageDiv);
        messages.scrollTop = messages.scrollHeight;
    }

    addBotMessage(message) {
        const messages = document.getElementById('chatMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = 'mb-3';
        messageDiv.innerHTML = `
            <div class="flex items-start">
                <div class="w-8 h-8 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                    <i class="fas fa-robot text-white text-xs"></i>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm max-w-xs border">
                    <p class="text-sm text-gray-800">${message}</p>
                </div>
            </div>
        `;
        messages.appendChild(messageDiv);
        messages.scrollTop = messages.scrollHeight;
    }

    generateResponse(message) {
        const lowerMessage = message.toLowerCase();
        
        // Check for keywords in the message
        for (const [category, keywords] of Object.entries(chatbotData.keywords)) {
            if (keywords.some(keyword => lowerMessage.includes(keyword))) {
                return this.getRandomResponse(category);
            }
        }
        
        // Default responses for unrecognized messages
        const defaultResponses = [
            "I'm not sure about that, but I'd be happy to help you with information about our products, contact details, or shipping!",
            "That's an interesting question! You can contact us directly at +977 9821589863 for more specific information.",
            "I can help you with questions about our handcrafted products, pricing, shipping, and contact information. What would you like to know?",
            "For detailed inquiries, please feel free to contact us via WhatsApp at +977 9821589863 or email at Ushanraz@gmail.com"
        ];
        
        return defaultResponses[Math.floor(Math.random() * defaultResponses.length)];
    }

    getRandomResponse(category) {
        const responses = chatbotData.responses[category];
        return responses[Math.floor(Math.random() * responses.length)];
    }

    showTooltip(message) {
        // Remove existing tooltip
        const existingTooltip = document.getElementById('chatTooltip');
        if (existingTooltip) {
            existingTooltip.remove();
        }

        // Create tooltip
        const tooltip = document.createElement('div');
        tooltip.id = 'chatTooltip';
        tooltip.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transform translate-x-0 opacity-100 transition-all duration-300';
        tooltip.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(tooltip);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            tooltip.style.transform = 'translateX(100%)';
            tooltip.style.opacity = '0';
            setTimeout(() => {
                if (tooltip.parentNode) {
                    tooltip.remove();
                }
            }, 300);
        }, 3000);
    }
}

// Initialize chatbot when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.chatbot = new Chatbot();
});