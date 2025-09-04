// Enhanced Professional Chatbot for Swastik Art & Craft
document.addEventListener('DOMContentLoaded', function() {
    const WHATSAPP_NUMBER = '+9779821589863';
    const COMPANY_INFO = {
        owner: 'Ushan Kunwar',
        company: 'Swastik Art & Craft',
        location: 'Kathmandu, Nepal',
        established: '2020',
        email: 'info@swastikartcraft.com',
        phone: '+977-9821589863',
        workingHours: '9:00 AM - 6:00 PM (Nepal Time)',
        specialties: ['Traditional Wooden Bowls', 'Ceramic & Terracotta Items', 'Bamboo Products', 'Sculptures & Wall Art', 'Home Decor Items']
    };

    let chatbotOpen = false;
    let conversationContext = [];

    // Enhanced quick reply responses
    window.quickReply = function(type) {
        const responses = {
            products: `🎨 **Our Handcrafted Collection:**

✨ **Traditional Wooden Bowls** - Hand-carved from sustainable wood
🏺 **Ceramic & Terracotta Items** - Authentic Nepalese pottery
🎋 **Bamboo Products** - Eco-friendly sustainable crafts  
🗿 **Sculptures & Wall Art** - Traditional and contemporary designs
🏠 **Home Decor Items** - Unique pieces for your living space

Each product is meticulously handcrafted by skilled artisans using techniques passed down through generations. Would you like to know more about any specific category?`,

            pricing: `💰 **Our Pricing Structure:**

🔹 **Wooden Bowls:** NPR 900 - 2,500
🔹 **Ceramic Items:** NPR 1,200 - 3,000  
🔹 **Bamboo Products:** NPR 800 - 2,200
🔹 **Sculptures:** NPR 1,500 - 3,500
🔹 **Home Decor:** NPR 1,000 - 2,800

💎 **Why Our Pricing:**
• Premium quality materials
• Skilled artisan craftsmanship
• Unique, one-of-a-kind pieces
• Fair trade practices

💳 **Payment Options:** Cash, Bank Transfer, Online Payment
🎁 **Special Offers:** Bulk discounts available for orders above NPR 5,000`,

            shipping: `🚚 **Worldwide Shipping Services:**

📍 **Domestic (Nepal):**
• Kathmandu Valley: 1-2 days
• Major Cities: 2-3 days  
• Remote Areas: 3-5 days
• Free delivery above NPR 2,500

🌍 **International Shipping:**
• SAARC Countries: 5-7 days
• Asia Pacific: 7-10 days
• Europe/Americas: 10-14 days
• Express shipping available

📦 **Packaging:** Eco-friendly materials with protective wrapping
🛡️ **Insurance:** All shipments insured against damage
📱 **Tracking:** Real-time tracking provided for all orders`,

            contact: `📞 **Get in Touch with Us:**

👨‍💼 **Owner:** ${COMPANY_INFO.owner}
🏢 **Company:** ${COMPANY_INFO.company}
📍 **Location:** ${COMPANY_INFO.location}
📧 **Email:** ${COMPANY_INFO.email}
☎️ **Phone:** ${COMPANY_INFO.phone}
🕒 **Hours:** ${COMPANY_INFO.workingHours}

🌐 **Social Media:**
• Facebook: @swastikartcraft
• Instagram: @swastikartcraft_nepal
• WhatsApp: Available 24/7 for quick queries

💬 **Preferred Contact:** WhatsApp for instant responses
📧 **Business Inquiries:** Email for detailed discussions`
        };
        
        addMessage(responses[type], 'bot');
    };

    // Enhanced bot response system
    window.getBotResponse = function(message) {
        const msg = message.toLowerCase();
        conversationContext.push(msg);
        
        // Greeting responses
        if (msg.includes('hello') || msg.includes('hi') || msg.includes('namaste') || msg.includes('hey')) {
            return `🙏 **Namaste and Welcome!**

I'm the virtual assistant for **${COMPANY_INFO.company}**, founded by **${COMPANY_INFO.owner}** in ${COMPANY_INFO.established}. 

We're Nepal's premier destination for authentic handcrafted products, located in the heart of ${COMPANY_INFO.location}.

How may I assist you today? I can help you with:
• Product information and catalogs
• Pricing and customization options  
• Shipping and delivery details
• Owner and company information
• Placing orders and inquiries`;
        }
        
        // Owner/Company information
        if (msg.includes('owner') || msg.includes('founder') || msg.includes('who') && (msg.includes('started') || msg.includes('created'))) {
            return `👨‍💼 **Meet Our Founder - ${COMPANY_INFO.owner}**

${COMPANY_INFO.owner} is a passionate entrepreneur and art enthusiast who founded ${COMPANY_INFO.company} in ${COMPANY_INFO.established} with a vision to preserve and promote Nepal's rich handicraft heritage.

🎯 **His Mission:** To provide sustainable livelihoods to local artisans while sharing Nepal's beautiful craftsmanship with the world.

🏆 **Achievements:**
• Supporting 50+ local artisan families
• Exporting to 15+ countries worldwide
• Maintaining 100% authentic traditional techniques
• Promoting eco-friendly sustainable practices

📞 **Direct Contact:** ${COMPANY_INFO.phone}
📧 **Email:** ${COMPANY_INFO.email}`;
        }
        
        // Company/Business information
        if (msg.includes('company') || msg.includes('business') || msg.includes('about') || msg.includes('story')) {
            return `🏢 **About ${COMPANY_INFO.company}**

📍 **Location:** ${COMPANY_INFO.location}, Nepal
📅 **Established:** ${COMPANY_INFO.established}
👨‍💼 **Founder:** ${COMPANY_INFO.owner}

🎨 **Our Story:**
We began as a small workshop with a big dream - to showcase Nepal's incredible handicraft traditions to the world. Today, we're proud to work with skilled artisans across Nepal, creating beautiful, authentic pieces that tell stories of our rich cultural heritage.

🌟 **What Makes Us Special:**
• 100% handcrafted authentic products
• Direct partnership with local artisans
• Sustainable and eco-friendly practices
• Quality guaranteed traditional techniques
• Worldwide shipping with care

🏆 **Our Impact:** Supporting traditional craftsmanship while providing modern market access to rural artisans.`;
        }
        
        // Product-related queries
        if (msg.includes('product') || msg.includes('item') || msg.includes('craft') || msg.includes('bowl') || msg.includes('ceramic')) {
            return `🎨 **Our Signature Products:**

🥣 **Traditional Wooden Bowls** (NPR 900-2,500)
• Hand-carved from sustainable hardwood
• Traditional Nepalese designs and patterns
• Food-safe natural finish
• Perfect for serving and decoration

🏺 **Ceramic & Terracotta** (NPR 1,200-3,000)  
• Authentic pottery techniques
• Natural clay from Nepal's hills
• Unique glazing and firing methods
• Functional and decorative pieces

🎋 **Bamboo Crafts** (NPR 800-2,200)
• Eco-friendly sustainable material
• Traditional weaving techniques
• Modern functional designs
• Baskets, containers, and decor

Would you like detailed information about any specific product category?`;
        }
        
        // Pricing queries
        if (msg.includes('price') || msg.includes('cost') || msg.includes('expensive') || msg.includes('cheap') || msg.includes('budget')) {
            return `💰 **Transparent Pricing Policy:**

Our prices reflect the true value of handcrafted artistry:

📊 **Price Ranges:**
• Wooden Bowls: NPR 900 - 2,500
• Ceramic Items: NPR 1,200 - 3,000
• Bamboo Products: NPR 800 - 2,200
• Sculptures: NPR 1,500 - 3,500
• Home Decor: NPR 1,000 - 2,800

💎 **Value Factors:**
• Hours of skilled craftsmanship
• Premium quality materials
• Unique, one-of-a-kind pieces
• Fair wages to artisans
• Sustainable practices

🎁 **Special Offers:**
• 10% discount on orders above NPR 5,000
• 15% discount on orders above NPR 10,000
• Custom bulk pricing for wholesale orders

💳 **Payment:** Cash, Bank Transfer, Online Payment accepted`;
        }
        
        // Shipping/Delivery queries
        if (msg.includes('ship') || msg.includes('deliver') || msg.includes('send') || msg.includes('courier')) {
            return `🚚 **Professional Shipping Services:**

📦 **Packaging Excellence:**
• Eco-friendly protective materials
• Custom cushioning for fragile items
• Moisture-resistant wrapping
• Branded packaging with care instructions

🇳🇵 **Domestic Shipping:**
• Kathmandu Valley: 1-2 days (Free above NPR 2,500)
• Major Cities: 2-3 days  
• Remote Areas: 3-5 days
• Same-day delivery available in Kathmandu

🌍 **International Shipping:**
• SAARC Countries: 5-7 days
• Asia Pacific: 7-10 days
• Europe/Americas: 10-14 days
• Express options available

🛡️ **Guarantee:** Full insurance coverage and tracking for all shipments`;
        }
        
        // Contact/Communication queries
        if (msg.includes('contact') || msg.includes('phone') || msg.includes('call') || msg.includes('reach')) {
            return `📞 **Multiple Ways to Connect:**

☎️ **Primary Contact:** ${COMPANY_INFO.phone}
📧 **Email:** ${COMPANY_INFO.email}
🕒 **Business Hours:** ${COMPANY_INFO.workingHours}

💬 **Instant Communication:**
• WhatsApp: ${COMPANY_INFO.phone} (24/7 available)
• Facebook Messenger: @swastikartcraft
• Direct call for urgent inquiries

🏢 **Visit Our Workshop:**
📍 ${COMPANY_INFO.location}
🗓️ **Appointment recommended for workshop visits**

👨‍💼 **Speak Directly with ${COMPANY_INFO.owner}:**
Available for consultations on custom orders and wholesale inquiries.

🌐 **Social Media:** Follow us for latest updates and behind-the-scenes content!`;
        }
        
        // Location/Address queries
        if (msg.includes('location') || msg.includes('address') || msg.includes('where') || msg.includes('visit')) {
            return `📍 **Visit Our Creative Hub:**

🏢 **${COMPANY_INFO.company}**
📍 **Address:** ${COMPANY_INFO.location}
🇳🇵 **Country:** Nepal

🗺️ **Why Kathmandu?**
• Heart of Nepal's cultural heritage
• Access to skilled traditional artisans
• Rich history of handicraft traditions
• Gateway for international shipping

🚗 **Getting Here:**
• 15 minutes from Tribhuvan International Airport
• Accessible by taxi, bus, or private vehicle
• Parking available for visitors

🕒 **Visit Hours:** ${COMPANY_INFO.workingHours}
📞 **Appointment:** Recommended - Call ${COMPANY_INFO.phone}

🎨 **Workshop Tours:** See our artisans at work and learn about traditional techniques!`;
        }
        
        // Quality/Authenticity queries
        if (msg.includes('quality') || msg.includes('authentic') || msg.includes('genuine') || msg.includes('original')) {
            return `✨ **Quality & Authenticity Guarantee:**

🏆 **Our Quality Promise:**
• 100% handcrafted by skilled artisans
• Traditional techniques preserved for generations
• Premium materials sourced locally
• Rigorous quality control process
• Certificate of authenticity with each piece

🔍 **Quality Checks:**
• Material inspection and selection
• Craftsmanship review at each stage
• Final quality assessment
• Packaging inspection
• Customer satisfaction guarantee

🛡️ **Authenticity Assurance:**
• Direct partnership with artisan communities
• Traditional methods documentation
• Artisan signature on premium pieces
• Story card with each product's origin

💯 **Customer Guarantee:**
• 30-day return policy
• Replacement for manufacturing defects
• Satisfaction or money-back guarantee`;
        }
        
        // Custom/Wholesale queries
        if (msg.includes('custom') || msg.includes('wholesale') || msg.includes('bulk') || msg.includes('order')) {
            return `🎯 **Custom & Wholesale Services:**

🎨 **Custom Orders:**
• Personalized designs and sizes
• Corporate gifts and branding
• Wedding and event favors
• Custom packaging options
• Minimum order: 10 pieces

🏢 **Wholesale Opportunities:**
• Competitive wholesale pricing
• Regular supply arrangements
• Quality consistency guaranteed
• International shipping support
• Dedicated account management

💼 **Business Partnerships:**
• Retail store partnerships
• Online marketplace collaboration
• Exhibition and fair participation
• Cultural center partnerships

📞 **For Custom/Wholesale Inquiries:**
Contact ${COMPANY_INFO.owner} directly at ${COMPANY_INFO.phone}
📧 Email: ${COMPANY_INFO.email}

⏱️ **Lead Time:** 2-4 weeks depending on order size and complexity`;
        }
        
        // Default response for unmatched queries
        return `🤔 **I'd love to help you with that!**

I can provide detailed information about:
• 🎨 Our handcrafted products and collections
• 💰 Pricing and payment options
• 🚚 Shipping and delivery services  
• 👨‍💼 Owner ${COMPANY_INFO.owner} and company story
• 📍 Location and workshop visits
• 📞 Contact and communication options
• 🎯 Custom orders and wholesale opportunities

For specific questions or immediate assistance, please contact us directly:
📞 **Call/WhatsApp:** ${COMPANY_INFO.phone}
📧 **Email:** ${COMPANY_INFO.email}

What would you like to know more about?`;
    };

    // Enhanced message display
    window.addMessage = function(text, sender) {
        const messagesDiv = document.getElementById('chatMessages');
        const messageDiv = document.createElement('div');
        
        if (sender === 'user') {
            messageDiv.className = 'bg-gold text-navy p-3 rounded-lg ml-8 mb-2';
        } else {
            messageDiv.className = 'bg-gray-100 p-3 rounded-lg mr-8 mb-2';
        }
        
        // Convert markdown-style formatting to HTML
        const formattedText = text
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/\n/g, '<br>');
        
        messageDiv.innerHTML = `<p class="text-sm">${formattedText}</p>`;
        messagesDiv.appendChild(messageDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    };

    // WhatsApp integration
    function createWhatsAppButton() {
        if (document.getElementById('whatsapp-chatbot')) return;

        const message = `🙏 Namaste! I'm interested in ${COMPANY_INFO.company}'s handcrafted products.

I'd like to know more about:
• Product catalog and pricing
• Shipping options
• Custom order possibilities

Please share more details. Thank you!`;

        // This function is called when WhatsApp button is clicked
        window.openWhatsApp = function() {
            const whatsappUrl = `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(message)}`;
            window.open(whatsappUrl, '_blank');
        };
    }

    createWhatsAppButton();
});